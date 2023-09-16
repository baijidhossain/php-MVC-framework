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

            <div class="box-header with-border">
              <h3 class="box-title">Stock Reports</h3>
              <form action="<?= APP_URL ?>/admin/reports/stock" method="get" class="ajax_search bottom15 pull-right">
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
                <table class="table table-striped table-bordered table-condensed">
                  <thead class="bg-color">

                    <tr>
                      <th style="width: 107px;">Product Code</th>
                      <th style="width: 190px;">Product Name</th>
                      <th style="width: 135px;">Current Buying Price</th>

                      <th style="width: 135px;">Final Selling Price</th>
                      <th style="width: 120px;">Opening Stock</th>
                      <th style="width: 111px;">Total Stock</th>
                      <th style="width: 120px;">Current Stock</th>
                      <th style="width: 111px;">Total Sold</th>
                      <th style="width: 120px;">Potential Profit</th>
                      <th style="width: 120px;">Total Stock Value</th>

                    </tr>

                  </thead>
                  <tbody>

                  <tbody>
                    <?php
                    foreach ($data['stocks']['paginateData'] as  $stock) { ?>
                      <tr>
                        <td><?= $stock['product_code']; ?></td>
                        <td> <a href="<?= APP_URL ?>/admin/products/edit/<?= $stock['product_id']; ?>"><?= $stock['name']; ?></a></td>
                        <td><?= $stock['buying_price']; ?></td>

                        <td><?= $stock['final_selling_price']; ?></td>
                        <td><?= $stock['new_opening_stock']; ?></td>
                        <td><?= $stock['total_stock']; ?></td>
                        <td><?= $stock['current_stock']; ?></td>
                        <td><?= $stock['total_sold']; ?></td>
                        <td><?= ($stock['final_selling_price'] * $stock['current_stock']) - ($stock['buying_price'] * $stock['current_stock'])   ?></td>
                        <td><?= $stock['stock_value']; ?></td>

                      </tr>

                    <?php
                    }
                    ?>

                  </tbody>
                  </tbody>
                </table>

              </div>
            </div>
            <div class="box-footer">
              <?= $data['stocks']['paginateNav'] ?>
              <?= $data['stocks']['paginateInfo'] ?>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>