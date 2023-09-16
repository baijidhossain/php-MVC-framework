<?php

class Account extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Account');
  }

  public function Index()
  {
    if (!AUTH::loggedin()) {
      Util::redirect(APP_URL . "/Account/login/");
    }

    if (isset($_FILES['profile-img']) && $_FILES['profile-img']['tmp_name'] != "") {
      $check = getimagesize($_FILES["profile-img"]["tmp_name"]);
      if ($check !== false) {
        if ($_FILES["profile-img"]["size"] < 500000) {
          $img_name = $_SESSION['userid'] . ".png";
          $save_path = PUBLIC_PATH . "images/user_img/" . $img_name;

          if (move_uploaded_file($_FILES["profile-img"]["tmp_name"], $save_path)) {
            $update = $this->model->profileImg($_SESSION['userid'], $img_name);

            if ($update) {
              $this->setAlert('success', 'Image Successfully Updated');

              Util::redirect(APP_URL . "/account/profile/");
            } else {
              $this->setAlert('error', 'Failed to Upload Image');
            }
          } else {
            $this->setAlert('error', 'Failed to Upload Image');
          }
        } else {
          $this->setAlert(
            'error',
            'Sorry, Your file is too large. Upload Size is Maximum 500KB'
          );
        }
      } else {
        $this->setAlert('error', 'Sorry, Your file is not an image.');
      }
    }
    $data = $this->model->getUserBalance($_SESSION['userid']);
    $data['user'] = $this->model->getUserByEmail($_SESSION['login']);
    $data['page_title'] = "Profile";
    $data['user']['photo'] = $data['user']['photo'] ? APP_URL . '/public/images/user_img/' .
      $data['user']['photo']
      : APP_URL . '/public/images/no-profile.jpg';
    $this->view('account/profile', $data);
  }

  public function UploadImg()
  {
    if (!AUTH::loggedin()) {
      Util::redirect(APP_URL . "/Account/login/");
    }

    $this->view('account/UploadImg');
  }

  /*public function Security()
        {
            if ( ! AUTH::loggedin()) {
                Util::redirect(APP_URL . "/Account/login/");
            }

            $user = $this->model->getUserByEmail($_SESSION['login']);
            $data = [
                'page_title'           => "Security",
                '2fa'                  => $user['2fa'],
                '2fa_message'          => '',
                'cur_password_err'     => '',
                'password_err'         => '',
                'confirm_password_err' => '',
            ];


            if (isset($_POST['cur_password']) && ! empty($_POST['cur_password'])) {
                $data['password'] = trim($_POST['password']);
                $data['confirm_password'] = trim($_POST['password2']);

                //Verify password
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                } elseif (strlen($data['password']) < 8 || strlen($data['password']) > 20) {
                    $data['password_err'] = 'Password must be between 8 and 20 characters';
                } else {
                    if (password_verify($_POST['cur_password'], $user['password'])) {
                        $update_password = $this->model->setPassword($user['id'],
                            password_hash($data['password'], PASSWORD_DEFAULT));

                        if ($update_password) {
                            $notification = new Notification;

                            $notification->event = 'PASSWORD_CHANGED';

                            $notification->Notify();

                            $this->setAlert('success',
                                'Your password has been updated successfully.');
                        } else {
                            $this->setAlert('error', 'Unknown error occurred.');
                        }
                    } else {
                        $data['cur_password_err'] = 'Wrong password';
                    }
                }
            }

            if (isset($_POST['2FA']) && ! empty($_POST['2FA'])) {
                if ( ! strlen($_POST['password']) < 8 || ! strlen($_POST['password']) > 20) {
                    if (password_verify($_POST['password'], $user['password'])) {
                        if ($_POST['2FA'] == 'enable' || $_POST['2FA'] == 'reset') {
                            require_once(FRAMEWORK_PATH .
                                         'libraries/GoogleAuthenticator/GoogleAuthenticator.php');

                            $GA = new GoogleAuthenticator();

                            $twoFactorCode = $GA->createSecret();

                            $qrCode = $GA->getQRCodeGoogleUrl(SITE_TITLE, $twoFactorCode);

                            if ($this->model->set2FA($user['id'], $twoFactorCode)) {
                                $data['2fa'] = 1;
                                $data['2fa_message'] = "
                                <div class='alert alert-warning alert-dismissible text-white'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                                    <h4><i class='icon fa fa-warning'></i> Setup Authentication Key</h4>
                                    <p>Two factor authentication is now active. Download your preferred authenticator app to your phone (any will work). If you don't have a preferred app, we recommend using 
                                    <a href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2'>Google Authenticator</a>. 
                                    You will be asked to verify your account using Authenticator App whenever you log in.</p>
                                    <p>Please scan the following QR Code with your Authenticator App. You may also enter the Key manually.</p>
                                    <p class='font16' style='margin-top: 15px; color: #8a0000;font-weight: 600'>You will not see this key after leaving this page.</p><br>
                                    <p class='font18' style='font-weight: 600; margin-bottom: 10px;'><i class='fa fa-key'></i> Key: {$twoFactorCode}</p>    
                                    
                                    <p style='margin-bottom: 5px;'>Scan QR Code</p>    
                                    <style>
                                    .qr_code {width: 220px;height: 220px;background-color: #fff;display: flex;align-items: center;justify-content: center;position: relative;}
                                    .qr_code::after{content: 'QR Code is loading';position: absolute;color: #000;text-align: center;}
                                    .qr_code img {position: relative;z-index: 1;}
                                    </style>                           
                                    <div class='qr_code'>
                                        <img height='200' width='200' src='{$qrCode}' alt='QR Code is loading...' class='img-responsive'>
                                    </div>
                                    <p style='margin-top: 10px'>If you are unable to active two-factor authentication with Google Authenticator at this time, please disable two-factor authentication for now otherwise you will not be able to log into your account next time.</p>
                                </div>
                            ";
                            } else {
                                $this->setAlert('error', '2FA not configured.');
                            }
                        } elseif ($_POST['2FA'] == 'disable') {
                            if ($this->model->unset2FA($user['id'])) {
                                $data['2fa'] = 0;
                                $this->setAlert('success', 'Two-factor authentication disabled');
                            } else {
                                $this->setAlert('error', '2FA not configured.');
                            }
                        }
                    } else {
                        $this->setAlert('error', 'Wrong password');
                    }
                } else {
                    $this->setAlert('error', 'Please enter a valid password');
                }
            }

            $this->view('account/security', $data);
        }*/

  public function TwoFactorModal($type)
  {
    if (!AUTH::loggedin()) {
      Util::redirect(APP_URL . "/Account/login/");
    }

    $data['type'] = $type;
    $this->view('account/twoFactorModal', $data);
  }

  public function onAuthenticate()
  {
    if (!AUTH::loggedin()) {
      Util::redirect(APP_URL . "/Account/login/");
    }

    if (isset($_SESSION['redirect'])) {
      Util::redirect($_SESSION['redirect']);
    } elseif ($_SESSION['groupid'] == '1') {
      Util::redirect(APP_URL . "/admin/");
    } else {
      Util::redirect(APP_URL . "/");
    }
  }

  public function Login()
  {
    if (AUTH::loggedin()) {
      Util::redirect(APP_URL . "/Account/onAuthenticate/");
    }

    if (isset($_POST['login'])) {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      if (empty($_POST['login']) || empty($_POST['password'])) {
        $this->setAlert('error', "Please enter your login details.");
      } elseif ($canLogin = $this->model->canLogin($_POST['login'])) {
        $verify_login = $this->model->verifyLoginData(
          $_POST['login'],
          $_POST['password']
        );

        if ($verify_login) {
          $twofactor = $this->model->isTwoFactor($_POST['login']);

          if ($twofactor) {
            $_SESSION['2FA'] = $_POST['login'];
          } else {
            $this->model->login();
            $this->model->addValidLogin($_POST['login']);
            Util::redirect(APP_URL . "/Account/onAuthenticate/");
          }
        } else {
          $this->model->addInvalidLogin($_POST['login']);
          $this->setAlert('error', "Invalid login details.");
        }
      } else {
        $this->setAlert(
          'error',
          "Your account has been locked for 30 minutes. Please try again after 30 minutes."
        );
      }
    }

    if (isset($_POST['2FA_otp']) && !empty($_POST['2FA_otp'])) {
      $user = $this->model->getUserByEmail($_SESSION['2FA']);

      require_once(FRAMEWORK_PATH .
        'libraries/GoogleAuthenticator/GoogleAuthenticator.php');
      $GA = new GoogleAuthenticator();

      if ($GA->verifyCode($user['2fa_token'], trim($_POST['2FA_otp']), 2)) {
        $this->model->login();
        unset($_SESSION['2FA']);
        $this->model->addValidLogin($user['email']);
        Util::redirect(APP_URL . "/Account/onAuthenticate/");
      } else {
        $this->setAlert('error', 'Wrong OTP');
      }
    }

    $data['page_title'] = isset($_SESSION['2FA']) ? 'Two Factor Authentication' : 'Login';
    $this->view('account/login', $data);
  }

  public function Register()
  {
    if (AUTH::loggedin()) {
      Util::redirect(APP_URL . "/Account/onAuthenticate/");
    }

    if (!ALLOW_REGISTRATION) {
      $this->setAlert('error', 'Registration is currently disabled.');
      Util::redirectBack("/Account/login/");
    }

    $data = [
      'page_heading'         => 'Register a new account',
      'name'                 => '',
      'email'                => '',
      'password'             => '',
      'confirm_password'     => '',
      'mobile'               => '',
      'name_err'             => '',
      'email_err'            => '',
      'country_err'          => '',
      'password_err'         => '',
      'confirm_password_err' => '',
      'mobile_err'           => '',
      'otp_err'              => '',
    ];


    if (isset($_SESSION['reg_otp'])) {
      $data['page_heading'] = '<b>Mobile Number Verification</b><br>A verification code has been sent to ' .
        $_SESSION['reg_mobile'];
      $data['otp_err'] = '';
    }


    if (isset($_POST['otp'])) {
      if (trim($_POST['otp']) == $_SESSION['reg_otp']) {
        if ($this->model->register()) {
          $this->setAlert(
            'success',
            'Your account has been successfully registered.'
          );

          if (VERIFY_EMAIL_AT_REGISTRATION) {
            $uinfo = $user = $this->model->getUserByEmail($_SESSION['reg_email']);

            $notification = new Notification;

            $notification->event = 'USER_REGISTERED';

            $notification->user_id = $uinfo['id'];

            $notification->setData(
              'VERYFY_LINK',
              APP_URL . '/account/confirm_email/' . $_SESSION['reg_email_token']
            );

            if ($notification->Notify()) {
              $this->setAlert(
                'warning',
                'A verification mail has been sent to ' . $data['email'] .
                  '. Please check & verify your email address.'
              );
            }
          }

          Util::redirect(APP_URL . "/account/login/");
        } else {
          $this->setAlert('error', 'Unknown error occurred.');
        }
      } else {
        $data['otp_err'] = 'Invalid OTP Code';
      }
    }

    if (isset($_POST['email'])) {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data['name'] = trim($_POST['name']);
      $data['email'] = trim($_POST['email']);
      $data['country'] = trim($_POST['country']);
      $data['password'] = trim($_POST['password']);
      $data['confirm_password'] = trim($_POST['password2']);
      $data['mobile'] = trim($_POST['mobile']);


      //Verfiy Name
      if (strlen($data['name']) < 5) {
        $data['name_err'] = 'Please enter your fullname';
      }

      //Verfiy Email
      if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $data['email_err'] = 'Please enter a valid email';
      } else {
        if ($this->model->getUserByEmail($data['email'])) {
          $data['email_err'] = 'Email already registered';
        }
      }
      // Verifycountry
      if ($data['country'] != "Bangladesh") {
        $data['country_err'] = 'IPX Wallet is not available in ' . $data['country'];
      }

      //Verify password
      if ($data['password'] != $data['confirm_password']) {
        $data['confirm_password_err'] = 'Passwords do not match';
      } elseif (strlen($data['password']) < 8 || strlen($data['password']) > 20) {
        $data['password_err'] = 'Password must be between 8 and 20 characters';
      }

      //Verfiy Mobile Number Format
      $checked_number = Util::validateNumber($data['mobile']);
      if (!$checked_number) {
        $data['mobile_err'] = 'Please enter a valid mobile number';
      } else {
        $data['mobile'] = $checked_number;
        if ($this->model->numberExists($checked_number)) {
          $data['mobile_err'] = 'Mobile number already exists';
        }
      }


      //Check data and proceed
      if (
        empty($data['name_err']) && empty($data['email_err']) &&
        empty($data['country_err']) && empty($data['password_err']) &&
        empty($data['confirm_password_err']) && empty($data['mobile_err'])
      ) {
        $_SESSION['reg_name'] = $data['name'];
        $_SESSION['reg_email'] = $data['email'];
        $_SESSION['reg_mobile'] = $data['mobile'];
        $_SESSION['reg_country'] = $data['country'];
        $_SESSION['reg_password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $_SESSION['reg_email_token'] = Util::generateRandomString();

        if (VERIFY_PHONE_AT_REGISTRATION) {
          $_SESSION['reg_otp'] = $this->generateOTP();

          require FRAMEWORK_PATH . 'helpers/sms.class.php';

          $sms_sender = new SMS;

          $sms_sender->sendSMS(
            $data['mobile'],
            "Your " . SITE_TITLE . " Verification Code is: " .
              $_SESSION['reg_otp']
          );

          $data['page_heading'] = '<b>Mobile Number Verification</b><br>A verification code has been sent to ' .
            $data['mobile'];
        } else {
          if ($this->model->register()) {
            $this->setAlert(
              'success',
              'Your account has been successfully registered.'
            );

            if (VERIFY_EMAIL_AT_REGISTRATION) {
              $uinfo = $user = $this->model->getUserByEmail($_SESSION['reg_email']);

              $notification = new Notification;

              $notification->event = 'USER_REGISTERED';

              $notification->user_id = $uinfo['id'];

              $notification->setData(
                'VERYFY_LINK',
                APP_URL . '/account/confirm_email/' .
                  $_SESSION['reg_email_token']
              );

              if ($notification->Notify()) {
                $this->setAlert(
                  'warning',
                  'A verification mail has been sent to ' . $data['email'] .
                    '. Please check & verify your email address.'
                );
              }
            }

            Util::redirect(APP_URL . "/account/login/");
          } else {
            $this->setAlert('error', 'Unknown error occurred.');
          }
        }
      }
    }

    $data['page_title'] = 'Registration';
    $this->view('account/register', $data);
  }

  private function generateOTP()
  {
    $otp = '';

    for ($i = 0; $i < 4; $i++) {
      $otp .= mt_rand(0, 9);
    }

    return $otp;
  }

  public function Recovery($token = null)
  {
    if (AUTH::loggedin()) {
      Util::redirect(APP_URL . "/Account/onAuthenticate/");
    }

    if (!ALLOW_FORGET_PASSWORD) {
      $this->setAlert('error', 'Password Recovery is currently disabled.');
      Util::redirectBack("/Account/login/");
    }

    $data = [
      'page_title'           => 'Forgot Password',
      'password'             => '',
      'confirm_password'     => '',
      'mode'                 => null,
      'password_err'         => '',
      'confirm_password_err' => '',
    ];

    if (isset($_POST['recovery_email'])) {
      $data['email'] = strtolower(trim($_POST['recovery_email']));

      //Verify Email
      if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $this->setAlert('error', 'Please enter a valid email');
      } elseif ($user = $this->model->getUserByEmail($data['email'])) {
        $Gtoken = Util::generateRandomString();

        $this->model->setRecovery($user, $Gtoken);

        $notification = new Notification();

        $notification->event = 'PASSWORD_RECOVERY';

        $notification->user_id = $user['id'];

        $notification->setData('VERYFY_LINK', APP_URL . '/account/recovery/' . $Gtoken);

        if ($notification->Notify()) {
          $this->setAlert(
            'success',
            'We have sent you an email with instructions on how to reset your password.'
          );
        } else {
          $this->setAlert('error', 'Something went wrong.');
        }
      } else {
        $this->setAlert('error', 'No account found with that email.');
      }
    }

    if (isset($token)) {
      if ($recovery = $this->model->getRecoveryInfo(trim($token))) {
        if (time() < strtotime($recovery['expire'])) {
          $data['page_title'] = "Set new password";
          $data['mode'] = "recovery";

          if (isset($_POST['password'])) {
            $data['password'] = trim($_POST['password']);
            $data['confirm_password'] = trim($_POST['password2']);

            //Verify password
            if ($data['password'] != $data['confirm_password']) {
              $data['confirm_password_err'] = 'Passwords do not match';
            } elseif (
              strlen($data['password']) < 8 ||
              strlen($data['password']) > 20
            ) {
              $data['password_err'] = 'Password must be between 8 and 20 characters';
            }

            if (
              empty($data['password_err']) &&
              empty($data['confirm_password_err'])
            ) {
              if ($this->model->setPassword(
                $recovery['user_id'],
                password_hash($data['password'], PASSWORD_DEFAULT)
              )) {
                $notification = new Notification;

                $notification->event = 'PASSWORD_CHANGED';

                $notification->user_id = $recovery['user_id'];

                $notification->Notify();

                $this->setAlert('success', 'Your password has been reset.');

                Util::redirect(APP_URL . "/account/login/");
              } else {
                $this->setAlert('error', 'Unknown error occurred.');
              }
            }
          }
        } else {
          $this->setAlert('error', 'Token Expired');
        }
      } else {
        $this->setAlert('error', 'Invalid token');
      }
    }

    $this->view('account/recovery', $data);
  }

  public function Confirm_email($token)
  {
    $user = $this->model->getTokenInfo($token);

    if ($user) {
      $datediff = time() - strtotime($user['email_token_expire']);
      if (floor($datediff / (60 * 60 * 24)) < 1) {
        $this->model->verifyEmail($user['email']);
        $this->setAlert(
          'success',
          'Thank You! Your email has been successfully verified.'
        );
      } else {
        $this->setAlert('error', 'Token Expired');
      }
    } else {
      $this->setAlert('error', 'Invalid token.');
    }

    Util::redirect(APP_URL . "/account/login");
  }

  public function Logout()
  {
    Session::destroy();

    Util::redirect(APP_URL . "/account/login");
  }
}
