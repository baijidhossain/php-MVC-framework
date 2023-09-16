<?php

class Contact_groupsModel
{

  public function __construct()
  {

    $this->db = new Database();
  }

  public function view($search, $params)
  {

    if (!empty($params)) {
      $group = $this->db->paginateQuery("SELECT g.name,g.id,(SELECT COUNT( cgr.contact_id ) FROM `contact_group_relation` AS cgr WHERE cgr.group_id = g.id) as total_contact FROM `contact_group` as g $search", $params);
    } else {
      $group = $this->db->paginateQuery("SELECT g.name,g.id,(SELECT COUNT( cgr.contact_id ) FROM `contact_group_relation` AS cgr WHERE cgr.group_id = g.id) as total_contact FROM `contact_group` as g ");
    }
    return $group;
  }

  public function add($insert)
  {
    $created = TIMESTAMP;
    $name    = $insert['name'];
    return $this->db->query("INSERT INTO contact_group (name,created) VALUES (?,?)", [$name, $created])->affectedRows();
  }

  public function edit($id)
  {
    return $this->db->query("SELECT * FROM contact_group WHERE id = ?", [$id])->fetchAll();
  }
  public function update($data)
  {
    $id   = $data['id'];
    $name = $data['name'];

    return (bool) $this->db->query("UPDATE contact_group SET name =?  WHERE id = ?", [$name, $id]);
  }
  public function dgroup($id)
  {
    return $this->db->query("DELETE  FROM contact_group WHERE id =?", [$id])->affectedRows();
  }
}
