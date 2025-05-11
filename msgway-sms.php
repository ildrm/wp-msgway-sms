<?php
/**
 * Plugin Name: MsgWay SMS
 * Plugin URI: https://msgway.com
 * Description: افزونه پیام‌رسان MsgWay برای ارسال پیام‌های SMS و پیام‌رسان به کاربران وردپرس و ووکامرس.
 * Version: 1.0.0
 * Author: MsgWay Team
 * Author URI: https://msgway.com
 * Text Domain: msgway-sms
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('MSGWAY_SMS_VERSION', '1.0.0');
define('MSGWAY_SMS_FILE', __FILE__);
define('MSGWAY_SMS_DIR', plugin_dir_path(__FILE__));
define('MSGWAY_SMS_URL', plugin_dir_url(__FILE__));

// Load plugin text domain for translations
add_action('plugins_loaded', function() {
    load_plugin_textdomain('msgway-sms', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Include required classes
require_once MSGWAY_SMS_DIR . 'includes/class-msgway-logs-table.php'; // Load logs table first
require_once MSGWAY_SMS_DIR . 'includes/class-msgway-logs.php';
require_once MSGWAY_SMS_DIR . 'includes/class-msgway-plugin.php';

// Initialize the main plugin class
if (class_exists('MsgWay_Plugin')) {
    $msgway_plugin = new MsgWay_Plugin();
    $msgway_plugin->init();
} else {
    error_log('MsgWay SMS: Main plugin class file is missing.');
}

// Initialize the logs class
if (class_exists('MsgWay_Logs')) {
    $msgway_logs = new MsgWay_Logs();
    $msgway_logs->init();
} else {
    error_log('MsgWay SMS: Logs class file is missing.');
}
?>