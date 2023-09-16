<?php

class NavsModel {

    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getNavs()
    {
        $navs = $this->db->query("SELECT * FROM `navigation` ORDER BY sort");

        if ($navs->numRows() > 0) {
            return $navs->fetchAll();
        }

        return false;
    }

    public function getPermissions()
    {
        $permissions = $this->db->query("SELECT * FROM `nav_permission`");

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

    public function setNavs($ids, $parent_ids)
    {
        try {

            $this->db->beginTransaction();

            for ($i = 0; $i < count($ids); $i++) {
                $this->db->query(
                    "UPDATE navigation SET parent_id = ?, sort = ? WHERE id = ?", $parent_ids[$i], $i, $ids[$i]
                );
            }

            $this->db->Commit();

            return true;

        } catch (Exception $e) {

            $this->db->Rollback();

            return false;
        }
    }

    public function getInfo($id)
    {
        $info = $this->db->query("SELECT * FROM `navigation` WHERE `id` = ?", $id);

        if ($info->numRows() > 0) {
            return $info->fetchArray();
        }

        return false;
    }

    public function getParents()
    {
        $parents = $this->db->query("SELECT * FROM `navigation` WHERE `parent_id` = 0");

        if ($parents->numRows() > 0) {
            return $parents->fetchAll();
        }

        return false;
    }

    public function insert($nav_name, $nav_path, $parent_id, $nav_icon, $group_id)
    {
        try {

            $this->db->beginTransaction();

            $this->db->query("INSERT INTO `navigation` (`nav_name`, `nav_path`, `parent_id`, `nav_icon`) VALUES(?,?,?,?)", $nav_name, $nav_path, $parent_id, $nav_icon);

            if (count($group_id) > 0) {
                $lastInsertID = $this->db->lastInsertID();

                foreach ($group_id as $gid) {
                    $this->db->query("INSERT INTO nav_permission (nav_id, group_id, created) VALUES (?, ?, ?)", $lastInsertID, $gid, TIMESTAMP);
                }
            }

            $this->db->Commit();

            return true;

        } catch (Exception $e) {

            $this->db->Rollback();

            return false;
        }
    }

    public function update($id, $nav_name, $nav_path, $parent_id, $nav_icon, $group_id)
    {
        try {

            $this->db->beginTransaction();

            $this->db->query("UPDATE `navigation` SET nav_name = ? , nav_path = ? , parent_id = ? , nav_icon = ? WHERE id = ?", $nav_name, $nav_path, $parent_id, $nav_icon, $id);

            if (count($group_id) > 0) {
                $this->db->query("DELETE FROM nav_permission WHERE nav_id = ? ", $id);
                foreach ($group_id as $gid) {
                    $this->db->query("INSERT INTO nav_permission (nav_id, group_id, created) VALUES (?, ?, ?)", $id, $gid, TIMESTAMP);
                }
            } else {
                $this->db->query("DELETE FROM nav_permission WHERE nav_id = ? ", $id);
            }

            $this->db->Commit();

            return true;

        } catch (Exception $e) {

            $this->db->Rollback();

            return false;
        }

    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            $this->db->query("DELETE FROM navigation WHERE id = ?", $id);

            $this->db->query("DELETE FROM nav_permission WHERE nav_id = ?", $id);

            $this->db->commit();

            return true;

        } catch (Exception $e) {

            $this->db->Rollback();

            return false;
        }
    }


}