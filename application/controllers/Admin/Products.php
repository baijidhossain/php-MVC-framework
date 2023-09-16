<?php
class Products extends Controller
{
  public function __construct()
  {
    $this->model = $this->loadModel('Products');
  }

  public function Index()
  {


    $search = "";
    $params = [];
    if (!empty($_GET['search'])) {
      $search .= empty($search) ? " WHERE " : " AND ";
      $searchTerm = $_GET['search'];
      $search .= " (p.name LIKE ? OR p.buying_price LIKE ? OR p.description LIKE ? OR p.minimum_qty LIKE ? ) ";
      $params[] = "%$searchTerm%";
      $params[] = "%$searchTerm%";
      $params[] = "%$searchTerm%";
      $params[] = "%$searchTerm%";
    }

    $prodects = $this->model->getProducts($search, $params);



    $data = [
      "products" => $prodects,
      "page_title"    => "Manage Products",
    ];

    $this->view('Products/Index', $data);
  }

  //----------------------------------------------------------------------------------------//

  public function Add($type = "")
  {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


      $validatedProduct = $this->validatedProduct();


      if (empty($validatedProduct)) {
        Util::redirectBack();
      }

      $product_id = $this->model->add($validatedProduct);

      if ($product_id) {

        $this->ImagUpload($product_id);

        $this->setAlert('success', 'Product successfully added');


        $validatedStockProduct = [
          'product_id' => $product_id,
          'product_code' => "BT-" . rand(1000, 9000) . $product_id,
          'product_name' => $validatedProduct['product_name'],

          'new_opening_stock' => $validatedProduct['new_opening_stock'] ?? 0,
          'total_stock' => $validatedProduct['new_opening_stock'] ?? 0,
          'adjustment_note' => $validatedProduct['adjustment_note'] ?? "",
          'current_stock' => $validatedProduct['new_opening_stock'] ?? 0,
          'stock_value' => !empty($validatedProduct['new_opening_stock']) ? ($validatedProduct['new_opening_stock'] * $validatedProduct['final_selling_price']) : 0,

        ];

        $this->model->stockProductsAdd($validatedStockProduct);

        if (!empty($validatedProduct['new_opening_stock'])) {
          $this->model->currentOpeningStockAdd($product_id, $validatedProduct['new_opening_stock'], $validatedProduct['adjustment_note']);
        }
      } else {

        $this->setAlert('error', 'Something went wrong');
      }
      Util::redirectBack();
    }

    $data = [
      "categories" => $this->model->getCategories(),
      "colors" => $this->model->getColors(),
      "companies" => $this->model->getBrands(),
      "units" => $this->model->getUnits(),
      "taxs" => $this->model->getTaxs(),
      "page_title"    => "Add product",

    ];

    $this->view('Products/Add', $data);
  }

  // End add 

  public function Edit($product_id)
  {



    $data = [
      'product_id' => $product_id,
      'productInfo' => $this->model->getProduct($product_id),
      'currentOpeningStocks' => $this->model->getCurrentOpeningStocks($product_id),
      "categories" => $this->model->getCategories(),
      "colors" => $this->model->getColors(),
      "companies" => $this->model->getBrands(),
      "units" => $this->model->getUnits(),
      "taxs" => $this->model->getTaxs(),

      "page_title"    => "Edit product",

    ];

    $this->view('Products/Edit', $data);
  }

  //----------------------------------------------------------------------------------------//

  //----Start update product----//
  public function Update()
  {

    $product_id = $_POST['product_id'];

    if (!$product_id) {
      $this->setAlert('error', 'Invalid id');
      Util::redirectBack();
    }

    $validatedProduct = $this->validatedProduct();


    if (empty($validatedProduct)) {
      Util::redirectBack();
    }

    $update = $this->model->update($validatedProduct, $product_id);
    $this->ImagUpload($product_id);

    $getCurrentOpeningStocks = $this->model->getCurrentOpeningStocks($product_id);
    $get_stock_report = $this->model->getStockReport($product_id);

    $validatedStockProduct = [
      'product_id' => $product_id,
      'new_opening_stock' => $validatedProduct['new_opening_stock'] ?? 0,
      'total_stock' => $validatedProduct['new_opening_stock'] ?? 0,
      'adjustment_note' => $validatedProduct['adjustment_note'],
      'current_stock' => $validatedProduct['new_opening_stock'] ?? 0,
      'stock_value' => !empty($validatedProduct['new_opening_stock']) ? ($get_stock_report['current_stock'] + $validatedProduct['new_opening_stock']) * ($validatedProduct['final_seling_price']) : ($get_stock_report['current_stock'] * $validatedProduct['final_seling_price']),

    ];



    $stockUpdate = $this->model->stockProductUpdate($validatedStockProduct, $product_id);

    if (!empty($validatedProduct['new_opening_stock'])) {
      $this->model->currentOpeningStockAdd($product_id, $validatedProduct['new_opening_stock'], $validatedProduct['adjustment_note']);
    }


    if ($stockUpdate) {
      $this->setAlert('success', 'Product stock successfully updated');
    } else {
      $this->setAlert('error', 'Something went wrong');
    }
    Util::redirectBack();
  }

  //----------------------//

  //----End add product----//

  public function delete($id = 0)
  {
    if (!$id) {
      $this->setAlert('error', 'Invalid id!');
      Util::redirect(APP_URL . "/admin/Products");
    }
    $delete = $this->model->delete($id);
    if ($delete) {
      $this->setAlert('success', 'Item successfully deleted .');
    } else {
      $this->setAlert('error', 'Item unsuccessfully deleted.');
    }
    Util::redirect(APP_URL . "/admin/Products");
  }

  public function openingstockdelete($openingstock_id)
  {


    if (!$openingstock_id) {

      $this->setAlert('error', 'Invalid id');
      Util::redirectBack();
    }

    $openingstockInfo = $this->model->getOpeningStockIdWise($openingstock_id);

    $get_stock_report = $this->model->getStockReport($openingstockInfo['product_id']);

    $validatedStockProduct = [
      'product_id' => $get_stock_report['id'],
      'new_opening_stock' =>  -$openingstockInfo['stock'],
      'total_stock' => -$openingstockInfo['stock'],
      'adjustment_note' => $get_stock_report['adjustment_note'],
      'current_stock' =>  -$openingstockInfo['stock'],
      'stock_value' => !empty($openingstockInfo) ? ($get_stock_report['current_stock'] - $openingstockInfo['stock']) * ($get_stock_report['final_seling_price']) : ($get_stock_report['current_stock'] *  $get_stock_report['final_seling_price']),

    ];

    $this->model->stockProductUpdate($validatedStockProduct, $get_stock_report['product_id']);

    $openingstockdelete = $this->model->openingstockdelete($openingstock_id);

    if ($openingstockdelete) {

      $this->setAlert('success', 'Opening stock successfully deleted');
    } else {
      $this->setAlert('error', 'Something went wrong');
    }

    Util::redirectBack();
  }

  public function addModal()
  {
    $data = [
      "categories" => $this->model->getCategories(),
      "colors" => $this->model->getAllcolor(),
      "companies" => $this->model->getAllcompany(),
      "modal_title"    => "Add product",
      "view_type" => "add"
    ];
    $this->view('ProductsModal', $data);
  }


  private function validatedProduct()
  {

    $fieldname = ['product_name', 'brand_id', 'category_id', 'color_id', 'price', 'tax', 'tax_type', 'selling_price', 'unit_id'];
    $validated = Util::checkPostValues($fieldname);

    if (!$validated) {
      $this->setAlert('error', 'Fill all the required felid');
      Util::redirectBack();
    }

    $product_name = htmlspecialchars(trim($_POST['product_name']));
    $brand_id = htmlspecialchars(trim($_POST['brand_id']));
    $category_id = htmlspecialchars(trim($_POST['category_id']));
    $color_id = htmlspecialchars(trim($_POST['color_id']));
    $minimum_qty = htmlspecialchars(trim(empty($_POST['minimum_qty']) ? 0 : $_POST['minimum_qty']));
    $unit_id = htmlspecialchars(trim($_POST['unit_id']));
    $barcode = htmlspecialchars(trim($_POST['barcode'] ?? ""));
    $expire = htmlspecialchars(trim($_POST['expire'] ?? ""));
    $description = htmlspecialchars(trim($_POST['description'] ?? ""));
    $price = htmlspecialchars(trim($_POST['price']));
    $tax = htmlspecialchars(trim($_POST['tax']));
    $tax_type = htmlspecialchars(trim($_POST['tax_type']));
    $selling_price = htmlspecialchars(trim($_POST['selling_price']));
    $discount = htmlspecialchars(trim(empty($_POST['discount']) ? 0 : $_POST['discount']));
    $discount_type = htmlspecialchars(trim(empty($_POST['discount_type']) ? 0 : $_POST['discount_type']));
    $current_opening_stock = htmlspecialchars(trim(empty($_POST['current_opening_stock'])  ? 0 : $_POST['current_opening_stock']));
    $new_opening_stock = htmlspecialchars(trim(empty($_POST['new_opening_stock']) ? 0 : $_POST['new_opening_stock']));
    $adjustment_note = htmlspecialchars(trim(empty($_POST['adjustment_note'])  ? "" : $_POST['adjustment_note']));



    $len = "";
    $numeric = "";
    $tax_amount = 0;

    $len = strlen($product_name);
    if ($len > 100) {
      $this->setAlert('error', 'Product name must be less than 100 characters or equal');
      return false;
    }

    $numeric = is_numeric($brand_id);
    if (!$numeric) {
      $this->setAlert('error', 'Brand name is invalid');
      return false;
    }

    $numeric = is_numeric($category_id);
    if (!$numeric) {
      $this->setAlert('error', 'Category name is invalid');
      return false;
    }

    $numeric = is_numeric($color_id);
    if (!$numeric) {
      $this->setAlert('error', 'Color name is invalid');
      return false;
    }


    if (!empty($minimum_qty)) {
      $is_numeric = is_numeric($minimum_qty);
      if (!$is_numeric) {
        $this->setAlert('error', 'Invalid qty');
        return false;
      }
    }


    if (!empty($unit_id)) {
      $numeric = is_numeric($unit_id);
      if (!$numeric) {
        $this->setAlert('error', 'Unit name is invalid');
        return false;
      }
    }

    if (!empty($barcode)) {
      $len = strlen($barcode);
      if ($len > 50) {
        $this->setAlert('error', 'Barcode must be less than 50 characters or equal ');
        return false;
      }
    }


    $is_numeric = is_numeric($price);
    if (!$is_numeric) {
      $this->setAlert('error', 'Invalid  price');
      return false;
    }

    $is_numeric = is_numeric($tax);
    if (!$is_numeric) {
      $this->setAlert('error', 'Invalid tax amount');
      return false;
    }


    if ($tax > 100) {
      $this->setAlert('error', 'Tax amount must be less than 100% or equal');
      return false;
    }
    if ($tax < 0) {
      $this->setAlert('error', 'Tax amount must be greater than 0  and less than 100 or equal or none');
      return false;
    }

    if (!in_array($tax_type, ['exclusive', 'inclusive'])) {
      $this->setAlert('error', 'Invalid tax type');
      return false;
    }

    $is_numeric = is_numeric($selling_price);
    if (!$is_numeric) {
      $this->setAlert('error', 'Invalid seling Price');
      return false;
    }

    if ($price < 0) {
      $this->setAlert('error', 'Invalid buying Price');
      return false;
    }

    if ($selling_price < $price) {
      $this->setAlert('error', 'Invalid seling Price');
      return false;
    }


    if (!empty($discount)) {
      $is_numeric = is_numeric($discount);
      if (!$is_numeric) {
        $this->setAlert('error', 'Invalid discount amount');
        return false;
      }
    }


    if (!in_array($discount_type, ['percent', 'fixed'])) {
      $this->setAlert('error', 'Invalid discount type');
      return false;
    }

    if (empty($new_opening_stock)) {
      $new_opening_stock = 0;
    }

    if (!empty($new_opening_stock)) {
      $is_numeric = is_numeric($new_opening_stock);
      if (!$is_numeric) {
        $this->setAlert('error', 'Invalid new opening stock value');
        return false;
      }
    }

    $len = strlen($adjustment_note);
    if ($len > 200) {
      $this->setAlert('error', 'adjustment_note must be less than 200 characters or equal');
      return false;
    }

    if (!empty($discount) && $discount_type == 'percent') {

      if ($discount > 100) {
        $this->setAlert('error', 'Discount amount invalid');
        return false;
      }
    }


    // Calculation


    if (!empty($tax) && !in_array($tax_type, ['exclusive', 'inclusive'])) {
      $this->setAlert('error', ' If the tax is determined, the tax type must be selected');
      return false;
    }

    // Buying price
    $tax_with_buying_price = $price;

    if (!empty($tax) && $tax != 0 &&  $tax_type == 'exclusive') {
      $tax_with_buying_price = $price + ($price * $tax / 100);
      $tax_amount = ($price * $tax / 100);
    }

    if ($selling_price < $tax_with_buying_price) {
      $this->setAlert('error', 'Selling price must be greater than buying price or equal');
      return false;
    }

    //Selfing price

    $final_selling_price = $selling_price;

    if (!empty($tax)  &&  $tax_type == 'exclusive') {
      $final_selling_price = $selling_price + ($selling_price * $tax / 100);
      $tax_amount = ($price * $tax / 100);
    }




    if (!empty($discount) && $discount_type == 'percent') {
      $final_selling_price  = $final_selling_price -  ($selling_price * $discount / 100);
      $discount_amount = ($selling_price * $discount / 100);
    }

    if (!empty($discount) && $discount_type == 'fixed') {
      $final_selling_price  = $final_selling_price - $discount;
      $discount_amount = $discount;
    }


    return [
      'product_name' =>  $product_name,
      'brand_id' => $brand_id,
      'category_id' => $category_id,
      'color_id' => $color_id,
      'minimum_qty' =>  $minimum_qty ?? 0,
      'unit_id' => $unit_id,
      'barcode' => $barcode,
      'expire' =>   !empty($expire)  ? date("Y-m-d H:I:s ", strtotime("$expire +2 month")) : "",
      'description' => $description,
      'price' => $price ?? 0.0000,

      'tax' => $tax ?? "",
      'tax_type' => $tax_type ?? "none",
      'tax_amount' => $tax_amount,

      'buying_price' => $tax_with_buying_price ?? 0.0000,
      'selling_price' => $selling_price ?? 0.0000,

      'discount' => $discount ?? 0,
      'discount_type' => $discount_type ?? "none",
      'discount_amount' => $discount_amount ?? 0.0000,

      'final_selling_price' =>  $final_selling_price ?? "",
      'current_opening_stock' => $current_opening_stock ?? 0,
      'new_opening_stock' => $new_opening_stock ?? 0,
      'adjustment_note' => $adjustment_note ?? "",

    ];
  }

  public function ImagUpload($product_id)
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

      $img_name = $product_id . '.png';

      $save_path = PUBLIC_PATH . "images/product/" . $img_name;

      if (file_exists($save_path)) {
        unlink($save_path); // correct
      }

      if (!move_uploaded_file($_FILES['inputfile']["tmp_name"], $save_path)) {
        $this->setAlert('error', 'Book image could not be uploded');
        return;
      }

      if (!$this->model->productImageUpdate($img_name, $product_id)) {
        $this->setAlert('error', 'Product image could not be updated!');
        return;
      }
    }
    // End file upload

  }
}
