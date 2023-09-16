<?php

class ReportsModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getStockReports($search, $params)
  {

    return $this->db->paginateQuery("SELECT sr.*,p.name,p.buying_price,p.selling_price,p.final_selling_price FROM stock_report AS sr
    JOIN products AS p ON p.id = sr.product_id $search ORDER BY id DESC", $params);
  }
}
