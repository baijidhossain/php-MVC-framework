<?php

class City extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('City');
  }

  public function Index()
  {


    // Start add City
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $City  = htmlspecialchars(trim($_POST['name']));
      $state  = $_POST['state'] ?? "";

      if (!is_numeric($state)) {
        $this->setAlert("error", "Invalid state");
        Util::redirectBack();
      }

      if ($state < 1) {
        $this->setAlert("error", "Invalid state");
        Util::redirectBack();
      }



      if (empty($City)) {
        $this->setAlert("error", "City name field is required ");
        Util::redirectBack();
      } else {



        if (strlen($City) > 50) {
          $this->setAlert("error", "City type must be less than or equal to 50 characters");
          Util::redirectBack();
        }

        $save = $this->model->add($state, $City);
        if ($save) {
          $this->setAlert("success", "City successfully added");
        } else {
          $this->setAlert("error", "City save unsuccessfully");
        }
      }
      Util::redirectBack();
    }

    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " ( c.name LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }
    $Cities = $this->model->getCities($search, $params);
    $data = [
      "cities" => $Cities,
      "page_title" => "City List"
    ];

    $this->View('City/index', $data);
  }

  public function getState()
  {

    $method = $_POST['methodName'] ?? "";
    $place_id = $_POST['place_id'] ?? "";

    $states =  $this->model->$method($place_id);

    foreach ($states as  $state) {
      echo '<option value=""></option>';
      echo '<option value="' . $state['id'] . '">' . $state['name'] . '</option>';
    }
  }


  public function Add()
  {
    $countries = $this->model->getCountries();

    $data = [
      'countries' => $countries,
      "modal_title" => "Add City",
      "action" => "add"
    ];

    $this->View('City/modal', $data);
  }


  public function Edit($id)
  {
    $countries = $this->model->getCountries();
    $city = $this->model->getCity($id);

    $data = [
      'countries' => $countries,
      'city' => $city,
      'modal_title' => 'Edit State',
      'action' => 'edit'
    ];

    $this->View('City/modal', $data);
  }

  public function Update($id)
  {

    $city  = htmlspecialchars(trim($_POST['name'] ?? ""));
    $state  = $_POST['state'] ?? "";



    if (!is_numeric($state)) {
      $this->setAlert("error", "Invalid state");
      Util::redirectBack();
    }

    if ($state < 1) {
      $this->setAlert("error", "Invalid state");
      Util::redirectBack();
    }

    if (empty($city)) {
      $this->setAlert("error", "City name field is required ");
      Util::redirectBack();
    } else {


      if (strlen($city) > 50) {
        $this->setAlert("error", "City type must be less than or equal to 50 characters");
        Util::redirectBack();
      }

      $update = $this->model->update($state, $city, $id);
      if ($update) {
        $this->setAlert("success", "City successfully updated");
      } else {
        $this->setAlert("error", "City updated unsuccessfully");
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
      $this->setAlert("success", "City successfully deleted");
    } else {
      $this->setAlert("error", "The state could not be deleted");
    }

    Util::redirectBack();
  }
}
