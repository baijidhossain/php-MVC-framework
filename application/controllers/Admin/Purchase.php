<?php


class Purchase extends Controller
{

  public function __construct()
  {
    $this->model = $this->loadModel('Purchase');
  }

  public function Index()
  {

    $search = "";
    $params = [];

    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " (p.bill_no LIKE ? ) ";
      $params[] = "%$searchTerm%";
    }

    $getPurchases = $this->model->getPurchases($search, $params);
    $data = [
      "purchases" => $getPurchases,
      "page_title" => "Purchase list"
    ];

    $this->View('Purchase/Index', $data);
  }

  public function Add()
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $this->addPurchase();

      Util::redirectBack();
    }

    // show invoice add page
    $suppliers = $this->model->getSuppliers();
    $products = $this->model->getProducts();

    $data = [

      'suppliers' => $suppliers,
      'products' => $products,
      "page_title" => "Add Purchase"

    ];

    $this->View('Purchase/Add', $data);
  }

  public function views($id = "")
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $purchaseinfo = $this->model->getPurchase($id);

    $purchaseproducts = $this->model->getPurchaseProducts($id);

    $paymentinfo = $this->model->getPayments($id);


    if (empty($purchaseinfo)) {
      $this->setAlert("error", "Purchase info not available");
      Util::redirectBack();
    }

    if (empty($purchaseproducts)) {
      $this->setAlert("error", "Purchase prodact not available");
      Util::redirectBack();
    }

    $getsupplireinfo = $this->model->getSupplier($purchaseinfo['supplier']);


    if (empty($getsupplireinfo)) {
      $this->setAlert("error", "Supplire info not available");
      Util::redirectBack();
    }

    $data = [

      'supplire' => $getsupplireinfo,
      'purchase' =>  $purchaseinfo,
      'purchaseproducts' => $purchaseproducts,
      'payment' =>  $paymentinfo,
      "page_title" => "Purchase Invoice Details"

    ];

    $this->View('Purchase/Views', $data);
  }

  public function Return($purchase_id = "")
  {

    if (!$purchase_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }
    unset($_SESSION["invoiceProduct"]);
    unset($_SESSION["product"]);

    $_SESSION["editurchaseID"] = $purchase_id;

    $getPurchase = $this->model->getPurchase($purchase_id);

    if (empty($getPurchase)) {
      $this->setAlert("error", "Purchase invoice not available");
      Util::redirectBack();
    }
    $purchaseProducts = $this->model->getPurchaseProducts($purchase_id);

    if (empty($purchaseProducts)) {
      $this->setAlert("error", "Purchase products info not available");
      Util::redirectBack();
    }

    if (in_array($getPurchase['return_status'], ['Return', 'Cancel'])) {
      $this->setAlert("error", "Purchase return invoice already generated!");
      Util::redirectBack();
    }

    foreach ($purchaseProducts as  $purchaseproduct) {

      $dynamicProduct['id'] = $purchaseproduct['product_id'];
      $dynamicProduct['name'] = $purchaseproduct['product_name'];

      $dynamicProduct['qty'] = $purchaseproduct['qty'];
      $dynamicProduct['unit'] = $purchaseproduct['unit'];
      $dynamicProduct['purchase_price'] = $purchaseproduct['purchase_price'];

      $dynamicProduct['discount'] = $purchaseproduct['discount'];
      $dynamicProduct['discount_type'] = $purchaseproduct['discount_type'];
      $dynamicProduct['discount_amount'] = $purchaseproduct['discount_amount'];

      $dynamicProduct['tax'] = $purchaseproduct['tax'];
      $dynamicProduct['tax_type'] = $purchaseproduct['tax_type'];
      $dynamicProduct['tax_amount'] = $purchaseproduct['tax_amount'];

      $dynamicProduct['unit_cost'] = $purchaseproduct['unit_cost'];
      $dynamicProduct['total_amount'] = $purchaseproduct['total_amount'];
      $dynamicProduct['purchase_id'] = $purchase_id;
      // $_SESSION["product"][$purchaseproduct['product_id']] = [];

      $_SESSION["product"][$purchaseproduct['product_id']] = $dynamicProduct;

      $_SESSION["invoiceProduct"][$purchaseproduct['product_id']] = $dynamicProduct;
    }

    $_SESSION["discount_on_all"] = $getPurchase['discount_on_all'];
    $_SESSION["other_charges"] = $getPurchase['other_charges'];
    $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
    $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);


    Util::redirect("/admin/purchase/invoice_return/$purchase_id");
  }

  public function invoice_return($purchase_id = "")
  {
    if (!$purchase_id) {
      $this->setAlert("error", "Invalid id");
      unset($_SESSION["invoiceProduct"]);
      unset($_SESSION["product"]);
      Util::redirect("/admin/purchase/");
    }

    if ($_SESSION["editurchaseID"] != $purchase_id) {
      $this->setAlert("error", "Wrong route");
      unset($_SESSION["invoiceProduct"]);
      unset($_SESSION["product"]);
      Util::redirect("/admin/purchase/");
    }

    $getSuppliers = $this->model->getSuppliers();
    $getPurchase = $this->model->getPurchase($purchase_id);

    if (empty($getPurchase)) {
      $this->setAlert("error", "In voice not available");
      unset($_SESSION["invoiceProduct"]);
      unset($_SESSION["product"]);
      Util::redirect("/admin/purchase/");
    }

    $products = $this->model->getProducts();
    $paymentinfo = $this->model->getPayments($purchase_id);

    $data = [

      'suppliers' => $getSuppliers['paginateData'],
      'products' => $products,
      'purchase' =>  $getPurchase,
      'paymentinfo' =>  $paymentinfo,
      "page_title" => "Purchase Invoice Return"

    ];

    $this->View('Purchase/invoice_return', $data);
  }

  public function purchase_edit($purchase_id = "")
  {

    if (!$purchase_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    unset($_SESSION["product"]);

    $_SESSION["editurchaseID"] = $purchase_id;

    $getPurchase = $this->model->getPurchase($purchase_id);
    $purchaseProducts = $this->model->getPurchaseProducts($purchase_id);

    if (empty($purchaseProducts)) {
      $this->setAlert("error", "Purchase products info not available");
      Util::redirectBack();
    }


    foreach ($purchaseProducts as  $purchaseproduct) {

      $dynamicProduct['id'] = $purchaseproduct['product_id'];
      $dynamicProduct['name'] = $purchaseproduct['product_name'];

      $dynamicProduct['qty'] = $purchaseproduct['qty'];
      $dynamicProduct['unit'] = $purchaseproduct['unit'];
      $dynamicProduct['purchase_price'] = $purchaseproduct['purchase_price'];

      $dynamicProduct['discount'] = $purchaseproduct['discount'];
      $dynamicProduct['discount_type'] = $purchaseproduct['discount_type'];
      $dynamicProduct['discount_amount'] = $purchaseproduct['discount_amount'];

      $dynamicProduct['tax'] = $purchaseproduct['tax'];
      $dynamicProduct['tax_type'] = $purchaseproduct['tax_type'];
      $dynamicProduct['tax_amount'] = $purchaseproduct['tax_amount'];

      $dynamicProduct['unit_cost'] = $purchaseproduct['unit_cost'];
      $dynamicProduct['total_amount'] = $purchaseproduct['total_amount'];

      // $_SESSION["product"][$purchaseproduct['product_id']] = [];

      $_SESSION["product"][$purchaseproduct['product_id']] = $dynamicProduct;
    }

    $_SESSION["discount_on_all"] = $getPurchase['discount_on_all'];
    $_SESSION["other_charges"] = $getPurchase['other_charges'];
    $_SESSION["subtotal"] = array_sum(array_column($_SESSION["product"], 'total_amount'));
    $_SESSION["grandtotal"] = (($_SESSION["subtotal"] + $_SESSION["other_charges"]) - $_SESSION["discount_on_all"]);


    Util::redirect("/admin/purchase/edit/$purchase_id");
  }

  public function Edit($id = "")
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    if ($_SESSION["editurchaseID"] != $id) {
      $this->setAlert("error", "Wrong route");

      Util::redirectBack("/admin/purchase/edit/" . $_SESSION["oldPurchaseID"]);
    }

    $getPurchase = $this->model->getPurchase($id);
    $purchaseProducts = $this->model->getPurchaseProducts($id);
    $getSuppliers = $this->model->getSuppliers();
    $products = $this->model->getProducts();
    $paymentinfo = $this->model->getPayments($id);

    if (empty($getPurchase)) {
      $this->setAlert("error", "Purchase info not available");
      Util::redirectBack();
    }

    $data = [

      'suppliers' => $getSuppliers,
      'products' => $products,
      'purchase' =>  $getPurchase,
      'paymentinfo' =>  $paymentinfo,
      "page_title" => "Purchase Invoice Edit"

    ];

    $this->View('Purchase/edit', $data);

    return;
  }

  public function Update($purchase_id)
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (!$purchase_id) {
        $this->setAlert("error", "Invalid old supplire id");
        Util::redirectBack();
      }

      $this->updatePurchase($purchase_id);

      Util::redirect("/admin/purchase/");
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

      $productinfo = $this->model->getProduct($productID);


      // Invoice return 
      if (isset($_SESSION["invoiceProduct"]) && !empty($_SESSION["invoiceProduct"])) {

        $invoiceProduct = array_column($_SESSION["invoiceProduct"], 'id');

        if (!in_array($productinfo['id'], $invoiceProduct)) {
          $this->setAlert("error", "Sorry! this item does not exist in this purchase entry");
          return;
        }
      }
      // Invoice return 


      if (empty($productinfo)) {
        $this->setAlert("error", "This product was not found");
        return;
      }

      if (!empty($_SESSION["product"])) {
        if (in_array($productID, array_column($_SESSION["product"], "id"))) {
          $this->setAlert("error", "This product has already been added to the card");
          return;
        }
      }


      $dynamicProduct['id'] = $productinfo['id'];
      $dynamicProduct['name'] = $productinfo['name'];
      $dynamicProduct['qty'] = 1;
      $dynamicProduct['unit'] = $productinfo['unit'];
      $dynamicProduct['purchase_price'] = $productinfo['price'];

      $dynamicProduct['discount'] = 0;
      $dynamicProduct['discount_type'] = $productinfo['discount_type'];
      $dynamicProduct['discount_amount'] = 0;

      $dynamicProduct['tax'] = $productinfo['tax'];
      $dynamicProduct['tax_type'] = $productinfo['tax_type'];
      $dynamicProduct['tax_amount'] = $productinfo['tax_amount'];

      $dynamicProduct['unit_cost'] = $productinfo['buying_price'];
      $dynamicProduct['total_amount'] = $productinfo['buying_price'];

      $dynamicProduct['final_selling_price'] = $productinfo['final_seling_price'];

      $_SESSION["product"][$productinfo['id']] = [];

      $_SESSION["product"][$productinfo['id']] = $dynamicProduct;

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
      $this->View('Purchase/modal', $data);
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
      // End valid data cheking__________


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

      // Calcualtion_____
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

    $purchaseivoice = $this->model->getPurchase($id);
    $supplire = $this->model->getSupplier($purchaseivoice['supplier']);

    if (empty($purchaseivoice)) {
      $this->setAlert("error", "Not available invoice");
      Util::redirectBack();
    }

    if (empty($supplire)) {
      $this->setAlert("error", "Not available supplire");
      Util::redirectBack();
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $validated = Util::checkPostValues(['date', 'paid_amount', 'payment_type']);

      if (!$validated) {

        $this->setAlert("error", "Fill all the required field");
        Util::redirectBack();
      }

      $paid_amount = htmlspecialchars(trim($_POST['paid_amount'] ?? ""));
      $payment_type = htmlspecialchars(trim($_POST['payment_type'] ?? ""));
      $date = htmlspecialchars(trim($_POST['date'] ?? ""));
      $payment_note = htmlspecialchars(trim($_POST['payment_note']));

      $paid_amount = abs($paid_amount);
      $due = $purchaseivoice['due'];
      $paid = $purchaseivoice['paid'];


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

      if ($paid_amount > $due) {
        $this->setAlert("error", "Entered Amount Not be Greater Than Due Amount");
        Util::redirectBack();
      }

      // Purchase invoice update____
      $due =  -$paid_amount;
      $paid =  $paid_amount;
      $updatePurchaseInvoice = $this->model->updatePurchaseInvoice($paid, $due, $purchaseivoice['id']);
      // End Purchase Invoice Update_____


      // Supplire update____
      $payable =  0;
      $supplire_paid =  $paid_amount;
      $supplire_due =  -$paid_amount;
      // End Supplire update____

      $updateSupplire = $this->model->updateSupplire($payable, $supplire_paid, $supplire_due, $supplire['id']);

      // Make Payment_____
      $paymentinfo = [
        'item_id' => $purchaseivoice['id'],
        'payment_amount' => $paid_amount,
        'payment_note' => $payment_note,
        'payment_type' => $payment_type,
        'controller' => "purchase"

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

      'purchaseivoice' => $purchaseivoice,
      'supplire' => $supplire,
      'action' => 'paynow',
      "modal_title" => "Add Payment"

    ];

    $this->View('Purchase/modal', $data);
  }

  public function payment_view($id = "")
  {
    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $purchaseinvoice = $this->model->getPurchase($id);
    if (empty($purchaseinvoice)) {
      $this->setAlert("error", "Not available Purchase");
      Util::redirectBack();
    }

    $paymentinfo = $this->model->getPayments($id);

    $supplire = $this->model->getSupplier($purchaseinvoice['supplier']);

    if (empty($purchaseinvoice)) {
      $this->setAlert("error", "Not Available Purchase Invoice");
      Util::redirectBack();
    }

    if (empty($supplire)) {
      $this->setAlert("error", "Not Available Supplire");
      Util::redirectBack();
    }


    $data = [
      'purchaseinvoice' => $purchaseinvoice,
      'supplire' => $supplire,
      'paymentinfo' => $paymentinfo,
      'action' => 'payment_view',
      "modal_title" => "Payment Info"

    ];

    $this->View('Purchase/modal', $data);
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
    $purchaseinvoice = $this->model->getPurchase($paymentinfo['item_id']);

    if (empty($purchaseinvoice)) {
      $this->setAlert("error", "purchase invoice  not available");
      Util::redirectBack();
    }

    $due = 0;
    $paid = 0;

    $due =    $paymentinfo['payment_amount'];
    $paid =   -$paymentinfo['payment_amount'];
    $this->model->updatePurchaseInvoice($paid, $due, $purchaseinvoice['id']);

    // End purchase info update


    // Start supplire info update
    $supplire = $this->model->getSupplier($purchaseinvoice['supplier']);

    if (empty($supplire)) {
      $this->setAlert("error", "Supplire info not available");
      Util::redirectBack();
    }

    $supplire_due = 0;
    $supplire_paid = 0;

    $supplire_payable = 0;
    $supplire_due =   $paymentinfo['payment_amount'];
    $supplire_paid =  -$paymentinfo['payment_amount'];
    $this->model->updateSupplire($supplire_payable, $supplire_paid, $supplire_due, $supplire['id']);

    // End supplire info update

    $deletepayment = $this->model->deletePayment($payment_id);

    if ($deletepayment) {
      $this->setAlert("success", "Payment successfully deleted");
    } else {
      $this->setAlert("error", "Something went wrong");
    }
    Util::redirectBack();
  }

  public function delete_purchase($purchase_id = "")
  {

    if (!$purchase_id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    // Start purchase info update
    $purchaseinvoice = $this->model->getPurchase($purchase_id);

    if (empty($purchaseinvoice)) {
      $this->setAlert("error", "purchase invoice  not available");
      Util::redirectBack();
    }

    // Start supplire info update
    $supplire = $this->model->getSupplier($purchaseinvoice['supplier']);

    if (empty($supplire)) {
      $this->setAlert("error", "Supplire info not available");
      Util::redirectBack();
    }

    $supplire_payable = 0;
    $supplire_due = 0;
    $supplire_paid = 0;

    $supplire_payable = -$purchaseinvoice['grandtotal'];
    $supplire_due =   -$purchaseinvoice['due'];
    $supplire_paid =  -$purchaseinvoice['paid'];

    $this->model->updateSupplire($supplire_payable, $supplire_paid, $supplire_due, $supplire['id']);

    // End supplire info update

    $this->model->deletePayment($purchase_id);
    $this->model->deletePurchaseProducts($purchase_id);

    $deletepurchase = $this->model->deletePurchase($purchase_id);

    if ($deletepurchase) {
      $this->setAlert("success", "Purchase invoice successfully deleted");
    } else {
      $this->setAlert("error", "Something went wrong");
    }
    Util::redirectBack();
  }


  public function pdfPurchaseInvoice($purchase_id)
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

    $purchaseinfo = $this->model->getPurchase($purchase_id);

    $purchaseproducts = $this->model->getPurchaseProducts($purchase_id);


    $paymentlist = $this->model->getPayments($purchase_id);

    $pdf->SetFont('Arial', '', 11);

    $cellHeight = 8;

    foreach ($purchaseproducts as  $product) {

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
    $pdf->Cell(30, 8, $purchaseinfo['subtotal'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Other Charge : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $purchaseinfo['other_charges'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Discount On All : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $purchaseinfo['discount_on_all'], 0, 1, 'L');

    $pdf->Cell(160, 8, 'Grand Total : ', 0, 0, 'R');
    $pdf->Cell(30, 8, $purchaseinfo['grandtotal'], 0, 1, 'L');

    // Payment list____
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 8, 'Payment Info: ', 0, 1, 'L');
    $pdf->Cell(40, 8, 'Date', 1, 0, 'L');
    $pdf->Cell(50, 8, 'Recieved by', 1, 0, 'L');
    $pdf->Cell(30, 8, 'Type', 1, 0, 'L');
    $pdf->Cell(40, 8, 'Note', 1, 0, 'L');
    $pdf->Cell(30, 8, 'Amount', 1, 1, 'L');

    $pdf->SetFont('Arial', '', 11);
    foreach ($paymentlist  as  $payment) {
      $pdf->Cell(40, 8, date_create($payment['created'])->format('M d, Y'), 1, 0, 'L');
      $pdf->Cell(50, 8, $payment['user'], 1, 0, 'L');
      $pdf->Cell(30, 8, $payment['payment_type'] == 1 ? 'Cash' : 'Bank', 1, 0, 'L');
      $pdf->Cell(40, 8, $payment['payment_note'], 1, 0, 'L');
      $pdf->Cell(30, 8, $payment['payment_amount'], 1, 1, 'L');
    }
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(160, 8, 'Total : ', 0, 0, 'R');
    $pdf->Cell(30, 8, array_sum(array_column($paymentlist, 'payment_amount')), 0, 1, 'L');

    $pdf->Output('I', 'ba', true);
  }

  public function printinvoice($id)
  {

    if (!$id) {
      $this->setAlert("error", "Invalid id");
      Util::redirectBack();
    }

    $purchaseinfo = $this->model->getPurchase($id);

    $purchaseproducts = $this->model->getPurchaseProducts($id);

    $paymentinfo = $this->model->getPayments($id);

    if (empty($purchaseinfo)) {

      $this->setAlert("error", "Purchase info not available");

      Util::redirectBack();
    }

    if (empty($purchaseproducts)) {

      $this->setAlert("error", "Purchase prodact not available");

      Util::redirectBack();
    }

    $getsupplireinfo = $this->model->getSupplier($purchaseinfo['supplier']);


    if (empty($getsupplireinfo)) {

      $this->setAlert("error", "Supplire info not available");

      Util::redirectBack();
    }

    $data = [

      'supplire' => $getsupplireinfo,
      'purchase' =>  $purchaseinfo,
      'purchaseproducts' => $purchaseproducts,
      'payment' =>  $paymentinfo,
      "page_title" => "Purchase Invoice Details"

    ];


    $this->View('Purchase/printinvoice', $data);
  }

  // Extra function_______

  private function validateProducts()
  {


    $validated = Util::checkPostValues(['supplier', 'purchase_date', 'status']);

    if (!$validated) {
      $this->setAlert("error", "Fill all the required field!");
      return false;
    }

    $old_supplier = htmlspecialchars(trim($_POST['old_supplier'] ?? ''));
    $supplier = htmlspecialchars(trim($_POST['supplier']));
    $purchase_date = htmlspecialchars(trim($_POST['purchase_date']));
    $status = htmlspecialchars(trim($_POST['status']));
    $note = htmlspecialchars(trim($_POST['note'] ?? ""));

    $payment_amount = htmlspecialchars(trim(empty($_POST['payment_amount'])  ? 0 : $_POST['payment_amount']));
    $payment_type = htmlspecialchars(trim($_POST['payment_type'] ?? ""));
    $payment_note = htmlspecialchars(trim($_POST['payment_note'] ?? ""));

    $exitsSupplier = $this->model->exitsSupplier($supplier);

    if ($exitsSupplier < 1) {
      $this->setAlert("error", "Invalid supplirer");
      return false;
    }


    if (!Util::validateDate($purchase_date)) {
      $this->setAlert("error", "Invalid date");
      return false;
    }

    if (!in_array($status, ['Received', 'Pending', 'Ordered'])) {
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
      'old_supplier' => $old_supplier,
      'supplier' => $supplier,
      'purchase_date' => $purchase_date,
      'status' => $status,
      'note' => $note,
      'payment_amount' => $payment_amount,
      'payment_type' => $payment_type,
      'payment_note' => $payment_note
    ];
  }

  private function addPurchase()
  {

    $validatePurchaseInfo = $this->validateProducts();

    if (!$validatePurchaseInfo) {
      Util::redirectBack();
    }

    if (empty($_SESSION['product'])) {
      $this->setAlert("error", "First select  product then enter the save button");
      Util::redirectBack();
    }


    $supplier = $validatePurchaseInfo['supplier'];
    $purchase_date = $validatePurchaseInfo['purchase_date'];
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

    $purchaseid = $this->model->addPurchase($supplier, $status, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $note, $purchase_date);

    $bill_no = 'BT-' . random_int(100, 999) . $purchaseid;

    $this->model->UpdatePurchaseBillNo($bill_no, $purchaseid);

    if (!$purchaseid) {
      $this->setAlert("error", "Something went wrong");
      return;
    }

    // Purchase document upload_______
    $this->ImagUpload($purchaseid);

    // Add Purchase Product_______
    foreach ($_SESSION['product'] as $product) {
      $this->model->AddPurchaseProducts($product, $purchaseid);
    }
    // End Add Purchase Product________

    // Update stock product
    $product_id = 0;
    $total_stock = 0;
    $current_stock = 0;

    foreach ($_SESSION['product'] as $key => $stockUpdate) {
      $product_id = $key;
      $total_stock =  $stockUpdate['qty'];
      $current_stock =  $stockUpdate['qty'];
      $this->model->stockProductUpdate($total_stock, $current_stock, $product_id);
    }
    // End update stock product 

    // Add Payment________
    if (!empty($validatePurchaseInfo['payment_amount'])) {

      $payment_amount = $validatePurchaseInfo['payment_amount'];
      $payment_type = $validatePurchaseInfo['payment_type'];
      $payment_note = $validatePurchaseInfo['payment_note'];
      $this->model->addPayment($purchaseid, $payment_amount, $payment_type, $payment_note);
    }

    // End Add Payment_______


    // Supplire Update_______
    $payable =   $grandtotal;
    $this->model->updateSupplire($payable, $paid, $due, $supplier);
    // End Supplire Update______

    $this->setAlert("success", "Purchase invoice successfully created");


    unset($_SESSION['product']);
    unset($_SESSION["subtotal"]);
    unset($_SESSION['other_charges']);
    unset($_SESSION['grandtotal']);
    unset($_SESSION['discount_on_all']);

    return;
  }

  private function updatePurchase($purchase_id)
  {

    $validatePurchaseInfo = $this->validateProducts();

    if (!$validatePurchaseInfo) {
      Util::redirectBack();
    }

    $purchaseinfo = $this->model->getPurchase($purchase_id);

    if (empty($_SESSION['product'])) {

      $this->setAlert("error", "First select  product then enter the update button");
      return;
    }


    $supplier = $validatePurchaseInfo['supplier'];
    $purchase_date = $validatePurchaseInfo['purchase_date'];
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

    $payments =    $this->model->getPayments($purchase_id);

    if (!empty($payments)) {

      $payment_amount = array_sum(array_column($payments, 'payment_amount'));;
      $paid += $payment_amount;
      $due -=  $payment_amount;
    }

    if ($purchaseinfo['paid'] > $grandtotal) {

      $this->setAlert("error", "Update the invoice after removing the overpayment on this purchase invoice");

      return;
    }


    $purchaseUpdate = $this->model->updatePurchase($supplier, $purchase_date, $status, $note, $subtotal, $other_charges, $discount_on_all, $discount_type, $grandtotal, $paid, $due, $purchase_id);

    if (!$purchaseUpdate) {

      $this->setAlert("error", "Purchase update failed");
      return;
    }

    $deletePurchaseProducts =  $this->model->deletePurchaseProducts($purchase_id);

    if (!$deletePurchaseProducts) {

      $this->setAlert("error", "Purchase product deleted failed");
      return;
    }


    // Purchase product_______
    foreach ($_SESSION['product'] as $product) {
      $this->model->AddPurchaseProducts($product, $purchase_id);
    }


    // Payment________
    if (!empty($validatePurchaseInfo['payment_amount'])) {

      $payment_amount = $validatePurchaseInfo['payment_amount'];
      $payment_type = $validatePurchaseInfo['payment_type'];
      $payment_note = $validatePurchaseInfo['payment_note'];

      $this->model->addPayment($purchase_id, $payment_amount, $payment_type, $payment_note);
    }

    // Old supplire

    $old_supplier = $this->model->getSupplier($purchaseinfo['supplier']);


    if (!$old_supplier) {
      $this->setAlert("error", "Old supplire not available");
      return;
    }

    $old_payable = -$purchaseinfo['grandtotal'];
    $old_paid = -$purchaseinfo['paid'];
    $old_due = -$purchaseinfo['due'];

    $updatesupplire = $this->model->updateSupplire($old_payable, $old_paid, $old_due, $old_supplier['id']);

    if (!$updatesupplire) {
      $this->setAlert("error", "Old supplire not updated");
      return;
    }

    // End old supplire


    // New supplire

    $supplire = $this->model->getSupplier($validatePurchaseInfo['supplier']);

    $payable = $grandtotal;
    $paid = $paid;
    $due =  $due;

    $updatesupplire = $this->model->updateSupplire($payable, $paid, $due, $supplire['id']);

    $this->setAlert("success", "Purchase invoice successfully updated");

    unset($_SESSION['product']);

    unset($_SESSION["subtotal"]);

    unset($_SESSION["other_charges"]);

    unset($_SESSION["discount_on_all"]);

    unset($_SESSION["grandtotal"]);

    return;
  }

  public function ImagUpload($purchase_id)
  {

    if (isset($_FILES['inputfile']) && $_FILES['inputfile']['tmp_name'] != '') {

      $check = getimagesize($_FILES['inputfile']["tmp_name"]);

      if (!$check) {
        $this->setAlert('error', 'Sorry, Your file is not an image.');
        return;
      }

      if ($_FILES['inputfile']["size"] / 1024 / 1024 > 2) {
        $this->setAlert('error', 'Sorry, Your file is too large. Upload Size is Maximum 2MB');
        return;
      }

      $filename = $_FILES['inputfile']['name'];
      // $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);

      $img_name = $purchase_id . '.png';

      $save_path = PUBLIC_PATH . "images/purchase/" . $img_name;

      if (file_exists($save_path)) {
        unlink($save_path); // correct
      }

      if (!move_uploaded_file($_FILES['inputfile']["tmp_name"], $save_path)) {
        $this->setAlert('error', 'Book image could not be uploded');
        return;
      }

      if (!$this->model->purchaseImageUpdate($img_name, $purchase_id)) {
        $this->setAlert('error', 'Purchase image could not be updated!');
        return;
      }
    }
    // End file upload

  }
}
