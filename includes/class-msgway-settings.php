<?php
class MsgWay_Settings {
    public function init() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_settings_page() {
        add_menu_page(
            __('پیام‌رسان MsgWay', 'msgway-sms'),
            __('پیام‌رسان MsgWay', 'msgway-sms'),
            'manage_options',
            'msgway-sms',
            array($this, 'render_settings_page'),
            'dashicons-sms'
        );
    }

    public function register_settings() {
        // تنظیمات عمومی
        register_setting('msgway_settings', 'msgway_api_key', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('msgway_settings', 'msgway_language', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('msgway_settings', 'msgway_default_provider', array('sanitize_callback' => 'sanitize_text_field'));

        add_settings_section(
            'msgway_general_section',
            __('تنظیمات اصلی', 'msgway-sms'),
            array($this, 'general_section_callback'),
            'msgway-sms-general'
        );

        add_settings_field(
            'msgway_api_key',
            __('کلید API', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-general',
            'msgway_general_section',
            array('option' => 'msgway_api_key', 'description' => __('کلید API خود را از سایت msgway.com دریافت کنید.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_general_hr_1',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-general',
            'msgway_general_section'
        );

        add_settings_field(
            'msgway_language',
            __('زبان پیام‌ها', 'msgway-sms'),
            array($this, 'render_select_field'),
            'msgway-sms-general',
            'msgway_general_section',
            array(
                'option' => 'msgway_language',
                'options' => array('fa' => 'فارسی', 'en' => 'انگلیسی'),
                'description' => __('زبان مورد استفاده برای پیام‌ها و پنل تنظیمات.', 'msgway-sms')
            )
        );

        add_settings_field(
            'msgway_general_hr_2',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-general',
            'msgway_general_section'
        );

        add_settings_field(
            'msgway_default_provider',
            __('سرویس پیش‌فرض پیام‌رسان', 'msgway-sms'),
            array($this, 'render_select_field'),
            'msgway-sms-general',
            'msgway_general_section',
            array(
                'option' => 'msgway_default_provider',
                'options' => array(
                    'sms_1' => 'SMS 3000x',
                    'sms_2' => 'SMS 2000x',
                    'sms_3' => 'SMS 9000x',
                    'messenger_1' => 'WhatsApp',
                    'messenger_2' => 'Gap'
                ),
                'description' => __('سرویس پیش‌فرض برای ارسال پیام‌ها.', 'msgway-sms')
            )
        );

        // تنظیمات OTP
        register_setting('msgway_settings', 'msgway_otp_enabled', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_otp_template_id', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_otp_expire_time', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_otp_length', array('sanitize_callback' => 'absint'));

        add_settings_section(
            'msgway_otp_section',
            __('تنظیمات کد یک‌بارمصرف (OTP)', 'msgway-sms'),
            array($this, 'otp_section_callback'),
            'msgway-sms-otp'
        );

        add_settings_field(
            'msgway_otp_enabled',
            __('فعال‌سازی OTP', 'msgway-sms'),
            array($this, 'render_checkbox_field'),
            'msgway-sms-otp',
            'msgway_otp_section',
            array('option' => 'msgway_otp_enabled', 'description' => __('ارسال کد یک‌بارمصرف برای ورود یا ثبت‌نام کاربران.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_otp_hr_1',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-otp',
            'msgway_otp_section'
        );

        add_settings_field(
            'msgway_otp_template_id',
            __('شناسه قالب OTP', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-otp',
            'msgway_otp_section',
            array('option' => 'msgway_otp_template_id', 'description' => __('شناسه قالب OTP را از msgway.com وارد کنید.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_otp_hr_2',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-otp',
            'msgway_otp_section'
        );

        add_settings_field(
            'msgway_otp_expire_time',
            __('مدت اعتبار OTP (ثانیه)', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-otp',
            'msgway_otp_section',
            array('option' => 'msgway_otp_expire_time', 'description' => __('مدت زمانی که کد OTP معتبر است (مثلاً 300 برای 5 دقیقه).', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_otp_hr_3',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-otp',
            'msgway_otp_section'
        );

        add_settings_field(
            'msgway_otp_length',
            __('تعداد ارقام OTP', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-otp',
            'msgway_otp_section',
            array('option' => 'msgway_otp_length', 'description' => __('تعداد ارقام کد OTP (مثلاً 6).', 'msgway-sms'))
        );

        // تنظیمات وردپرس
        register_setting('msgway_settings', 'msgway_welcome_enabled', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_welcome_template_id', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_welcome_provider', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('msgway_settings', 'msgway_reset_password_enabled', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_reset_password_template_id', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_reset_password_provider', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('msgway_settings', 'msgway_post_published_enabled', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_post_published_template_id', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_post_published_provider', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('msgway_settings', 'msgway_post_status_changed_enabled', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_post_status_changed_template_id', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_post_status_changed_provider', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('msgway_settings', 'msgway_comment_posted_enabled', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_comment_posted_template_id', array('sanitize_callback' => 'absint'));
        register_setting('msgway_settings', 'msgway_comment_posted_provider', array('sanitize_callback' => 'sanitize_text_field'));

        add_settings_section(
            'msgway_wordpress_section',
            __('تنظیمات پیام‌های وردپرس', 'msgway-sms'),
            array($this, 'wordpress_section_callback'),
            'msgway-sms-wordpress'
        );

        add_settings_field(
            'msgway_welcome_enabled',
            __('ارسال پیام خوش‌آمدگویی', 'msgway-sms'),
            array($this, 'render_checkbox_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_welcome_enabled', 'description' => __('ارسال پیام به کاربران پس از ثبت‌نام در سایت.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_welcome_template_id',
            __('شناسه قالب پیام خوش‌آمدگویی', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_welcome_template_id', 'description' => __('شناسه قالب پیام خوش‌آمدگویی از msgway.com.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_welcome_provider',
            __('سرویس پیام خوش‌آمدگویی', 'msgway-sms'),
            array($this, 'render_select_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array(
                'option' => 'msgway_welcome_provider',
                'options' => array(
                    'sms_1' => 'SMS 3000x',
                    'sms_2' => 'SMS 2000x',
                    'sms_3' => 'SMS 9000x',
                    'messenger_1' => 'WhatsApp',
                    'messenger_2' => 'Gap'
                ),
                'description' => __('سرویس مورد استفاده برای ارسال پیام خوش‌آمدگویی.', 'msgway-sms')
            )
        );

        add_settings_field(
            'msgway_wordpress_hr_1',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section'
        );

        add_settings_field(
            'msgway_reset_password_enabled',
            __('ارسال پیام بازنشانی رمز عبور', 'msgway-sms'),
            array($this, 'render_checkbox_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_reset_password_enabled', 'description' => __('ارسال کد برای بازنشانی رمز عبور کاربران.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_reset_password_template_id',
            __('شناسه قالب بازنشانی رمز عبور', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_reset_password_template_id', 'description' => __('شناسه قالب پیام بازنشانی رمز از msgway.com.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_reset_password_provider',
            __('سرویس پیام بازنشانی رمز عبور', 'msgway-sms'),
            array($this, 'render_select_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array(
                'option' => 'msgway_reset_password_provider',
                'options' => array(
                    'sms_1' => 'SMS 3000x',
                    'sms_2' => 'SMS 2000x',
                    'sms_3' => 'SMS 9000x',
                    'messenger_1' => 'WhatsApp',
                    'messenger_2' => 'Gap'
                ),
                'description' => __('سرویس مورد استفاده برای ارسال پیام بازنشانی رمز عبور.', 'msgway-sms')
            )
        );

        add_settings_field(
            'msgway_wordpress_hr_2',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section'
        );

        add_settings_field(
            'msgway_post_published_enabled',
            __('ارسال پیام برای انتشار پست جدید', 'msgway-sms'),
            array($this, 'render_checkbox_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_post_published_enabled', 'description' => __('ارسال پیام به کاربران هنگام انتشار پست جدید.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_post_published_template_id',
            __('شناسه قالب پیام انتشار پست', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_post_published_template_id', 'description' => __('شناسه قالب پیام انتشار پست از msgway.com.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_post_published_provider',
            __('سرویس پیام انتشار پست', 'msgway-sms'),
            array($this, 'render_select_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array(
                'option' => 'msgway_post_published_provider',
                'options' => array(
                    'sms_1' => 'SMS 3000x',
                    'sms_2' => 'SMS 2000x',
                    'sms_3' => 'SMS 9000x',
                    'messenger_1' => 'WhatsApp',
                    'messenger_2' => 'Gap'
                ),
                'description' => __('سرویس مورد استفاده برای ارسال پیام انتشار پست.', 'msgway-sms')
            )
        );

        add_settings_field(
            'msgway_wordpress_hr_3',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section'
        );

        add_settings_field(
            'msgway_post_status_changed_enabled',
            __('ارسال پیام برای تغییر وضعیت پست', 'msgway-sms'),
            array($this, 'render_checkbox_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_post_status_changed_enabled', 'description' => __('ارسال پیام به کاربران هنگام تغییر وضعیت پست (مثل پیش‌نویس به منتشرشده).', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_post_status_changed_template_id',
            __('شناسه قالب پیام تغییر وضعیت پست', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_post_status_changed_template_id', 'description' => __('شناسه قالب پیام تغییر وضعیت پست از msgway.com.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_post_status_changed_provider',
            __('سرویس پیام تغییر وضعیت پست', 'msgway-sms'),
            array($this, 'render_select_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array(
                'option' => 'msgway_post_status_changed_provider',
                'options' => array(
                    'sms_1' => 'SMS 3000x',
                    'sms_2' => 'SMS 2000x',
                    'sms_3' => 'SMS 9000x',
                    'messenger_1' => 'WhatsApp',
                    'messenger_2' => 'Gap'
                ),
                'description' => __('سرویس مورد استفاده برای ارسال پیام تغییر وضعیت پست.', 'msgway-sms')
            )
        );

        add_settings_field(
            'msgway_wordpress_hr_4',
            '',
            array($this, 'render_hr_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section'
        );

        add_settings_field(
            'msgway_comment_posted_enabled',
            __('ارسال پیام برای ثبت نظر جدید', 'msgway-sms'),
            array($this, 'render_checkbox_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_comment_posted_enabled', 'description' => __('ارسال پیام به کاربران هنگام ثبت نظر جدید.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_comment_posted_template_id',
            __('شناسه قالب پیام ثبت نظر', 'msgway-sms'),
            array($this, 'render_text_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array('option' => 'msgway_comment_posted_template_id', 'description' => __('شناسه قالب پیام ثبت نظر از msgway.com.', 'msgway-sms'))
        );

        add_settings_field(
            'msgway_comment_posted_provider',
            __('سرویس پیام ثبت نظر', 'msgway-sms'),
            array($this, 'render_select_field'),
            'msgway-sms-wordpress',
            'msgway_wordpress_section',
            array(
                'option' => 'msgway_comment_posted_provider',
                'options' => array(
                    'sms_1' => 'SMS 3000x',
                    'sms_2' => 'SMS 2000x',
                    'sms_3' => 'SMS 9000x',
                    'messenger_1' => 'WhatsApp',
                    'messenger_2' => 'Gap'
                ),
                'description' => __('سرویس مورد استفاده برای ارسال پیام ثبت نظر.', 'msgway-sms')
            )
        );

        // تنظیمات ووکامرس
        if (class_exists('WooCommerce')) {
            $wc_statuses = wc_get_order_statuses();
            foreach ($wc_statuses as $status => $label) {
                $status_key = str_replace('wc-', '', $status);
                register_setting('msgway_settings', "msgway_order_{$status_key}_enabled", array('sanitize_callback' => 'absint'));
                register_setting('msgway_settings', "msgway_order_{$status_key}_template_id", array('sanitize_callback' => 'absint'));
                register_setting('msgway_settings', "msgway_order_{$status_key}_provider", array('sanitize_callback' => 'sanitize_text_field'));

                // Add fields with a unique section for each status
                add_settings_section(
                    "msgway_woocommerce_{$status_key}_section",
                    sprintf(__('وضعیت سفارش: %s', 'msgway-sms'), $label),
                    '__return_null',
                    "msgway-sms-woocommerce-{$status_key}"
                );

                add_settings_field(
                    "msgway_order_{$status_key}_enabled",
                    __('فعال‌سازی پیام', 'msgway-sms'),
                    array($this, 'render_checkbox_field'),
                    "msgway-sms-woocommerce-{$status_key}",
                    "msgway_woocommerce_{$status_key}_section",
                    array('option' => "msgway_order_{$status_key}_enabled", 'description' => sprintf(__('پیام هنگام تغییر وضعیت سفارش به "%s" ارسال شود.', 'msgway-sms'), $label))
                );

                add_settings_field(
                    "msgway_woocommerce_{$status_key}_hr_1",
                    '',
                    array($this, 'render_hr_field'),
                    "msgway-sms-woocommerce-{$status_key}",
                    "msgway_woocommerce_{$status_key}_section"
                );

                add_settings_field(
                    "msgway_order_{$status_key}_template_id",
                    __('شناسه قالب پیام', 'msgway-sms'),
                    array($this, 'render_text_field'),
                    "msgway-sms-woocommerce-{$status_key}",
                    "msgway_woocommerce_{$status_key}_section",
                    array('option' => "msgway_order_{$status_key}_template_id", 'description' => sprintf(__('شناسه قالب پیام برای وضعیت "%s" از msgway.com.', 'msgway-sms'), $label))
                );

                add_settings_field(
                    "msgway_woocommerce_{$status_key}_hr_2",
                    '',
                    array($this, 'render_hr_field'),
                    "msgway-sms-woocommerce-{$status_key}",
                    "msgway_woocommerce_{$status_key}_section"
                );

                add_settings_field(
                    "msgway_order_{$status_key}_provider",
                    __('سرویس پیام', 'msgway-sms'),
                    array($this, 'render_select_field'),
                    "msgway-sms-woocommerce-{$status_key}",
                    "msgway_woocommerce_{$status_key}_section",
                    array(
                        'option' => "msgway_order_{$status_key}_provider",
                        'options' => array(
                            'sms_1' => 'SMS 3000x',
                            'sms_2' => 'SMS 2000x',
                            'sms_3' => 'SMS 9000x',
                            'messenger_1' => 'WhatsApp',
                            'messenger_2' => 'Gap'
                        ),
                        'description' => sprintf(__('سرویس مورد استفاده برای ارسال پیام وضعیت "%s".', 'msgway-sms'), $label)
                    )
                );
            }

            // تنظیمات عمومی ووکامرس (تأیید سفارش، سبد خرید رها شده، تبلیغاتی)
            register_setting('msgway_settings', 'msgway_order_confirmation_enabled', array('sanitize_callback' => 'absint'));
            register_setting('msgway_settings', 'msgway_order_confirmation_template_id', array('sanitize_callback' => 'absint'));
            register_setting('msgway_settings', 'msgway_order_confirmation_provider', array('sanitize_callback' => 'sanitize_text_field'));
            register_setting('msgway_settings', 'msgway_abandoned_cart_enabled', array('sanitize_callback' => 'absint'));
            register_setting('msgway_settings', 'msgway_abandoned_cart_template_id', array('sanitize_callback' => 'absint'));
            register_setting('msgway_settings', 'msgway_abandoned_cart_provider', array('sanitize_callback' => 'sanitize_text_field'));
            register_setting('msgway_settings', 'msgway_promotional_enabled', array('sanitize_callback' => 'absint'));
            register_setting('msgway_settings', 'msgway_promotional_template_id', array('sanitize_callback' => 'absint'));
            register_setting('msgway_settings', 'msgway_promotional_provider', array('sanitize_callback' => 'sanitize_text_field'));

            add_settings_section(
                'msgway_woocommerce_general_section',
                __('تنظیمات عمومی فروشگاه', 'msgway-sms'),
                array($this, 'woocommerce_section_callback'),
                'msgway-sms-woocommerce-general'
            );

            add_settings_field(
                'msgway_order_confirmation_enabled',
                __('ارسال پیام تأیید سفارش', 'msgway-sms'),
                array($this, 'render_checkbox_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array('option' => 'msgway_order_confirmation_enabled', 'description' => __('پیام بلافاصله پس از ثبت سفارش توسط مشتری ارسال شود.', 'msgway-sms'))
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_1',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_order_confirmation_template_id',
                __('شناسه قالب پیام تأیید سفارش', 'msgway-sms'),
                array($this, 'render_text_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array('option' => 'msgway_order_confirmation_template_id', 'description' => __('شناسه قالب پیام تأیید سفارش از msgway.com.', 'msgway-sms'))
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_2',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_order_confirmation_provider',
                __('سرویس پیام تأیید سفارش', 'msgway-sms'),
                array($this, 'render_select_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array(
                    'option' => 'msgway_order_confirmation_provider',
                    'options' => array(
                        'sms_1' => 'SMS 3000x',
                        'sms_2' => 'SMS 2000x',
                        'sms_3' => 'SMS 9000x',
                        'messenger_1' => 'WhatsApp',
                        'messenger_2' => 'Gap'
                    ),
                    'description' => __('سرویس مورد استفاده برای ارسال پیام تأیید سفارش.', 'msgway-sms')
                )
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_3',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_abandoned_cart_enabled',
                __('ارسال پیام سبد خرید رها شده', 'msgway-sms'),
                array($this, 'render_checkbox_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array('option' => 'msgway_abandoned_cart_enabled', 'description' => __('پیام برای سبدهای خریدی که کاربران رها کرده‌اند ارسال شود.', 'msgway-sms'))
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_4',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_abandoned_cart_template_id',
                __('شناسه قالب پیام سبد خرید رها شده', 'msgway-sms'),
                array($this, 'render_text_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array('option' => 'msgway_abandoned_cart_template_id', 'description' => __('شناسه قالب پیام سبد خرید رها شده از msgway.com.', 'msgway-sms'))
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_5',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_abandoned_cart_provider',
                __('سرویس پیام سبد خرید رها شده', 'msgway-sms'),
                array($this, 'render_select_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array(
                    'option' => 'msgway_abandoned_cart_provider',
                    'options' => array(
                        'sms_1' => 'SMS 3000x',
                        'sms_2' => 'SMS 2000x',
                        'sms_3' => 'SMS 9000x',
                        'messenger_1' => 'WhatsApp',
                        'messenger_2' => 'Gap'
                    ),
                    'description' => __('سرویس مورد استفاده برای ارسال پیام سبد خرید رها شده.', 'msgway-sms')
                )
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_6',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_promotional_enabled',
                __('ارسال پیام‌های تبلیغاتی', 'msgway-sms'),
                array($this, 'render_checkbox_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array('option' => 'msgway_promotional_enabled', 'description' => __('ارسال پیام‌های تبلیغاتی به مشتریان فروشگاه.', 'msgway-sms'))
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_7',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_promotional_template_id',
                __('شناسه قالب پیام تبلیغاتی', 'msgway-sms'),
                array($this, 'render_text_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array('option' => 'msgway_promotional_template_id', 'description' => __('شناسه قالب پیام تبلیغاتی از msgway.com.', 'msgway-sms'))
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_8',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_promotional_provider',
                __('سرویس پیام تبلیغاتی', 'msgway-sms'),
                array($this, 'render_select_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array(
                    'option' => 'msgway_promotional_provider',
                    'options' => array(
                        'sms_1' => 'SMS 3000x',
                        'sms_2' => 'SMS 2000x',
                        'sms_3' => 'SMS 9000x',
                        'messenger_1' => 'WhatsApp',
                        'messenger_2' => 'Gap'
                    ),
                    'description' => __('سرویس مورد استفاده برای ارسال پیام‌های تبلیغاتی.', 'msgway-sms')
                )
            );

            add_settings_field(
                'msgway_woocommerce_general_hr_9',
                '',
                array($this, 'render_hr_field'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section'
            );

            add_settings_field(
                'msgway_promotional_send',
                __('ارسال پیام‌های تبلیغاتی', 'msgway-sms'),
                array($this, 'render_promotional_send_button'),
                'msgway-sms-woocommerce-general',
                'msgway_woocommerce_general_section',
                array('description' => __('ارسال پیام‌های تبلیغاتی به مشتریانی که سفارش کامل‌شده دارند.', 'msgway-sms'))
            );
        }
    }

    public function general_section_callback() {
        echo '<p>' . __('تنظیمات کلی افزونه پیام‌رسان MsgWay برای اتصال به سرویس و تنظیمات پایه.', 'msgway-sms') . '</p>';
    }

    public function otp_section_callback() {
        echo '<p>' . __('تنظیمات مربوط به ارسال کدهای یک‌بارمصرف (OTP) برای ورود و ثبت‌نام.', 'msgway-sms') . '</p>';
    }

    public function wordpress_section_callback() {
        echo '<p>' . __('تنظیمات پیام‌های مربوط به فعالیت‌های وردپرس مانند ثبت‌نام، انتشار پست، و نظرات.', 'msgway-sms') . '</p>';
    }

    public function woocommerce_section_callback() {
        echo '<p>' . __('تنظیمات پیام‌های مربوط به فروشگاه ووکامرس، مانند وضعیت سفارش و پیام‌های تبلیغاتی.', 'msgway-sms') . '</p>';
    }

    public function logs_section_callback() {
        echo '<p>' . __('نمایش گزارش‌های پیام‌های ارسالی و وضعیت آنها.', 'msgway-sms') . '</p>';
    }

    public function render_text_field($args) {
        $value = get_option($args['option'], '');
        echo '<input type="text" name="' . esc_attr($args['option']) . '" value="' . esc_attr($value) . '" class="regular-text" />';
        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
    }

    public function render_checkbox_field($args) {
        $value = get_option($args['option'], '0');
        echo '<input type="checkbox" name="' . esc_attr($args['option']) . '" value="1" ' . checked(1, $value, false) . ' />';
        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
    }

    public function render_select_field($args) {
        $value = get_option($args['option'], '');
        echo '<select name="' . esc_attr($args['option']) . '">';
        foreach ($args['options'] as $key => $label) {
            echo '<option value="' . esc_attr($key) . '" ' . selected($value, $key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
    }

    public function render_promotional_send_button($args) {
        echo '<a href="' . wp_nonce_url(admin_url('admin-post.php?action=msgway_send_promotional'), 'msgway_send_promotional') . '" class="button">' . __('ارسال پیام‌های تبلیغاتی', 'msgway-sms') . '</a>';
        if (!empty($args['description'])) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
    }

    public function render_hr_field() {
        echo '<hr class="msgway-hr">';
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('تنظیمات پیام‌رسان MsgWay', 'msgway-sms'); ?></h1>
            <div class="nav-tab-wrapper">
                <a href="#general" class="nav-tab nav-tab-active"><?php _e('تنظیمات اصلی', 'msgway-sms'); ?></a>
                <a href="#otp" class="nav-tab"><?php _e('کد OTP', 'msgway-sms'); ?></a>
                <a href="#wordpress" class="nav-tab"><?php _e('وردپرس', 'msgway-sms'); ?></a>
                <?php if (class_exists('WooCommerce')): ?>
                    <a href="#woocommerce" class="nav-tab"><?php _e('فروشگاه (ووکامرس)', 'msgway-sms'); ?></a>
                <?php endif; ?>
                <a href="#logs" class="nav-tab"><?php _e('گزارش‌ها', 'msgway-sms'); ?></a>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('msgway_settings'); ?>
                <div id="general" class="tab-content">
                    <?php do_settings_sections('msgway-sms-general'); ?>
                </div>
                <div id="otp" class="tab-content" style="display:none;">
                    <?php do_settings_sections('msgway-sms-otp'); ?>
                </div>
                <div id="wordpress" class="tab-content" style="display:none;">
                    <?php do_settings_sections('msgway-sms-wordpress'); ?>
                </div>
                <?php if (class_exists('WooCommerce')): ?>
                    <div id="woocommerce" class="tab-content" style="display:none;">
                        <h2><?php _e('تنظیمات پیام‌های فروشگاه (ووکامرس)', 'msgway-sms'); ?></h2>
                        <?php $this->woocommerce_section_callback(); ?>
                        <?php do_settings_sections('msgway-sms-woocommerce-general'); ?>
                        <h3><?php _e('تنظیمات وضعیت‌های سفارش', 'msgway-sms'); ?></h3>
                        <?php
                        $wc_statuses = wc_get_order_statuses();
                        foreach ($wc_statuses as $status => $label) {
                            $status_key = str_replace('wc-', '', $status);
                            ?>
                            <fieldset class="msgway-fieldset">
                                <?php do_settings_sections("msgway-sms-woocommerce-{$status_key}"); ?>
                            </fieldset>
                            <?php
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <div id="logs" class="tab-content" style="display:none;">
                    <h2><?php _e('گزارش‌های پیام‌ها', 'msgway-sms'); ?></h2>
                    <?php $this->logs_section_callback(); ?>
                    <?php
                    $logs = new MsgWay_Logs();
                    $logs->render_logs_page();
                    ?>
                </div>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'msgway-sms') !== false) {
            wp_enqueue_style('msgway-admin-css', MSGWAY_SMS_URL . 'admin/css/style.css', array(), MSGWAY_SMS_VERSION);
            wp_enqueue_script('msgway-admin-js', MSGWAY_SMS_URL . 'admin/js/script.js', array('jquery'), MSGWAY_SMS_VERSION, true);
        }
    }
}
?>