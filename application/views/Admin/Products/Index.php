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


      <div style="margin-top: 20px;" class="row">
        <div class="col-md-12">
          <div class="box box-widget">
            <div class="box-header with-border ">
              <h3 class="box-title">Product List</h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/products/add" class="btn btn btn-sm bg-color ">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New product
                </a>
              </div>

            </div>


            <div class="box-header ">

              <form action="" method="get" class="ajax_search bottom15 pull-right">
                <div class="input-group input-group-sm">
                  <input value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" type="search" name="search" class="form-control" placeholder="Search" style="width: 170px;" value="">
                  <div class="input-group-btn" style="width: 30px;">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped border  table-bordered ">
                  <thead class="bg-color">
                    <tr>
                      <th style="width: 130px;">Product Code</th>
                      <th style="width: 180px;" class=" text-center">Image</th>
                      <th style="width: 180px;">Product Name</th>
                      <th style="width: 125px;"> Brand</th>
                      <th style="width: 125px;"> Category</th>
                      <th style="width: 100px;"> Color</th>
                      <th style="width: 130px;"> Tax</th>
                      <th style="width: 125px;"> Purchase Price</th>
                      <th style="width: 130px;"> Discount</th>
                      <th style="width: 125px;">Selling Price</th>
                      <th style="width: 160px;"> Action</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($data['products']['paginateData'] as  $product) { ?>
                      <tr>
                        <td><?= $product['product_code']; ?></td>
                        <td class=" text-center"> <img loading="lazy" class="img-thumbnail" src="<?= APP_URL ?>/public/images/product/<?= $product['image'] ?? "noimage.png" ?>" alt="" style="height: 50px; width:50px; object-fit:cover;"> </td>
                        <td> <a href="<?= APP_URL ?>/admin/products/edit/<?= $product['id']; ?>"><?= $product['name']; ?></a></td>
                        <td><?= $product['brand']; ?></td>
                        <td><?= $product['category']; ?></td>
                        <td><?= $product['color']; ?></td>

                        <td>
                          <?= $product['tax']; ?>%
                          [<?= $product['tax_type']  ?>]
                        </td>

                        <td><?= $product['buying_price']; ?></td>

                        <td>

                          <?= $product['discount']; ?>

                          [<?= $product['discount_type']  ?>]
                        </td>

                        <td><?= $product['final_selling_price']; ?></td>
                        <td>
                          <a class=" text-purple" href="<?= APP_URL ?>/admin/products/edit/<?= $product['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>
                          &nbsp;
                          <a class="text-danger" href="javascript:runDelete(<?= $product['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>
                        </td>
                      </tr>

                    <?php
                    }
                    ?>

                  </tbody>
                </table>

              </div>
            </div>
            <div class="box-footer">
              <?php
              echo $data['products']['paginateNav'];
              echo $data['products']['paginateInfo'];
              ?>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>



  <script>
    function runDelete(id) {
      let conf = confirm('Are you sure want to delete this?');
      if (conf) {
        window.location = '<?= APP_URL ?>/admin/products/delete/' + id;
      }
    }
  </script>