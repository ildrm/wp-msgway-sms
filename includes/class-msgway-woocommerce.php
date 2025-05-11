<?php
/**
 * MsgWay_WooCommerce class handles WooCommerce-related SMS notifications.
 */
class MsgWay_WooCommerce {
    private $api;

    /**
     * Constructor to initialize the API.
     */
    public function __construct() {
        $this->api = new MsgWay_API();
    }

    /**
     * Initialize WooCommerce hooks.
     */
    public function init() {
        // Hook for order status changes
        add_action('woocommerce_order_status_changed', array($this, 'send_order_status_message'), 10, 4);
        
        // Hook for order confirmation (after order is placed)
        add_action('woocommerce_checkout_order_processed', array($this, 'send_order_confirmation_message'), 10, 3);
        
        // Hook for abandoned cart (requires additional plugin or custom logic)
        // Example: add_action('woocommerce_cart_has_been_abandoned', array($this, 'send_abandoned_cart_message'));
        
        // Hook for promotional messages (manual trigger via admin action)
        add_action('admin_post_msgway_send_promotional', array($this, 'send_promotional_message'));
    }

    /**
     * Send SMS for order status changes.
     *
     * @param int    $order_id   Order ID.
     * @param string $old_status Old order status.
     * @param string $new_status New order status.
     * @param WC_Order $order    Order object.
     */
    public function send_order_status_message($order_id, $old_status, $new_status, $order) {
        $mobile = $order->get_billing_phone();
        if (!$mobile) {
            return;
        }

        $status_key = str_replace('wc-', '', $new_status);
        $enabled = get_option("msgway_order_{$status_key}_enabled", '0');
        $template_id = get_option("msgway_order_{$status_key}_template_id", 0);
        $provider = get_option("msgway_order_{$status_key}_provider", 'sms_1');

        if (!$enabled || !$template_id) {
            return;
        }

        $data = array(
            'method' => $this->get_message_method($provider),
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'params' => array($order->get_order_number(), $new_status),
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', "وضعیت سفارش: {$new_status}", $response);

        // Allow developers to extend this functionality
        do_action('msgway_after_order_status_message', $order_id, $new_status, $response);
    }

    /**
     * Send SMS for order confirmation after checkout.
     *
     * @param int    $order_id   Order ID.
     * @param array  $posted_data Posted checkout data.
     * @param WC_Order $order    Order object.
     */
    public function send_order_confirmation_message($order_id, $posted_data, $order) {
        $mobile = $order->get_billing_phone();
        if (!$mobile) {
            return;
        }

        $enabled = get_option('msgway_order_confirmation_enabled', '0');
        $template_id = get_option('msgway_order_confirmation_template_id', 0);
        $provider = get_option('msgway_order_confirmation_provider', 'sms_1');

        if (!$enabled || !$template_id) {
            return;
        }

        $data = array(
            'method' => $this->get_message_method($provider),
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'params' => array($order->get_order_number(), $order->get_total()),
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', 'تأیید سفارش', $response);

        // Allow developers to extend this functionality
        do_action('msgway_after_order_confirmation_message', $order_id, $response);
    }

    /**
     * Send SMS for abandoned cart (requires additional plugin or custom logic).
     *
     * @param WC_Cart $cart Cart object.
     */
    public function send_abandoned_cart_message($cart) {
        $enabled = get_option('msgway_abandoned_cart_enabled', '0');
        $template_id = get_option('msgway_abandoned_cart_template_id', 0);
        $provider = get_option('msgway_abandoned_cart_provider', 'sms_1');

        if (!$enabled || !$template_id) {
            return;
        }

        // Assume mobile is retrieved from user or cart data (requires custom logic or plugin)
        $mobile = $this->get_cart_user_mobile($cart);
        if (!$mobile) {
            return;
        }

        $data = array(
            'method' => $this->get_message_method($provider),
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'params' => array('سبد خرید رها شده'),
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', 'سبد خرید رها شده', $response);

        // Allow developers to extend this functionality
        do_action('msgway_after_abandoned_cart_message', $cart, $response);
    }

    /**
     * Send promotional SMS to customers (triggered manually via admin).
     */
    public function send_promotional_message() {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied');
        }

        $enabled = get_option('msgway_promotional_enabled', '0');
        $template_id = get_option('msgway_promotional_template_id', 0);
        $provider = get_option('msgway_promotional_provider', 'sms_1');

        if (!$enabled || !$template_id) {
            wp_die('Promotional messages are disabled or not configured.');
        }

        // Example: Get customers with recent orders
        $orders = wc_get_orders(array('limit' => 100, 'status' => 'completed'));
        foreach ($orders as $order) {
            $mobile = $order->get_billing_phone();
            if (!$mobile) {
                continue;
            }

            $data = array(
                'method' => $this->get_message_method($provider),
                'mobile' => preg_replace('/^\+/', '', $mobile),
                'templateID' => $template_id,
                'params' => array($order->get_billing_first_name()),
                'countryCode' => (int) substr($mobile, 1, 2),
            );

            $response = $this->api->send_message($data);
            do_action('msgway_log_message', 'پیام تبلیغاتی', $response);
        }

        wp_redirect(admin_url('admin.php?page=msgway-sms#woocommerce'));
        exit;
    }

    /**
     * Helper function to get message method based on provider.
     *
     * @param string $provider Provider ID.
     * @return string Message method (sms, messenger, etc.).
     */
    private function get_message_method($provider) {
        switch ($provider) {
            case 'messenger_1':
            case 'messenger_2':
                return 'messenger';
            default:
                return 'sms';
        }
    }

    /**
     * Helper function to get mobile number from cart (requires custom logic or plugin).
     *
     * @param WC_Cart $cart Cart object.
     * @return string|null Mobile number or null if not available.
     */
    private function get_cart_user_mobile($cart) {
        // Placeholder: Replace with actual logic or plugin integration
        // Example: Use a plugin like WooCommerce Cart Abandonment Recovery
        return null; // Replace with actual mobile number retrieval
    }
}
?>