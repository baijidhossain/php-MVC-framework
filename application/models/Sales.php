<?php
class SalesModel
{


  public function __construct()
  {

    $this->db = new Database;
  }

public function getCustomers(){

  return $this->db->Query("SELECT * FROM customers")->fetchAll();

}


}
