<?php

define('APP_URL', 'https://projects.dev.alpha.net.bd');
define('FORCE_HTTPS', FALSE);

define('DB_HOST', 'localhost');
define('DB_USER', 'mvc');
define('DB_PASS', 'mvc@786');
define('DB_NAME', 'mvc');

define('MAILER', 'SMTP'); // Values(SMTP OR PHP Mail)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', 'noreply@ipxwallet.com');
define('SMTP_PASS', 'mr2tuzcdhe1gjobviq8');

define('SITE_TITLE', 'MVC');
define('SITE_NAME', 'MVC');
define('APP_MODE', 'Debug'); //Values (Debug OR Live)
define('TIMEZONE', 'Asia/Dhaka');
define('ALLOW_FORGET_PASSWORD', FALSE);
define('ALLOW_REGISTRATION', FALSE);
define('DEFAULT_REGISTRATION_GROUP', 3);
define('VERIFY_PHONE_AT_REGISTRATION', FALSE);
define('VERIFY_EMAIL_AT_REGISTRATION', TRUE);
define('REGISTRATION_CAPTCHA', FALSE);
define('LOGIN_CAPTCHA', FALSE);
define('PAGINATION_LIMIT', 20);
define('SKIN_COLOR', 'skin-blue');

setlocale(LC_MONETARY, 'en_IN');