<?php
class Contacts extends Controller
{
  public function __construct()
  {
    $this->model = $this->loadModel('Contacts');
  }

  public function AddModal()
  {

    $data = [
      'groups'      => $this->model->getGroups(),
      'modal_title' => 'Add contact',
    ];
    $this->view('addModal', $data);
  }
  public function Index()
  {


    //-------Add contact-------//
    if (isset($_POST['add'])) {

      // field validation
      $valided = Util::checkPostValues(['name', 'email', 'phone', 'address']);

      if (!$valided) {

        $this->setAlert("error", "Fill all the required field!");
      } else {

        $insertcontact['name']    = $_POST['name'];
        $insertcontact['email']   = $_POST['email'];
        $insertcontact['phone']   = $_POST['phone'];
        $insertcontact['address'] = $_POST['address'];
        $insertcontact['created'] = TIMESTAMP;
        $insertgroup['groups']    = !empty($_POST['groups']) ?
          $_POST['groups'] : [];


        $insert = $this->model->add($insertcontact, $insertgroup);

        if ($insert) {

          $this->setAlert("success", "Contact save was successfully.");
        } else {

          $this->setAlert("error", "Insert Failed");
        }
      }
    }
    //----------End add contact-----------//

    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " (cg.name LIKE ? OR cg.email = ? OR cg.phone = ? ) ";
      $params[] = "%$searchTerm%";
      $params[] = "$searchTerm";
      $params[] = "$searchTerm";
    }

    if (!empty($_GET['group'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['group'];
      $search .= " cg.id IN (SELECT contact_id FROM contact_group_relation WHERE group_id=?) ";
      $params[] = $searchTerm;
    }

    $contacts       = $this->model->getContacts($search, $params);
    $allgroups      = $this->model->getGroups();
    $groupsRelation = $this->model->getGroupsRelation();

    $data = [
      'allgroup'   => $allgroups,
      'contacts'   => $contacts,
      'groups'     => $groupsRelation,
      'page_title' => 'Contact List',
    ];

    $this->view('Contact', $data);
  }

  public function editModal($id)
  {
    $data = [
      'relation'    => $this->model->getEditRelation($id),
      'group'       => $this->model->getGroups(),
      'editdata'    => $this->model->getEditContactData($id),
      'modal_title' => 'Edit contact',
    ];
    $this->view('editModal', $data);
  }

  public function update()
  {

    $valided = Util::checkPostValues(['name', 'email', 'phone', 'id']);

    if (!$valided) {
      $this->setAlert("error", "Fill all the required field!");
      Util::redirectBack();
    } else {

      $data['id']    = $_POST['id'];
      $data['name']  = $_POST['name'];
      $data['email'] = $_POST['email'];
      $data['phone'] = $_POST['phone'];
      $data['photo'] = !empty($_FILES['photo']) ?  $_FILES['photo'] : '';

      $groups = !empty($_POST['groups']) ?  $_POST['groups'] : [];
      //-------Start Image upload update-------//

      $img_name = "";
      if (isset($_FILES['photo']) && $_FILES['photo']['tmp_name'] != "") {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
          if ($_FILES["photo"]["size"] < 2097152) {
            $img_name  = $_POST['id'] . ".png";
            $save_path = PUBLIC_PATH . "images/contactimg/" . $img_name;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $save_path);
          } else {
            $this->setAlert('error', 'Sorry, Your file is too large. Upload Size is Maximum 2MB');
            Util::redirect(APP_URL . "contacts");
          }
        } else {
          $this->setAlert(
            'error',
            'Sorry, Your file is not an image.'
          );
          Util::redirect(APP_URL . "/admin/contacts");
        }
      }
      //-------End Image upload update-------//

      $update = $this->model->update($data, $groups, $img_name);
      if ($update) {

        $this->setAlert("success", "Contact updated was successfully.");
      } else {
        $this->setAlert("error", "Contact updated unsuccessfully.");
      }

      Util::redirect(APP_URL . "/admin/contacts");
    }
  }

  public function delete($contact_id = 0)
  {
    if (!$contact_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirect(APP_URL . "contacts");
    }

    $data = $this->model->deleteContacts($contact_id);
    $this->model->deleteContactRelation($contact_id);

    if ($data > 0) {
      $this->setAlert("success", "Contact deleted was succesfully.");
      Util::redirect(APP_URL . "/admin/contacts");
    }
    Util::redirect(APP_URL . "/admin/contacts");
  }
}
