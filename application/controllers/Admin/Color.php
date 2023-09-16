<?php

class Color extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Color');
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


    $getAllcolor = $this->model->getallcolor($search, $params);
    $data = [
      "color" => $getAllcolor,
      "page_title" => "Color list"
    ];

    $this->View('Color/Index', $data);
  }


  public function Add()
  {

    // Start add color
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $validated = Util::checkPostValues(['name']);
      if (!$validated) {
        $this->setAlert("error", "Color nmae field is required");
        Util::redirectBack();
      } else {
        $data = [
          "name" => $_POST['name']
        ];
        $save = $this->model->add($data);
        if ($save) {
          $this->setAlert("success", "Color  successfully added");
        } else {
          $this->setAlert("error", "Color save unsuccessfully");
        }
      }
      Util::redirectBack();
    }

    // End Add color


    $data = [
      "modal_title" => "Add Color",
      "action" => "add"
    ];
    $this->View('Color/Modal', $data);
  }

  public function Edit($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirect(APP_URL . "/admin/color");
    } else {
      $editCaolor = $this->model->geteditColor($id);
      $data = [
        "color" => $editCaolor,
        "modal_title" => "Edit Color",
        "action" => "edit"
      ];

      $this->View('Color/Modal', $data);
    }
  }

  public function Update()
  {
    $validated = Util::checkPostValues(['name', 'id']);
    if (!$validated) {
      $this->setAlert("error", "Color name field is required");
      Util::redirectBack();
    } else {
      $data['id'] = $_POST['id'];
      $data['name'] = $_POST['name'];
      $update = $this->model->update($data);
      if ($update) {
        $this->setAlert("success", "Color successfully updated .");
      } else {
        $this->setAlert("error", "Updated unsuccessfully.");
      }
      
    }
    Util::redirectBack();
  }

  public function Delete($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id!");
      Util::redirectBack();
    } else {
      $delete = $this->model->delete($id);
      if ($delete) {
        $this->setAlert("success", "Color successfully deleted");
       
      } else {
        $this->setAlert("error", " Color deleted unsuccessfully.");
        
      }
    }
    Util::redirectBack();
  }


}
