<?php

class Division extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Division');
  }

  public function index()
  {



    // Start add color
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $validet = Util::checkPostValues(['name']);

      if (!$validet) {
        $this->setAlert("error", "Division name field is required ");
        Util::redirectBack();
      } else {

        $division  = htmlspecialchars(trim($_POST['name']));

        if (strlen($division) > 50) {
          $this->setAlert("error", "Division type must be less than or equal to 50 characters");
          Util::redirectBack();
        }

        $save = $this->model->add($division);
        if ($save) {
          $this->setAlert("success", "Division successfully added");
        } else {
          $this->setAlert("error", "Division save unsuccessfully");
        }
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

    $data = [
      "divisions" => $this->model->getDivisions($search, $params),
      "page_title" => "Divisions"
    ];

    $this->View('Division/index', $data);
  }

  public function add()
  {
    $data = [
      "modal_title" => "Add Division",
      "action" => "add"
    ];

    $this->View('Division/Model', $data);
  }

  public function delete($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
    }
  }
}
