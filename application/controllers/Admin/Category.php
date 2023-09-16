<?php

class Category extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Category');
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

    $category = $this->model->getCategories($search, $params);

    $data = [
      "getAllcategory" => $category,
      "page_title" => "Manage Category"
    ];

    $this->View('Category/Index', $data);
  }
  public function Add()
  {


    // addCategory
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $validated = Util::checkPostValues(['name']);
      if (!$validated) {
        $this->setAlert("error", "Category name field is required");
        Util::redirectBack();
      } else {
        $name = $_POST['name'];
        $addcategory = $this->model->add($name);

        if ($addcategory) {
          $this->setAlert("success", "Category save was successfully.");
         
        } else {
          $this->setAlert("error", "Category save was failed!.");
          
        }
      }
      Util::redirectBack();
    }
    // end add category

    $data = [
      "modal_title" => "Add Category",
      "action" => "add",
    ];
    $this->View('Category/Modal', $data);
  }

  public function Edit(int $id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    } else {
      $categoryInfo = $this->model->geteCategory($id);
      $data = [
        "category" => $categoryInfo,
        "modal_title" => "Edit Category",
        "action" => "edit"
      ];

      $this->View('Category/Modal', $data);
    }
  }
  public function Update()
  {
    $validated  = Util::checkPostValues(['name', 'id']);
    if ($validated == false) {
      $this->setAlert("error", "Category name field is required");
      Util::redirectBack();
    } else {
      $data['id'] =     $_POST['id'];
      $data['name'] = $_POST['name'];
      $update = $this->model->update($data);
      if ($update) {
        $this->setAlert("success", "Category successfully updated");
      } else {
        $this->setAlert("error", "Category updated failed!");
      }
      Util::redirectBack();
    }
  }

  public function Delete($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id!");
      Util::redirect(APP_URL . "/admin/category");
    }
    $delete = $this->model->delete($id);
    if ($delete) {
      $this->setAlert("success", "Category deleted successfully.");
      Util::redirect(APP_URL . "/admin/category");
    } else {
      $this->setAlert("error", "Category deleted unsuccessfully.");
      Util::redirect(APP_URL . "/admin/category");
    }
  }


}
