<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require FRAMEWORK_PATH . 'libraries/PHPMailer/PHPMailer.php';
require FRAMEWORK_PATH . 'libraries/PHPMailer/Exception.php';
require FRAMEWORK_PATH . 'libraries/PHPMailer/SMTP.php';

class Mailer extends PHPMailer{
	
	public function __construct(){
		
		if(MAILER == 'SMTP'){
			
			$this->isSMTP();
			$this->SMTPAuth = true;
			//$this->SMTPSecure = "tls";
			$this->Host = SMTP_HOST;
			$this->Port = SMTP_PORT;
			$this->Username = SMTP_USER;
			$this->Password = SMTP_PASS;
		}
		
		$this->IsHTML(true);
		$this->CharSet = 'UTF-8';
		
		$from = (filter_var(SMTP_USER, FILTER_VALIDATE_EMAIL) ? SMTP_USER : 'noreply@'.parse_url(APP_URL, PHP_URL_HOST));
		$this->SetFrom($from, SITE_TITLE);
	}
}