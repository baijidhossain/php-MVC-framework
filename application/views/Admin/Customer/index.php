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

  .row_height {

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
                <a href="<?= APP_URL ?>/admin/customers/add" class="btn btn-sm btn-primary bg-color">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New customer
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
                <table class="table table-striped table-bordered  table-condensed mb-0">
                  <thead>
                    <tr>

                      <th style="width:60px"> Name</th>
                      <th style="width:60px">Phone</th>
                      <th style="width:120px">Address</th>
                      <th style="width:50px">Status</th>
                      <th style="width:50px">Opening Balance</th>
                      <th style="width:50px">Payable</th>
                      <th style="width:50px">Paid</th>
                      <th style="width:50px">Due</th>
                      <th style="width:80px">Action</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($data['customers']['paginateData'] as  $customer) { ?>
                      <tr class="item_row">

                        <td><?= $customer['name']; ?></td>

                        <td><?= $customer['phone']; ?></td>
                        <td>
                          <div class="line-clamp-1"><?= $customer['address']; ?></div>
                        </td>
                        <td><?= $customer['status']; ?></td>
                        <td><?= $customer['opening_balance']; ?></td>
                        <td><?= $customer['payable']; ?></td>
                        <td><?= $customer['paid']; ?></td>
                        <td><?= $customer['due']; ?></td>

                        <td class="text-center">

                          <div class="btn-group">

                            <button type="button" class="btn btn-sm bg-color dropdown-toggle" data-toggle="dropdown">
                              Action <span class="caret"></span>

                            </button>


                            <ul class="dropdown-menu dropdown-menu-right border">

                              <li>
                                <a href="<?= APP_URL ?>/admin/customers/edit/<?= $customer['id'] ?>"><i class="fa fa-edit"></i> Edit</a>
                              </li>

                              <li>
                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/customers/due_payment/<?= $customer['id'] ?>"><i class="fa fa-fw fa-money "></i> Pay Due Payment</a>
                              </li>

                              <li>
                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/customers/return_payment/<?= $customer['id'] ?>"><i class="fa fa-fw fa-money "></i> Pay Return Due </a>
                              </li>

                              <li>
                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/customers/delete/<?= $customer['id'] ?>"><i class="fa fa-fw fa-trash text-danger"></i> Delete </a>
                              </li>

                            </ul>
                          </div>

                        </td>

                      </tr>

                    <?php
                    }
                    ?>

                  </tbody>
                </table>
                <?php
                if (empty($data['customers'])) {
                  echo '<div class="text-red text-center">No data found!</div>';
                }
                ?>
              </div>
            </div>
            <div class="box-footer">
              <div class="row">

                <div class="col-md-6">
                  <?php
                  echo $data['customers']['paginateInfo'];
                  ?>
                </div>

                <div class="col-md-6">
                  <?php
                  echo $data['customers']['paginateNav'];
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
    $(".btn").on('click', function() {

      if ($('.item_row').length > 0) {
        $('.item_row').removeClass('row_height')
      }

      $(this).parent('div').parent('td').parent('tr').addClass('row_height');

    })


    $(window).click(function() {
      $('.item_row').removeClass('row_height')
    });

    $('.line-clamp-1').click(function() {

      $(this).removeClass('line-clamp-1');

    })
  </script>