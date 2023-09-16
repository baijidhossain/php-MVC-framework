<?php

class Customers extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Customers');
  }

  public function index()
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $this->addCustomer();
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

    $customers = $this->model->getCustomers($search, $params);
    $data = [
      "customers" => $customers,
      "page_title" => "Customers"
    ];

    $this->View('Customer/index', $data);
  }

  public function Add()
  {

    $countries = $this->model->getCountries();

    $data = [
      'countries' => $countries,
      "page_title" => "Add Customer"
    ];

    $this->View('Customer/add', $data);
  }
  public function Edit($id)
  {
    $customer = $this->model->getCustomer($id);
    $countries = $this->model->getCountries();
    $states = $this->model->getStates( $customer['country']);
    $cities = $this->model->getCities($customer['state']);

    // print_r($states);
    // die; 

    $data = [
      'countries' => $countries,
      'states' => $states,
      'cities' => $cities,
      'customer'=>$customer,
      "page_title" => "Edit Customer"
    ];

    $this->View('Customer/edit', $data);
  }

  public function Update($id){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $this->updateCustomer($id);
      Util::redirectBack();
    }

  }

  public function getPlace()
  {

    $method = $_POST['methodName'] ?? "";
    $place_id = $_POST['place_id'] ?? "";

    $states =  $this->model->$method($place_id);

    foreach ($states as  $state) {
      echo '<option value=""></option>';
      echo '<option value="' . $state['id'] . '">' . $state['name'] . '</option>';
    }
  }

  // extra function______

  private function validatedCustomerData()
  {


    $validated = Util::checkPostValues(['name', 'email', 'phone']);

    if (!$validated) {
      $this->setAlert("error", "Fill all the required field");
      return false;
    }

    $name = htmlspecialchars(trim($_POST['name']));

    $email = htmlspecialchars(trim($_POST['email']));

    $phone = htmlspecialchars(trim($_POST['phone']));

    $opening_balance = $_POST['opening_balance'] ?? "";

    $country = $_POST['country'] ?? '';

    $state = $_POST['state'] ?? '';

    $city = $_POST['city'] ?? '';

    $zip = $_POST['zip'] ?? '';

    $address = htmlspecialchars(trim($_POST['address'] ?? ''));

    if (strlen($name) > 50) {
      $this->setAlert("error", "Customer name must be less than or equal to 50 characters");
      return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->setAlert("error", "Invalid email");
      return false;
    }

    if (strlen($phone) > 15) {
      $this->setAlert("error", "Phone number must be less than or equal to 15 characters");
      return false;
    }


    if (!Util::validateNumber($phone)) {
      $this->setAlert("error", "Invalid phone number");
      return false;
    }


    if (!is_numeric($country) || $country < 1) {
      $this->setAlert("error", "Invalid Country");
      return false;
    }

    if (!is_numeric($state) || $state < 1) {
      $this->setAlert("error", "Invalid state");
      return false;
    }
    if (!is_numeric($city) || $city < 1) {
      $this->setAlert("error", "Invalid city");
      return false;
    }


    if (strlen($zip) > 100) {
      $this->setAlert("error", "zip must be less than or equal to 100 characters");
      return false;
    }


    return [
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'opening_balance' => $opening_balance,
      'country' => $country,
      'state' => $state,
      'city' => $city,
      'zip' => $zip,
      'address' => $address,
    ];
  }

  private function addCustomer()
  {


    $validatedCustomerData = $this->validatedCustomerData();

    if (empty($validatedCustomerData)) {
      return false;
    }

    if ($this->model->add($validatedCustomerData)) {
      $this->setAlert("success", "Customer successfully added");
      return false;
    } else {
      $this->setAlert("error", "Something went wrong");
    }

    return true;
  }

  private function updateCustomer()
  {


    $validatedCustomerData = $this->validatedCustomerData();

    if (empty($validatedCustomerData)) {
      return false;
    }

    if ($this->model->update($validatedCustomerData)) {
      $this->setAlert("success", "Customer successfully updated");
      return false;
    } else {
      $this->setAlert("error", "Something went wrong");
    }

    return true;
  }
}
