<?php

class TaxModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getTaxs($search,$params){

    return $this->db->paginateQuery("SELECT * FROM tax $search",$params);
  }

}    
