<?php

class ContactsModel
{

  public function __construct()
  {

    $this->db = new Database();
  }

  //--get data for show--//
  public function getContacts($search, $params)
  {
    if (!empty($search)) {
      return $this->db->paginateQuery(" SELECT * FROM contacts AS cg $search", $params);
    }
    return $this->db->paginateQuery(" SELECT * FROM contacts AS cg ");
  }
  public function getGroups()
  {
    return $this->db->query(" SELECT * FROM contact_group ")
      ->fetchAll();
  }

  public function getGroupsRelation()
  {
    return $this->db->query("SELECT cg.name,cgr.contact_id FROM contact_group AS cg JOIN contact_group_relation AS cgr ON cg.id=cgr.group_id ")
      ->fetchAll();
  }
  //--End get data for show--//

  //--Get data for edit--//
  public function getEditRelation($id)
  {
    return $this->db->query(" SELECT * FROM contact_group  AS cg JOIN contact_group_relation AS cgr ON cg.id=cgr.group_id WHERE cgr.contact_id=?", [$id])
      ->fetchAll();
  }
  public function getEditContactData($id)
  {
    return $this->db->query(" SELECT * FROM contacts WHERE id = ? ", [$id])
      ->fetchArray();
  }
  //--End Get data for edit--//

  //--delete all table--//
  public function deleteContacts($id)
  {
    return $this->db->query("DELETE FROM contacts WHERE id= ? ", [$id])
      ->affectedRows();
  }
  public function deleteContactRelation($id)
  {
    return $this->db->query("DELETE FROM contact_group_relation WHERE contact_id= ? ", [$id])
      ->affectedRows();
  }

  //--delete all table--//

  //--Add contact and groups--//
  public function add(array $addcontact, array $addtgroup)
  {

    $insertcontact  = $this->db->query(" INSERT INTO  contacts (name,email,phone,address,created) VALUES (?,?,?,?,?)", $addcontact);

    $lastid = $insertcontact->lastInsertID();

    if ($lastid) {

      foreach ($addtgroup['groups'] as $group) {
        $this->db->query("INSERT INTO contact_group_relation (contact_id,group_id,created) VALUES (?,?,?)", [$lastid, $group, TIMESTAMP]);
      }
    }


    return $insertcontact;
  }
  //--End Add contact and groups--//

  //--update--//
  public function update(array $editContact, array $groups, $img_name)
  {
    $contact_id = $editContact['id'];
    $name       = $editContact['name'];
    $email      = $editContact['email'];
    $phone      = $editContact['phone'];

    if (!empty($img_name)) {

      $update = $this->db->query("UPDATE contacts SET name=?,email=?,phone=?,photo=? WHERE id = ? ", [$name, $email, $phone, $img_name, $contact_id]);
    } else {

      $update = $this->db->query("UPDATE contacts SET name=?,email=?,phone=? WHERE id = ? ", [$name, $email, $phone, $contact_id]);
    }

    $deletegroup = $this->deleteContactRelation($contact_id);

    if (!empty($groups)) {
      foreach ($groups as $group) {
        $this->db->query("INSERT INTO contact_group_relation (contact_id,group_id,created) VALUES (?,?,?)", [$contact_id, $group, TIMESTAMP]);
      }
    }
    return $update;
  }
  //--end update--//

}
