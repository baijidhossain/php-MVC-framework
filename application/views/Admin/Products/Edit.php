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

  hr {
    margin-top: 20px;
    margin-bottom: 20px;
    border: 0;
    border-top: 2px solid #eee;
  }

  .textarea_resize_vertical {
    resize: vertical;

  }

  .imagearea {
    position: relative;
    height: 80px;
    width: 80px;
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
    object-fit: cover;
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

      <form method="POST" action="<?= APP_URL ?>/admin/products/update" enctype="multipart/form-data">
        <?php $this->getAlert(); ?>

        <input type="text" name="product_id" hidden value="<?= $data['product_id'] ?? "" ?>">

        <div class="box box-widget">

          <div class="box-body">

            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                  <label for="product_name"> Name <span class="text-danger">*</span></label>
                  <input name="product_name" type="text" class="form-control " value="<?= $data['productInfo']['name'] ?? "" ?>" placeholder="Enter Name">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="brand_name"> Brand <span class="text-danger">*</span></label>
                  <select class="form-control select2" name="brand_id">
                    <option value="">-Select-</option>
                    <?php
                    foreach ($data['companies'] as $company) { ?>
                      <option <?= isset($data['productInfo']['brand_id']) && $data['productInfo']['brand_id'] == $company['id'] ? 'selected' : '' ?> value=" <?= $company['id'] ?>">
                        <?php echo $company['name']; ?>
                      </option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="category_name">Category Name <span class="text-danger">*</span></label>
                  <select class="form-control select2" name="category_id">
                    <option value="">-Select-</option>
                    <?php
                    foreach ($data['categories'] as $category) { ?>
                      <option <?= isset($data['productInfo']['category_id']) && $data['productInfo']['category_id'] == $category['id'] ? 'selected' : '' ?> value=" <?= $category['id'] ?>">
                        <?= $category['name'] ?>
                      </option>
                    <?php
                    }
                    ?>

                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="color_id"> Color Name <span class="text-danger">*</span></label>
                  <select class="form-control select2" name="color_id">
                    <option value="">-Select-</option>
                    <?php
                    foreach ($data['colors'] as $color) { ?>
                      <option <?= isset($data['productInfo']['color_id']) && $data['productInfo']['color_id'] == $color['id'] ? 'selected' : '' ?> value="<?= $color['id'] ?>">
                        <?= $color['name'] ?>
                      </option>
                    <?php
                    }
                    ?>

                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty"> Alert Qty</label>
                  <input name="minimum_qty" type="number" class="form-control" value="<?= $data['productInfo']['minimum_qty'] ?? '' ?>" placeholder="Alert Qty">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="unit">Unit <span class="text-danger">*</span></label>
                  <select class="form-control select2" name="unit_id">
                    <option value="">-Select-</option>
                    <?php
                    foreach ($data['units'] as $unit) { ?>
                      <option <?= isset($data['productInfo']['unit_id']) && $data['productInfo']['unit_id'] == $unit['id'] ? 'selected' : '' ?> value=" <?= $unit['id'] ?>">
                        <?= $unit['name'] ?>
                      </option>
                    <?php
                    }
                    ?>

                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="barcode"> Barcode</label>
                  <input name="barcode" type="text" class="form-control" value="<?= $data['productInfo']['barcode'] ?? '' ?>" placeholder="Barcode">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="expire"> Expire Date </label>
                  <input name="expire" type="date" class="form-control" value="<?= date_create($data['productInfo']['expire'])->format('Y-m-d') ?>" placeholder="Expire Date">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="description"> Description</label>
                  <textarea class="form-control textarea_resize_vertical" name="description" rows="1" placeholder="description"><?= $data['productInfo']['description'] ?? "" ?></textarea>
                </div>
              </div>
            </div>

            <hr>
            <div class="row">


              <div class="col-md-3">
                <div class="form-group">
                  <label for="price"> Price <span class="text-danger">*</span></label>
                  <input name="price" type="number" class="form-control price" value="<?= $data['productInfo']['price'] ?? "" ?>" placeholder="price">
                </div>
              </div>


              <div class="col-md-3">
                <div class="form-group">
                  <label for="tax">Tax <span class="text-danger">*</span></label>
                  <select class="form-control select2 tax" name="tax">
                    <option value="0">None</option>
                    <?php
                    foreach ($data['taxs'] as $tax) { ?>
                      <option <?= isset($data['productInfo']['tax']) && $data['productInfo']['tax'] == $tax['tax'] ? 'selected' : '' ?> value=" <?= $tax['tax']; ?>">
                        <?= $tax['name']; ?>(<?= $tax['tax']; ?>)
                      </option>
                    <?php
                    }
                    ?>

                  </select>
                </div>
              </div>


              <div class="col-md-3">
                <div class="form-group">
                  <label for="tax_type">Tax Type <span class="text-danger">*</span></label>
                  <select class="form-control select2 tax_type" name="tax_type">
                    <option <?= isset($data['productInfo']['tax_type']) && $data['productInfo']['tax_type'] == 'exclusive' ? 'selected' : '' ?> value="exclusive">Exclusive</option>
                    <option <?= isset($data['productInfo']['tax_type']) && $data['productInfo']['tax_type'] == 'inclusive' ? 'selected' : '' ?> value="inclusive">Inclusive</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="tax_amount">Tax Amount<span class="text-danger">*</span></label>
                  <input name="tax_amount" type="number" class="form-control seling_price" value="<?= $data['productInfo']['tax_amount'] ?? "" ?>" placeholder="Tax Amount" readonly>
                </div>
              </div>


              <div class="col-md-3">
                <div class="form-group">
                  <label for="seling_price">Seling Price <span class="text-danger">*</span></label>
                  <input name="seling_price" type="number" class="form-control seling_price" value="<?= $data['productInfo']['seling_price'] ?? "" ?>" placeholder="Seling price">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="seling_price">Final Buying Price</label>
                  <input type="number" class="form-control seling_price" value="<?= $data['productInfo']['buying_price'] ?? "" ?>" readonly placeholder="Seling price">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="seling_price">Final Seling Price</label>
                  <input type="number" class="form-control seling_price" value="<?= $data['productInfo']['final_seling_price'] ?? "" ?>" readonly placeholder="Seling price">
                </div>
              </div>

            </div>

            <hr>

            <div class="row">

              <div class="form-group col-md-3">
                <label for="discount">Discount</label>
                <input type="text" class="form-control " name="discount" value="<?= $data['productInfo']['discount'] ?? "" ?>">

              </div>


              <div class="form-group col-md-3">
                <label for="discount_type">Discount Type</label>
                <select class="form-control select2" name="discount_type">
                  <option <?= isset($data['productInfo']['tax_type']) && $data['productInfo']['discount_type'] == 'percent' ? 'selected' : '' ?> value="percent">Percentage(%)</option>
                  <option <?= isset($data['productInfo']['tax_type']) && $data['productInfo']['discount_type'] == 'fixed' ? 'selected' : '' ?> value="fixed">Fixed</option>
                </select>

              </div>

              <div class="form-group col-md-3">
                <label for="discount_amount">Discount Amount</label>
                <input type="text" class="form-control " name="discount_amount" value="<?= $data['productInfo']['discount_amount'] ?? "" ?>" readonly>

              </div>

              <div class="col-md-3">

                <div class="form-group">
                  <label for="image">Product Image</label>
                  <input name="inputfile" type="file" class="form-control inputfile" style="display: none !important;">
                  <div class="imagearea">
                    <img class=" img-thumbnail" src="<?= APP_URL ?>/public/images/product/<?= $data['productInfo']['image'] ?? "noimage.png" ?>" style="height: 80px ; width:">

                  </div>
                </div>
              </div>


            </div>

            <hr>

            <div class="row">
              <div class="form-group col-md-4">

                <label for="current_opening_stock">Current Opening Stock</label>
                <input type="text" class="form-control " name="current_opening_stock" placeholder="" readonly="" value="<?= $data['productInfo']['new_opening_stock'] ?? "" ?>">

              </div>
              <div class="form-group col-md-4">
                <label for="new_opening_stock">Adjust Stock <i class="hover-q " data-placement="top" data-toggle="tooltip" data-placement="top" title="Add(+) or Deduct(-) Stock, if you wanted to deduct then you should use a minimum symbol. (Eg: -10.00)" data-html="true" data-trigger="hover" data-original-title="">
                    <i class="fa fa-info-circle text-maroon text-black hover-q"></i>
                  </i></label>
                <input type="text" class="form-control" name="new_opening_stock" placeholder="-/+" value="">

              </div>
              <div class="form-group col-md-4">
                <label for="adjustment_note">Adjustment Note</label>
                <textarea type="text" class="form-control textarea_resize_vertical" rows="1" name="adjustment_note" placeholder=""></textarea>

              </div>
            </div>


          </div>
          <!-- /.box-body -->


          <div class="box-footer">

            <button type="submit" name="addProduct" class="btn bg-color">Update</button>
          </div>

        </div>

      </form>


      <div class="box box-widget">
        <div class="box-header">
          <h3 class="box-title text-blue">Opening Stock Adjustment Records</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

          <table class="table table-bordered table-hover " id="report-data">
            <thead>
              <tr class="bg-gray">
                <th>#</th>
                <th>Entry Date</th>
                <th>Stock</th>
                <th>Note</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              <?php
              $index = 1;
              if (empty($data['currentOpeningStocks'])) {
                echo '<tr><td colspan="5" class="text-center text-bold">No Previous Stock Entry Found!!</td> </tr>';
              } else {

                foreach ($data['currentOpeningStocks'] as $currentOpeningStock) { ?>

                  <tr>
                    <td><?= $index++; ?></td>
                    <td><?= date_create($currentOpeningStock['created'])->format('d M, Y') ?></td>
                    <td><?= $currentOpeningStock['stock'] ?? "" ?></td>
                    <td><?= $currentOpeningStock['adjustment_note'] ?? "" ?></td>
                    <td>
                      <a class="text-danger" href="javascript:runDelete(<?= $currentOpeningStock['id'] ?? "" ?>)"><i class="fa fa-trash"></i> Delete </a>
                    </td>

                  </tr>

              <?php }
              }

              ?>

            </tbody>
          </table>


        </div>
        <!-- /.box-body -->
      </div>


    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>

  <script>
    $('.select2').select2({
      placeholder: 'Select an option'
    });



    function runDelete(id) {
      let conf = confirm('Are you sure want to delete this?');
      if (conf) {
        window.location = '<?= APP_URL ?>/admin/products/openingstockdelete/' + id;
      }
    }
  </script>

  <script>
    $(document).ready(function() {

      var inputfile = $(".inputfile");

      $(".imagearea").on('click', function() {
        $(".inputfile").click();
      })

      $(".imagearea").on("dragover", function(event) {

        event.preventDefault();
        $(".imagearea").on("drop", function(event) {
          event.preventDefault();
          // File recieve_____
          image = event.originalEvent.dataTransfer.files[0];
          $(".inputfile").files = null;
          $(".inputfile").files = event.originalEvent.dataTransfer.files;
          image_display()
        });
      });

      $(".inputfile").on("change", function() {
        // File recieve_____
        image = event.srcElement.files[0]
        image_display();
      })

    });

    // Extra Function_______________

    function image_display() {

      let fileReader = new FileReader();

      fileReader.onload = function() {

        let imgTag = `<img src="${fileReader.result}" >`;

        $(".imagearea").html(imgTag);
      };

      fileReader.readAsDataURL(image);
    }
  </script>