<?php
class MsgWay_User_Notifications {
    private $api;

    public function __construct() {
        $this->api = new MsgWay_API();
    }

    public function init() {
        add_action('user_register', array($this, 'send_welcome_message'));
        add_action('retrieve_password', array($this, 'send_password_reset_otp'));
    }

    public function send_welcome_message($user_id) {
        $enabled = get_option('msgway_welcome_enabled', '0');
        if (!$enabled) {
            return;
        }

        $user = get_userdata($user_id);
        $mobile = get_user_meta($user_id, 'mobile', true);
        if (!$mobile) {
            return;
        }

        $template_id = get_option('msgway_welcome_template_id', 0);
        if (!$template_id) {
            return;
        }

        $data = array(
            'method' => 'sms',
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'params' => array($user->user_login),
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', 'پیام خوش‌آمدگویی', $response);
    }

    public function send_password_reset_otp($user_login) {
        $enabled = get_option('msgway_reset_password_enabled', '0');
        if (!$enabled) {
            return;
        }

        $user = get_user_by('login', $user_login);
        $mobile = get_user_meta($user->ID, 'mobile', true);
        if (!$mobile) {
            return;
        }

        $otp_code = wp_rand(100000, 999999);
        update_user_meta($user->ID, 'password_reset_otp', $otp_code);

        $template_id = get_option('msgway_reset_password_template_id', 0);
        if (!$template_id) {
            return;
        }

        $data = array(
            'method' => 'sms',
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'code' => (string) $otp_code,
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', 'OTP بازنشانی رمز', $response);
    }
}
?>