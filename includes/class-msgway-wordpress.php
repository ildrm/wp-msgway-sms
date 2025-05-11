<?php
/**
 * MsgWay_Wordpress class handles WordPress-related SMS notifications.
 */
class MsgWay_Wordpress {
    private $api;

    /**
     * Constructor to initialize the API.
     */
    public function __construct() {
        $this->api = new MsgWay_API();
    }

    /**
     * Initialize WordPress hooks.
     */
    public function init() {
        // Hook for user registration
        add_action('user_register', array($this, 'send_welcome_message'));
        
        // Hook for password reset
        add_action('retrieve_password', array($this, 'send_password_reset_otp'));
        
        // Hook for post publication
        add_action('publish_post', array($this, 'send_post_published_message'), 10, 2);
        
        // Hook for post status transition
        add_action('transition_post_status', array($this, 'send_post_status_changed_message'), 10, 3);
        
        // Hook for comment posting
        add_action('comment_post', array($this, 'send_comment_posted_message'), 10, 2);
    }

    /**
     * Send welcome message to new users.
     *
     * @param int $user_id User ID.
     */
    public function send_welcome_message($user_id) {
        $enabled = get_option('msgway_welcome_enabled', '0');
        $template_id = get_option('msgway_welcome_template_id', 0);
        $provider = get_option('msgway_welcome_provider', 'sms_1');

        if (!$enabled || !$template_id) {
            return;
        }

        $user = get_userdata($user_id);
        $mobile = get_user_meta($user_id, 'mobile', true);
        if (!$mobile) {
            return;
        }

        $data = array(
            'method' => $this->get_message_method($provider),
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'params' => array($user->user_login),
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', 'پیام خوش‌آمدگویی', $response);
    }

    /**
     * Send OTP for password reset.
     *
     * @param string $user_login User login.
     */
    public function send_password_reset_otp($user_login) {
        $enabled = get_option('msgway_reset_password_enabled', '0');
        $template_id = get_option('msgway_reset_password_template_id', 0);
        $provider = get_option('msgway_reset_password_provider', 'sms_1');

        if (!$enabled || !$template_id) {
            return;
        }

        $user = get_user_by('login', $user_login);
        $mobile = get_user_meta($user->ID, 'mobile', true);
        if (!$mobile) {
            return;
        }

        $otp_code = wp_rand(100000, 999999);
        update_user_meta($user->ID, 'password_reset_otp', $otp_code);

        $data = array(
            'method' => $this->get_message_method($provider),
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'params' => array((string)$otp_code),
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', 'OTP بازنشانی رمز عبور', $response);
    }

    /**
     * Send message when a post is published.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     */
    public function send_post_published_message($post_id, $post) {
        $enabled = get_option('msgway_post_published_enabled', '0');
        $template_id = get_option('msgway_post_published_template_id', 0);
        $provider = get_option('msgway_post_published_provider', 'sms_1');

        if (!$enabled || !$template_id) {
            return;
        }

        // Send to all users with mobile number (example: subscribers)
        $users = get_users(array('role' => 'subscriber'));
        foreach ($users as $user) {
            $mobile = get_user_meta($user->ID, 'mobile', true);
            if (!$mobile) {
                continue;
            }

            $data = array(
                'method' => $this->get_message_method($provider),
                'mobile' => preg_replace('/^\+/', '', $mobile),
                'templateID' => $template_id,
                'params' => array($post->post_title),
                'countryCode' => (int) substr($mobile, 1, 2),
            );

            $response = $this->api->send_message($data);
            do_action('msgway_log_message', 'انتشار پست جدید', $response);
        }
    }

    /**
     * Send message when a post status changes.
     *
     * @param string  $new_status New post status.
     * @param string  $old_status Old post status.
     * @param WP_Post $post       Post object.
     */
    public function send_post_status_changed_message($new_status, $old_status, $post) {
        $enabled = get_option('msgway_post_status_changed_enabled', '0');
        $template_id = get_option('msgway_post_status_changed_template_id', 0);
        $provider = get_option('msgway_post_status_changed_provider', 'sms_1');

        if (!$enabled || !$template_id || $new_status === $old_status) {
            return;
        }

        // Send to all users with mobile number (example: subscribers)
        $users = get_users(array('role' => 'subscriber'));
        foreach ($users as $user) {
            $mobile = get_user_meta($user->ID, 'mobile', true);
            if (!$mobile) {
                continue;
            }

            $data = array(
                'method' => $this->get_message_method($provider),
                'mobile' => preg_replace('/^\+/', '', $mobile),
                'templateID' => $template_id,
                'params' => array($post->post_title, $new_status),
                'countryCode' => (int) substr($mobile, 1, 2),
            );

            $response = $this->api->send_message($data);
            do_action('msgway_log_message', 'تغییر وضعیت پست', $response);
        }
    }

    /**
     * Send message when a comment is posted.
     *
     * @param int $comment_id Comment ID.
     * @param int $comment_approved Comment approval status.
     */
    public function send_comment_posted_message($comment_id, $comment_approved) {
        $enabled = get_option('msgway_comment_posted_enabled', '0');
        $template_id = get_option('msgway_comment_posted_template_id', 0);
        $provider = get_option('msgway_comment_posted_provider', 'sms_1');

        if (!$enabled || !$template_id || $comment_approved !== 1) {
            return;
        }

        $comment = get_comment($comment_id);
        $post = get_post($comment->comment_post_ID);
        $author = get_user_by('id', $post->post_author);

        $mobile = get_user_meta($author->ID, 'mobile', true);
        if (!$mobile) {
            return;
        }

        $data = array(
            'method' => $this->get_message_method($provider),
            'mobile' => preg_replace('/^\+/', '', $mobile),
            'templateID' => $template_id,
            'params' => array($post->post_title, $comment->comment_author),
            'countryCode' => (int) substr($mobile, 1, 2),
        );

        $response = $this->api->send_message($data);
        do_action('msgway_log_message', 'ثبت نظر جدید', $response);
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
}
?>