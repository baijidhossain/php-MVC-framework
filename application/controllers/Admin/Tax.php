<?php

class Tax extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Tax');
  }

  public function index()
  {

   
    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " ( tax LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }

   
 
    $data = [
      "taxs"=>$this->model->getTaxs($search,$params),
      "page_title" => "Tax"
    ];

  $this->View('Tax/Index', $data);

  }



}
