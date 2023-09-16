<?php

class CityModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getCountries()
  {

    return  $this->db->Query("SELECT * FROM country ")->fetchAll();
  }

  public function getStates($country_id)
  {

    return $this->db->Query("SELECT * FROM `state` WHERE country_id = ?", [$country_id])->fetchAll();
  }

  public function getCities($search, $params)
  {
    return $this->db->paginateQuery("SELECT c.*, s.name AS state,cou.name AS country FROM city AS c LEFT JOIN state AS s ON s.id = c.state_id LEFT JOIN country AS cou ON cou.id = s.country_id $search", $params);
  }

  public function add($state_id, $state)
  {
    return $this->db->Query("INSERT INTO city (`state_id` ,`name`,created) VALUES (?,?,?)", [$state_id, $state, TIMESTAMP]);
  }

  public function getCity($id)
  {

    return $this->db->Query("SELECT * FROM city WHERE id = ?", [$id])->fetchArray();
  }

  public function update($state_id, $city, $id)
  {

    return $this->db->Query("UPDATE city SET state_id = ?, `name` = ? WHERE id = ?", [$state_id, $city, $id]);
  }

  public function delete($id)
  {
    return $this->db->Query("DELETE FROM city WHERE id = ?", [$id]);
  }
}
