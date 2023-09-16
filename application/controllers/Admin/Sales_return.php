<?php


class Sales_return extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Sales_return');
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

    $SalesReturns = $this->model->getSalesReturns($search, $params);


    $data = [
      "PurchaseReturns" => $SalesReturns,
      "page_title" => "Sales Return list"
    ];

    $this->View('Sales_return/index', $data);
  }


  public function Add()
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $this->addSalesReturn();

      Util::redirectBack();
    }


    // show invoice add page
    $customers = $this->model->getCustomers();
    $products = $this->model->getProducts();

    $data = [

      'customers' => $customers,
      'products' => $products,
      "page_title" => "Sales Return"

    ];

    $this->View('Sales_return/add', $data);
  }

  public function views($id = "")
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $salesReturnInfo = $this->model->getSalesReturn($id);

    $salesProducts = $this->model->getSalesReturnProducts($id);

    $paymentinfo = $this->model->getPayments($id);


    if (empty($purchaseinfo)) {
      $this->setAlert("error", "Sales info not available");
      Util::redirectBack();
    }

    if (empty($salesProducts)) {
      $this->setAlert("error", "Sales prodact not available");
      Util::redirectBack();
    }

    $getcustomerinfo = $this->model->getCustomer($purchaseinfo['customer']);


    if (empty($getcustomerinfo)) {
      $this->setAlert("error", "Supplire info not available");
      Util::redirectBack();
    }

    $data = [

      'customer' => $getcustomerinfo,
      'salesReturn' =>  $salesReturnInfo,
      'salesReturnProducts' => $salesProducts,
      'payment' =>  $paymentinfo,
      "page_title" => "Sales Return Invoice Details"

    ];


    $this->View('Sales_return/views', $data);
  }

  public function Return_edit($return_id = "")
  {

    if (!$return_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    unset($_SESSION["product"]);

    $_SESSION["editReturnID"] = $return_id;

    $salesReturn = $this->model->getSalesReturn($return_id);
    $salesProducts = $this->model->getSalesReturnProducts($return_id);

    if (empty($salesProducts)) {
      $this->setAlert("error", "Purchase products info not available");
      Util::redirectBack();
    }


    foreach ($salesProducts as  $salesReturnProduct) {

      $dynamicProduct['id'] = $salesReturnProduct['product_id'];
      $dynamicProduct['name'] = $salesReturnProduct['product_name'];

      $dynamicProduct['qty'] = $salesReturnProduct['qty'];
      $dynamicProduct['unit'] = $salesReturnProduct['unit'];
      $dynamicProduct['purchase_price'] = $salesReturnProduct['purchase_price'];

      $dynamicProduct['discount'] = $salesReturnProduct['discount'];
      $dynamicProduct['discount_type'] = $salesReturnProduct['discount_type'];
      $dynamicProduct['discount_amount'] = $salesReturnProduct['discount_amount'];

      $dynamicProduct['tax'] = $salesReturnProduct['tax'];
      $dynamicProduct['tax_type'] = $salesReturnProduct['tax_type'];
      $dynamicProduct['tax_amount'] = $salesReturnProduct['tax_amount'];

      $dynamicProduct['unit_cost'] = $salesReturnProduct['unit_cost'];
      $dynamicProduct['total_amount'] = $salesReturnProduct['total_amount'];

      // $_SESSION["product"][$salesReturnProduct['product_id']] = [];

      $_SESSION["product"][$salesReturnProduct['product_id']] = $dynamicProduct;
    }

    $_SESSION["discount_on_all"] = $salesReturn['discount_on_all'];
    $_SESSION["other_charges"] = $salesReturn['other_charges'];
    $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
    $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);


    Util::redirect("/admin/sales_return/edit/$return_id");
  }

  public function Edit($id = "")
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      unset($_SESSION["product"]);
      unset($_SESSION["editReturnID"]);
      Util::redirect("/admin/purchase_return/");
    }

    if ($_SESSION["editReturnID"] != $id) {
      unset($_SESSION["product"]);
      unset($_SESSION["editReturnID"]);
      Util::redirect("/admin/purchase_return/edit/" . $_SESSION["editReturnID"]);
    }

    $salesReturn = $this->model->getSalesReturn($id);

    $getCustomers = $this->model->getCustomers();
    $products = $this->model->getProducts();
    $paymentinfo = $this->model->getPayments($id);




    if (empty($salesReturn)) {
      $this->setAlert("error", "Purchase info not available");
      Util::redirectBack();
    }

    $data = [

      'customers' => $getCustomers,
      'products' => $products,
      'salesReturn' =>  $salesReturn,
      'paymentinfo' =>  $paymentinfo,
      "page_title" => "Sales Return Invoice Edit"

    ];

    $this->View('Sales_return/edit', $data);

    return;
  }

  public function Update($sales_id)
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (!$sales_id) {
        $this->setAlert("error", "Invalid old customer id");
        Util::redirectBack();
      }

      $this->updateSalesReturn($sales_id);

      Util::redirect("/admin/sales_return/");
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

      $productID = $_POST['product'] ?? "";

      $dynamicProduct = [];

      $productInfo = $this->model->getProduct($productID);

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
      $this->View('Purchase_return/modal', $data);
    }

    if ($method == "itemUpdate") {

      $id = $_POST['id'];
      $purchase_price = htmlspecialchars(trim($_POST['purchase_price'] ?? 0));
      $qty = trim(empty($_POST['qty']) ? 1 : $_POST['qty']);
      $tax = trim(empty($_POST['tax']) ? 0 : $_POST['tax']);
      $tax_type = htmlspecialchars(trim($_POST['tax_type'] ?? ''));
      $discount = trim(empty($_POST['discount'])  ? 0 : $_POST['discount']);
      $discount_type = trim($_POST['discount_type'] ?? '');


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

  public function paynow($id = "")
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $salesReturnInvoice = $this->model->getSalesReturn($id);
    $customer = $this->model->getCustomer($salesReturnInvoice['customer']);

    if (empty($salesReturnInvoice)) {
      $this->setAlert("error", "Not available invoice");
      Util::redirectBack();
    }

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

      $paid_amount = trim($_POST['paid_amount'] ?? "");
      $payment_type = trim($_POST['payment_type'] ?? "");
      $date = trim($_POST['date'] ?? "");
      $payment_note = htmlspecialchars(trim($_POST['payment_note']));


      $due = $salesReturnInvoice['due'];
      $paid = $salesReturnInvoice['paid'];


      if (!Util::validateDate($date)) {

        $this->setAlert("error", "Invalid Date");
        Util::redirectBack();
      }

      if (!is_numeric($paid_amount)) {
        $this->setAlert("error", "Please Enter Valid Amount");
        Util::redirectBack();
      }

      if ($paid_amount < 1) {
        $this->setAlert("error", "Please Enter Valid Amount");
        Util::redirectBack();
      }

      $paid_amount = abs($paid_amount);

      if ($paid_amount > $due) {
        $this->setAlert("error", "Entered Amount Not be Greater Than Due Amount");
        Util::redirectBack();
      }

      // Purchase invoice update____
      $due =  -$paid_amount;
      $paid =  $paid_amount;
      $updatePurchaseInvoice = $this->model->updateSalesReturnInvoice($paid, $due, $salesReturnInvoice['id']);
      // End Purchase Invoice Update_____


      // Supplire update____
      $payable =  0;
      $customer_paid =  $paid_amount;
      $customer_due =  -$paid_amount;
      // End Supplire update____

      $updateSupplire = $this->model->updateCustomer($payable, $customer_paid, $customer_due, $customer['id']);

      // Make Payment_____
      $paymentinfo = [
        'item_id' => $salesReturnInvoice['id'],
        'payment_amount' => $paid_amount,
        'payment_note' => $payment_note,
        'payment_type' => $payment_type,
        'controller' => "sales_return"

      ];

      if ($updateSupplire && $updatePurchaseInvoice) {
        $make_payment = $this->model->makePayment($paymentinfo);
      }
      if ($make_payment) {
        $this->setAlert("success", "Payment Recorded Successfully");
      } else {
        $this->setAlert("success", "Something went wrong");
      }


      Util::redirectBack();
    }


    $data = [

      'salesreturnInvoice' => $salesReturnInvoice,
      'customer' => $customer,
      'action' => 'paynow',
      "modal_title" => "Add Payment"

    ];

    $this->View('Sales_return/modal', $data);
  }

  public function payment_view($id = "")
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $salesReturnInvoice = $this->model->getSalesReturn($id);
    if (empty($salesReturnInvoice)) {
      $this->setAlert("error", "Not available Purchase");
      Util::redirectBack();
    }

    $paymentinfo = $this->model->getPayments($id);

    $customer = $this->model->getCustomer($salesReturnInvoice['customer']);

    if (empty($salesReturnInvoice)) {
      $this->setAlert("error", "Not Available Purchase Return Invoice");
      Util::redirectBack();
    }

    if (empty($customer)) {
      $this->setAlert("error", "Not Available customer");
      Util::redirectBack();
    }



    $data = [
      'salesreturninvoice' => $salesReturnInvoice,
      'customer' => $customer,
      'paymentinfo' => $paymentinfo,
      'action' => 'payment_view',
      "modal_title" => "Payment Info"

    ];

    $this->View('Sales_return/modal', $data);
  }

  public function delete_payment($payment_id = "")
  {

    if (!$payment_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    // payment info
    $paymentinfo = $this->model->getPayment($payment_id);

    if (empty($paymentinfo)) {
      $this->setAlert("error", "Payment info not available");
      Util::redirectBack();
    }

    // Start purchase info update
    $salesInvoice = $this->model->getSalesReturn($paymentinfo['item_id']);

    if (empty($salesInvoice)) {
      $this->setAlert("error", "Sales invoice  not available");
      Util::redirectBack();
    }

    $due = 0;
    $paid = 0;

    $due =    $paymentinfo['payment_amount'];
    $paid =   -$paymentinfo['payment_amount'];

    $this->model->updateSalesReturnInvoice($paid, $due, $salesInvoice['id']);

    // End purchase info update

    // Start customer info update
    $customer = $this->model->getCustomer($salesInvoice['customer']);

    if (empty($customer)) {

      $this->setAlert("error", "customer info not available");

      Util::redirectBack();
    }

    $customer_due = 0;
    $customer_paid = 0;

    $customer_payable = 0;
    $customer_due =   $paymentinfo['payment_amount'];
    $customer_paid =  -$paymentinfo['payment_amount'];

    $this->model->updateCustomer($customer_payable, $customer_paid, $customer_due, $customer['id']);

    // End customer info update

    $deletePayment = $this->model->deletePayment($payment_id);

    if ($deletePayment) {

      $this->setAlert("success", "Return payment successfully deleted");
    } else {

      $this->setAlert("error", "Something went wrong");
    }
    Util::redirectBack();
  }

  public function delete_sales($sales_id = "")
  {

    if (!$sales_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    // Start purchase info update
    $SalesReturnInvoice = $this->model->getSalesReturn($sales_id);

    if (empty($SalesReturnInvoice)) {
      $this->setAlert("error", "purchase invoice  not available");
      Util::redirectBack();
    }

    // Start customer info update
    $customer = $this->model->getCustomer($SalesReturnInvoice['customer']);

    if (empty($customer)) {
      $this->setAlert("error", "Customer info not available");
      Util::redirectBack();
    }

    $customer_payable = 0;
    $customer_due = 0;
    $customer_paid = 0;

    $customer_payable = -$SalesReturnInvoice['grandtotal'];
    $customer_due =   -$SalesReturnInvoice['due'];
    $customer_paid =  -$SalesReturnInvoice['paid'];

    $this->model->updateCustomer($customer_payable, $customer_paid, $customer_due, $customer['id']);

    // End supplire info update

    $this->model->deletePayments($sales_id);
    $this->model->deleteSalesReturnProducts($sales_id);

    $deleteSales = $this->model->deleteSalesReturn($sales_id);

    if ($deleteSales) {
      $this->setAlert("success", "Return invoice successfully deleted");
    } else {
      $this->setAlert("error", "Something went wrong");
    }
    Util::redirectBack();
  }

  public function pdfSalesReturnInvoice($sales_id)
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

    $salesReturnInfo = $this->model->getSalesReturn($sales_id);

    $salesProducts = $this->model->getSalesReturnProducts($sales_id);


    $paymentList = $this->model->getPayments($sales_id);

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
    $pdf->Cell(30, 8, $salesReturnInfo['subtotal'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Other Charge : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $salesReturnInfo['other_charges'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Discount On All : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $salesReturnInfo['discount_on_all'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Grand Total : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $salesReturnInfo['grandtotal'], 0, 1, 'L');

    // Payment list____
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 8, 'Payment Info: ', 0, 1, 'L');
    $pdf->Cell(40, 8, 'Date', 1, 0, 'L');
    $pdf->Cell(50, 8, 'Received by', 1, 0, 'L');
    $pdf->Cell(30, 8, 'Type', 1, 0, 'L');
    $pdf->Cell(40, 8, 'Note', 1, 0, 'L');
    $pdf->Cell(30, 8, 'Amount', 1, 1, 'L');

    $pdf->SetFont('Arial', '', 11);
    foreach ($paymentList  as  $payment) {
      $pdf->Cell(40, 8, date_create($payment['created'])->format('M d, Y'), 1, 0, 'L');
      $pdf->Cell(50, 8, $payment['user'], 1, 0, 'L');
      $pdf->Cell(30, 8, $payment['payment_type'] == 1 ? 'Cash' : 'Bank', 1, 0, 'L');
      $pdf->Cell(40, 8, $payment['payment_note'], 1, 0, 'L');
      $pdf->Cell(30, 8, $payment['payment_amount'], 1, 1, 'L');
    }
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(160, 8, 'Total : ', 0, 0, 'R');
    $pdf->Cell(30, 8, array_sum(array_column($paymentList, 'payment_amount')), 0, 1, 'L');

    $pdf->Output('I', 'ba', true);
  }

  public function printinvoice($id)
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $SalesReturnInfo = $this->model->getSalesReturn($id);

    $salesProducts = $this->model->getSalesReturnProducts($id);

    $paymentinfo = $this->model->getPayments($id);

    if (empty($SalesReturnInfo)) {

      $this->setAlert("error", "Purchase info not available");

      Util::redirectBack();
    }

    if (empty($salesProducts)) {

      $this->setAlert("error", "Purchase product not available");

      Util::redirectBack();
    }

    $getCustomerinfo = $this->model->getCustomer($SalesReturnInfo['customer']);


    if (empty($getCustomerinfo)) {

      $this->setAlert("error", "Customer info not available");

      Util::redirectBack();
    }

    $data = [

      'customer' => $getCustomerinfo,
      'purchase' =>  $SalesReturnInfo,
      'salesProducts' => $salesProducts,
      'payment' =>  $paymentinfo,
      "page_title" => "Purchase Return Invoice "

    ];


    $this->View('Sales_return/printinvoice', $data);
  }

  // Extra function_______

  private function validateProducts()
  {


    $validated = Util::checkPostValues(['customer', 'sale_date', 'status']);

    if (!$validated) {
      $this->setAlert("error", "Fill all the required field!");
      return false;
    }


    $customer = htmlspecialchars(trim($_POST['customer']));
    $sale_date = htmlspecialchars(trim($_POST['sale_date']));
    $status = htmlspecialchars(trim($_POST['status']));
    $note = htmlspecialchars(trim($_POST['note'] ?? ""));

    $payment_amount = htmlspecialchars(trim(empty($_POST['payment_amount'])  ? 0 : $_POST['payment_amount']));
    $payment_type = htmlspecialchars(trim($_POST['payment_type'] ?? ""));
    $payment_note = htmlspecialchars(trim($_POST['payment_note'] ?? ""));

    $exitsCustomer = $this->model->exitsCustomer($customer);

    if ($exitsCustomer < 1) {
      $this->setAlert("error", "Invalid customer");
      return false;
    }


    if (!Util::validateDate($sale_date)) {
      $this->setAlert("error", "Invalid date");
      return false;
    }

    if (!in_array($status, ['Return', 'Cancel'])) {
      $this->setAlert("error", "Invalid Status");
      return false;
    }


    // Make payment________

    if (!empty($payment_amount)) {

      if (!is_numeric($payment_amount)) {
        $this->setAlert("error", "Invalid payment amount");
        return false;
      }

      if ($payment_amount < 0) {
        $this->setAlert("error", "The negative amount does not allowed");
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
      'sale_date' => $sale_date,
      'status' => $status,
      'note' => $note,
      'payment_amount' => $payment_amount,
      'payment_type' => $payment_type,
      'payment_note' => $payment_note
    ];
  }


  private function updateSalesReturn($sales_id)
  {

    $validatePurchaseInfo = $this->validateProducts();

    if (!$validatePurchaseInfo) {
      Util::redirectBack();
    }

    $salesReturnInfo = $this->model->getSalesReturn($sales_id);

    if (empty($_SESSION['product'])) {

      $this->setAlert("error", "First select  product then enter the update button");
      return;
    }


    $customer = $validatePurchaseInfo['customer'];
    $purchase_date = $validatePurchaseInfo['sale_date'];
    $status = $validatePurchaseInfo['status'];
    $note = $validatePurchaseInfo['note'];

    $subtotal = $_SESSION['subtotal'];
    $other_charges =  $_SESSION['other_charges'];
    $discount_on_all =  $_SESSION['discount_on_all'];
    $discount_type = "Fixed";
    $grandtotal = $_SESSION['grandtotal'];
    $paid =  0;
    $due = $_SESSION['grandtotal'];


    if (!empty($validatePurchaseInfo['payment_amount'])) {

      $paid = $validatePurchaseInfo['payment_amount'];

      $due  =  ($_SESSION['grandtotal'] - $validatePurchaseInfo['payment_amount']);
    }

    $payments =    $this->model->getPayments($sales_id);

    if (!empty($payments)) {

      $payment_amount = array_sum(array_column($payments, 'payment_amount'));;
      $paid += $payment_amount;
      $due -=  $payment_amount;
    }

    if ($salesReturnInfo['paid'] > $grandtotal) {

      $this->setAlert("error", "Update the invoice after removing the overpayment on this purchase invoice");

      return;
    }


    $purchaseUpdate = $this->model->updateSalesReturn($customer, $purchase_date, $status, $note, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $sales_id);

    if (!$purchaseUpdate) {

      $this->setAlert("error", "Purchase update failed");
      return;
    }

    $deletePurchaseProducts =  $this->model->deleteSalesReturnProducts($sales_id);

    if (!$deletePurchaseProducts) {

      $this->setAlert("error", "Purchase product deleted failed");
      return;
    }

    // Purchase product_______
    foreach ($_SESSION['product'] as $product) {
      $this->model->addSaleReturnProducts($product, $sales_id);
    }


    // Payment________
    if (!empty($validatePurchaseInfo['payment_amount'])) {

      $payment_amount = $validatePurchaseInfo['payment_amount'];
      $payment_type = $validatePurchaseInfo['payment_type'];
      $payment_note = $validatePurchaseInfo['payment_note'];

      $this->model->addPayment($sales_id, $payment_amount, $payment_type, $payment_note);
    }

    // Old customer

    $old_supplier = $this->model->getCustomer($salesReturnInfo['customer']);


    if (!$old_supplier) {
      $this->setAlert("error", "Old customer not available");
      return;
    }

    $old_payable = -$salesReturnInfo['grandtotal'];
    $old_paid = -$salesReturnInfo['paid'];
    $old_due = -$salesReturnInfo['due'];

    $updateCustomer = $this->model->updateCustomer($old_payable, $old_paid, $old_due, $old_supplier['id']);

    if (!$updateCustomer) {
      $this->setAlert("error", "Old customer not updated");
      return;
    }

    // End old customer


    // New customer

    $customer = $this->model->getCustomer($validatePurchaseInfo['customer']);

    $payable = $grandtotal;
    $paid = $paid;
    $due =  $due;

    $updateCustomer = $this->model->updateCustomer($payable, $paid, $due, $customer['id']);

    $this->setAlert("success", "Sales return invoice successfully updated");

    unset($_SESSION['product']);

    unset($_SESSION["subtotal"]);

    unset($_SESSION["other_charges"]);

    unset($_SESSION["discount_on_all"]);

    unset($_SESSION["grandtotal"]);
    unset($_SESSION["editReturnID"]);
    return;
  }


  private function addSalesReturn()
  {

    $validatePurchaseInfo = $this->validateProducts();

    if (!$validatePurchaseInfo) {
      Util::redirectBack();
    }


    if (empty($_SESSION['product'])) {
      $this->setAlert("error", "First select  product then enter the save button");
      Util::redirectBack();
    }


    $customer = $validatePurchaseInfo['customer'];
    $sales_date = $validatePurchaseInfo['sale_date'];
    $status = $validatePurchaseInfo['status'];
    $note = $validatePurchaseInfo['note'];

    $subtotal = $_SESSION['subtotal'];
    $other_charges = $_SESSION['other_charges'];
    $discount_on_all = $_SESSION['discount_on_all'];
    $discount_type = "Fixed";
    $grandtotal = $_SESSION['grandtotal'];
    $paid =  0;
    $due = $_SESSION['grandtotal'];

    if (!empty($validatePurchaseInfo['payment_amount'])) {

      $paid = $validatePurchaseInfo['payment_amount'];
      $due =  ($_SESSION['grandtotal'] - $validatePurchaseInfo['payment_amount']);
    }

    $sale_return_id = $this->model->addSaleReturn($customer, $status, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $note, $sales_date);

    $bill_no = 'BT-' . random_int(100, 999) . $sale_return_id;

    $this->model->UpdateSaleReturnBillNo($bill_no, $sale_return_id);


    if (!$sale_return_id) {
      $this->setAlert("error", "Something went wrong");
      return;
    }

    // Purchase document upload_______

    // Add Purchase Product_______
    foreach ($_SESSION['product'] as $product) {
      $this->model->AddSaleReturnProducts($product, $sale_return_id);
    }

    // End Add Purchase Product________

    // Update stock product
    $product_id = 0;
    $total_stock = 0;
    $current_stock = 0;
    $stock_value = 0;

    foreach ($_SESSION['product'] as $key => $stockUpdate) {

      $product_id = $key;
      $total_stock =  $stockUpdate['qty'];
      $current_stock =  $stockUpdate['qty'];
      $stock_value =  ($stockUpdate['final_selling_price'] * $stockUpdate['qty']);

      $this->model->stockProductUpdate($total_stock, $current_stock, $stock_value, $product_id);
    }
    // End update stock product 



    // Add Payment________
    if (!empty($validatePurchaseInfo['payment_amount'])) {

      $payment_amount = $validatePurchaseInfo['payment_amount'];
      $payment_type = $validatePurchaseInfo['payment_type'];
      $payment_note = $validatePurchaseInfo['payment_note'];

      $this->model->addPayment($sale_return_id, $payment_amount, $payment_type, $payment_note);
    }
    // End Add Payment_______

    // Supplire Update_______
    $payable =   $grandtotal;
    $this->model->updateCustomer($payable, $paid, $due, $customer);
    // End Supplire Update______

    $this->setAlert("success", "Sale return invoice successfully created");


    unset($_SESSION['product']);
    unset($_SESSION["subtotal"]);
    unset($_SESSION['other_charges']);
    unset($_SESSION['grandtotal']);
    unset($_SESSION['discount_on_all']);
    unset($_SESSION["invoiceProduct"]);

    Util::redirectBack();
  }
}
