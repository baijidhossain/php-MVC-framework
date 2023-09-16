<?php


    class PermissionsModel {

        public function __construct()
        {
            $this->db = new Database;
        }

        public function getAll()
        {
            $navs = $this->db->query("SELECT * FROM `permission`");

            if ($navs->numRows() > 0) {
                return $navs->fetchAll();
            }

            return false;
        }

        public function getPermissions()
        {
            $permissions = $this->db->query("SELECT * FROM `acl`");

            if ($permissions->numRows() > 0) {
                return $permissions->fetchAll();
            }

            return false;
        }

        public function getGroups()
        {
            $groups = $this->db->query("SELECT * FROM `user_group`");

            if ($groups->numRows() > 0) {
                return $groups->fetchAll();
            }

            return false;
        }

        public function getInfo($action)
        {
            $info = $this->db->query("SELECT * FROM `permission` WHERE `action` = ?", $action);

            if ($info->numRows() > 0) {
                return $info->fetchArray();
            }

            return false;
        }

        public function store($nav_path, $permissions)
        {
            try {
                $this->db->beginTransaction();

                $this->db->query("INSERT INTO `permission` (`action`) VALUES(?)", $nav_path);

                if (count($permissions) > 0) {
                    $lastInsertID = $this->db->lastInsertID();

                    foreach ($permissions as $pid) {
                        $this->db->query("INSERT INTO acl (permission_id, group_id, created) VALUES (?, ?, ?)", $lastInsertID, $pid, TIMESTAMP);
                    }
                }

                $this->db->Commit();

                return true;

            } catch (Exception $e) {

                $this->db->Rollback();

                return false;
            }
        }

        public function update($id, $nav_path, $permissions = [])
        {
            try {
                $this->db->beginTransaction();

                $this->db->query("UPDATE `permission` SET `action` = ? WHERE `id` = ? ", $nav_path, $id);

                if (count($permissions) > 0) {
                    $this->db->query("DELETE FROM acl WHERE permission_id = ?", $id);
                    foreach ($permissions as $pid) {
                        $this->db->query("INSERT INTO acl (permission_id, group_id, created) VALUES (?, ?, ?)", $id, $pid, TIMESTAMP);
                    }

                } else {
                    $this->db->query("DELETE FROM acl WHERE permission_id = ?", $id);
                    $this->db->query("DELETE FROM permission WHERE `id` = ?", $id);
                }

                $this->db->Commit();

                return true;

            } catch (Exception $e) {

                $this->db->Rollback();

                return false;
            }
        }


    }