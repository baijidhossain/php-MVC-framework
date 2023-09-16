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

    height: 260px !important;
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
                <a href="<?= APP_URL ?>/admin/sales/add" class="btn btn btn-sm  bg-color">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New Sale
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
                      <th style="width: 300px;">Customer</th>
                      <th style="width: 180px;">Bill No</th>
                      <th style="width: 200px;">Sales Date</th>
                      <th style="width: 155px;">Sales Status</th>

                      <th style="width: 150px;">Grand Total</th>
                      <th style="width: 150px;">Paid</th>
                      <th style="width: 150px;">Due</th>
                      <th style="width: 150px;">Payment Status</th>
                      <th class="text-center" style="width: 126px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php

                    if (empty($data['sales']['paginateData'])) {
                      echo '<tr><td  colspan="10" class="text-center">No Data Found</td></tr>';
                    } else {
                      foreach ($data['sales']['paginateData'] as  $sale) { ?>

                        <tr class="purchaserow">
                          <td><?= $sale['customer']; ?></td>

                          <td><?= $sale['bill_no']; ?>

                            <?php
                            if ($sale['return_status'] == "Return" || $sale['return_status'] == "Cancel") { ?>
                              <span class="label label-danger" style="cursor:pointer"><i class="fa fa-fw fa-undo"></i>Return Raised</span>

                            <?php }

                            ?>

                          </td>
                          <td><?= date_create($sale['sales_date'])->format('d M, Y '); ?></td>
                          <td><?php

                              if ($sale['status'] == "Final") {
                                echo 'Final';
                              } elseif ($sale['status'] == "Quotation") {
                                echo 'Quotation';
                              }

                              ?>

                          </td>

                          <td> <?= $sale['grandtotal']; ?></td>
                          <td> <?= $sale['paid']; ?></td>
                          <td> <?= $sale['due']; ?></td>

                          <td>

                            <?php
                            if ($sale['grandtotal'] == $sale['paid']) { ?>
                              <span class="label label-primary">Paid</span>
                            <?php }

                            if ($sale['paid'] < 1 && $sale['due'] > 0) { ?>
                              <span class="label label-danger ">Unpaid</span>
                            <?php }

                            if ($sale['paid'] < $sale['grandtotal'] && $sale['paid'] > 0) { ?>
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
                                <li><a href="<?= APP_URL ?>/admin/sales/views/<?= $sale['id'] ?>"> <i class="fa fa-eye text-purple"></i> View Sales</a></li>
                                <li><a href="<?= APP_URL ?>/admin/sales/sales_edit/<?= $sale['id'] ?? "" ?>"><i class="fa fa-edit text-purple"></i> Edit</a></li>
                                <li><a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/sales/payment_view/<?= $sale['id'] ?>"><i class="fa  fa-money text-purple"></i> View Payments</a></li>
                                <li><a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/sales/pay_now/<?= $sale['id'] ?>"><i class="fa   fa-hourglass-half text-purple"></i> Pay Now</a></li>

                                <li><a href="<?= APP_URL ?>/admin/sales/printinvoice/<?= $sale['id'] ?? "" ?>" target="_blank"><i class="fa  fa-print text-purple"></i> Print</a></li>
                                <li><a href="<?= APP_URL ?>/admin/sales/pdfSalesInvoice/<?= $sale['id'] ?>" target="_blank"><i class="fa  fa-file-pdf-o text-purple"></i> PDF</a></li>
                                <li><a href="<?= APP_URL ?>/admin/sales/return/<?= $sale['id'] ?? "" ?>"> <i class="fa  fa-undo text-purple"></i> Sales Return</a></li>
                                <li><a href="javascript:invoiceDelete(<?= $sale['id'] ?? "" ?>)"><i class="fa  fa-trash text-purple"></i> Delete</a></li>

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
                      <td><?= array_sum(array_column($data['sales']['paginateData'], 'grandtotal')) ?></td>
                      <td><?= array_sum(array_column($data['sales']['paginateData'], 'paid')) ?></td>
                      <td><?= array_sum(array_column($data['sales']['paginateData'], 'due')) ?></td>
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
                  echo $data['sales']['paginateInfo'];
                  ?>
                </div>

                <div class="col-md-6">
                  <?php
                  echo $data['sales']['paginateNav'];
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


    function deletePayment(id) {
      let conf = confirm('Are you sure want to delete payment?');
      if (conf) {
        window.location = "<?= APP_URL ?>/admin/sales/deletePayment/" + id;
      }
    }


    function invoiceDelete(id) {
      let conf = confirm('Are you sure want to delete this?');
      if (conf) {
        window.location = "<?= APP_URL ?>/admin/sales/delete_sale/" + id;
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