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

  .line-clamp {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .trheight {

    height: 233px !important;
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
              <h3 class="box-title"><?= $data['page_title'] ?></h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/purchase_return/add" class="btn btn btn-sm  bg-color">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New purchase Return
                </a>
              </div>

            </div>


            <div class="box-header ">

              <form action="" method="get" class="ajax_search bottom15 pull-right">
                <div class="input-group input-group-md">
                  <input value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" type="search" name="search" class="form-control" placeholder="Search" style="width: 190px;" value="">
                  <div class="input-group-btn" style="width: 30px;">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered  table-condensed mb-0">
                  <thead class="bg-color">
                    <tr>
                      <th style="width: 300px;">Supplier</th>
                      <th style="width: 180px;">Bill No</th>
                      <th style="width: 200px;">Purchase Date</th>
                      <th style="width: 155px;">Return Status</th>

                      <th style="width: 150px;">Grand Total</th>
                      <th style="width: 150px;">Paid</th>
                      <th style="width: 150px;">Due</th>
                      <th style="width: 150px;">Payment Status</th>
                      <th class="text-center" style="width: 126px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php

                    if (empty($data['PurchaseReturns']['paginateData'])) {
                      echo '<tr><td  colspan="10" class="text-center">No Data Found</td></tr>';
                    } else {
                      foreach ($data['PurchaseReturns']['paginateData'] as  $purchase) { ?>

                        <tr class="purchaserow">
                          <td><?= $purchase['supplier']; ?></td>

                          <td> <?= $purchase['bill_no']; ?>

                          </td>
                          <td><?= date_create($purchase['purchase_date'])->format('d M, Y '); ?></td>
                          <td> <?= $purchase['status'] ?></td>

                          <td> <?= $purchase['grandtotal']; ?></td>
                          <td> <?= $purchase['paid']; ?></td>
                          <td> <?= $purchase['due']; ?></td>

                          <td>

                            <?php
                            if ($purchase['grandtotal'] == $purchase['paid']) { ?>
                              <span class="label label-primary">Paid</span>
                            <?php }

                            if ($purchase['paid'] < 1 && $purchase['due'] > 0) { ?>
                              <span class="label label-danger ">Unpaid</span>
                            <?php }

                            if ($purchase['paid'] < $purchase['grandtotal'] && $purchase['paid'] > 0) { ?>
                              <span class="label label-warning">Partial</span>
                            <?php }

                            ?>
                          </td>

                          <td class="text-center">
                            <div class="btn-group btn-group-sm">
                              <button type="button" class="btn  bg-color dropdown-toggle" data-toggle="dropdown">
                                Action <span class="caret"></span>

                              </button>
                              <ul class="dropdown-menu dropdown-menu-right border" role="menu">
                                <li><a href="<?= APP_URL ?>/admin/purchase_return/views/<?= $purchase['id'] ?>"> <i class="fa fa-eye text-purple"></i> View Purchase</a></li>
                                <li><a href="<?= APP_URL ?>/admin/purchase_return/return_edit/<?= $purchase['id'] ?? "" ?>"><i class="fa fa-edit text-purple"></i> Edit</a></li>
                                <li><a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/purchase_return/payment_view/<?= $purchase['id'] ?>"><i class="fa  fa-money text-purple"></i> View Payments</a></li>
                                <li><a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/purchase_return/paynow/<?= $purchase['id'] ?>"><i class="fa   fa-hourglass-half text-purple"></i> Pay Now</a></li>

                                <li><a href="<?= APP_URL ?>/admin/purchase_return/printinvoice/<?= $purchase['id'] ?? "" ?>"><i class="fa  fa-print text-purple"></i> Print</a></li>
                                <li><a href="<?= APP_URL ?>/admin/purchase_return/pdfPurchaseInvoice/<?= $purchase['id'] ?>"><i class="fa  fa-file-pdf-o text-purple"></i> PDF</a></li>

                                <li><a href="javascript:invoiceDelete(<?= $purchase['id'] ?? "" ?>)"><i class="fa  fa-trash text-purple"></i> Delete</a></li>
                              </ul>
                            </div>
                          </td>
                        </tr>

                    <?php


                      }
                    }
                    ?>

                  </tbody>

                  <tfoot class="text-bold bg-gray">

                    <tr>
                      <td colspan="4" class="text-right">Total</td>
                      <td><?= array_sum(array_column($data['PurchaseReturns']['paginateData'], 'grandtotal')) ?></td>
                      <td><?= array_sum(array_column($data['PurchaseReturns']['paginateData'], 'paid')) ?></td>
                      <td><?= array_sum(array_column($data['PurchaseReturns']['paginateData'], 'due')) ?></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>

              </div>
            </div>
            <div class="box-footer">
              <div class="row">

                <div class="col-md-6">
                  <?php
                  echo $data['PurchaseReturns']['paginateInfo'];
                  ?>
                </div>

                <div class="col-md-6">
                  <?php
                  echo $data['PurchaseReturns']['paginateNav'];
                  ?>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>

  <script>
    $(document).ready(function() {
      $(".line-clamp").click(function() {
        $(this).removeClass("line-clamp");
      });

    });


    function paymentDelete(id) {
      let conf = confirm('Are you sure want to delete payment?');
      if (conf) {
        window.location = "<?= APP_URL ?>/admin/purchase_return/delete_payment/" + id;
      }
    }


    function invoiceDelete(id) {
      let conf = confirm('Are you sure want to delete this?');
      if (conf) {
        window.location = "<?= APP_URL ?>/admin/purchase_return/delete_purchase/" + id;
      }
    }

    $(".btn").on('click', function() {

      if ($('.purchaserow').length > 0) {
        $('.purchaserow').removeClass('trheight')
      }

      $(this).parent('div').parent('td').parent('tr').addClass('trheight');

    })

    $(window).click(function() {
      $('.purchaserow').removeClass('trheight')
    });
  </script>