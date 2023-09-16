<?php

class Country extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Country');
  }

  public function Index()
  {

    // Start add color
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $validated = Util::checkPostValues(['name']);

      if (!$validated) {
        $this->setAlert("error", "Country name field is required ");
        Util::redirectBack();
      } else {

        $country  = htmlspecialchars(trim($_POST['name']));

        if (strlen($country) > 50) {
          $this->setAlert("error", "Country type must be less than or equal to 50 characters");
          Util::redirectBack();
        }

        $save = $this->model->add($country);
        if ($save) {
          $this->setAlert("success", "Country successfully added");
        } else {
          $this->setAlert("error", "Country save unsuccessfully");
        }
      }
      Util::redirectBack();
    }




    $countries = $this->model->getCountries();

    $data = [
      'countries' => $countries,
      'page_title' => 'Country List'
    ];

    $this->view('Country/index', $data);
  }

  public function Add()
  {

    $data = [
      'modal_title' => 'Add New Country',
      'action' => 'add'
    ];

    $this->View('Country/modal', $data);
  }

  public function Edit($id)
  {

    $country = $this->model->getCountry($id);

    $data = [
      'country' => $country,
      'modal_title' => 'Edit Country',
      'action' => 'edit'
    ];

    $this->View('Country/modal', $data);
  }

  public function Update($id)
  {

    $country  = htmlspecialchars(trim($_POST['name'] ?? ""));

    if (empty($country)) {
      $this->setAlert("error", "Country name field is required ");
      Util::redirectBack();
    } else {


      if (strlen($country) > 50) {
        $this->setAlert("error", "Country type must be less than or equal to 50 characters");
        Util::redirectBack();
      }

      $save = $this->model->update($country, $id);
      if ($save) {
        $this->setAlert("success", "Country successfully updated");
      } else {
        $this->setAlert("error", "Country updated unsuccessfully");
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
      $this->setAlert("success", "Country successfully deleted");
    } else {
      $this->setAlert("error", "The country could not be deleted");
    }

    Util::redirectBack();
  }
}
