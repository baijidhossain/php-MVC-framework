<?php


class UsersModel
{
  // extends Model
  /**
   * UsersModel constructor.
   */
  public function __construct()
  {
    $this->db = new Database;
  }

  public function getUsers()
  {
    $customer_group_id = 3;
    $rows              = $this->db->query("SELECT u.*, g.group_name FROM user AS u JOIN user_group_relation AS r ON r.user_id=u.id JOIN user_group as g on r.group_id = g.id WHERE r.group_id != ?", [$customer_group_id]);

    if ($rows->numRows() > 0) {
      return $rows->fetchAll();
    }

    return false;
  }

  public function setStatus($id, $status)
  {
    $row = $this->db->query("UPDATE user SET status = ? WHERE id = ?", $status, $id);

    return $row->affectedRows() > 0;
  }

  public function getUser($id)
  {
    $rows = $this->db->query("SELECT u.id, u.name, u.phone, u.email, u.status, u.created, r.group_id FROM user AS u JOIN user_group_relation AS r ON r.user_id=u.id WHERE u.id = ?", $id);

    if ($rows->numRows() > 0) {
      return $rows->fetchArray();
    }

    return false;
  }

  public function getGroups()
  {
    $rows = $this->db->query("SELECT * FROM user_group WHERE id != 3");

    if ($rows->numRows() > 0) {
      return $rows->fetchAll();
    }

    return false;
  }

  public function update($id, $group_id)
  {
    try {
      $this->db->beginTransaction();
      $this->db->query("DELETE FROM user_group_relation WHERE user_id = ?", $id);
      $this->db->query("INSERT INTO user_group_relation (`user_id`, `group_id`, `created`) VALUES (?, ?, ?)", $id, $group_id, TIMESTAMP);
      $this->db->commit();

      return true;
    } catch (Exception $e) {
      $this->db->Rollback();

      return false;
    }
  }

  public function setPassword($user_id, $password)
  {
    $update = $this->db->query("UPDATE user SET password = ? WHERE id = ?", $password, $user_id);

    return $update->affectedRows() > 0;
  }

  public function getUserByEmail($email)
  {

    $email_search = $this->db->query("SELECT * FROM user WHERE email = ?", $email);
    if ($email_search->numRows() > 0) {

      return $email_search->fetchArray();
    }

    return false;
  }

  public function numberExists($num)
  {

    $number_search = $this->db->query("SELECT * FROM user WHERE phone = ?", $num);

    return $number_search->numRows() > 0;
  }

  public function AddUser($name, $email, $phone, $status, $group_id, $password)
  {
    $insert = $this->db->query("INSERT INTO `user`(`name`, `phone`, `email`, `password`, `status`, `created`) VALUES (?, ?, ?, ?, ?, ?)", $name, $phone, $email, $password, $status, TIMESTAMP);

    if ($insert) {

      $userid = $this->db->lastInsertID();
      $this->db->query('INSERT INTO user_group_relation (user_id,group_id,created) VALUES (?,?,?)', $userid, $group_id, TIMESTAMP);

      return true;
    }

    return false;
  }
}
