<?php

class AccountModel
{

 private $db;

 public function __construct()
 {

  $this->db = new Database;
 }

 public function login()
 {

  if (isset($_SESSION['w_login'])) {

   $_SESSION['login'] = $_SESSION['w_login'];
   $_SESSION['userid'] = $_SESSION['w_userid'];
   $_SESSION['groupid'] = $_SESSION['w_groupid'];
   $_SESSION['name'] = $_SESSION['w_name'];
   $_SESSION['avatar'] = $_SESSION['w_avatar'];
   $_SESSION['groupname'] = $_SESSION['w_groupname'];

   unset($_SESSION['w_login']);
   unset($_SESSION['w_userid']);
   unset($_SESSION['w_groupid']);
   unset($_SESSION['w_name']);
   unset($_SESSION['w_avatar']);
   unset($_SESSION['w_groupname']);


   if (isset($_SESSION['remember_me'])) {

    $signature = @crypt($_SERVER['HTTP_USER_AGENT']);
    $authkey = md5($_SESSION['userid'] . Util::generateRandomString(32));

    $this->db->query('UPDATE user SET login_key = ? WHERE id = ?', $authkey, $_SESSION['userid']);

    setcookie('AUTHKEY', $authkey, time() + (86400 * 30), "/");
    setcookie('_signature', $signature, time() + (86400 * 30), "/");
   }
  }

  return false;
 }

 public function verifyLoginData($login, $password)
 {

  $user_query = $this->db->query('SELECT * FROM user WHERE email = ? AND status=1', $login);

  if ($user_query->numRows() > 0) {

   $user_info = $user_query->fetchArray();

   if (password_verify($password, $user_info['password'])) {

    $user_group_query = $this->db->query("SELECT g.id, g.group_name FROM user_group AS g JOIN user_group_relation AS r ON g.id=r.group_id WHERE r.user_id = ? ORDER BY g.id", $user_info['id']);

    if ($user_group_query->numRows() > 0) {

     $user_group_info = $user_group_query->fetchAll();

     $_SESSION['w_groupid'] = $_SESSION['w_groupname'] = array();

     foreach ($user_group_info as $group) {
      $_SESSION['w_groupid'] = $group['id'];
      $_SESSION['w_groupname'] = $group['group_name'];
     }

     $_SESSION['w_login'] = $user_info['email'];
     $_SESSION['w_userid'] = $user_info['id'];
     $_SESSION['w_name'] = $user_info['name'];
     $_SESSION['w_avatar'] = $user_info['photo'];

     if (isset($_POST['remember_me'])) {
      $_SESSION['remember_me'] = true;
     }

     return true;
    }
   }
  }

  return false;
 }

 public function isTwoFactor($email)
 {

  $user_query = $this->db->query('SELECT * FROM user WHERE email = ?', $email);

  if ($user_query->numRows() > 0) {

   $user_info = $user_query->fetchArray();
   if ($user_info['2fa'] == '1') {
    return true;
   }
  }

  return false;
 }

 public function register()
 {

  $token_expire = date("Y-m-d H:i:s", strtotime('+1 day', time()));

  $insert = $this->db->query('INSERT INTO user (name,phone,email,password,email_token,email_token_expire,created) VALUES (?,?,?,?,?,?,?)', $_SESSION['reg_name'], $_SESSION['reg_mobile'], $_SESSION['reg_email'], $_SESSION['reg_password'], $_SESSION['reg_email_token'], $token_expire, TIMESTAMP);

  if ($insert) {

   $userid = $this->db->lastInsertID();
   $this->db->query('INSERT INTO user_group_relation (user_id,group_id,created) VALUES (?,?,?)', $userid, DEFAULT_REGISTRATION_GROUP, TIMESTAMP);

   return true;
  }

  return false;
 }

 public function getUserByEmail($email)
 {

  $email_search = $this->db->query("SELECT * FROM user WHERE email = ?", $email);
  if ($email_search->numRows() > 0) {

   return $email_search->fetchArray();
  }

  return false;
 }

 public function verifyEmail($email)
 {

  $verify = $this->db->query('UPDATE user SET email_verified = ? WHERE email = ?', '1', $email);

  if ($verify) {
   return true;
  } else {
   return false;
  }
 }

 public function getTokenInfo($token)
 {

  $token_search = $this->db->query("SELECT * FROM user WHERE email_token = ? AND email_verified = ?", $token, '0');
  if ($token_search->numRows() > 0) {

   return $token_search->fetchArray();
  }

  return false;
 }

 public function getVerificationEmailTemplate()
 {

  $content = $this->db->query("SELECT subject,html,text FROM mail_template WHERE name = ?", 'email_verification_template')->fetchArray();

  return $content;
 }

 public function numberExists($num)
 {

  $number_search = $this->db->query("SELECT * FROM user WHERE phone = ?", $num);
  if ($number_search->numRows() > 0) {

   return true;
  }

  return false;
 }

 public function getRecoveryEmailTemplate()
 {

  $content = $this->db->query("SELECT subject,html,text FROM mail_template WHERE name = ?", 'forgot_password_template')->fetchArray();

  return $content;
 }

 public function setRecovery($user, $token)
 {
  $token_expire = date("Y-m-d H:i:s", strtotime('+12 hours', time()));

  $insert = $this->db->query('INSERT INTO `recovery` (user_id, token, expire, req_time) VALUES (?,?,?,?)', $user['id'], $token, $token_expire, TIMESTAMP);

  if ($insert->affectedRows() > 0) {
   return true;
  }

  return false;
 }

 public function getRecoveryInfo($token)
 {

  $token_search = $this->db->query("SELECT * FROM recovery WHERE token = ?", $token);
  if ($token_search->numRows() > 0) {

   return $token_search->fetchArray();
  }

  return false;
 }

 public function setPassword($user_id, $password)
 {
  $update = $this->db->query("UPDATE user SET password = ? WHERE id = ?", $password, $user_id);

  if ($update->affectedRows() > 0) {
   if ($this->db->query("SELECT * FROM recovery WHERE user_id = ? ", $user_id)->numRows() > 0) {
    $this->db->query("DELETE FROM recovery WHERE user_id = ? ", $user_id);
   }

   return true;
  }

  return false;
 }

 public function set2FA($id, $token)
 {
  $update = $this->db->query("UPDATE user SET 2fa = 1, 2fa_token = ? WHERE id = ?", $token, $id);

  if ($update->affectedRows() > 0) {
   return true;
  }

  return false;
 }

 public function unset2FA($id)
 {
  $update = $this->db->query("UPDATE user SET 2fa = 0 WHERE id = ?", $id);

  if ($update->affectedRows() > 0) {
   return true;
  }

  return false;
 }

 public function profileImg($id, $img_name)
 {

  $update = $this->db->Query("UPDATE user set photo=? WHERE id=?", $img_name, $id);

  if ($update) {

   return true;
  }

  return false;
 }

 public function addInvalidLogin($email)
 {
  $insert = $this->db->query('INSERT INTO `invalid_login` (ip_address, email, attempted) VALUES (?,?,?)', $_SERVER['REMOTE_ADDR'], $email, TIMESTAMP);

  if ($insert->affectedRows() > 0) {
   return true;
  }

  return false;
 }

 public function addValidLogin($email)
 {
  $insert = $this->db->query('DELETE FROM `invalid_login` WHERE email = ? AND ip_address = ?', $email, $_SERVER['REMOTE_ADDR']);

  if ($insert->affectedRows() > 0) {
   return true;
  }

  return false;
 }

 public function canLogin($email)
 {
  $before30Min = date("Y-m-d H:i:s", strtotime('-30 minutes', time()));
  $rows = $this->db->query("SELECT id FROM `invalid_login` WHERE email = ? AND ip_address = ? AND attempted >= ? ", $email, $_SERVER['REMOTE_ADDR'], $before30Min);

  if ($rows->numRows() > 5) {
   return false;
  }

  return true;
 }

 public function getUserBalance($id)
 {
  $current_balance = $this->db->query("SELECT balance FROM user WHERE id=?", $id);

  if ($current_balance->numRows() > 0) {
   return $current_balance->fetchArray();
  }

  return false;
 }
}
