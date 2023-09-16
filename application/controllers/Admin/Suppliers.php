<?php

class Suppliers extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Suppliers');
  }

  public function index()
  {


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
     

      if (empty($validatedData)) {
        Util::redirectBack();
      }

      if ($this->model->add($validatedData)) {
        $this->setAlert("success", "Customer successfully added");
        Util::redirectBack();
      } else {
        $this->setAlert("error", "Somthing went wrong");
        Util::redirectBack();
      }

      Util::redirectBack();
    }


    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " ( name LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }



    $suppliers = $this->model->getSuppliers($search, $params);

    $data = [
      "suppliers" => $suppliers,
      "page_title" => "Suppliers"
    ];

    return $this->View('Supplier/Index', $data);
  }
}
