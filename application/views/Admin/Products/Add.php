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

      <form method="POST" action="<?= APP_URL ?>/admin/products/add" enctype="multipart/form-data">
        <?php $this->getAlert(); ?>

        <div class="box box-widget">

          <div class="box-body">

            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                  <label for="product_name"> Name <span class="text-danger">*</span></label>
                  <input name="product_name" type="text" class="form-control " placeholder="Enter Name">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="brand_name"> Brand <span class="text-danger">*</span></label>
                  <select class="form-control select2" name="brand_id">
                    <option value="">-Select-</option>
                    <?php
                    foreach ($data['companies'] as $company) { ?>
                      <option value=" <?php echo $company['id']; ?>">
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
                      <option value=" <?php echo $category['id']; ?>">
                        <?php echo $category['name']; ?>
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
                      <option value="<?php echo $color['id']; ?>">
                        <?php echo $color['name']; ?>
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
                  <input name="minimum_qty" type="number" class="form-control" placeholder="Enter Qty">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="unit">Unit<span class="text-danger">*</span></label>
                  <select class="form-control select2" name="unit_id">
                    <option value="">-Select-</option>
                    <?php
                    foreach ($data['units'] as $unit) { ?>
                      <option value=" <?php echo $unit['id']; ?>">
                        <?php echo $unit['name']; ?>
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
                  <input name="barcode" type="text" class="form-control" placeholder="Barcode">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="expire"> Expire Date </label>
                  <input name="expire" type="date" class="form-control" value="<?= date('Y-m-d') ?>" placeholder="Expire Date">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="description"> Description</label>
                  <textarea class="form-control textarea_resize_vertical" name="description" rows="1" placeholder="description"></textarea>
                </div>
              </div>
            </div>

            <hr>
            <div class="row">


              <div class="col-md-3">
                <div class="form-group">
                  <label for="price"> Price <span class="text-danger">*</span></label>
                  <input name="price" type="number" class="form-control price" placeholder="price">
                </div>
              </div>


              <div class="col-md-3">
                <div class="form-group">
                  <label for="tax">Tax <span class="text-danger">*</span></label>
                  <select class="form-control select2 tax" name="tax">
                    <option value="0">None</option>
                    <?php
                    foreach ($data['taxs'] as $tax) { ?>
                      <option value=" <?= $tax['tax']; ?>">
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

                    <option value="exclusive">Exclusive</option>
                    <option value="inclusive">Inclusive</option>
                  </select>
                </div>
              </div>


              <div class="col-md-3">
                <div class="form-group">
                  <label for="selling_price">Selling Price <span class="text-danger">*</span></label>
                  <input name="selling_price" type="number" class="form-control selling_price" placeholder="Selling price">
                </div>
              </div>
            </div>

            <hr>

            <div class="row">

              <div class="form-group col-md-3">
                <label for="discount">Discount</label>
                <input type="text" class="form-control " name="discount" value="0">
              </div>


              <div class="form-group col-md-3">
                <label for="discount_type">Discount Type</label>
                <select class="form-control select2" name="discount_type">
                  <option value="percent">Percentage(%)</option>
                  <option value="fixed">Fixed</option>
                </select>

              </div>



              <div class="col-md-3">

                <div class="form-group">
                  <label for="image">Product Image</label>
                  <input name="inputfile" type="file" class="form-control inputfile" style="display: none !important;">
                  <div class="imagearea">
                    <i class="fa fa-download"></i>
                  </div>
                </div>
              </div>


            </div>

            <hr>

            <div class="row">
              <div class="form-group col-md-4">
                <label for="current_opening_stock">Current Opening Stock</label>
                <input type="text" class="form-control " name="current_opening_stock" placeholder="" readonly="" value="0">

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

            <button type="submit" name="addProduct" class="btn bg-color">Save</button>
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
              <tr>
                <td colspan="5" class="text-center text-bold">No Previous Stock Entry Found!!</td>
              </tr>
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