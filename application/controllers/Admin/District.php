<?php

class District extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('District');
  }

  public function Index()
  {


    // Start add district
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $validet = Util::checkPostValues(['name']);

      if (!$validet) {
        $this->setAlert("error", "District name field is required ");
        Util::redirectBack();
      } else {

        $District  = htmlspecialchars(trim($_POST['name']));

        if (strlen($District) > 50) {
          $this->setAlert("error", "District type must be less than or equal to 50 characters");
          Util::redirectBack();
        }

        $save = $this->model->add($District);
        if ($save) {
          $this->setAlert("success", "District successfully added");
        } else {
          $this->setAlert("error", "District save unsuccessfully");
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
    $districts = $this->model->getDistricts($search, $params);
    $data = [
      "districts" => $districts,
      "page_title" => "Districts"
    ];

    $this->View('District/index', $data);
  }

  public function Add()
  {
    $data = [
      "modal_title" => "Add District",
      "action" => "add"
    ];

    $this->View('District/modal', $data);
  }
}
