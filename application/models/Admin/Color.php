<?php

class ColorModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getallcolor($search, $params)
  {
    return $this->db->paginateQuery("SELECT * FROM color $search", $params);
  }

  public function add(array $data)
  {
    return $this->db->query("INSERT INTO color (name,created) VALUES (?,?)", [$data['name'], TIMESTAMP]);
  }
  public function geteditColor($id)
  {

    return $this->db->query("SELECT * FROM color WHERE id = ?", [$id])->fetchArray();
  }
  public function update(array $data)
  {
    return $this->db->query("UPDATE color SET name = ? WHERE id = ?", [$data['name'], $data['id']]);
  }
  public function delete($id = 0)
  {

    $this->db->query("DELETE FROM product_color_relation WHERE color_id =? ", [$id]);

    return $this->db->query("DELETE FROM color WHERE id = ?", [$id]);
  }
}
