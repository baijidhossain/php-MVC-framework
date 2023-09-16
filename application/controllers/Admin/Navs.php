<?php

class Navs extends Controller
{

  /**
   * @var mixed
   */
  private $model;

  public function __construct()
  {
    $this->model = $this->loadModel('Navs');
  }

  public function Index()
  {
    if (isset($_POST['update'])) {
      // check required fields
      $required = ['id', 'nav_icon', 'nav_name', 'nav_path', 'parent_id', 'group_id'];

      $validated = Util::checkPostValues($required);

      if ($validated) {
        $update = $this->model->update($_POST['id'], $_POST['nav_name'], $_POST['nav_path'], $_POST['parent_id'], $_POST['nav_icon'], $_POST['group_id']);

        $update ? $this->setAlert('success', 'Navigations are updated') : $this->setAlert('error', 'Something went wrong');
      } else {
        $this->setAlert('error', 'Fill the form correctly');
      }
    } elseif (isset($_POST['add'])) {
      // check required fields
      $required = ['nav_icon', 'nav_name', 'nav_path', 'parent_id', 'group_id'];
      $validated = Util::checkPostValues($required);

      if ($validated) {
        $insert = $this->model->insert($_POST['nav_name'], $_POST['nav_path'], $_POST['parent_id'], $_POST['nav_icon'], $_POST['group_id']);

        $insert ? $this->setAlert('success', 'New item inserted successfully') : $this->setAlert('error', 'Something went wrong');
      } else {
        $this->setAlert('error', 'Fill the form correctly');
      }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

    $db_navs = $this->model->getNavs();
    $permissions = $this->model->getPermissions();
    $db_groups = $this->model->getGroups();

    // get all groups
    $groups = array_combine(
      array_column($db_groups, 'id'),
      array_column($db_groups, 'group_name')
    );
    // insert group ids to navs array
    $navs = [];
    foreach ($db_navs as $db_nav) {
      $navs[$db_nav['id']] = $db_nav;
    }

    if ($permissions) {
      foreach ($permissions as $p) {
        $navs[$p['nav_id']]['group_id'][] = $p['group_id'];
      }
    }

    $data = [
      'view_type' => 'view',
      'page_title' => 'Control Navigations',
      'groups' => $groups,
      'navs' => $navs
    ];

    $this->view('Navs', $data);
  }

  public function Add()
  {
    $icons = require(VIEW_PATH . 'extras/fontawesome.php');

    $data = [
      'view_type' => 'modalAdd',
      'page_title' => 'Add New Navigation Item',
      'navParents' => $this->model->getParents(),
      'groups' => $this->model->getGroups(),
      'icons' => $icons
    ];

    $this->view('Navs', $data);
  }

  public function UpdateNav()
  {
    if (!isset($_POST['id']) or empty($_POST['id']) or !isset($_POST['parent_id']) or empty($_POST['parent_id'])) {
      $this->setAlert('error', 'Empty ID or Parent ID');
    }

    $update = $this->model->setNavs($_POST['id'], $_POST['parent_id']);

    if ($update) {
      $this->setAlert('success', 'Navigation successfully updated');
    } else {
      $this->setAlert('error', 'Something went wrong');
    }
  }

  public function Edit($id = 0)
  {
    // require font awesome icon array
    $icons = require(VIEW_PATH . 'extras/fontawesome.php');
    $data['page_title'] = "Update this item";
    if (!empty($icons)) {
      $data['icons'] = $icons;
    }
    $data['view_type'] = 'modalEdit';
    $permissions = $this->model->getPermissions();
    $data['navinfo'] = $this->model->getInfo($id);
    $data['navParents'] = $this->model->getParents();
    $data['groups'] = $this->model->getGroups();
    $data['submit'] = '/admin/navs/update/';

    // push group ids
    if ($permissions) {
      foreach ($permissions as $p) {
        $data['navinfo']['group_id'][] = ($data['navinfo']['id'] == $p['nav_id']) ? $p['group_id'] : [];
      }
    }



    $this->view('Navs', $data);
  }

  public function Delete()
  {
    if (isset($_POST['id']) and !empty(($_POST['id']))) {
      $id = $_POST['id'];
    }

    $delete = $this->model->delete($id);

    $delete ? $this->setAlert('success', 'Item delete successful') : $this->setAlert('error', 'Unfortunately item is not deleted');

    Util::redirect('/admin/navs');
  }
}
