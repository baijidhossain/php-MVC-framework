<?php

class CategoryModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getCategories($search, $params)
  {
    return $this->db->paginateQuery("SELECT c.id,c.name,(SELECT COUNT(pcr.category_id) AS total_asset FROM product_category_relation AS pcr WHERE pcr.category_id = c.id) AS total_asset FROM category AS c $search", $params);
  }

  public function add($name)
  {
    return $this->db->query("INSERT INTO category (name,created) VALUES (?,?)", [$name, TIMESTAMP]);
  }

  public function geteCategory($id)
  {
    return $this->db->query("SELECT * FROM category WHERE id=?", [$id])->fetchArray();
  }

  public function update($data)
  {
    return $this->db->query(" UPDATE category SET name=? WHERE id = ?", [$data['name'], $data['id']]);
  }
  public function delete($id)
  {
    return $this->db->query("DELETE FROM category WHERE id =?", [$id]);
  }
}
