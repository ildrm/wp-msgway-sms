<?php
class MsgWay_API {
    private $api_key;
    private $base_url = 'https://api.msgway.com';

    public function __construct() {
        $this->api_key = get_option('msgway_api_key', '');
    }

    public function send_message($data) {
        $url = $this->base_url . '/send';
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $this->api_key,
                'accept-language' => get_option('msgway_language', 'fa'),
            ),
            'body' => json_encode($data),
            'timeout' => 30,
        );
        $response = wp_remote_post($url, $args);
        if (is_wp_error($response)) {
            do_action('msgway_log_error', 'API Send Error', $response->get_error_message());
            return $response;
        }
        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function verify_otp($mobile, $otp, $country_code) {
        $data = array('mobile' => $mobile, 'OTP' => $otp, 'countryCode' => $country_code);
        $url = $this->base_url . '/otp/verify';
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $this->api_key,
                'accept-language' => get_option('msgway_language', 'fa'),
            ),
            'body' => json_encode($data),
            'timeout' => 30,
        );
        $response = wp_remote_post($url, $args);
        if (is_wp_error($response)) {
            do_action('msgway_log_error', 'OTP Verify Error', $response->get_error_message());
            return $response;
        }
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
?>