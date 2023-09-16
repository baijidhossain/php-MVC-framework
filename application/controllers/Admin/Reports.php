<?php

class Reports extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Reports');
  }

  public function Stock()
  {


    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " (sr.product_code LIKE ? OR sr.product_name LIKE ? OR sr.buying_price LIKE ? ) ";
      $params[] = "%$searchTerm%";
      $params[] = "%$searchTerm%";
      $params[] = "%$searchTerm%";
      $params[] = "%$searchTerm%";
    }

    $stocks = $this->model->getStockReports($search, $params);

    $data = [
      "stocks" => $stocks,
      "page_title" => "Stocks"
    ];

    $this->View('Reports/stock', $data);
  }
}
