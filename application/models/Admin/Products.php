<?php
class ProductsModel
{
  public function __construct()
  {

    $this->db = new Database();
  }

  public function getProducts($search, $params)
  {

    return $this->db->paginateQuery("SELECT p.id,p.name,p.barcode,p.minimum_qty,p.expire,p.price,p.tax,p.tax_type,p.buying_price,p.selling_price,p.discount,p.discount_type,p.final_selling_price,p.image,
    (SELECT name FROM company WHERE id=p.brand_id) AS  brand,
    (SELECT name FROM category WHERE id=p.category_id) AS category,
    (SELECT name FROM color WHERE id= p.color_id) AS color,
    (SELECT name FROM units WHERE id = p.unit_id) AS unit,
    (SELECT product_code FROM stock_report WHERE product_id = p.id) AS product_code
    FROM `products` AS p $search ORDER BY id DESC", $params);
  }

  public function getProduct($product_id)
  {
    return $this->db->Query("SELECT p.*,sr.adjustment_note,sr.new_opening_stock FROM products AS p 
    JOIN stock_report AS sr ON sr.product_id = p.id
     WHERE p.id =?", [$product_id])->fetchArray();
  }

  public function getColorReletion()
  {
    return $this->db->query("SELECT c.id,c.name,p_color_r.product_id,p_color_r.color_id FROM `color` AS c JOIN product_color_relation AS p_color_r ON c.id=p_color_r.color_id")->fetchAll();
  }


  public function getCategories()
  {
    return $this->db->query("SELECT * FROM category")->fetchAll();
  }

  public function getColors()
  {
    return $this->db->query("SELECT * FROM color")->fetchAll();
  }

  public function getBrands()
  {
    return $this->db->query("SELECT * FROM company")->fetchAll();
  }

  public function getUnits()
  {

    return $this->db->query("SELECT * FROM units")->fetchAll();
  }

  public function getTaxs()
  {

    return $this->db->Query("SELECT * FROM tax")->fetchAll();
  }

  public function getproductcolorreletion($id)
  {
    return $this->db->query("SELECT * FROM `color` As c JOIN product_color_relation AS pcr ON c.id =pcr.color_id WHERE pcr.product_id=?", [$id])->fetchAll();
  }

  //----------------------------------------------------------------------------------------//

  //----Start add product----//

  public function add($data)
  {

    $data = [$data['product_name'], $data['brand_id'], $data['category_id'], $data['color_id'], $data['minimum_qty'], $data['unit_id'], $data['barcode'], $data['expire'], $data['price'], $data['tax'], $data['tax_type'], $data['tax_amount'], $data['buying_price'], $data['selling_price'], $data['discount'], $data['discount_type'], $data['discount_amount'], $data['final_selling_price'], $data['description'], $_SESSION['userid'], TIMESTAMP];

    $fieldname = "name,brand_id,category_id,color_id,minimum_qty,unit_id,barcode,expire,price,tax,tax_type,tax_amount,buying_price,selling_price,discount,discount_type,discount_amount,final_selling_price,description,created_by,created";
    return $this->db->Query("INSERT INTO products ($fieldname) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $data)->lastInsertID();
  }

  public function productImageUpdate($image_name, $product_id)
  {

    return $this->db->Query("UPDATE products SET image = ? WHERE id = ?", [$image_name, $product_id]);
  }

  public function stockProductsAdd($data)
  {
    $stockField = "product_id,product_code,new_opening_stock,total_stock,adjustment_note,current_stock,stock_value,created";
    $stockData = [$data['product_id'], $data['product_code'], $data['new_opening_stock'], $data['total_stock'], $data['adjustment_note'], $data['current_stock'], $data['stock_value'], TIMESTAMP];

    return $this->db->Query("INSERT INTO stock_report ($stockField) VALUES (?,?,?,?,?,?,?,?)", $stockData)->lastInsertID();
  }

  public function currentOpeningStockAdd($product_id, $stock, $note)
  {
    return $this->db->Query("INSERT INTO current_opening_stock (product_id,stock,adjustment_note,created)VALUES(?,?,?,?)", [$product_id, $stock, $note, TIMESTAMP]);
  }

  public function getCurrentOpeningStocks($product_id)
  {
    return $this->db->Query("SELECT * FROM current_opening_stock WHERE product_id = ? ", [$product_id])->fetchAll();
  }

  public function getOpeningStockIdWise($OpeningStockId)
  {

    return $this->db->Query("SELECT * FROM current_opening_stock WHERE id = ?", [$OpeningStockId])->fetchArray();
  }

  public function getStockReport($product_id)
  {
    return $this->db->Query("SELECT sr.*,p.final_seling_price FROM stock_report AS sr
    JOIN products AS p ON p.id = sr.product_id
     WHERE sr.product_id = ?", [$product_id])->fetchArray();
  }
  public function update($data, $product_id)
  {

    $data = [$data['product_name'], $data['brand_id'], $data['category_id'], $data['color_id'], $data['minimum_qty'], $data['unit_id'], $data['barcode'], $data['expire'], $data['price'], $data['tax'], $data['tax_type'], $data['tax_amount'], $data['buying_price'], $data['selling_price'], $data['discount'], $data['discount_type'], $data['discount_amount'], $data['final_selling_price'], $data['description'], $product_id];

    $fieldname = "name =?,brand_id =?,category_id =?,color_id =?,minimum_qty =?,unit_id =?,barcode =?,expire =?,price  =?,tax  =?,tax_type  =?,tax_amount =?,buying_price  =?,selling_price  =?,discount  =?,discount_type  =?,discount_amount  =?,final_selling_price =?,description =? WHERE id =?";
    return $this->db->Query("UPDATE products SET  $fieldname ", $data);
  }

  public function stockProductUpdate($data, $product_id)
  {

    $stockField = "new_opening_stock = new_opening_stock + ?,adjustment_note = ?,total_stock = total_stock + ?,current_stock = current_stock + ?,stock_value =  ? WHERE product_id = ?";
    $stockData = [$data['new_opening_stock'], $data['adjustment_note'], $data['total_stock'], $data['current_stock'], $data['stock_value'], $product_id];

    return $this->db->Query("UPDATE  stock_report SET $stockField", $stockData);
  }

  public function currentStockUpdate($new_opening_stock, $total_stock, $current_stock, $stock_id)
  {
  }

  //----End Add product----//

  //----------------------------------------------------------------------------------------//

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

  public function openingstockdelete($openingstockId)
  {
    try {

      $this->db->beginTransaction();

      $openingstockdelete =  $this->db->Query("DELETE FROM current_opening_stock WHERE id = ?", [$openingstockId]);


      $this->db->commit();

      return $openingstockdelete;
    } catch (Exception $e) {

      $this->db->Rollback();

      return false;
    }
  }

  //----End Delete product----//




}
