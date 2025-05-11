# MsgWay SMS - WordPress Plugin

![Banner](assets/banner.png)
![License](https://img.shields.io/badge/license-GPLv2-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.2%2B-blue.svg)
![Version](https://img.shields.io/badge/version-1.0.0-green.svg)

---

## English

### Overview

**MsgWay SMS**, developed by Shahin Ilderemi, is a robust and feature-rich WordPress plugin designed to streamline SMS-based communication for WordPress websites. Seamlessly integrating with WordPress core, WooCommerce, and multiple messaging providers, it empowers site owners to send notifications, one-time passwords (OTPs), and promotional messages with ease. With its intuitive interface and comprehensive logging system, MsgWay SMS ensures reliable and trackable messaging for enhanced user engagement.

### Key Features

- **Versatile Notifications**: Send automated messages for user registration, password resets, post publications, and comment activities.
- **Secure OTP Authentication**: Implement customizable OTP codes for secure user login and registration.
- **WooCommerce Integration**: Deliver order confirmations, status updates, abandoned cart reminders, and promotional campaigns to customers.
- **Detailed Message Logs**: Monitor sent messages with a filterable table displaying type, status, mobile number, and response details.
- **Multiple Providers**: Support for SMS 3000x, SMS 2000x, SMS 9000x, WhatsApp, and Gap, with flexible provider selection.
- **Multilingual Support**: Fully localized for Persian (Farsi) and English, ensuring accessibility for diverse audiences.
- **Customizable Settings**: Configure API keys, message templates, OTP settings, and provider preferences through an intuitive admin panel.

### Why Choose MsgWay SMS?

MsgWay SMS is built for performance, scalability, and ease of use. Whether you're running a blog, an e-commerce platform, or a membership site, this plugin provides a reliable messaging solution to keep your users informed and engaged. Its deep integration with WooCommerce and support for Persian makes it a perfect fit for global and Persian-speaking markets.

### Installation

1. **Download and Upload**:
   - Download the plugin ZIP file from the [releases page](https://github.com/ildrm/wp-msgway-sms/releases).
   - Upload the `wp-msgway-sms` folder to `/wp-content/plugins/` via FTP, or install directly from the WordPress admin panel (Plugins > Add New).
2. **Activate**:
   - Navigate to Plugins in your WordPress dashboard and activate **MsgWay SMS**.
3. **Configure**:
   - Go to the **MsgWay SMS** menu in the WordPress admin panel.
   - Enter your API key from [ildrm.com](https://ildrm.com) and configure settings under the General, OTP, WordPress, and WooCommerce tabs.
4. **Test**:
   - Send a test message or OTP to verify the setup.
   - Review the **Logs** tab to ensure messages are logged correctly.

### Usage

1. **General Settings**:
   - Set your API key, preferred language (Persian or English), and default messaging provider.
2. **OTP Configuration**:
   - Enable OTP, define the code length (e.g., 6 digits), and set the expiration time (e.g., 300 seconds).
3. **WordPress Notifications**:
   - Enable notifications for user registration, password resets, post publications, or comment submissions.
4. **WooCommerce Notifications**:
   - Configure messages for order confirmations, status changes, abandoned carts, or promotional campaigns.
5. **Message Logs**:
   - Access the **Logs** tab to view and filter sent messages by type, status, or date. Click "View Details" to inspect response data.

### Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.2 or higher
- **WooCommerce**: 4.0 or higher (optional, for e-commerce features)
- **API Key**: Obtainable from [ildrm.com](https://ildrm.com)

### Screenshots

1. **General Settings**  
   Configure API key, language, and messaging provider.  
   ![General Settings](assets/screenshot-1.png)

2. **OTP Settings**  
   Customize OTP code length and expiration time.  
   ![OTP Settings](assets/screenshot-2.png)

3. **WooCommerce Integration**  
   Set up notifications for order statuses and promotions.  
   ![WooCommerce Integration](assets/screenshot-3.png)

4. **Message Logs**  
   Filter and view detailed logs of sent messages.  
   ![Message Logs](assets/screenshot-4.png)

### Contributing

We welcome contributions from the community! To contribute:

1. Fork the repository on [GitHub](https://github.com/ildrm/wp-msgway-sms).
2. Create a feature branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m "Add your feature"`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request.

Please follow our [contribution guidelines](https://github.com/ildrm/wp-msgway-sms/blob/main/CONTRIBUTING.md) for coding standards and submission processes.

### Support

For assistance, please:
- Visit the [WordPress Support Forum](https://wordpress.org/support/plugin/wp-msgway-sms).
- Contact Shahin Ilderemi at [ildrm@hotmail.com](mailto:ildrm@hotmail.com).
- Open an issue on [GitHub](https://github.com/ildrm/wp-msgway-sms/issues).

### License

MsgWay SMS is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html). You are free to use, modify, and distribute this plugin in accordance with the license terms.

### Acknowledgements

- Built with ❤️ by Shahin Ilderemi.
- Special thanks to the WordPress and WooCommerce communities for their amazing support.

---

## فارسی

### مرور کلی

**MsgWay SMS**، توسعه‌یافته توسط شاهین ایلدرمی، یک افزونه قدرتمند وردپرس است که برای ساده‌سازی ارتباطات مبتنی بر پیامک طراحی شده است. این افزونه با ادغام یکپارچه با هسته وردپرس، ووکامرس و چندین ارائه‌دهنده خدمات پیام‌رسان، به مدیران سایت امکان می‌دهد اعلان‌ها، کدهای یک‌بارمصرف (OTP) و پیام‌های تبلیغاتی را به‌راحتی ارسال کنند. با رابط کاربری بصری و سیستم جامع ثبت لاگ، MsgWay SMS ارتباطات قابل‌اعتماد و قابل‌ردیابی را برای افزایش تعامل کاربران فراهم می‌کند.

### ویژگی‌های کلیدی

- **اعلان‌های متنوع**: ارسال پیام‌های خودکار برای ثبت‌نام کاربر، بازنشانی رمز عبور، انتشار پست و فعالیت‌های نظرات.
- **احراز هویت امن OTP**: پیاده‌سازی کدهای یک‌بارمصرف قابل‌تنظیم برای ورود و ثبت‌نام امن کاربران.
- **ادغام با ووکامرس**: ارسال تأیید سفارش، به‌روزرسانی وضعیت، یادآوری سبد خرید رها شده و کمپین‌های تبلیغاتی به مشتریان.
- **لاگ‌های دقیق پیام‌ها**: نظارت بر پیام‌های ارسالی با جدولی قابل‌فیلتر که نوع، وضعیت، شماره موبایل و جزئیات پاسخ را نمایش می‌دهد.
- **پشتیبانی از چندین ارائه‌دهنده**: پشتیبانی از SMS 3000x، SMS 2000x، SMS 9000x، واتساپ و گپ، با انتخاب انعطاف‌پذیر ارائه‌دهنده.
- **پشتیبانی چندزبانه**: کاملاً بومی‌سازی‌شده برای فارسی و انگلیسی، مناسب برای مخاطبان متنوع.
- **تنظیمات قابل‌تنظیم**: پیکربندی کلید API، الگوهای پیام، تنظیمات OTP و اولویت‌های ارائه‌دهنده از طریق پنل مدیریت.

### چرا MsgWay SMS را انتخاب کنید؟

MsgWay SMS برای عملکرد، مقیاس‌پذیری و سهولت استفاده طراحی شده است. چه یک وبلاگ، یک پلتفرم تجارت الکترونیک یا یک سایت عضویت داشته باشید، این افزونه راه‌حلی مطمئن برای اطلاع‌رسانی و تعامل با کاربران ارائه می‌دهد. ادغام عمیق با ووکامرس و پشتیبانی از زبان فارسی آن را به گزینه‌ای ایده‌آل برای بازارهای جهانی و فارسی‌زبان تبدیل کرده است.

### نصب

1. **دانلود و آپلود**:
   - فایل ZIP افزونه را از [صفحه انتشار](https://github.com/ildrm/wp-msgway-sms/releases) دانلود کنید.
   - پوشه `wp-msgway-sms` را از طریق FTP به `/wp-content/plugins/` آپلود کنید یا از پنل مدیریت وردپرس (افزونه‌ها > افزودن) نصب کنید.
2. **فعال‌سازی**:
   - به بخش افزونه‌ها در پیشخوان وردپرس بروید و **MsgWay SMS** را فعال کنید.
3. **پیکربندی**:
   - به منوی **پیام‌رسان MsgWay** در پنل مدیریت وردپرس بروید.
   - کلید API خود را از [ildrm.com](https://ildrm.com) وارد کنید و تنظیمات عمومی، OTP، وردپرس و ووکامرس را پیکربندی کنید.
4. **تست**:
   - یک پیام آزمایشی یا OTP ارسال کنید تا تنظیمات را بررسی کنید.
   - به تب **گزارش‌ها** بروید تا مطمئن شوید پیام‌ها به‌درستی ثبت شده‌اند.

### استفاده

1. **تنظیمات عمومی**:
   - کلید API، زبان ترجیحی (فارسی یا انگلیسی) و ارائه‌دهنده پیام‌رسان پیش‌فرض را تنظیم کنید.
2. **پیکربندی OTP**:
   - OTP را فعال کنید، تعداد ارقام کد (مثلاً 6 رقم) و زمان انقضا (مثلاً 300 ثانیه) را مشخص کنید.
3. **اعلان‌های وردپرس**:
   - اعلان‌ها را برای ثبت‌نام کاربر، بازنشانی رمز عبور، انتشار پست یا ارسال نظر فعال کنید.
4. **اعلان‌های ووکامرس**:
   - پیام‌ها را برای تأیید سفارش، تغییر وضعیت، سبد خرید رها شده یا کمپین‌های تبلیغاتی پیکربندی کنید.
5. **گزارش‌های پیام**:
   - به تب **گزارش‌ها** بروید تا پیام‌های ارسالی را بر اساس نوع، وضعیت یا تاریخ فیلتر و مشاهده کنید. روی «مشاهده جزئیات» کلیک کنید تا داده‌های پاسخ را بررسی کنید.

### پیش‌نیازها

- **وردپرس**: نسخه 5.0 یا بالاتر
- **PHP**: نسخه 7.2 یا بالاتر
- **ووکامرس**: نسخه 4.0 یا بالاتر (اختیاری، برای قابلیت‌های تجارت الکترونیک)
- **کلید API**: قابل دریافت از [ildrm.com](https://ildrm.com)

### تصاویر

1. **تنظیمات عمومی**  
   پیکربندی کلید API، زبان و ارائه‌دهنده پیام‌رسان.  
   ![تنظیمات عمومی](assets/screenshot-1.png)

2. **تنظیمات OTP**  
   سفارشی‌سازی تعداد ارقام و زمان انقضای کد OTP.  
   ![تنظیمات OTP](assets/screenshot-2.png)

3. **ادغام با ووکامرس**  
   تنظیم اعلان‌ها برای وضعیت‌های سفارش و تبلیغات.  
   ![ادغام با ووکامرس](assets/screenshot-3.png)

4. **گزارش‌های پیام**  
   فیلتر و مشاهده لاگ‌های دقیق پیام‌های ارسالی.  
   ![گزارش‌های پیام](assets/screenshot-4.png)

### مشارکت

ما از مشارکت جامعه استقبال می‌کنیم! برای مشارکت:

1. مخزن را در [GitHub](https://github.com/ildrm/wp-msgway-sms) فورک کنید.
2. یک شاخه جدید ایجاد کنید (`git checkout -b feature/your-feature`).
3. تغییرات خود را کامیت کنید (`git commit -m "Add your feature"`).
4. شاخه را推送 کنید (`git push origin feature/your-feature`).
5. یک درخواست کشش (Pull Request) باز کنید.

لطفاً [راهنمای مشارکت](https://github.com/ildrm/wp-msgway-sms/blob/main/CONTRIBUTING.md) را برای استانداردهای کدنویسی و فرآیند ارسال مطالعه کنید.

### پشتیبانی

برای دریافت کمک، لطفاً:
- به [انجمن پشتیبانی وردپرس](https://wordpress.org/support/plugin/wp-msgway-sms) مراجعه کنید.
- با شاهین ایلدرمی در [ildrm@hotmail.com](mailto:ildrm@hotmail.com) تماس بگیرید.
- یک مسئله در [GitHub](https://github.com/ildrm/wp-msgway-sms/issues) ثبت کنید.

### لایسنس

MsgWay SMS تحت [لایسنس GPLv2 یا بالاتر](https://www.gnu.org/licenses/gpl-2.0.html) منتشر شده است. شما آزاد هستید که این افزونه را طبق شرایط لایسنس استفاده، اصلاح و توزیع کنید.

### قدردانی

- ساخته‌شده با ❤️ توسط شاهین ایلدرمی.
- تشکر ویژه از جوامع وردپرس و ووکامرس برای حمایت فوق‌العاده‌شان.

---