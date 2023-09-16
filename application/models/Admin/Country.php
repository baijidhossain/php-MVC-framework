<?php

class CountryModel
{


  public function __construct()
  {

    $this->db = new Database;
  }


  public function getCountries()
  {

    return  $this->db->paginateQuery("SELECT * FROM country ");
  }

  public function getCountry($id)
  {

    return $this->db->Query("SELECT * FROM country WHERE id =?", [$id])->fetchArray();
  }

  public function add($name)
  {

    return $this->db->Query("INSERT INTO country (name,created)VALUES(?,?)", [$name, TIMESTAMP]);
  }
  public function update($country, $id)
  {

    return $this->db->Query("UPDATE country SET name = ? WHERE id = ?", [$country, $id]);
  }

  public function delete($id)
  {

    return $this->db->Query("DELETE FROM country WHERE id = ?", [$id]);
  }
}
