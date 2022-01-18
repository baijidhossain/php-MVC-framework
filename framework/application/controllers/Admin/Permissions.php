<?php

class Permissions extends Controller {

    public function __construct()
    {
        $this->model = $this->loadModel('Permissions');
    }

    public function Index()
    {
        if (isset($_POST['store'])) {

            // check required fields
            if (!empty($_POST['nav_path']) && !empty($_POST['permissions'])) {

                $insert = $this->model->store($_POST['nav_path'], $_POST['permissions']);

                $insert ? $this->setAlert('success', 'New item inserted successfully') : $this->setAlert('error', 'Something went wrong');

            } else {

                $this->setAlert('error', 'Fill the form correctly');
            }

        } elseif (isset($_POST['update'])) {

            // check for required fields
            if (!empty($_POST['nav_path']) && !empty($_POST['id'])) {
                if (empty($_POST['permissions'])){
                    $_POST['permissions'] = [];
                }

                $update = $this->model->update($_POST['id'], $_POST['nav_path'], $_POST['permissions']);

                $update ? $this->setAlert('success', 'Item updated successfully') : $this->setAlert('error', 'Something went wrong');

            } else {

                $this->setAlert('error', 'Fill the form correctly');
            }

        }


        $db_navs = $this->model->getAll();
        $db_permissions = $this->model->getPermissions();
        $db_groups = $this->model->getGroups();
        $permissions = [];

        // get all groups
        $groups = array_combine(
            array_column($db_groups, 'id'),
            array_column($db_groups, 'group_name')
        );


        // insert group ids to navs array
        if (!empty($db_navs)) {
            foreach ($db_navs as $nav) {
                $permissions[$nav['id']] = $nav;
            }
        }
        if ($db_permissions){
            foreach ($db_permissions as $p) {
                $permissions[$p['permission_id']]['pid'][] = $p['group_id'];
            }
        }
        //------------------------

        $db_paths = array_column($permissions, 'action');
        $scanned_paths = $this->scanPath();
        $nav_paths = array_diff($scanned_paths, $db_paths);
        $paths = [];

        foreach ($nav_paths as $path) {
            $paths[] = [
                'action' => $path,
                'pid'    => []
            ];
        }

        $navs = array_merge($permissions, $paths);

        // usort($navs, function ($a, $b) {
        //     return $a['action'] <=> $b['action'];
        // });

        $data = [
            'view_type'  => 'view',
            'page_title' => 'Access Control',
            'groups'     => $groups,
            'paths'      => $navs
        ];

        $this->view('Permissions', $data);
    }

    private function scanPath()
    {

        $path = [];

        $controllers = glob(APP_PATH . 'controllers/' . '*.php');

        $secs = json_decode(file_get_contents(APP_PATH . "config/section.json"), true);

        foreach ($secs as $sec) {

            $controllers = array_merge($controllers, glob(APP_PATH . 'controllers/' . ucfirst($sec) . '/*.php'));
        }

        foreach ($controllers as $file) {

            $name = str_replace(APP_PATH . 'controllers/', "", pathinfo($file, PATHINFO_DIRNAME) . '/' . pathinfo($file, PATHINFO_FILENAME));

            $arr = file($file);

            foreach ($arr as $line) {
                if (preg_match('/public function ([_A-Za-z0-9]+)/', $line, $regs)) {

                    if ($regs[1] != '__construct') {

                        $path[] = $name . '/' . $regs[1];
                    }
                }

            }


        }

        return $path;

    }

    public function Edit($action = '')
    {
        $data = [
            'view_type'   => 'modal',
            'modal_title' => 'Edit Access Control',
            'groups'      => $this->model->getGroups()
        ];

        $db_nav = $this->model->getInfo(urldecode($action));
        if ($db_nav) {
            $data['navinfo'] = $db_nav;
        } else {
            $data['navinfo'] = [
                'id'     => null,
                'action' => $action,
                'pid'    => []
            ];
        }

        $permissions = $this->model->getPermissions();

        // insert group ids to navs array
        $data['navinfo']['pid'] = [];

        if ($permissions){
            foreach ($permissions as $p) {
                if ($data['navinfo']['id'] == $p['permission_id']) {
                    $data['navinfo']['pid'][] = $p['group_id'];
                }
            }
        }

        $this->view('Permissions', $data);

    }

}