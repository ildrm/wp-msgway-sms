<?php
/**
 * MsgWay_Logs_Table class handles the display of message logs using WP_List_Table.
 */
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class MsgWay_Logs_Table extends WP_List_Table {
    /**
     * Array of unique message types for filtering.
     *
     * @var array
     */
    public $message_types = [];

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(array(
            'singular' => 'log',
            'plural'   => 'logs',
            'ajax'     => false,
        ));
    }

    /**
     * Get columns for the table.
     */
    public function get_columns() {
        return array(
            'message_type' => __('نوع پیام', 'msgway-sms'),
            'mobile'       => __('شماره موبایل', 'msgway-sms'),
            'status'       => __('وضعیت', 'msgway-sms'),
            'created_at'   => __('تاریخ و زمان', 'msgway-sms'),
            'details'      => __('جزئیات', 'msgway-sms'),
        );
    }

    /**
     * Get sortable columns.
     */
    protected function get_sortable_columns() {
        return array(
            'message_type' => array('message_type', false),
            'status'       => array('status', false),
            'created_at'   => array('created_at', true),
        );
    }

    /**
     * Prepare items for the table.
     */
    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'msgway_logs';

        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            echo '<div class="error"><p>' . __('خطا: جدول لاگ‌ها وجود ندارد. لطفاً افزونه را غیرفعال و دوباره فعال کنید.', 'msgway-sms') . '</p></div>';
            return;
        }

        // Handle filters
        $filter_type = isset($_GET['filter_type']) ? sanitize_text_field($_GET['filter_type']) : '';
        $filter_status = isset($_GET['filter_status']) ? sanitize_text_field($_GET['filter_status']) : '';
        $filter_date = isset($_GET['filter_date']) ? sanitize_text_field($_GET['filter_date']) : '';

        // Pagination
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        // Build query
        $where = array();
        if ($filter_type) {
            $where[] = $wpdb->prepare('message_type = %s', $filter_type);
        }
        if ($filter_status) {
            $where[] = $wpdb->prepare('status = %s', $filter_status);
        }
        if ($filter_date) {
            $where[] = $wpdb->prepare('DATE(created_at) = %s', $filter_date);
        }
        $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Sorting
        $orderby = !empty($_GET['orderby']) ? sanitize_sql_orderby($_GET['orderby']) : 'created_at';
        $order = !empty($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC';
        $order_clause = "ORDER BY $orderby $order";

        // Get logs
        $query = "SELECT * FROM $table_name $where_clause $order_clause LIMIT %d OFFSET %d";
        $this->items = $wpdb->get_results($wpdb->prepare($query, $per_page, $offset));

        // Get total logs for pagination
        $total_logs = $wpdb->get_var("SELECT COUNT(*) FROM $table_name $where_clause");
        $this->set_pagination_args(array(
            'total_items' => $total_logs,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_logs / $per_page),
        ));

        // Get unique message types for filter
        $this->message_types = $wpdb->get_col("SELECT DISTINCT message_type FROM $table_name");
    }

    /**
     * Render column values.
     */
    protected function column_default($item, $column_name) {
        switch ($column_name) {
            case 'message_type':
                return esc_html($item->message_type);
            case 'mobile':
                return $item->mobile ? esc_html($item->mobile) : '-';
            case 'status':
                return $item->status === 'success' ?
                    '<span style="color: green;">' . __('موفق', 'msgway-sms') . '</span>' :
                    '<span style="color: red;">' . __('ناموفق', 'msgway-sms') . '</span>';
            case 'created_at':
                return esc_html($item->created_at);
            case 'details':
                return sprintf(
                    '<button class="button view-details" data-response="%s">%s</button>',
                    esc_attr(json_encode(maybe_unserialize($item->response))),
                    __('مشاهده جزئیات', 'msgway-sms')
                );
            default:
                return '';
        }
    }

    /**
     * Render the table navigation (filters).
     */
    protected function extra_tablenav($which) {
        if ($which === 'top') {
            ?>
            <div class="alignleft actions">
                <form method="get" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="page" value="msgway-sms">
                    <select name="filter_type">
                        <option value=""><?php _e('همه انواع پیام', 'msgway-sms'); ?></option>
                        <?php foreach ($this->message_types as $type): ?>
                            <option value="<?php echo esc_attr($type); ?>" <?php selected(isset($_GET['filter_type']) ? $_GET['filter_type'] : '', $type); ?>>
                                <?php echo esc_html($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="filter_status">
                        <option value=""><?php _e('همه وضعیت‌ها', 'msgway-sms'); ?></option>
                        <option value="success" <?php selected(isset($_GET['filter_status']) ? $_GET['filter_status'] : '', 'success'); ?>>
                            <?php _e('موفق', 'msgway-sms'); ?>
                        </option>
                        <option value="failed" <?php selected(isset($_GET['filter_status']) ? $_GET['filter_status'] : '', 'failed'); ?>>
                            <?php _e('ناموفق', 'msgway-sms'); ?>
                        </option>
                    </select>
                    <input type="date" name="filter_date" value="<?php echo esc_attr(isset($_GET['filter_date']) ? $_GET['filter_date'] : ''); ?>">
                    <input type="submit" class="button" value="<?php _e('فیلتر', 'msgway-sms'); ?>">
                </form>
            </div>
            <?php
        }
    }

    /**
     * Display the modal for log details.
     */
    public function display() {
        parent::display();
        ?>
        <div id="log-details-modal" style="display: none;">
            <div class="modal-content">
                <h2><?php _e('جزئیات پیام', 'msgway-sms'); ?></h2>
                <pre id="log-details-content"></pre>
                <button class="button close-modal"><?php _e('بستن', 'msgway-sms'); ?></button>
            </div>
        </div>
        <style>
            #log-details-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
            #log-details-modal .modal-content {
                background: #fff;
                padding: 20px;
                border-radius: 5px;
                max-width: 600px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
            }
            #log-details-modal pre {
                background: #f5f5f5;
                padding: 10px;
                border: 1px solid #ddd;
                white-space: pre-wrap;
            }
            #log-details-modal .close-modal {
                margin-top: 10px;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
                $('.view-details').on('click', function() {
                    var response = $(this).data('response');
                    $('#log-details-content').text(JSON.stringify(response, null, 2));
                    $('#log-details-modal').show();
                });
                $('.close-modal').on('click', function() {
                    $('#log-details-modal').hide();
                });
            });
        </script>
        <?php
    }
}
?>