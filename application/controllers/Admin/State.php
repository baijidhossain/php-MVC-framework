<?php

class State extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('State');
  }

  public function index()
  {

    // Start add state
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $state  = htmlspecialchars(trim($_POST['name'] ?? ""));
      $country_id =  $_POST['country'] ?? "";


      if (!is_numeric($country_id)) {
        $this->setAlert("error", "Invalid country");
        Util::redirectBack();
      }

      if ($country_id < 1) {
        $this->setAlert("error", "Invalid country");
        Util::redirectBack();
      }

      if (empty($state)) {
        $this->setAlert("error", "State name field is required ");
        Util::redirectBack();
      } else {



        if (strlen($state) > 50) {
          $this->setAlert("error", "State type must be less than or equal to 50 characters");
          Util::redirectBack();
        }

        $save = $this->model->add($country_id, $state);
        if ($save) {
          $this->setAlert("success", "State successfully added");
        } else {
          $this->setAlert("error", "State save unsuccessfully");
        }
      }
      Util::redirectBack();
    }


    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " ( s.name LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }

    $states = $this->model->getStates($search, $params);


    $data = [

      "states" => $states,
      "page_title" => "States"
    ];

    $this->View('State/index', $data);
  }

  public function add()
  {
    $countries = $this->model->getCountries();

    $data = [
      'countries' => $countries,
      "modal_title" => "Add New State",
      "action" => "add"
    ];

    $this->View('State/modal', $data);
  }

  public function Edit($id)
  {


    $state = $this->model->getState($id);
    $countries = $this->model->getCountries();

    $data = [
      'countries' => $countries,
      'state' => $state,
      'modal_title' => 'Edit State',
      'action' => 'edit'
    ];

    $this->View('State/modal', $data);
  }

  public function Update($id)
  {

    $state  = htmlspecialchars(trim($_POST['name'] ?? ""));
    $country_id =  $_POST['country'] ?? "";


    if (!is_numeric($country_id)) {
      $this->setAlert("error", "Invalid country");
      Util::redirectBack();
    }

    if ($country_id < 1) {
      $this->setAlert("error", "Invalid country");
      Util::redirectBack();
    }


    if (empty($state)) {
      $this->setAlert("error", "State name field is required ");
      Util::redirectBack();
    } else {


      if (strlen($state) > 50) {
        $this->setAlert("error", "State type must be less than or equal to 50 characters");
        Util::redirectBack();
      }

      $save = $this->model->update($country_id, $state, $id);
      if ($save) {
        $this->setAlert("success", "State successfully updated");
      } else {
        $this->setAlert("error", "State updated unsuccessfully");
      }
    }
    Util::redirectBack();
  }

  public function Delete($id = 0)
  {

    if (!$id) {
      $this->setAlert("error", "Invalid ID");
      Util::redirectBack();
    }

    $delete = $this->model->delete($id);

    if ($delete) {
      $this->setAlert("success", "State successfully deleted");
    } else {
      $this->setAlert("error", "The state could not be deleted");
    }

    Util::redirectBack();
  }
}
