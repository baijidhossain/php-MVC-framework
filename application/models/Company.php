<?php

class CompanyModel
{

  private $db;

  public function __construct()
  {

    $this->db = new Database;
  }


  public function getAllcompany($search, $params)
  {
    return $this->db->paginateQuery("SELECT * FROM company $search", $params);
  }


  public function add($data)
  {
    return $this->db->query("INSERT INTO company (name,address,created) VALUES (?,?,?)", [$data['name'], $data['address'], TIMESTAMP]);
  }

  public function geteditcompany($id = "")
  {
    return $this->db->query("SELECT * FROM company WHERE id=?", [$id])->fetchArray();
  }

  public function update(array $data)
  {
    return $this->db->query("UPDATE company SET name=?,address=? WHERE id=?", [$data['name'], $data['address'], $data['id']]);
  }
  public function delete($id = "")
  {

    $deletecolor = $this->db->query("DELETE FROM company WHERE id = ?", [$id]);

    if ($deletecolor) {
      $this->db->query("DELETE FROM product_company_relation WHERE company_id =? ", [$id]);
    }
    return $deletecolor;
  }
}
