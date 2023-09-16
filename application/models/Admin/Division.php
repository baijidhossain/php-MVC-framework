<?php

class DivisionModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getDivisions($search, $params)
  {
    return $this->db->paginateQuery("SELECT * FROM division $search", $params);
  }

  public function add($data)
  {
    return $this->db->Query("INSERT INTO division (name,created) VALUES (?,?)", [$data, TIMESTAMP]);
  }
}
