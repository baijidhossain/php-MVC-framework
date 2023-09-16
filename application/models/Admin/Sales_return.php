<?php

class Sales_returnModel
{



  public function __construct()
  {

    $this->db = new Database;
  }

  public function getSalesReturns($search, $params)
  {
    return $this->db->paginateQuery("SELECT s.*,(SELECT name FROM customers WHERE id = s.customer) AS customer FROM sales_return AS s $search ORDER BY s.id DESC", $params);
  }

  public function getProducts()
  {
    return $this->db->Query("SELECT * FROM products")->fetchAll();
  }

  public function getProduct($id)
  {
    return $this->db->Query("SELECT p.*,(SELECT u.name FROM units AS u WHERE u.id = p.unit_id) AS unit FROM products AS p WHERE p.id = ?", [$id])->fetchArray();
  }

  public function getCustomers()
  {

    return $this->db->Query("SELECT * FROM customers")->fetchAll();
  }

  public function getProductinfo($id)
  {

    return $this->db->Query("SELECT p.*,(SELECT u.name FROM units AS u WHERE u.id = p.unit_id) AS unit FROM products AS p WHERE p.id = ?", [$id])->fetchArray();
  }

  public function getTaxs()
  {

    return $this->db->Query("SELECT * FROM tax")->fetchAll();
  }

  public function addSaleReturn($customer, $status, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $note, $sales_date)
  {
    $field = "customer,status,subtotal,other_charges,discount_on_all,discount_type,grandtotal,paid,due,note,sales_date,created_by,created";
    return $this->db->Query("INSERT INTO sales_return ($field) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)", [$customer, $status, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $note, $sales_date, $_SESSION['userid'], TIMESTAMP])->lastInsertID();
  }

  public function purchaseReturnImageUpdate($img_name, $purchase_id)
  {
    return $this->db->Query("UPDATE purchase_return SET image = ? WHERE id = ?", [$img_name, $purchase_id]);
  }

  public function updateSalesReturn($customer, $purchase_date, $status, $note, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $purchase_id)
  {

    $field = "customer = ?,sales_date= ?,status = ?,note = ? ,subtotal = ?,other_charges = ?,discount_on_all = ?,discount_type = ?,grandtotal = ?,paid= ?,due = ? WHERE id = ?";

    return $this->db->Query("UPDATE sales_return SET $field ", [$customer, $purchase_date, $status, $note, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $purchase_id]);
  }

  public function addSaleReturnProducts($products, $saleid)
  {

    $field = "sales_id,product_id,product_name,qty,unit,purchase_price,discount,discount_type,discount_amount,tax,tax_type,tax_amount,unit_cost,total_amount,created";
    $fieldata = [$saleid, $products['id'], $products['name'], $products['qty'], $products['unit'], $products['purchase_price'], $products['discount'], $products['discount_type'], $products['discount_amount'], $products['tax'], $products['tax_type'], $products['tax_amount'], $products['unit_cost'], $products['total_amount'], TIMESTAMP];

    return $this->db->Query("INSERT INTO sales_return_products ($field) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $fieldata);
  }

  public function deleteSalesReturnProducts($sale_id)
  {
    return $this->db->Query("DELETE FROM `sales_return_products` WHERE sales_id = ?", [$sale_id]);
  }

  public function addPayment($purchaseid, $payment_amount, $payment_type, $payment_note)
  {
    return $this->db->Query("INSERT INTO payment (item_id,controller,payment_amount,payment_type,payment_note,created_by,created) VALUES (?,?,?,?,?,?,?)", [$purchaseid, 'sale_return', $payment_amount, $payment_type, $payment_note, $_SESSION['userid'], TIMESTAMP]);
  }

  public function getSalesReturn($id)
  {

    return $this->db->Query("SELECT * FROM sales_return WHERE id = ?", [$id])->fetchArray();
  }

  public function getSalesReturnProducts($id)
  {

    return $this->db->Query("SELECT * FROM sales_return_products  WHERE sales_id = ?", [$id])->fetchAll();
  }

  public function getCustomer($customer)
  {

    return $this->db->Query("SELECT * FROM customers WHERE id = ?", [$customer])->fetchArray();
  }
  public function exitsCustomer($customer)
  {
    return $this->db->Query("SELECT * FROM customers WHERE id = ?", [$customer])->numRows();
  }

  public function updateSupplire($payable, $paid, $due, $supplire_id)
  {

    return $this->db->Query("UPDATE suppliers SET return_payable = return_payable + ? , return_paid = return_paid + ?, return_due = return_due + ? WHERE id = ?", [$payable, $paid, $due, $supplire_id]);
  }

  public function updatePurchaseStatus($status, $purchase_id)
  {

    return $this->db->Query("UPDATE purchase SET return_status = ?  WHERE id = ?", [$status, $purchase_id]);
  }

  public function updateSalesReturnInvoice($paid, $due, $purchaseid)
  {

    return $this->db->Query("UPDATE sales_return SET paid = paid  + ?, due = due + ? WHERE id = ?", [$paid, $due, $purchaseid]);
  }

  public function UpdateSaleReturnBillNo($bil_no, $purchaseid)
  {

    return $this->db->Query("UPDATE sales_return SET bill_no =? WHERE id = ?", [$bil_no, $purchaseid]);
  }

  public function makePayment($paymentinfo)
  {

    return $this->db->Query("INSERT INTO payment(item_id,controller,payment_amount,payment_type,payment_note,created_by,created) VALUES (?,?,?,?,?,?,?)", [$paymentinfo['item_id'], $paymentinfo['controller'], $paymentinfo['payment_amount'], $paymentinfo['payment_type'], $paymentinfo['payment_note'], $_SESSION['userid'], TIMESTAMP]);
  }

  public function getPayments($purchaseid)
  {

    return $this->db->Query("SELECT p.*, (SELECT name FROM user WHERE id = p.created_by) AS user FROM payment AS p WHERE item_id = ? AND controller = ?", [$purchaseid, "sales_return"])->fetchAll();
  }

  public function stocks($id)
  {

    return $this->db->Query("SELECT id FROM stocks WHERE id = ?", [$id])->numRows();
  }

  public function stockProductUpdate($total_stock, $current_stock, $stock_value, $product_id)
  {

    $stockField = "total_stock = total_stock + ?,current_stock = current_stock + ?,stock_value =  stock_value + ? WHERE product_id = ?";
    $stockData = [$total_stock, $current_stock, $stock_value, $product_id];

    return $this->db->Query("UPDATE  stock_report SET $stockField", $stockData);
  }

  public function updateCustomer($payable, $paid, $due, $customer_id)
  {

    return $this->db->Query("UPDATE customers SET payable = payable + ? , paid = paid + ?, due = due + ? WHERE id = ?", [$payable, $paid, $due, $customer_id]);
  }

  public function getPayment($payment_id)
  {

    return $this->db->Query("SELECT * FROM payment WHERE id = ? AND  controller =?", [$payment_id, 'sales_return'])->fetchArray();
  }
  public function deletePayment($payment_id)
  {
    return $this->db->Query("DELETE FROM payment WHERE id = ? AND controller = ? ", [$payment_id, "sales_return"]);
  }

  public function deletePayments($item_id)
  {
    return $this->db->Query("DELETE FROM payment WHERE item_id = ? AND controller = ? ", [$item_id, "sales_return"]);
  }

  public function deleteSalesReturn($sales_return_id)
  {
    return $this->db->Query("DELETE FROM sales_return WHERE id = ?  ", [$sales_return_id,]);
  }
}
