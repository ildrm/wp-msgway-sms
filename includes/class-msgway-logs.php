<?php
/**
 * MsgWay_Logs class handles logging and displaying message logs.
 */
class MsgWay_Logs {
    /**
     * Initialize the logs functionality.
     */
    public function init() {
        // Create logs table on plugin activation
        register_activation_hook(MSGWAY_SMS_FILE, array($this, 'create_logs_table'));
        
        // Handle log message action
        add_action('msgway_log_message', array($this, 'log_message'), 10, 2);
    }

    /**
     * Create the logs table in the database.
     */
    public function create_logs_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'msgway_logs';
        $charset_collate = $wpdb->get_charset_collate();
        $db_version = '1.0';

        // Check if table needs creation or update
        if (get_option('msgway_logs_db_version') !== $db_version) {
            $sql = "CREATE TABLE $table_name (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                message_type VARCHAR(255) NOT NULL,
                mobile VARCHAR(20) DEFAULT NULL,
                response TEXT DEFAULT NULL,
                status VARCHAR(20) DEFAULT 'unknown',
                created_at DATETIME NOT NULL,
                PRIMARY KEY (id),
                KEY created_at (created_at)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            $result = dbDelta($sql);

            // Log the result for debugging
            if (!empty($result)) {
                error_log('MsgWay SMS: Table creation successful - ' . print_r($result, true));
            } else {
                error_log('MsgWay SMS: Table creation failed or no changes needed.');
            }

            // Update the database version
            update_option('msgway_logs_db_version', $db_version);
        } else {
            error_log('MsgWay SMS: Table creation skipped - version ' . $db_version . ' already applied.');
        }

        // Verify table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            error_log('MsgWay SMS: Error - Table ' . $table_name . ' was not created.');
        }
    }

    /**
     * Log a message to the database.
     *
     * @param string $message_type Type of message (e.g., 'وضعیت سفارش', 'خوش‌آمدگویی').
     * @param mixed  $response     API response.
     */
    public function log_message($message_type, $response) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'msgway_logs';

        $mobile = isset($response['mobile']) ? $response['mobile'] : null;
        $status = isset($response['success']) && $response['success'] ? 'success' : 'failed';

        $result = $wpdb->insert(
            $table_name,
            array(
                'message_type' => sanitize_text_field($message_type),
                'mobile' => $mobile ? sanitize_text_field($mobile) : null,
                'response' => maybe_serialize($response),
                'status' => $status,
                'created_at' => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%s', '%s')
        );

        if (false === $result) {
            error_log('MsgWay SMS: Failed to insert log - ' . $wpdb->last_error);
        }
    }

    /**
     * Render the logs page.
     */
    public function render_logs_page() {
        $logs_table = new MsgWay_Logs_Table();
        $logs_table->prepare_items();
        ?>
        <div class="wrap">
            <h1><?php _e('گزارش‌های پیام‌ها', 'msgway-sms'); ?></h1>
            <?php $logs_table->display(); ?>
        </div>
        <?php
    }
}
?>