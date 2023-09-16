<?php

class Brands extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Brands');
  }

  public function Index()
  {
  
    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " ( name LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }

    $data = [
      "brands" => $this->model->getBrands($search, $params),
      "page_title" => "Manage Brand"
    ];

    $this->view('Brand/Index', $data);
  }

  public function add()
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $validet = Util::checkPostValues(['name', 'address']);
      if (!$validet) {
        $this->setAlert('error', 'Fill all the required field!');
        Util::redirectBack(); 
      } else {
        $data['name'] = $_POST['name'];
        $data['address'] = $_POST['address'];
        $add = $this->model->add($data);
        if ($add) {
          $this->setAlert('success', 'Brand  successfuly created');
          
        } else {
          $this->setAlert('error', 'Brand created  unsuccessfuly.');
         
        }
      }
      Util::redirectBack();
    }


    $data = [

      "modal_title" => "Add Brand",
      "action" => "add"
    ];

    $this->view('Brand/Modal', $data);
  }

  function Edit($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }
    $brandInfo = $this->model->getBrand($id);
    $data = [
      "brand" => $brandInfo,
      "action" => "edit",
      "modal_title" => "Edit Brand",
    ];
    $this->view('Brand/Modal', $data);
  }
  public function update()
  {
    $validet = Util::checkPostValues(['name', 'address', 'id']);

    if (!$validet) {
      $this->setAlert("error", "Fill all the required field!");
      Util::redirectBack();
    } else {
      $data = [
        "id" => $_POST['id'],
        "name" => $_POST['name'],
        "address" => $_POST['address'],
      ];
      $update = $this->model->update($data);
      if ($update) {
        $this->setAlert("success", "Brand successfully updated ");
      } else {
        $this->setAlert("error", "Brand unsuccessfully updated");
      }
    
    }
    Util::redirectBack();

  }

  public function delete($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id!");
      Util::redirectBack();
    } else {
      $delete = $this->model->delete($id);
      if ($delete) {
        $this->setAlert("success", "Company deleted successfully.");
      } else {
        $this->setAlert("error", "Company deleted unsuccessfully.");
      }
    }
    Util::redirectBack();
  }


}
