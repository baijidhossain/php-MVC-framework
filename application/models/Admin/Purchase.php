<?php

class PurchaseModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getPurchases($search, $params)
  {
    return $this->db->paginateQuery("SELECT p.*,(SELECT name FROM suppliers WHERE id = p.supplier) AS supplier FROM purchase AS p $search ORDER BY p.id DESC", $params);
  }

  public function getProducts()
  {
    return $this->db->Query("SELECT * FROM products")->fetchAll();
  }

  public function getSuppliers()
  {
    return $this->db->paginateQuery("SELECT * FROM suppliers");
  }

  public function getProduct($id)
  {
    return $this->db->Query("SELECT p.*,(SELECT u.name FROM units AS u WHERE u.id = p.unit_id) AS unit FROM products AS p WHERE p.id = ?", [$id])->fetchArray();
  }
  public function getTaxs()
  {

    return $this->db->Query("SELECT * FROM tax")->fetchAll();
  }
  public function addPurchase($supplier, $status, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $note, $purchase_date)
  {
    $field = "supplier,status,subtotal,other_charges,discount_on_all,discount_type,grandtotal,paid,due,note,purchase_date,created_by,created";
    return $this->db->Query("INSERT INTO purchase ($field) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)", [$supplier, $status, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $note, $purchase_date, $_SESSION['userid'], TIMESTAMP])->lastInsertID();
  }

  public function purchaseImageUpdate($img_name, $purchase_id)
  {
    return $this->db->Query("UPDATE purchase SET image = ? WHERE id = ?", [$img_name, $purchase_id]);
  }

  public function updatePurchase($supplier, $purchase_date, $status, $note, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $purchase_id)
  {

    $field = "supplier = ?,purchase_date= ?,status = ?,note = ? ,subtotal = ?,other_charges = ?,discount_on_all = ?,discount_type = ?,grandtotal = ?,paid= ?,due = ? WHERE id = ?";

    return $this->db->Query("UPDATE purchase SET $field ", [$supplier, $purchase_date, $status, $note, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $purchase_id]);
  }

  public function AddPurchaseProducts($products, $purchaseid)
  {

    $field = "purchase_id,product_id,product_name,qty,unit,purchase_price,discount,discount_type,discount_amount,tax,tax_type,tax_amount,unit_cost,total_amount,created";
    $fieldata = [$purchaseid, $products['id'], $products['name'], $products['qty'], $products['unit'], $products['purchase_price'], $products['discount'], $products['discount_type'], $products['discount_amount'], $products['tax'], $products['tax_type'], $products['tax_amount'], $products['unit_cost'], $products['total_amount'], TIMESTAMP];

    return $this->db->Query("INSERT INTO purchase_products ($field) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $fieldata);
  }

  public function deletePurchaseProducts($purchase_id)
  {

    return $this->db->Query("DELETE FROM `purchase_products` WHERE purchase_id = ?", [$purchase_id]);
  }

  public function addPayment($purchaseid, $payment_amount, $payment_type, $payment_note)
  {
    return $this->db->Query("INSERT INTO payment (item_id,controller,payment_amount,payment_type,payment_note,created_by,created) VALUES (?,?,?,?,?,?,?)", [$purchaseid, 'purchase', $payment_amount, $payment_type, $payment_note, $_SESSION['userid'], TIMESTAMP]);
  }

  public function getPurchase($id)
  {

    return $this->db->Query("SELECT * FROM purchase WHERE id = ?", [$id])->fetchArray();
  }

  public function getPurchaseProducts($id)
  {

    return $this->db->Query("SELECT * FROM purchase_products  WHERE purchase_id = ?", [$id])->fetchAll();
  }

  public function getSupplier($supplier)
  {

    return $this->db->Query("SELECT * FROM suppliers WHERE id = ?", [$supplier])->fetchArray();
  }
  public function exitsSupplier($supplier)
  {
    return $this->db->Query("SELECT * FROM suppliers WHERE id = ?", [$supplier])->numRows();
  }

  public function updateSupplire($payable, $paid, $due, $supplire_id)
  {

    return $this->db->Query("UPDATE suppliers SET payable = payable + ? , paid = paid + ?, due = due + ? WHERE id = ?", [$payable, $paid, $due, $supplire_id]);
  }

  public function updatePurchaseInvoice($paid, $due, $purchaseid)
  {

    return $this->db->Query("UPDATE purchase SET paid = paid  + ?, due = due + ? WHERE id = ?", [$paid, $due, $purchaseid]);
  }

  public function UpdatePurchaseBillNo($bil_no, $purchaseid)
  {

    return $this->db->Query("UPDATE purchase SET bill_no =? WHERE id = ?", [$bil_no, $purchaseid]);
  }

  public function makePayment($paymentinfo)
  {

    return $this->db->Query("INSERT INTO payment(item_id,controller,payment_amount,payment_type,payment_note,created_by,created) VALUES (?,?,?,?,?,?,?)", [$paymentinfo['item_id'], $paymentinfo['controller'], $paymentinfo['payment_amount'], $paymentinfo['payment_type'], $paymentinfo['payment_note'], $_SESSION['userid'], TIMESTAMP]);
  }

  public function getPayments($purchaseid)
  {

    return $this->db->Query("SELECT p.*, (SELECT name FROM user WHERE id = p.created_by) AS user FROM payment AS p WHERE item_id = ? AND controller = ?", [$purchaseid, "purchase"])->fetchAll();
  }

  public function stocks($id)
  {

    return $this->db->Query("SELECT id FROM stocks WHERE id = ?", [$id])->numRows();
  }

  public function stockProductUpdate($total_stock, $current_stock, $product_id)
  {

    $stockField = "total_stock = total_stock + ?,current_stock = current_stock + ?,stock_value = current_stock * (SELECT final_selling_price FROM products WHERE id = ?) WHERE product_id = ?";
    $stockData = [$total_stock, $current_stock, $product_id, $product_id];

    return $this->db->Query("UPDATE  stock_report SET $stockField", $stockData);
  }

  public function getPayment($payment_id)
  {

    return $this->db->Query("SELECT * FROM payment WHERE id = ? AND  controller =?", [$payment_id, 'purchase'])->fetchArray();
  }
  public function deletePayment($item_id)
  {
    return $this->db->Query("DELETE FROM payment WHERE item_id = ? AND controller = ? ", [$item_id, "purchase"]);
  }

  public function deletePurchase($purchase_id)
  {
    return $this->db->Query("DELETE FROM purchase WHERE id = ?  ", [$purchase_id,]);
  }
}
