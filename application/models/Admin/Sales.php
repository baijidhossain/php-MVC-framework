<?php
class SalesModel
{


  public function __construct()
  {

    $this->db = new Database;
  }

  public function getSales($search, $params)
  {
    return $this->db->paginateQuery("SELECT s.*,(SELECT name FROM customers WHERE id = s.customer) AS customer FROM sales AS s $search ORDER BY s.id DESC", $params);
  }

  public function getSale($id)
  {

    return $this->db->Query("SELECT * FROM sales WHERE id = ?", [$id])->fetchArray();
  }

  public function getSalesProducts($id)
  {

    return $this->db->Query("SELECT * FROM sales_products   WHERE sales_id = ?", [$id])->fetchAll();
  }

  public function getPayments($salesID)
  {
    return $this->db->Query("SELECT p.*, (SELECT name FROM user WHERE id = p.created_by) AS user FROM payment AS p WHERE item_id = ? AND controller = ?", [$salesID, "sales"])->fetchAll();
  }

  public function getCustomer($customer_id)
  {

    return $this->db->Query("SELECT * FROM customers WHERE id = ?", [$customer_id])->fetchArray();
  }

  public function getCustomers()
  {

    return $this->db->Query("SELECT * FROM customers")->fetchAll();
  }

  public function getProducts()
  {
    return $this->db->Query("SELECT * FROM products")->fetchAll();
  }

  public function getProduct($id)
  {
    return $this->db->Query("SELECT p.*,(SELECT u.name FROM units AS u WHERE u.id = p.unit_id) AS unit FROM products AS p WHERE p.id = ?", [$id])->fetchArray();
  }

  public function existCustomer($customer)
  {
    return $this->db->Query("SELECT * FROM customers WHERE id = ?", [$customer])->numRows();
  }

  public function getTaxs()
  {
    return $this->db->Query("SELECT * FROM tax")->fetchAll();
  }


  public function UpdateSalesBillNo($bil_no, $sales_id)
  {

    return $this->db->Query("UPDATE sales SET bill_no =? WHERE id = ?", [$bil_no, $sales_id]);
  }

  public function addSales($salesInvoiceInfo, $payment)
  {
    $customer = $salesInvoiceInfo['customer'];
    $sales_date = $salesInvoiceInfo['sales_date'];
    $status = $salesInvoiceInfo['status'];
    $invoice_note = $salesInvoiceInfo['note'];

    $subtotal = $_SESSION['subtotal'];
    $other_charges = $_SESSION['other_charges'];
    $discount_on_all = $_SESSION['discount_on_all'];
    $discount_type = 'Fixed';
    $grandtotal = $_SESSION['grandtotal'];

    $payment_paid = $payment['payment_amount'] ?? 0;
    $payment_due = $_SESSION['grandtotal'] - $payment_paid;
    $payment_type = $payment['payment_type'] ?? 0;
    $payment_note = $payment['payment_note'] ?? 0;

    $field = "customer,status,subtotal,other_charges,discount_on_all,discount_type,grandtotal,paid,due,note,sales_date,created_by,created";

    try {

      $this->db->beginTransaction();

      $salesLastID =  $this->db->Query("INSERT INTO sales ($field) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)", [$customer, $status, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $payment_paid, $payment_due, $invoice_note, $sales_date, $_SESSION['userid'], TIMESTAMP])->lastInsertID();

      foreach ($_SESSION['product'] as $key => $product) {
        $this->AddSalesProducts($product, $salesLastID);
        $this->updateStockQty(-$product['qty'], $product['id']);
      }

      $this->updateCustomer($grandtotal, $payment_paid, $payment_due, $customer);

      if (!empty($payment['payment_amount'] >= 1)) {
        $this->addPayment($salesLastID, $payment['payment_amount'], $payment_type, $payment_note);
      }

      $this->db->commit();
      return $salesLastID;
    } catch (Exception $e) {
      $this->db->rollBack();
      return false;
    }
  }

  public function updateSales($salesInvoiceInfo, $payment, $sales_id)
  {

    $sales_id = $sales_id;
    $customer = $salesInvoiceInfo['customer'];
    $sales_date = $salesInvoiceInfo['sales_date'];
    $status = $salesInvoiceInfo['status'];
    $invoice_note = $salesInvoiceInfo['note'];

    $subtotal = $_SESSION['subtotal'];
    $other_charges = $_SESSION['other_charges'];
    $discount_on_all = $_SESSION['discount_on_all'];
    $discount_type = 'Fixed';
    $grandtotal = $_SESSION['grandtotal'];

    $payment_paid = $payment['payment_amount'] ?? 0;
    $payment_due = ($_SESSION['grandtotal'] - $payment_paid);
    $payment_type = $payment['payment_type'] ?? "";
    $payment_note = $payment['payment_note'] ?? "";

    try {

      $this->db->beginTransaction();

      $ediTableSale = $this->getSale($sales_id);

      $getPayments = $this->getPayments($sales_id);

      if (!empty($getPayments)) {
        $was_paid = array_sum(array_column($getPayments, 'payment_amount'));
        $payment_paid =  ($payment_paid + $was_paid);
        $payment_due  =  ($_SESSION['grandtotal'] - $payment_paid);
      }

      $this->updateCustomer(-$ediTableSale['grandtotal'], -$ediTableSale['paid'], -$ediTableSale['due'], $ediTableSale['customer']);
      $field = "customer = ?,sales_date= ?,status = ?,note = ? ,subtotal = ?,other_charges = ?,discount_on_all = ?,discount_type = ?,grandtotal = ?,paid= ?,due = ? WHERE id = ?";
      $salesUpdate =  $this->db->Query("UPDATE sales SET $field ", [$customer, $sales_date, $status, $invoice_note, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $payment_paid, $payment_due, $sales_id]);
      $getSalesProducts = $this->getSalesProducts($sales_id);

      // Add sales product in stock
      foreach ($getSalesProducts  as  $product) {
        $this->updateStockQty($product['qty'], $product['product_id']);
      }

      $this->deleteSalesProducts($sales_id);

      foreach ($_SESSION['product'] as  $product) {
        $this->AddSalesProducts($product, $sales_id);
        $this->updateStockQty(-$product['qty'], $product['id']);
      }

      $this->updateCustomer($grandtotal, $payment_paid, $payment_due, $customer);

      if ($payment['payment_amount'] >= 1) {
        $this->addPayment($sales_id, $payment['payment_amount'], $payment_type, $payment_note);
      }

      $this->db->commit();
      return $salesUpdate;
    } catch (Exception $e) {
      $this->db->rollBack();
      return false;
    }
  }


  public function updateSalePayment($paid, $due, $sale_id)
  {
    return $this->db->Query("UPDATE sales SET paid = paid  + ?, due = due + ? WHERE id = ?", [$paid, $due, $sale_id]);
  }

  public function AddSalesProducts($products, $salesID)
  {

    $field = "sales_id,product_id,product_name,qty,unit,purchase_price,discount,discount_type,discount_amount,tax,tax_type,tax_amount,unit_cost,total_amount,created";
    $fieldData = [$salesID, $products['id'], $products['name'], $products['qty'], $products['unit'], $products['purchase_price'], $products['discount'], $products['discount_type'], $products['discount_amount'], $products['tax'], $products['tax_type'], $products['tax_amount'], $products['unit_cost'], $products['total_amount'], TIMESTAMP];
    return $this->db->Query("INSERT INTO sales_products ($field) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $fieldData);
  }

  public function addPayment($sales_id, $payment_amount, $payment_type, $payment_note)
  {
    return $this->db->Query("INSERT INTO payment (item_id,controller,payment_amount,payment_type,payment_note,created_by,created) VALUES (?,?,?,?,?,?,?)", [$sales_id, 'sales', $payment_amount, $payment_type, $payment_note, $_SESSION['userid'], TIMESTAMP]);
  }

  public function checkStockQty($product_id)
  {
    return $this->db->Query("SELECT  current_stock FROM stock_report WHERE product_id =?", [$product_id])->fetchArray();
  }

  public function updateStockQty($current_stock, $product_id)
  {

    $stockField = "current_stock = current_stock + ?, total_sold = total_sold + ?, stock_value =  current_stock * (SELECT final_selling_price FROM products WHERE id = ?) WHERE product_id = ?";
    $stockData = [$current_stock, abs($current_stock), $product_id, $product_id];

    return $this->db->Query("UPDATE  stock_report SET $stockField", $stockData);
  }


  public function updateCustomer($payable, $paid, $due, $customer_id)
  {

    return $this->db->Query("UPDATE customers SET payable = payable + ? , paid = paid + ?, due = due + ? WHERE id = ?", [$payable, $paid, $due, $customer_id]);
  }

  public function deleteSalesProducts($sales_id)
  {

    return $this->db->Query("DELETE FROM `sales_products` WHERE sales_id = ?", [$sales_id]);
  }

  public function deletePayment($item_id)
  {
    return $this->db->Query("DELETE FROM payment WHERE item_id = ? AND controller = ? ", [$item_id, "sales"]);
  }

  public function deleteSale($sale_id)
  {
    return $this->db->Query("DELETE FROM sales WHERE id = ?  ", [$sale_id,]);
  }
}
