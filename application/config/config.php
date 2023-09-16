<?php

define('APP_URL', 'http://localhost/');
define('FORCE_HTTPS', false);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mvc');

define('MAILER', 'SMTP'); // Values(SMTP OR PHP Mail)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', 'noreply@ipxwallet.com');
define('SMTP_PASS', 'mr2tuzcdhe1gjobviq8');

define('SITE_TITLE', 'MVC');
define('SITE_NAME', 'MVC');
define('APP_MODE', 'Debug'); // Values (Debug OR Live)
define('TIMEZONE', 'Asia/Dhaka');
define('ALLOW_FORGET_PASSWORD', false);
define('ALLOW_REGISTRATION', false);
define('DEFAULT_REGISTRATION_GROUP', 3);
define('VERIFY_PHONE_AT_REGISTRATION', false);
define('VERIFY_EMAIL_AT_REGISTRATION', true);
define('REGISTRATION_CAPTCHA', false);
define('LOGIN_CAPTCHA', false);
define('PAGINATION_LIMIT', 20);
define('SKIN_COLOR', 'skin-blue');

setlocale(LC_MONETARY, 'en_IN');
