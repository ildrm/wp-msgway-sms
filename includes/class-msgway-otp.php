<?php
class MsgWay_OTP {
    private $api;

    public function __construct() {
        $this->api = new MsgWay_API();
    }

    public function init() {
        if (get_option('msgway_otp_enabled', '0')) {
            add_action('login_form', array($this, 'add_mobile_field'));
            add_action('wp_ajax_msgway_send_otp', array($this, 'send_otp'));
            add_action('wp_ajax_nopriv_msgway_send_otp', array($this, 'send_otp'));
            add_action('wp_ajax_msgway_verify_otp', array($this, 'verify_otp'));
            add_action('wp_ajax_nopriv_msgway_verify_otp', array($this, 'verify_otp'));
        }
    }

    public function add_mobile_field() {
        ?>
        <p>
            <label for="mobile"><?php _e('Mobile Number', 'msgway-sms'); ?></label>
            <input type="text" name="mobile" id="mobile" class="input" value="" placeholder="+989123456789">
        </p>
        <p id="otp-field" style="display:none;">
            <label for="otp"><?php _e('Enter OTP', 'msgway-sms'); ?></label>
            <input type="text" name="otp" id="otp" class="input">
            <button type="button" id="verify-otp"><?php _e('Verify', 'msgway-sms'); ?></button>
        </p>
        <script>
        jQuery(document).ready(function($) {
            $('#mobile').on('change', function() {
                var mobile = $(this).val();
                if (mobile) {
                    $.post(ajaxurl, { action: 'msgway_send_otp', mobile: mobile }, function(response) {
                        if (response.success) {
                            $('#otp-field').show();
                        } else {
                            alert('خطا در ارسال OTP: ' + (response.data.message || 'لطفاً دوباره تلاش کنید.'));
                        }
                    });
                }
            });
            $('#verify-otp').on('click', function() {
                var mobile = $('#mobile').val();
                var otp = $('#otp').val();
                $.post(ajaxurl, { action: 'msgway_verify_otp', mobile: mobile, otp: otp }, function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert('خطا در تأیید OTP: ' + (response.data.message || 'کد نامعتبر است.'));
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function send_otp() {
        $mobile = isset($_POST['mobile']) ? sanitize_text_field($_POST['mobile']) : '';
        if (empty($mobile) || !preg_match('/^\+\d{10,15}$/', $mobile)) {
            wp_send_json_error(array('message' => 'شماره موبایل نامعتبر است.'));
        }

        $otp_enabled = get_option('msgway_otp_enabled', '0');
        $template_id = get_option('msgway_otp_template_id', 0);
        $expire_time = get_option('msgway_otp_expire_time', 300);
        $otp_length = get_option('msgway_otp_length', 6);

        if (!$otp_enabled || !$template_id) {
            wp_send_json_error(array('message' => 'OTP غیرفعال است یا تنظیمات ناقص است.'));
        }

        $otp_code = wp_rand(pow(10, $otp_length - 1), pow(10, $otp_length) - 1);

        $data = array(
            'method' => 'sms',
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'code' => (string) $otp_code,
            'countryCode' => (int) substr($mobile, 1, 2),
            'expireTime' => $expire_time,
            'length' => $otp_length,
        );

        $response = $this->api->send_message($data);
        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => $response->get_error_message()));
        }

        if (isset($response['status']) && $response['status'] === 'success') {
            set_transient('msgway_otp_' . md5($mobile), $otp_code, $expire_time);
            wp_send_json_success();
        } else {
            wp_send_json_error(array('message' => isset($response['error']['message']) ? $response['error']['message'] : 'خطا در ارسال OTP.'));
        }
    }

    public function verify_otp() {
        $mobile = isset($_POST['mobile']) ? sanitize_text_field($_POST['mobile']) : '';
        $otp = isset($_POST['otp']) ? sanitize_text_field($_POST['otp']) : '';

        if (empty($mobile) || empty($otp)) {
            wp_send_json_error(array('message' => 'شماره موبایل یا کد OTP نامعتبر است.'));
        }

        $stored_otp = get_transient('msgway_otp_' . md5($mobile));
        if ($stored_otp && $stored_otp == $otp) {
            delete_transient('msgway_otp_' . md5($mobile));

            $user = get_users(array('meta_key' => 'mobile', 'meta_value' => $mobile));
            if ($user) {
                wp_set_auth_cookie($user[0]->ID);
            } else {
                $user_id = wp_create_user($mobile, wp_generate_password(), $mobile . '@example.com');
                if (!is_wp_error($user_id)) {
                    update_user_meta($user_id, 'mobile', $mobile);
                    wp_set_auth_cookie($user_id);
                } else {
                    wp_send_json_error(array('message' => 'خطا در ثبت‌نام کاربر.'));
                }
            }
            wp_send_json_success();
        }

        $response = $this->api->verify_otp(preg_replace('/^\+/', '', $mobile), $otp, (int) substr($mobile, 1, 2));
        if (isset($response['status']) && $response['status'] === 'success') {
            $user = get_users(array('meta_key' => 'mobile', 'meta_value' => $mobile));
            if ($user) {
                wp_set_auth_cookie($user[0]->ID);
            } else {
                $user_id = wp_create_user($mobile, wp_generate_password(), $mobile . '@example.com');
                if (!is_wp_error($user_id)) {
                    update_user_meta($user_id, 'mobile', $mobile);
                    wp_set_auth_cookie($user_id);
                } else {
                    wp_send_json_error(array('message' => 'خطا در ثبت‌نام کاربر.'));
                }
            }
            wp_send_json_success();
        }

        wp_send_json_error(array('message' => 'کد OTP نامعتبر است.'));
    }
}
?>