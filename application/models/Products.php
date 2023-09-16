<?php
class ProductsModel
{
  public function __construct()
  {

    $this->db = new Database();
  }

  public function getAllproduct($search, $params)
  {


    return $this->db->paginateQuery("SELECT p.id,p.name,p.price,p.image,p.qty,p.description,
(SELECT c.name FROM category AS c WHERE c.id=pcr.category_id) AS category_name,
(SELECT company.name FROM company  WHERE company.id=pcor.company_id) AS company_name,
(SELECT COUNT(pcor.product_id) FROM product_color_relation AS pcor WHERE pcor.product_id=p.id ) AS quantity

FROM `products` AS p
LEFT JOIN product_category_relation AS pcr ON p.id=pcr.product_id
LEFT JOIN product_company_relation AS pcor ON p.id =pcor.product_id $search ORDER BY p.id", $params);
  }


  public function getColorReletion()
  {
    return $this->db->query("SELECT c.id,c.name,p_color_r.product_id,p_color_r.color_id FROM `color` AS c JOIN product_color_relation AS p_color_r ON c.id=p_color_r.color_id")->fetchAll();
  }


  public function getAllcategory()
  {
    return $this->db->query("SELECT * FROM category")->fetchAll();
  }

  public function getAllcolor()
  {
    return $this->db->query("SELECT * FROM color")->fetchAll();
  }

  public function getAllcompany()
  {
    return $this->db->query("SELECT * FROM company")->fetchAll();
  }

  public function getUnits()
  {
    return $this->db->query("SELECT * FROM units")->fetchAll();
  }

  public function getproductcolorreletion($id)
  {
    return $this->db->query("SELECT * FROM `color` As c JOIN product_color_relation AS pcr ON c.id =pcr.color_id WHERE pcr.product_id=?", [$id])->fetchAll();
  }

  //----------------------------------------------------------------------------------------//

  //----Start add product----//

  public function addproduct($data, $image, $color_name, $sub_images)
  {
    $product_name = $data['product_name'];
    $price = $data['price'];
    $category_name = $data['category_name'];
    $company_name = $data['company_name'];
    $qty = $data['qty'];
    $description = $data['description'];

    if (!empty($image)) {
      $product = $this->db->query("INSERT INTO products (name,price,image,qty,description,created) VALUES (?,?,?,?,?,?)", [$product_name, $price, $image, $qty, $description, TIMESTAMP]);
    } else {
      $product = $this->db->query("INSERT INTO products (name,price,qty,description,created) VALUES (?,?,?,?,?)", [$product_name, $price, $qty, $description, TIMESTAMP]);
    }
    $lastid = $product->lastInsertID();

    if ($lastid) {
      $category = $this->db->query("INSERT INTO product_category_relation (product_id,category_id) VALUES (?,?)", [$lastid, $category_name]);
      $copmany = $this->db->query("INSERT INTO product_company_relation (product_id,company_id) VALUES (?,?)", [$lastid, $company_name]);


      foreach ($color_name as $colors_name) {
        $color = $this->db->query("INSERT INTO product_color_relation (product_name,product_id,color_id) VALUES (?,?,?)", [$product_name, $lastid, $colors_name]);
      }

      if (!empty($sub_images)) {
        foreach ($sub_images as $sub_image) {

          $this->db->query("INSERT INTO sub_image (product_id,name,created) VALUES (?,?,?)", [$lastid, $sub_image, TIMESTAMP]);
        }
      }
    }
    return $product;
  }

  //----End Add product----//

  //----------------------------------------------------------------------------------------//

  //----Start view product----//
  public function views($id)
  {

    $pinfo['product'] = $this->db->query("SELECT p.name,p.image,p.qty,p.description,(SELECT c.name FROM company AS c WHERE id = prcr.company_id) AS company FROM products AS p
    LEFT JOIN product_company_relation AS prcr ON p.id=prcr.product_id WHERE p.id=?", [$id])->fetchArray();
    $pinfo['color'] = $this->db->query("SELECT c.name FROM color AS c JOIN product_color_relation AS pcor ON c.id=pcor.color_id WHERE pcor.product_id=?", [$id])->fetchAll();
    $pinfo['sub_image'] = $this->db->query("SELECT * FROM sub_image WHERE product_id =?", [$id])->fetchAll();

    return $pinfo;
  }

  // ----Start view product----//

  //----------------------------------------------------------------------------------------//

  //----Start view product----//

  public function edit($id)
  {
    $editdata = $this->db->query("SELECT p.id,p.name,p.price,p.image,p.qty,p.description,
  (SELECT c.name FROM category AS c WHERE c.id=pcr.category_id) AS category,
  (SELECT company.name FROM company  WHERE company.id=pcor.company_id) AS company,
  (SELECT COUNT(pcor.product_id) FROM product_color_relation AS pcor WHERE pcor.product_id=p.id ) AS quantity

    FROM `products` AS p
    LEFT JOIN product_category_relation AS pcr ON p.id=pcr.product_id
    LEFT JOIN product_company_relation AS pcor ON p.id =pcor.product_id WHERE p.id=? ORDER BY p.id  ", [$id])->fetchArray();

    return $editdata;
  }

  //----Start view product----//

  //----------------------------------------------------------------------------------------//

  //---- Start Product update----//

  public function update($data, $image, $color_name, $sub_images)
  {

    $id = $data['id'];
    $product_name = $data['product_name'];
    $price = $data['price'];
    $category_name = $data['category_name'];
    $company_name = $data['company_name'];

    // $color_name = $data['color_name'];
    $qty = $data['qty'];
    $description = $data['description'];


    if (!empty($image)) {
      $product = $this->db->query("UPDATE  products SET name=?,price=?,image=?,qty=?,description=? WHERE id =?", [$product_name, $price, $image, $qty, $description, $id]);
    } else {
      $product = $this->db->query("UPDATE  products SET name=?,price=?,qty=?,description=? WHERE id =?", [$product_name, $price, $qty, $description, $id]);
    }

    if ($product) {
      $category = $this->db->query("UPDATE product_category_relation SET product_id=?,category_id=? WHERE product_id =? ", [$id, $category_name, $id]);
      $copmany = $this->db->query("UPDATE product_company_relation SET product_id=?,company_id=? WHERE product_id=?", [$id, $company_name, $id]);


      if (!empty($color_name)) {

        $this->db->query("DELETE FROM product_color_relation WHERE product_id=?", [$id]);
        foreach ($color_name as $colors_name) {
          $color = $this->db->query("INSERT INTO  product_color_relation (product_name,product_id,color_id) VALUES (?,?,?)", [$product_name, $id, $colors_name]);
        }
      }


      if (!empty($sub_images)) {
        $this->db->query("DELETE FROM sub_image WHERE product_id=?", [$id]);
        foreach ($sub_images as $sub_image) {

          $this->db->query("INSERT INTO  sub_image ( product_id,name) VALUES (?,?)", [$id, $sub_image]);
        }
      }
    }

    return  $product;
  }
  //----Start view product----//

  //----------------------------------------------------------------------------------------//

  //----End  Product update----//



  //----Start Delete product----//

  public function delete($id)
  {
    $product =  $this->db->query("DELETE FROM products WHERE id =?", [$id]);

    if ($product) {
      $this->db->query("DELETE FROM product_category_relation WHERE product_id =? ", [$id]);
      $this->db->query("DELETE FROM product_company_relation WHERE product_id =? ", [$id]);
      $this->db->query("DELETE FROM product_color_relation WHERE product_id =? ", [$id]);
      $this->db->query("DELETE FROM sub_image WHERE product_id=?", [$id]);
    }
    return $product;
  }

  //----End Delete product----//




}
