<?php
class MsgWay_Plugin {
    public function init() {
        // Load dependencies
        require_once MSGWAY_SMS_DIR . 'includes/class-msgway-api.php';
        require_once MSGWAY_SMS_DIR . 'includes/class-msgway-settings.php';
        require_once MSGWAY_SMS_DIR . 'includes/class-msgway-otp.php';
        require_once MSGWAY_SMS_DIR . 'includes/class-msgway-wordpress.php';
        require_once MSGWAY_SMS_DIR . 'includes/class-msgway-logs.php';

        // Initialize components
        $settings = new MsgWay_Settings();
        $settings->init();

        $otp = new MsgWay_OTP();
        $otp->init();

        $wordpress = new MsgWay_Wordpress();
        $wordpress->init();

        $logs = new MsgWay_Logs();
        $logs->init();

        // Load WooCommerce integration if active
        if (class_exists('WooCommerce')) {
            require_once MSGWAY_SMS_DIR . 'includes/class-msgway-woocommerce.php';
            $woocommerce = new MsgWay_WooCommerce();
            $woocommerce->init();
        }

        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'msgway-sms') !== false || strpos($hook, 'msgway-logs') !== false) {
            wp_enqueue_style('msgway-admin-css', MSGWAY_SMS_URL . 'admin/css/style.css', array(), MSGWAY_SMS_VERSION);
            wp_enqueue_script('msgway-admin-js', MSGWAY_SMS_URL . 'admin/js/script.js', array('jquery'), MSGWAY_SMS_VERSION, true);
        }
    }
}
?>