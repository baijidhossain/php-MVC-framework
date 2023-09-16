<?php include_once VIEW_PATH . '_common/header.php'; ?>
<style>
  .statistics_table td {
    padding: 18px 8px !important;
  }

  .statistics_table tr>td:nth-child(2) {
    text-align: right;
  }

  .statistics_table td>div {
    white-space: nowrap;
  }


  .imagearea {
    position: relative;
    width: 100%;
    height: 35px;
    background-color: #eee;
    cursor: pointer;
  }

  .imagearea i {
    top: 50%;
    left: 50%;
    position: absolute;
    transform: translate(-50%, -50%);
  }

  .imagearea img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
</style>
<div class="wrapper">

  <?php include_once VIEW_PATH . '_common/admin_top.php'; ?>
  <?php include_once VIEW_PATH . '_common/navigation.php'; ?>

  <div class="content-wrapper">

    <section class="content-header">
      <h1><?= $data['page_title'] ?></h1>
      <ol class="breadcrumb">
        <li><a href="<?= APP_URL ?>/account/onAuthenticate"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?= $data['page_title'] ?></li>
      </ol>
    </section>

    <section class="content">

      <?php $this->getAlert(); ?>

      <form action="<?= APP_URL ?>/admin/purchase/add" method="post" id="form" enctype="multipart/form-data">
        <div style="margin-top: 20px;" class="row">

          <div class="col-md-12">
            <div class="box box-widget">
              <div class="box-header with-border ">
                <h3 class="box-title">Purchase Info</h3>
              </div>

              <div class="box-body">
                <div class="row">

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="supplier">Supplier <span class="text-danger">*</span></label>
                      <select datapleaceholder="jo" class="form-control select2" name="supplier">
                        <option value="">Select Supplier</option>
                        <?php
                        foreach ($data['suppliers']['paginateData'] as  $supplier) { ?>
                          <option value="<?= $supplier['id'] ?>"><?= $supplier['name'] ?></option>
                        <?php } ?>

                      </select>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="date">Purchase Date <span class="text-danger">*</span></label>
                      <input type="date" class="form-control" name="purchase_date" value="<?= date("Y-m-d") ?>">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="">Status <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="status">
                        <option value="">Select Status</option>
                        <option value="Received">Received</option>
                        <option value="Pending">Pending</option>
                        <option value="Ordered">Ordered</option>
                      </select>
                    </div>
                  </div>

                  <div class=" col-md-4">
                    <div class="form-group">
                      <label for="">Note</label>
                      <textarea class="form-control" name="note" rows="1" style="resize: vertical;"></textarea>
                    </div>
                  </div>

                  <div class=" col-md-2">
                    <div class="form-group ">
                      <label for="image">Document <i class="hover-q " data-placement="top" data-toggle="tooltip" title="" data-html="true" data-trigger="hover" data-original-title="Only Image ">
                          <i class="fa fa-info-circle text-gray hover-q"></i>
                        </i></label>
                      <input name="inputfile" type="file" class="form-control inputfile" style="display: none !important;">
                      <div class="imagearea m-auto">
                        <i class="fa fa-download"></i>
                      </div>

                    </div>
                  </div>

                </div>

              </div>

            </div>

          </div>

          <div class="col-md-12">
            <div class="box box-widget">
              <div class="box-header with-border ">
                <h3 class="box-title">Selected Products</h3>
              </div>

              <div class="box-header with-border ">

                <div class="row">
                  <div class="col-md-8 col-md-offset-2">
                    <div class="form-group mb-0">

                      <select class="form-control select2 product" name="product">
                        <option value="">Select Product</option>
                        <?php
                        foreach ($data['products'] as  $product) { ?>
                          <option value="<?= $product['id'] ?>"><?= $product['name'] ?></option>
                        <?php } ?>
                      </select>

                    </div>
                  </div>
                </div>

              </div>

              <div class="box-body">

                <div class="table-responsive">
                  <table class="table table-striped table-bordered  table-condensed mb-0">
                    <thead class="bg-color">

                      <tr>
                        <th style="width:250px ;">Item Name</th>
                        <th style="width:100px ;">QTY</th>
                        <th style="width:170px ;">Purchase Price</th>
                        <th style="width:170px ;">Discount</th>
                        <th style="width:200px ;">Discount Amount</th>
                        <th style="width:170px ;">Vat/Tax</th>
                        <th style="width:170px ;">Tax Amount</th>
                        <th style="width:170px ;">Unit Cost</th>
                        <th style="width:170px ;">Total Amount</th>
                        <th style="width:120px ;" class="text-right">Action</th>
                      </tr>

                    </thead>
                    <tbody class="rowappend">

                      <?php

                      if (empty($_SESSION['product'])) {
                        echo '<tr> <td colspan="12" class="text-center">No Item Selected</td> </tr>';
                      } else {
                        foreach ($_SESSION['product'] as $key => $product) { ?>

                          <tr class="newrow">

                            <td> <?= $product['name'] ?></td>

                            <td> <?= $product['qty'] ?> [<?= $product['unit'] ?>] </td>

                            <td> <?= $product['purchase_price'] ?></td>

                            <td> <?= $product['discount'] ?> [<?= $product['discount_type'] ?>] </td>

                            <td> <?= $product['discount_amount'] ?> </td>

                            <td> <?= $product['tax'] ?> %[<?= $product['tax_type'] ?>] </td>

                            <td> <?= $product['tax_amount'] ?> </td>

                            <td> <?= $product['unit_cost'] ?> </td>

                            <td> <?= $product['total_amount'] ?> </td>

                            <td class="text-right">
                              <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/purchase/invoice/edit/<?= $key ?>"> <i class="fa fa-pencil text-primary"> </i> </a>
                              &nbsp;&nbsp;
                              <a href="<?= APP_URL ?>/admin/purchase/invoice/itemRemove/<?= $key ?>"> <i class="fa fa-trash text-danger "> </i> </a>
                            </td>

                          </tr>

                      <?php }
                      }

                      ?>

                    </tbody>

                    <tfoot class="  text-bold text-right">

                      <tr>
                        <td class="text-right" colspan="8">Subtotal</td>
                        <td class="subtotal " colspan="2"><input type="text" class="form-control subtotal text-right input-sm" value="<?= $_SESSION["subtotal"] ??  0 ?>" readonly></td>
                      </tr>

                      <tr>
                        <td class="text-right" colspan="8">Other Charges/Fixed</td>
                        <td colspan="2"><input type="text" class="form-control other_charges text-right input-sm" value="<?= $_SESSION["other_charges"] ?? 0 ?>" placeholder="0"></td>
                      </tr>

                      <tr>
                        <td class="text-right" colspan="8">Discount On All/Fixed</td>
                        <td colspan="2"><input type="text" class="form-control discount_on_all text-right input-sm" value="<?= $_SESSION["discount_on_all"] ?? 0 ?>" name="discount_on_all" placeholder="0"></td>
                      </tr>

                      <tr>
                        <td class="text-right" colspan="8">Grand Total</td>
                        <td colspan="2"><input type="text" class="form-control grandtotal text-right input-sm" value="<?= $_SESSION["grandtotal"] ?? 0 ?>" readonly></td>
                      </tr>

                      <tr>
                        <td colspan="8"></td>
                        <td colspan="2">
                          <button type="button" onclick="invoiceUpdate()" class="btn btn-sm bg-color invoiceUpdate " style="width:100px;"> Update </button>
                        </td>
                      </tr>

                    </tfoot>
                  </table>
                </div>

              </div>

            </div>

          </div>

          <div class="col-md-12">
            <div class="box box-widget">
              <div class="box-header with-border ">
                <h3 class="box-title">Make Payment</h3>
              </div>

              <div class="box-body">
                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Amount</label>
                      <input type="number" class="form-control" name="payment_amount">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="payment_type">Payment Type
                        <i class="hover-q " data-placement="top" data-toggle="tooltip" title="" data-html="true" data-trigger="hover" data-original-title="If you want to make a payment, you must be select the payment type">
                          <i class="fa fa-info-circle text-gray hover-q"></i>
                        </i>
                      </label>
                      <select class="form-control select2" name="payment_type">
                        <option value="">Select Payment Type</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank">Bank</option>

                      </select>
                    </div>
                  </div>


                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Payment Note</label>
                      <textarea class="form-control" name="payment_note" rows="1"></textarea>
                    </div>
                  </div>

                </div>

              </div>
              <div class="box-footer text-right">
                <button type="submit" class="btn btn-sm bg-color save float-right" style="width:100px;">Save</button>

              </div>
            </div>

          </div>

        </div>

      </form>
    </section>
  </div>


  <?php include_once VIEW_PATH . '_common/footer.php'; ?>

  <script>
    $(document).ready(function() {

      $('.select2').select2({
        width: "100%",
        placeholder: "Please select an option",

      });

      $('.product').change(function() {
        var product = $(this).children("option:selected").val();
        var other_charges = $('.othercharges').val();
        var discount_on_all = $('.discount_on_all').val();
        var url = "/admin/purchase/Invoice/add";
        $.ajax({
          url: url,
          type: "POST",
          data: {
            product: product,
            discount_on_all: discount_on_all,
            other_charges: other_charges
          },
          success: function(data) {
            window.location.reload();
          }
        });
      })
    });


    function invoiceUpdate() {
      var other_charges = $('.other_charges').val();
      var discount_on_all = $('.discount_on_all').val();
      var url = "/admin/purchase/Invoice/invoiceUpdate";
      $.ajax({
        url: url,
        type: "POST",
        data: {
          discount_on_all: discount_on_all,
          other_charges: other_charges
        },
        success: function(data) {
          window.location.reload();
        }
      });
    }
  </script>

  <script>
    const dragArea = document.querySelector('.imagearea');

    let inputfile = document.querySelector('.inputfile');

    dragArea.addEventListener('dragover', function(event) {

      event.preventDefault();

    });

    dragArea.addEventListener('drop', function(event) {

      event.preventDefault();

      file_diplay(event.dataTransfer.files[0]);

      inputfile.files = null;

      inputfile.files = event.dataTransfer.files;

    });

    dragArea.addEventListener('click', function(event) {

      inputfile.click();

    });

    inputfile.addEventListener('change', function() {

      let previewImage = this.files[0];

      if (previewImage !== undefined) {

        dragArea.classList.add('dragAreaActive');

        file_diplay(previewImage);
      }

    });

    //   Start file display function
    function file_diplay(previewImage) {

      let filetype = previewImage.type;

      let validextention = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

      if (!validextention.includes(filetype)) {

        alert('This file not image!');
        exit;

      }

      if ((previewImage.size / 1024 / 1024) > 1) {

        alert('Must be file size less than 2 MB');
        exit;

      }

      let fileReader = new FileReader();

      fileReader.onload = () => {

        let fileUrl = fileReader.result;

        dragArea.innerHTML = `<img class="img-thumbnail" src="${fileUrl}" alt="">`;

      };

      fileReader.readAsDataURL(previewImage);

    }

    //   End file display function
  </script>