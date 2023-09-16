<?php

class CustomersModel
{



  public function __construct()
  {

    $this->db = new Database;
  }
  public function getCustomers($search, $params)
  {
    return $this->db->paginateQuery("SELECT * FROM customers $search", $params);
  }
  public function getCustomer($id)
  {
    return $this->db->Query("SELECT * FROM customers WHERE id = ? ",[$id])->fetchArray();
  }
  public function getCountries()
  {

    return $this->db->Query("SELECT * FROM country")->fetchAll();
  }

  public function getStates($country_id)
  {

    return $this->db->Query("SELECT * FROM `state` WHERE country_id = ?", [$country_id])->fetchAll();
  }

  public function getCities($sate_id)
  {

    return $this->db->Query("SELECT * FROM `city` WHERE state_id = ?", [$sate_id])->fetchAll();
  }


  public function add($data)
  {

    $customerData = [$data['name'], $data['email'], $data['phone'], $data['opening_balance'], '1', $data['country'], $data['state'], $data['city'], $data['zip'], $data['address'], TIMESTAMP];

    return $this->db->Query("INSERT INTO `customers` (`name`,`email`,`phone`,`opening_balance`,`status`,`country`,`state`,`city`,`zip`,`address`,`created`) VALUES (?,?,?,?,?,?,?,?,?,?,?)", $customerData);
  }

  public function update($data)
  {

    $customerData = [$data['name'], $data['email'], $data['phone'], $data['opening_balance'], '1', $data['country'], $data['state'], $data['city'], $data['zip'], $data['address']];

    return $this->db->Query("UPDATE `customers` SET `name` =?,`email`=?,`phone`=?,`opening_balance`=?,`status`=?,`country`=?,`state`=?,`city`=?,`zip`=?,`address`=?",$customerData);
  }
}
