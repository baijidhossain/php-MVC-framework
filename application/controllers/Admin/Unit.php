<?php

class Unit extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Unit');
  }

  public function index()
  {


    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " ( name LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }


    $getunits = $this->model->getUnits($search, $params);
    $data = [
      "units" => $getunits,
      "page_title" => "Units"
    ];

    $this->View('Unit/index', $data);
  }

  public function Add()
  {

    // Start add color
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $validet = Util::checkPostValues(['name']);
      if (!$validet) {
        $this->setAlert("error", "Fill all the required field!");
        Util::redirectBack();
      } else {
        $data = [
          "name" => $_POST['name']
        ];
        $save = $this->model->add($data);
        if ($save) {
          $this->setAlert("success", "Unit  successfully created");
        } else {
          $this->setAlert("error", "Unit created unsuccessfully");
        }
      }
      Util::redirectBack();
    }

    // End Add color


    $data = [
      "modal_title" => "Add Unit",
      "action" => "add"
    ];
    $this->View('Unit/Modal', $data);
  }
  public function Edit($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    } else {
      $unitInfo = $this->model->getUnit($id);
      $data = [
        "unit" => $unitInfo,
        "modal_title" => "Edit Unit",
        "action" => "edit"
      ];

      $this->View('Unit/Modal', $data);
    }
  }

  public function Update()
  {
    $validet = Util::checkPostValues(['name', 'id']);
    if (!$validet) {
      $this->setAlert("error", "Fill all the required field!");
      Util::redirectBack();
    } else {
      $data['id'] = $_POST['id'];
      $data['name'] = $_POST['name'];
      $update = $this->model->update($data);
      if ($update) {
        $this->setAlert("success", "Unit successfully updated");
      } else {
        $this->setAlert("error", "Unit updated unsuccessfully.");
      }
      Util::redirectBack();
    }
  }

  public function Delete($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id!");
      Util::redirectBack();
    } else {
      $delete = $this->model->delete($id);
      if ($delete) {
        $this->setAlert("success", "Unit successfully deleted");
       
      } else {
        $this->setAlert("error", " Unit deleted unsuccessfully");
        
      }
    }
    Util::redirectBack();
  }

}
