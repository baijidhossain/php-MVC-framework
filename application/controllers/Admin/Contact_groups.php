
<?php

class Contact_groups extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Contact_groups');
  }

  public function index()
  {
    //--Add group--//
    if (isset($_POST['submitGroup'])) {
      $insert['name'] = $_POST['name'];
      $valided        = Util::checkPostValues(['name']);
      if (!$valided) {
        $this->setAlert("error", "Fill all the required field!");
        Util::redirect(APP_URL . "Admin/Contact_groups");
      } else {
        $insert = $this->model->add($insert);
        if ($insert) {
          $this->setAlert("success", "Group save was successfully.");
          Util::redirect(APP_URL . "Admin/Contact_groups");
        }
      }
    }

    //--End Add group--//

    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $search .= " (g.name LIKE ? ) ";
      $params[] = "%" . $_GET['search'] . "%";
    }

    $group = $this->model->view($search, $params);

    $data = [
      'groups'     => $group,
      'page_title' => "Group list",
    ];

    $this->view('Admin/Group', $data);
  }

  public function update()
  {
    $data['id']   = $_POST['id'];
    $data['name'] = $_POST['name'];
    $editgroup    = $this->model->update($data);

    if ($editgroup) {
      $this->setAlert("success", "Group updated was successfully.");
    } else {
      $this->setAlert("error", "Group updated was unsuccessfully.");
    }

    Util::redirect(APP_URL . "Admin/Contact_groups");
  }

  public function delete($id = 0)
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirect(APP_URL . "Admin/Contact_groups");
    }
    $dg = $this->model->dgroup($id);
    if ($dg) {
      $this->setAlert("success", "Group deleted was successfully.");
    }
    Util::redirect(APP_URL . "Admin/Contact_groups");
  }

  public function addgm()
  {
    $data = [
      'group_title' => "Add group",
    ];
    $this->view('Admin/addgroup', $data);
  }

  public function editgm($id)
  {
    $editgroup = $this->model->edit($id);
    $data      = [
      'editdata'    => $editgroup,
      'group_title' => "Edit group",
    ];

    $this->view('Admin/editgroup', $data);
  }
}
?>
