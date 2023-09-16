<?php

class StateModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getCountries()
  {

    return  $this->db->Query("SELECT * FROM country ")->fetchAll();
  }

  public function getStates($search, $params)
  {

    return $this->db->paginateQuery("SELECT s.*,c.name AS country FROM `state` AS s LEFT JOIN country AS c ON c.id = s.country_id $search ORDER BY s.country_id", $params);
  }

  public function getState($id)
  {

    return $this->db->Query("SELECT * FROM `state` WHERE id =? ", [$id])->fetchArray();
  }

  public function add($country_id, $state)
  {
    return $this->db->Query("INSERT INTO `state` (`country_id`, `name`,created) VALUES (?,?,?)", [$country_id, $state, TIMESTAMP]);
  }

  public function update($country_id, $state, $id)
  {

    return $this->db->Query("UPDATE `state` SET country_id = ?, `name` = ? WHERE id = ?", [$country_id, $state, $id]);
  }

  public function delete($id)
  {
    return $this->db->Query("DELETE FROM `state` WHERE id =? ", [$id]);
  }
}
