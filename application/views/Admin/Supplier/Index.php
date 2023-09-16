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
                <a href="<?= APP_URL ?>/admin/suppliers/add" class="btn btn btn-sm bg-color">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New supplier
                </a>
              </div>

            </div>


            <div class="box-header ">

              <form action="" method="get" class="ajax_search bottom15 pull-right">

                <div class="input-group input-group-sm">

                  <input value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" type="search" name="search" class="form-control" placeholder="Search" style="width: 170px;" value="">

                  <div class="input-group-btn" style="width: 30px;">

                    <button type="submit" class="btn bg-color"><i class="fa fa-search"></i>
                    </button>

                  </div>

                </div>

              </form>
            </div>

            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped border  table-bordered table-condensed">
                  <thead class="bg-color">
                    <tr>


                      <th style="width:70px">Name</th>

                      <th style="width:80px">Phone</th>
                      <th style="width:90px">Address</th>

                      <th style="width:80px">Opening Receivable</th>
                      <th style="width:80px">Opening Balance</th>
                      <th style="width:50px">Payable</th>
                      <th style="width:50px">Paid</th>
                      <th style="width:50px">Due</th>

                      <th style="width:50px">Return Payable</th>
                      <th style="width:50px">Return Paid</th>
                      <th style="width:50px">Return Due</th>
                      <th style="width:60px"> Action</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($data['suppliers']['paginateData'] as  $supplier) { ?>
                      <tr>


                        <td><?= $supplier['name']; ?></td>

                        <td><?= $supplier['phone']; ?></td>

                        <td>
                          <div class="line-clamp-1"><?= $supplier['address']; ?></div>
                        </td>

                        <td><?= $supplier['opening_receivable']; ?></td>
                        <td><?= $supplier['opening_balance']; ?></td>
                        <td><?= $supplier['payable']; ?></td>
                        <td><?= $supplier['paid']; ?></td>
                        <td><?= $supplier['due']; ?></td>

                        <td><?= $supplier['return_payable']; ?></td>
                        <td><?= $supplier['return_paid']; ?></td>
                        <td><?= $supplier['return_due']; ?></td>

                        <td class="text-center">


                          <div class="btn-group btn-group-sm">

                            <button type="button" class="btn dropdown-toggle bg-color" data-toggle="dropdown" aria-expanded="false">
                              Action <span class="caret"></span>

                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                              <li>
                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/suppliers/view/<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i> View</a>
                              </li>

                              <li>
                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/suppliers/edit/<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>
                              </li>
                              <li>
                                <a href="javascript:runDelete('<?= APP_URL ?>/admin/suppliers/delete/<?= $supplier['id'] ?>')" class="text-red"><i class="fa fa-trash"></i> Delete</a>
                              </li>
                              <div class="divider"></div>
                              <li>

                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/suppliers/edit/<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i> Pay</a>
                              </li>

                              <li>
                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/suppliers/edit/<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i> Sale</a>
                              </li>

                              <li>
                                <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/suppliers/edit/<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i> Ledger Report</a>
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


    function runDelete(id) {
      let conf = confirm('Are you sure want to delete this?');
      if (conf) {
        window.location = "<?= APP_URL ?>/admin/purchase/delete_payment/" + id;
      }
    }
  </script>