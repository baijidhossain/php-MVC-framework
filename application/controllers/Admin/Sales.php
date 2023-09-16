<?php
class Sales extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Sales');
  }

  public function Index()
  {

    $search = "";
    $params = [];

    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " (s.bill_no LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }

    $sales = $this->model->getSales($search, $params);
    $data = [
      "sales" => $sales,
      "page_title" => "Sales list"
    ];

    $this->View('Sales/index', $data);
  }

  public  function Add()
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $this->addSales();

      Util::redirectBack();
    }

    $customers = $this->model->getCustomers();
    $products = $this->model->getProducts();
    $data['page_title'] = "Add New Sale";
    $data['customers'] = $customers;
    $data['products'] = $products;

    $this->view("Sales/add", $data);
  }

  public function views($id = "")
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $salesInfo = $this->model->getSale($id);

    $salesProducts = $this->model->getSalesProducts($id);

    $paymentinfo = $this->model->getPayments($id);


    if (empty($salesInfo)) {
      $this->setAlert("error", "Purchase info not available");
      Util::redirectBack();
    }

    if (empty($salesProducts)) {
      $this->setAlert("error", "Purchase product not available");
      Util::redirectBack();
    }

    $customerinfo = $this->model->getCustomer($salesInfo['customer']);


    if (empty($customerinfo)) {
      $this->setAlert("error", "Supplier info not available");
      Util::redirectBack();
    }

    $data = [

      'customer' => $customerinfo,
      'sales' =>  $salesInfo,
      'salesProducts' => $salesProducts,
      'payment' =>  $paymentinfo,
      "page_title" => "Sales Invoice Details"

    ];

    $this->View('Sales/views', $data);
  }

  public function printinvoice($id)
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $saleInvoice = $this->model->getSale($id);

    $saleProducts = $this->model->getSalesProducts($id);

    $paymentinfo = $this->model->getPayments($id);

    if (empty($saleInvoice)) {

      $this->setAlert("error", "Sale info not available");

      Util::redirectBack();
    }

    if (empty($saleProducts)) {

      $this->setAlert("error", "Sale product not available");

      Util::redirectBack();
    }

    $customer = $this->model->getCustomer($saleInvoice['customer']);


    if (empty($customer)) {

      $this->setAlert("error", "customer info not available");

      Util::redirectBack();
    }

    $data = [

      'customer' => $customer,
      'saleInvoice' =>  $saleInvoice,
      'saleProducts' => $saleProducts,
      'payment' =>  $paymentinfo,
      "page_title" => "Sale Invoice Details"

    ];


    $this->View('Sales/printinvoice', $data);
  }

  public function pdfSalesInvoice($sale_id)
  {


    $pdf = new FPDF();

    $pdf->addPage("P", "A4");
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetLineWidth(.1);
    $pdf->SetX(10);


    // $pdf->Line(0, 100, 210, 48);

    $pdf->Cell(60, 10, 'Product Name', 1, 0, 'L');
    $pdf->Cell(20, 10, 'Rate', 1, 0, 'L');
    $pdf->Cell(20, 10, 'QTY', 1, 0, 'L');
    $pdf->Cell(20, 10, 'Tax', 1, 0, 'L');
    $pdf->Cell(20, 10, 'Discount', 1, 0, 'L');
    $pdf->Cell(20, 10, 'Unit Cost', 1, 0, 'L');
    $pdf->Cell(30, 10, 'Total Amount', 1, 0, 'L');

    $pdf->Ln(10);

    $saleInfo = $this->model->getSale($sale_id);

    $salesProducts = $this->model->getSalesProducts($sale_id);


    $payments = $this->model->getPayments($sale_id);

    $pdf->SetFont('Arial', '', 11);

    $cellHeight = 8;

    foreach ($salesProducts as  $product) {

      $pdf->Cell(60, $cellHeight, $product['product_name'], 1, 0, 'L');
      $pdf->Cell(20, $cellHeight, $product['purchase_price'], 1, 0, 'L');
      $pdf->Cell(20, $cellHeight, $product['qty'], 1, 0, 'L');
      $pdf->Cell(20, $cellHeight, $product['tax'], 1, 0, 'L');
      $pdf->Cell(20, $cellHeight, $product['discount'], 1, 0, 'L');
      $pdf->Cell(20, $cellHeight, $product['unit_cost'], 1, 0, 'L');
      $pdf->Cell(30, $cellHeight, $product['total_amount'], 1, 1, 'L');
    }
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(160, 8, 'Subtotal : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $saleInfo['subtotal'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Other Charge : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $saleInfo['other_charges'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Discount On All : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $saleInfo['discount_on_all'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Grand Total : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $saleInfo['grandtotal'], 0, 1, 'L');

    // Payment list____
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 8, 'Payment Info: ', 0, 1, 'L');
    $pdf->Cell(40, 8, 'Date', 1, 0, 'L');
    $pdf->Cell(50, 8, 'Received by', 1, 0, 'L');
    $pdf->Cell(30, 8, 'Type', 1, 0, 'L');
    $pdf->Cell(40, 8, 'Note', 1, 0, 'L');
    $pdf->Cell(30, 8, 'Amount', 1, 1, 'L');

    $pdf->SetFont('Arial', '', 11);
    foreach ($payments  as  $payment) {
      $pdf->Cell(40, 8, date_create($payment['created'])->format('M d, Y'), 1, 0, 'L');
      $pdf->Cell(50, 8, $payment['user'], 1, 0, 'L');
      $pdf->Cell(30, 8, $payment['payment_type'] == 1 ? 'Cash' : 'Bank', 1, 0, 'L');
      $pdf->Cell(40, 8, $payment['payment_note'], 1, 0, 'L');
      $pdf->Cell(30, 8, $payment['payment_amount'], 1, 1, 'L');
    }
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(160, 8, 'Total : ', 0, 0, 'R');
    $pdf->Cell(30, 8, array_sum(array_column($payments, 'payment_amount')), 0, 1, 'L');

    $pdf->Output('I', 'ba', true);
  }

  public function pay_now($saleInvoice_id = "")
  {

    if (!$saleInvoice_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $saleInvoice = $this->model->getSale($saleInvoice_id);

    if (empty($saleInvoice)) {
      $this->setAlert("error", "Not available invoice");
      Util::redirectBack();
    }
    $customer = $this->model->getCustomer($saleInvoice['customer']);

    if (empty($customer)) {
      $this->setAlert("error", "Not available customer");
      Util::redirectBack();
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $validated = Util::checkPostValues(['date', 'paid_amount', 'payment_type']);

      if (!$validated) {

        $this->setAlert("error", "Fill all the required field");
        Util::redirectBack();
      }

      $paid_amount = $_POST['paid_amount'] ?? "";
      $payment_type = $_POST['payment_type'] ?? "";
      $date = trim($_POST['date'] ?? "");
      $payment_note = htmlspecialchars(trim($_POST['payment_note']));


      $due = $saleInvoice['due'];

      $paid = $saleInvoice['paid'];


      if (!Util::validateDate($date)) {

        $this->setAlert("error", "Invalid Date");

        Util::redirectBack();
      }


      if (!is_numeric($paid_amount) || $paid_amount < 1) {

        $this->setAlert("error", "Please Enter Valid Amount");

        Util::redirectBack();
      }

      if (!in_array($payment_type, ['Cash', 'Bank'])) {
        $this->setAlert("error", "Payment type invalid");

        Util::redirectBack();
      }

      if ($paid_amount > $due) {

        $this->setAlert("error", "Entered Amount Not be Greater Than Due Amount");

        Util::redirectBack();
      }

      // Purchase invoice update____
      $due =  -$paid_amount;

      $paid =  $paid_amount;

      $updateSalePayment = $this->model->updateSalePayment($paid, $due, $saleInvoice_id);
      // End Purchase Invoice Update_____


      // customer update____
      $payable =  0;
      $customer_paid =  $paid_amount;
      $customer_due =  -$paid_amount;
      // End customer update____

      $updateCustomer = $this->model->updateCustomer($payable, $customer_paid, $customer_due, $customer['id']);

      if ($updateCustomer && $updateSalePayment) {
        $make_payment = $this->model->addPayment($saleInvoice_id, $paid_amount, $payment_type, $payment_note);
      }
      if ($make_payment) {
        $this->setAlert("success", "Payment Recorded Successfully");
      } else {
        $this->setAlert("success", "Something went wrong");
      }


      Util::redirectBack();
    }


    $data = [

      'saleInvoice' => $saleInvoice,
      'customer' => $customer,
      'action' => 'pay_now',
      "modal_title" => "Add Payment"

    ];

    $this->View('Sales/modal', $data);
  }


  public function delete_sale($sale_id = "")
  {

    if (!$sale_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    // Start purchase info update
    $purchaseinvoice = $this->model->getSale($sale_id);

    if (empty($purchaseinvoice)) {
      $this->setAlert("error", "Sale invoice  not available");
      Util::redirectBack();
    }

    // Start supplier info update
    $supplier = $this->model->getCustomer($purchaseinvoice['customer']);

    if (empty($supplier)) {
      $this->setAlert("error", "Customer info not available");
      Util::redirectBack();
    }

    $supplier_payable = 0;
    $supplier_due = 0;
    $supplier_paid = 0;

    $supplier_payable = -$purchaseinvoice['grandtotal'];
    $supplier_due =   -$purchaseinvoice['due'];
    $supplier_paid =  -$purchaseinvoice['paid'];

    $this->model->updateCustomer($supplier_payable, $supplier_paid, $supplier_due, $supplier['id']);

    // End supplier info update

    $this->model->deletePayment($sale_id);
    $this->model->deleteSalesProducts($sale_id);

    $deletePurchase = $this->model->deleteSale($sale_id);

    if ($deletePurchase) {
      $this->setAlert("success", "Sale invoice successfully deleted");
    } else {
      $this->setAlert("error", "Something went wrong");
    }
    Util::redirectBack();
  }

  public function payment_view($saleID = "")
  {
    if (!$saleID) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $sale = $this->model->getSale($saleID);
    if (empty($sale)) {
      $this->setAlert("error", "Not available sale invoice");
      Util::redirectBack();
    }

    $payments = $this->model->getPayments($saleID);

    $customer = $this->model->getCustomer($sale['customer']);

    if (empty($sale)) {
      $this->setAlert("error", "Not Available Purchase Invoice");
      Util::redirectBack();
    }

    if (empty($customer)) {
      $this->setAlert("error", "Not available customer");
      Util::redirectBack();
    }


    $data = [
      'saleInvoice' => $sale,
      'customer' => $customer,
      'payments' => $payments,
      'action' => 'payment_view',
      "modal_title" => "Payment Info"

    ];

    $this->View('Sales/modal', $data);
  }

  public function sales_edit($sales_id = "")
  {

    if (!$sales_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    unset($_SESSION["product"]);

    $_SESSION["editSalesID"] = $sales_id;

    $getSales = $this->model->getSale($sales_id);

    $salesProducts = $this->model->getSalesProducts($sales_id);

    if (empty($salesProducts)) {
      $this->setAlert("error", "Sales products info not available");
      Util::redirectBack();
    }

    foreach ($salesProducts as  $product) {

      $dynamicProduct['id'] = $product['product_id'];
      $dynamicProduct['name'] = $product['product_name'];

      $dynamicProduct['qty'] = $product['qty'];
      $dynamicProduct['unit'] = $product['unit'];
      $dynamicProduct['purchase_price'] = $product['purchase_price'];

      $dynamicProduct['discount'] = $product['discount'];
      $dynamicProduct['discount_type'] = $product['discount_type'];
      $dynamicProduct['discount_amount'] = $product['discount_amount'];

      $dynamicProduct['tax'] = $product['tax'];
      $dynamicProduct['tax_type'] = $product['tax_type'];
      $dynamicProduct['tax_amount'] = $product['tax_amount'];

      $dynamicProduct['unit_cost'] = $product['unit_cost'];
      $dynamicProduct['total_amount'] = $product['total_amount'];


      $_SESSION["product"][$product['product_id']] = $dynamicProduct;
    }

    $_SESSION["discount_on_all"] = $getSales['discount_on_all'];
    $_SESSION["other_charges"] = $getSales['other_charges'];
    $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
    $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);


    Util::redirect("/admin/sales/edit/$sales_id");
  }

  public function Edit($id = "")
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    if ($_SESSION["editSalesID"] != $id) {
      $this->setAlert("error", "Wrong route");

      Util::redirectBack("/admin/sales/");
    }

    $getSale = $this->model->getSale($id);
    $Customers = $this->model->getCustomers();
    $products = $this->model->getProducts();
    $payments = $this->model->getPayments($id);



    if (empty($getSale)) {
      $this->setAlert("error", "Purchase info not available");
      Util::redirectBack();
    }

    $data = [

      'customers' => $Customers,
      'products' => $products,
      'sale' =>  $getSale,
      'payments' =>  $payments,
      "page_title" => "Sales Invoice Edit"

    ];

    $this->View('Sales/edit', $data);

    return;
  }

  public function Update($sales_id)
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (!$sales_id) {
        $this->setAlert("error", "Invalid  id");
        Util::redirectBack();
      }

      $this->updateSales($sales_id);

      Util::redirect("/admin/sales/");
    }
  }

  // Invoice make____
  public function Invoice($method = "", $id = "")
  {
    if (empty($method)) {
      $this->setAlert("error", "Invalid Parameter");
      Util::redirectBack();
    }

    if ($method == "add") {

      $productID = htmlspecialchars(trim(!empty($_POST['product']) ? $_POST['product'] : ""));

      $dynamicProduct = [];


      $productInfo = $this->model->getProduct($productID);


      // Invoice return 
      if (isset($_SESSION["invoiceProduct"]) && !empty($_SESSION["invoiceProduct"])) {

        $invoiceProduct = array_column($_SESSION["invoiceProduct"], 'id');

        if (!in_array($productInfo['id'], $invoiceProduct)) {
          $this->setAlert("error", "Sorry! this item does not exist in this purchase entry");
          return;
        }
      }
      // Invoice return 


      if (empty($productInfo)) {
        $this->setAlert("error", "This product was not found");
        return;
      }

      if (!empty($_SESSION["product"])) {
        if (in_array($productID, array_column($_SESSION["product"], "id"))) {
          $this->setAlert("error", "This product has already been added to the card");
          return;
        }
      }


      $dynamicProduct['id'] = $productInfo['id'];
      $dynamicProduct['name'] = $productInfo['name'];
      $dynamicProduct['qty'] = 1;
      $dynamicProduct['unit'] = $productInfo['unit'];
      $dynamicProduct['purchase_price'] = $productInfo['price'];

      $dynamicProduct['discount'] = 0;
      $dynamicProduct['discount_type'] = $productInfo['discount_type'];
      $dynamicProduct['discount_amount'] = 0;

      $dynamicProduct['tax'] = $productInfo['tax'];
      $dynamicProduct['tax_type'] = $productInfo['tax_type'];
      $dynamicProduct['tax_amount'] = $productInfo['tax_amount'];

      $dynamicProduct['unit_cost'] = $productInfo['buying_price'];
      $dynamicProduct['total_amount'] = $productInfo['buying_price'];

      $dynamicProduct['final_selling_price'] = $productInfo['final_seling_price'];

      $_SESSION["product"][$productInfo['id']] = [];

      $_SESSION["product"][$productInfo['id']] = $dynamicProduct;

      // Calculation____
      $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
      $_SESSION['other_charges'] = 0;
      $_SESSION['discount_on_all'] = 0;
      $_SESSION["grandtotal"] =  array_sum(array_column($_SESSION["product"], 'total_amount')) + $_SESSION["other_charges"] - $_SESSION["discount_on_all"];

      $this->setAlert("success", "New item successfully added");

      return;
    }

    if ($method == "edit") {

      $data = [
        'taxs' => $this->model->getTaxs(),
        'id' => $id,
        'item' => $_SESSION['product'][$id],
        "modal_title" => "Edit Item",
        "action" => "itemUpdate"
      ];
      $this->View('Sales/modal', $data);
    }

    if ($method == "itemUpdate") {

      $id = $_POST['id'];
      $purchase_price = htmlspecialchars(trim($_POST['purchase_price'] ?? 0));
      $qty = htmlspecialchars(trim(empty($_POST['qty']) ? 1 : $_POST['qty']));
      $tax = htmlspecialchars(trim(empty($_POST['tax']) ? 0 : $_POST['tax']));
      $tax_type = htmlspecialchars(trim($_POST['tax_type'] ?? ''));
      $discount = htmlspecialchars(trim(empty($_POST['discount'])  ? 0 : $_POST['discount']));
      $discount_type = htmlspecialchars(trim($_POST['discount_type'] ?? ''));


      if (!is_numeric($purchase_price) || $purchase_price < 1) {
        $this->setAlert("error", "Invalid purchase amount");
        Util::redirectBack();
      }


      if (!is_numeric($qty) || $qty < 1) {
        $this->setAlert("error", "Invalid Qty");
        Util::redirectBack();
      }

      if (!in_array($tax_type, ['exclusive', 'inclusive'])) {
        $this->setAlert("error", "Invalid tax type");
        Util::redirectBack();
      }

      if (!in_array($discount_type, ['percent', 'fixed'])) {
        $this->setAlert("error", "Invalid discount type");
        Util::redirectBack();
      }

      if (!is_numeric($tax) || $tax < 0) {
        $this->setAlert("error", "Invalid tax amount");
        Util::redirectBack();
      }

      if (!is_numeric($discount) || $discount < 0) {
        $this->setAlert("error", "Invalid discount amount");
        Util::redirectBack();
      }
      // End valid data checking__________

      $total_purchase_price =  ($purchase_price * $qty);

      $total_amount = $total_purchase_price;

      $unit_cost = $purchase_price;

      $tax_amount = 0;

      $discount_amount = $discount;

      if (!empty($tax) && $tax != 0 && $tax_type ==   'exclusive') {

        if ($tax > 100) {

          $this->setAlert("error", "Tax amount must be less than 100 or equal");
          Util::redirectBack();
        }

        $total_amount +=  ($total_purchase_price  * $tax / 100);

        $unit_cost += ($purchase_price  * $tax / 100);

        $tax_amount += ($total_purchase_price  * $tax / 100);
      }

      if (!empty($discount) && $discount_type == 'percent') {

        if ($discount > 100) {

          $this->setAlert("error", "Discount amount must be less than 100 or equal");
          Util::redirectBack();
        }


        $discount_amount = ($total_purchase_price  * $discount / 100);

        $total_amount  -= $discount_amount;

        $unit_cost -= ($purchase_price  * $discount / 100);
      }


      if (!empty($discount) && $discount_type == 'fixed') {
        if ($discount > $total_amount) {
          $this->setAlert("error", "Invalid discount amount");
          Util::redirectBack();
          return false;
        }

        $total_amount  -=  $discount;
        $unit_cost -= $discount;
      }

      $_SESSION['product'][$id]['purchase_price'] = $purchase_price;
      $_SESSION['product'][$id]['qty'] = $qty;

      $_SESSION['product'][$id]['tax'] = $tax;
      $_SESSION['product'][$id]['tax_type'] = $tax_type;
      $_SESSION['product'][$id]['tax_amount'] = $tax_amount;

      $_SESSION['product'][$id]['discount'] = $discount;
      $_SESSION['product'][$id]['discount_type'] = $discount_type;
      $_SESSION['product'][$id]['discount_amount'] = $discount_amount;

      $_SESSION['product'][$id]['unit_cost'] = $unit_cost;

      $_SESSION['product'][$id]['total_amount'] = $total_amount;

      // Calculations_____
      $_SESSION["discount_on_all"] = 0;
      $_SESSION["other_charges"] = 0;
      $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
      $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);

      $this->setAlert("success", "Item successfully updated");
      Util::redirectBack();
    }

    if ($method == "invoiceUpdate") {

      if (count($_SESSION['product']) == 0) {
        $this->setAlert("error", "First of all product select then update");
        return;
      }

      $other_charges = htmlspecialchars(trim(empty($_POST['other_charges'])  ? 0 : $_POST['other_charges']));
      $discount_on_all = htmlspecialchars(trim(empty($_POST['discount_on_all'])  ? 0 : $_POST['discount_on_all']));

      if (!is_numeric($other_charges) || $other_charges < 0) {
        $this->setAlert("error", "Invalid other charges amount");
        $_SESSION["other_charges"] = 0;

        $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
        $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);
        return;
      }

      if (!is_numeric($discount_on_all) || $discount_on_all < 0) {
        $this->setAlert("error", "Invalid discount amount");
        $_SESSION["discount_on_all"] = 0;

        $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
        $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);
        return;
      }

      $grandtotal =  array_sum(array_column($_SESSION["product"], 'total_amount')) + $other_charges;

      if ($discount_on_all  >=  $grandtotal) {

        $this->setAlert("error", "Discount all amount must be less than grand total amount ");
        $_SESSION["discount_on_all"] = 0;

        $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
        $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);

        return;
      }

      $_SESSION["discount_on_all"] = $discount_on_all;
      $_SESSION["other_charges"] = $other_charges;
      $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
      $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);

      $this->setAlert("success", "Invoice successfully updated");

      return;
    }

    if ($method == "itemRemove") {

      if (count($_SESSION['product']) > 0 && count($_SESSION['product']) < 2) {

        unset($_SESSION['product']);

        unset($_SESSION["subtotal"]);

        unset($_SESSION["other_charges"]);

        unset($_SESSION["discount_on_all"]);

        unset($_SESSION["grandtotal"]);

        $this->setAlert("success", "All item deleted");
        Util::redirectBack();
      }

      unset($_SESSION['product'][$id]);

      $_SESSION["discount_on_all"] = 0;
      $_SESSION["other_charges"] = 0;
      $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
      $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);


      $this->setAlert("success", "Invoice Item Successfully removed");

      Util::redirectBack();
    }

    return;
  }
  // End invoice make____

  // Extra function_______

  private function validateProducts()
  {

    $validated = Util::checkPostValues(['customer', 'sales_date', 'status']);

    if (!$validated) {
      $this->setAlert("error", "Fill all the required field!");
      return false;
    }

    $customer = $_POST['customer'] ?? "";
    $sales_date = htmlspecialchars(trim($_POST['sales_date']));
    $status = htmlspecialchars(trim($_POST['status']));
    $note = htmlspecialchars(trim($_POST['note'] ?? ""));

    $payment_amount = $_POST['payment_amount'] ?? "";
    $payment_type = htmlspecialchars(trim($_POST['payment_type'] ?? ""));
    $payment_note = htmlspecialchars(trim($_POST['payment_note'] ?? ""));

    $exitsSupplier = $this->model->existCustomer($customer);

    if ($exitsSupplier < 1) {
      $this->setAlert("error", "Invalid supplier");
      return false;
    }


    if (!Util::validateDate($sales_date)) {
      $this->setAlert("error", "Invalid date");
      return false;
    }

    if (!in_array($status, ['Final', 'Quotation'])) {
      $this->setAlert("error", "Invalid Status");
      return false;
    }

    // Make payment________

    if (!empty($payment_amount)) {


      if (!is_numeric($payment_amount)) {
        $this->setAlert("error", "Invalid payment amount");
        return false;
      }

      if ($payment_amount < 1) {
        $this->setAlert("error", "The payment amount must be greater than or equal to 1");
        return false;
      }

      if ($payment_amount > $_SESSION['grandtotal']) {
        $this->setAlert("error", "The payment amount must be less than grand total amount or equal");
        return false;
      }

      if (!in_array($payment_type, ['Cash', 'Bank'])) {
        $this->setAlert("error", "Invalid payment type");
        return false;
      }

      if (strlen($payment_note) > 250) {
        $this->setAlert("error", "Payment note must be less than or equal to 250 characters");
        return false;
      }
    }

    return [

      'customer' => $customer,
      'sales_date' => $sales_date,
      'status' => $status,
      'note' => $note,
      'payment_amount' => $payment_amount,
      'payment_type' => $payment_type,
      'payment_note' => $payment_note
    ];
  }

  private function addSales()
  {

    $validateSalesProduct = $this->validateProducts();

    if (!$validateSalesProduct) {
      Util::redirectBack();
    }

    if (empty($_SESSION['product'])) {
      $this->setAlert("error", "First select  product then enter the save button");
      Util::redirectBack();
    }

    $salesInvoiceInfo['customer'] = $validateSalesProduct['customer'];
    $salesInvoiceInfo['sales_date'] = $validateSalesProduct['sales_date'];
    $salesInvoiceInfo['status'] = $validateSalesProduct['status'];
    $salesInvoiceInfo['note'] = $validateSalesProduct['note'];

    $payment['payment_amount'] = 0;

    if (!empty($validateSalesProduct['payment_amount'])) {

      $payment['payment_amount'] = $validateSalesProduct['payment_amount'];
      $payment['payment_type'] = $validateSalesProduct['payment_type'];
      $payment['payment_note'] = $validateSalesProduct['payment_note'];
    }


    // echo '<pre>';
    // print_r($_SESSION['product']);
    // echo '</pre>';
    // die;


    foreach ($_SESSION['product'] as  $product) {

      $stockQty =  $this->model->checkStockQty($product['id']);

      $qty = $stockQty['current_stock'];
      $name = $product['name'];

      if ($stockQty['current_stock']  < $product['qty']) {
        $this->setAlert("error", "You have only ( $qty - $name ) items in stock");
        return false;
      }
    }

    $salesLastId = $this->model->addSales($salesInvoiceInfo, $payment);

    $bil_no = rand(100000, 999999) . $salesLastId;

    if ($salesLastId) {

      $this->model->UpdateSalesBillNo($bil_no,   $salesLastId);

      $this->setAlert("success", "Sales invoice successfully created");
    } else {

      $this->setAlert("error", "Something went wrong");
    }

    unset($_SESSION['product']);
    unset($_SESSION["subtotal"]);
    unset($_SESSION['other_charges']);
    unset($_SESSION['grandtotal']);
    unset($_SESSION['discount_on_all']);

    return true;
  }

  private function updateSales($sales_id)
  {

    $validateSalesProduct = $this->validateProducts();

    if (!$validateSalesProduct) {
      Util::redirectBack();
    }

    if (empty($_SESSION['product'])) {
      $this->setAlert("error", "First select  product then enter the save button");
      Util::redirectBack();
    }

    $salesInvoiceInfo['customer'] = $validateSalesProduct['customer'];
    $salesInvoiceInfo['sales_date'] = $validateSalesProduct['sales_date'];
    $salesInvoiceInfo['status'] = $validateSalesProduct['status'];
    $salesInvoiceInfo['note'] = $validateSalesProduct['note'];

    $payment['payment_amount'] = 0;
    if (!empty($validateSalesProduct['payment_amount'])) {
      $payment['payment_amount'] = $validateSalesProduct['payment_amount'];
      $payment['payment_type'] = $validateSalesProduct['payment_type'];
      $payment['payment_note'] = $validateSalesProduct['payment_note'];
    }

    $salesUpdated = $this->model->updateSales($salesInvoiceInfo, $payment, $sales_id);

    if ($salesUpdated) {

      $this->setAlert("success", "Sales invoice successfully updated");
    } else {

      $this->setAlert("error", "Something went wrong");
    }

    unset($_SESSION['product']);
    unset($_SESSION["subtotal"]);
    unset($_SESSION['other_charges']);
    unset($_SESSION['grandtotal']);
    unset($_SESSION['discount_on_all']);

    return true;
  }
}
