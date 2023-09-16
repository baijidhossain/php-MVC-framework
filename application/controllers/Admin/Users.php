<?php


class Users extends Controller
{



  public function __construct()
  {
    $this->model = $this->loadModel('Users');
  }





  public function Index()
  {


    if (isset($_POST['AddNew'])) {
      $requiredFields = ['name', 'email', 'phone', 'status', 'group_id', 'password'];
      $validated      = Util::checkPostValues($requiredFields);

      //Verify Name
      if (strlen($_POST['name']) < 5) {
        $this->setMessage('error', 'Name must be at least 5 characters');
        $validated = false;
      }

      //Verify Email
      if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $this->setMessage('error', 'Please enter a valid email');
        $validated = false;
      } else {
        if ($this->model->getUserByEmail($_POST['email'])) {
          $this->setMessage('error', 'Email already registered');
          $validated = false;
        }
      }

      //Verify password
      if (strlen($_POST['password']) < 8 || strlen($_POST['password']) > 20) {
        $this->setMessage('error', 'Password must be between 8 and 20 characters');
        $validated = false;
      }

      //Verify Mobile Number Format
      $checked_number = $this->validateNumber($_POST['phone']);
      if (!$checked_number) {
        $this->setMessage('error', 'Please enter a valid mobile number');
        $validated = false;
      } else {
        $_POST['phone'] = $checked_number;
        if ($this->model->numberExists($checked_number)) {
          $this->setMessage('error', 'Mobile number already exists');
          $validated = false;
        }
      }

      if ($validated) {
        $insert = $this->model->AddUser($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['status'], $_POST['group_id'], password_hash(trim($_POST['password']), PASSWORD_DEFAULT));

        if ($insert) {
          $this->setMessage('success', 'User added successfully');
        } else {
          $this->setMessage('error', 'Could not add user');
        }
      } else {
        $this->setMessage('error', 'Fill the form Correctly');
      }
    }



    $data = [
      "page_title" => "Manage Users",
      "users" => $this->model->getUsers(),

    ];

    $this->View('users/index', $data);
  }

  private function validateNumber($num)
  {
    $num    = ltrim($num, "+88");
    $number = '88' . ltrim($num, "88");

    $ext = ["88017", "88013", "88016", "88015", "88018", "88019", "88014"];
    if (is_numeric($number) && strlen($number) == 13) {
      if (in_array(substr($number, 0, 5), $ext)) {
        return $number;
      }

      return false;
    }

    return false;
  }

  public function addModal()
  {
    // $this->data['groups']     = $this->model->getGroups();
    // $this->data['page_title'] = 'Add New User';
    // $this->data['action'] = 'add';
    // $this->view               = 'users/modal';

    // $this->response();

    // echo '<pre>';
    // print_r($this->model->getGroups());
    // echo '</pre>';



    $data = [
      "modal_title" => "Add User",
      "action" => "add",
      "groups" => $this->model->getGroups(),
    ];

    $this->View('/users/modal', $data);
  }

  public function ChangeStatus($id, $status)
  {
    if (!$id || !isset($status)) {
      $this->setMessage('error', 'Invalid ID or Status');
    } else {
      if ($this->model->setStatus($id, $status)) {
        $this->setMessage('success', 'User status updated successfully');
      } else {
        $this->setMessage('error', 'Something went wrong');
      }
    }

    $this->redirect("/admin/users/");
  }

  public function ModifyModal($id = NULL)
  {
    if (!$id) {
      $this->setMessage('error', 'Invalid ID or Status');
    } else {
      $this->data['user']   = $this->model->getUser($id);
      $this->data['groups'] = $this->model->getGroups();
      $this->data['action'] = 'edit';

      $this->view = 'users/modal';

      $this->response();
    }
  }

  public function Update()
  {
    $requiredFields = ['id', 'group_id'];
    $validated      = Util::checkPostValues($requiredFields);

    if ($validated) {
      if ($this->model->update($_POST['id'], $_POST['group_id'])) {
        $this->setMessage('success', 'User updated successfully');
      } else {
        $this->setMessage('error', 'Something went wrong');
      }

      if (isset($_POST['password']) && !empty($_POST['password'])) {
        if (strlen($_POST['password']) < 8 || strlen($_POST['password']) > 20) {
          $this->setMessage('error', 'Password must be between 8 and 20 characters');
        } elseif ($this->model->setPassword($_POST['id'], password_hash(trim($_POST['password']), PASSWORD_DEFAULT))) {
          $this->setMessage('success', 'Password changed successfully');
        } else {
          $this->setMessage('error', 'Unknown error occurred.');
        }
      }
    } else {
      $this->setMessage('error', 'Fill the form Correctly');
    }

    $this->redirect("/admin/users/");
  }
}
