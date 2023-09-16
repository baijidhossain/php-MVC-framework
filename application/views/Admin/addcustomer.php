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


      <form method="POST" enctype="multipart/form-data" action="<?php APP_URL ?>/admin/customers/">
        <div style="margin-top: 20px;" class="row">
          <div class="col-md-6">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Add Customer</h3>
              </div>

              <div class="box-body">

                <div class="form-group">
                  <label for="exampleInputEmail1">Customer Type</label>
                  <select name="customer_type" class="form-control">
                    <option value="retail_buyer">Retail buyer</option>
                    <option value="wholesale_buyer">Wholesale buyer</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input value="" name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Email</label>
                  <input value="" name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Phone</label>
                  <input value="" name="phone" type="number" class="form-control" id="exampleInputEmail1" placeholder="Enter Phone">
                </div>


                <div class="form-group">
                  <label for="exampleInputEmail1">Opening Balance</label>
                  <input value="0" min="0" name="opening_balace" type="number" class="form-control" id="exampleInputEmail1" placeholder="Opening Balance">
                </div>



              </div>
              <!-- /.box-body -->

            </div>
          </div>



          <div class="col-md-6">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Address</h3>
              </div>

              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Division</label>
                      <select name="division" class="form-control">
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="rajshahi">Rajshahi</option>
                        <option value="khulna">Khulna</option>
                        <option value="mymensingh">Mymensingh</option>
                        <option value="barisal">Barisal</option>
                        <option value="Rangpur">Rangpur</option>
                        <option value="sylhet">Sylhet</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="exampleInputEmail1">District</label>
                      <input type="text" class="form-control" name="district" placeholder="District">
                    </div>

                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Upazila</label>
                      <input type="text" class="form-control" name="upazila" placeholder="Upazila">
                    </div>
                  </div>

                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Village</label>
                      <input type="text" class="form-control" name="village" placeholder="Village">
                    </div>

                  </div>

                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">ZIP</label>
                  <input type="text" class="form-control" name="zip" placeholder="Zip">
                </div>

                <div class="form-group">
                  <label for="address">Address</label>

                  <textarea name="address" class=" form-control" rows="2"></textarea>
                </div>


              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
      </form>





    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>