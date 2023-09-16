<?php

class UnitModel
{



  public function __construct()
  {

    $this->db = new Database;
  }
  public function getUnits($search, $params)
  {
    return $this->db->paginateQuery("SELECT * FROM units $search", $params);
  }
  public function add(array $data)
  {
    return $this->db->query("INSERT INTO units (name,created) VALUES (?,?)", [$data['name'], TIMESTAMP]);
  }

  public function getUnit($unit_id){
    return $this->db->Query("SELECT * FROM units WHERE id =?",[$unit_id])->fetchArray();

  }

  public function update(array $data)
  {
    return $this->db->query("UPDATE units SET name = ? WHERE id = ?", [$data['name'], $data['id']]);
  }

  public function delete($unit_id){

    return  $this->db->Query("DELETE FROM units WHERE id = ?",[$unit_id]);
  }
}    
