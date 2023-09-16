<?php

class SuppliersModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getSuppliers($search, $params)
  {
    return $this->db->paginateQuery("SELECT * FROM suppliers $search", $params);
  }
}
