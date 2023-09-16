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
        <li><a href="<?= APP_URL ?>admin/account/onAuthenticate"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?= $data['page_title'] ?></li>
      </ol>
    </section>

    <section class="content">

      <?php $this->getAlert(); ?>


      <div style="margin-top: 20px;" class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border ">
              <h3 class="box-title"><?= $data['page_title'] ?></h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>admin/contact_groups/addgm" class="btn btn btn-sm btn-primary " data-toggle="modal" data-target="#myModal">
                  <i class="fa fa-plus"></i>&nbsp;&nbsp;
                  Add
                </a>
              </div>

            </div>

            <div class="box-header ">

              <form action="<?= APP_URL ?>Admin/contact_groups/index" method="get" class="ajax_search bottom15 pull-right">
                <div class="input-group input-group-sm">
                  <input type="search" name="search" class="form-control" placeholder="Search" style="width: 170px;" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
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
                  <thead>
                    <tr>
                      <th><i class="fa fa-user"></i> Name</th>
                      <th><i class="fa fa-users"></i> Contact list</th>
                      <th><i class="fa fa-wrench"></i> Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    foreach ($data['groups']['paginateData'] as $group) {
                    ?>
                      <tr>
                        <td>
                          <?php
                          echo $group['name'];
                          ?>
                        </td>
                        <td>
                          <?php echo $group['total_contact']; ?>
                        </td>
                        <td>
                          <a href="<?= APP_URL ?>admin/contact_groups/editgm/<?php echo $group['id'] ?>" data-toggle="modal" data-target="#myModal"> <i class="fa fa-pencil"></i> Edit</a>
                          &nbsp;&nbsp;
                          <!-- <a class="text-red" href="<?= APP_URL ?>/contact/delete/<?php echo $group['id'] ?>"> <i class="fa fa-trash"></i> Delete</a> -->
                          <a class="text-red" href="javascript:runDelete('<?= APP_URL ?>admin/contact_groups/delete/<?php echo $group['id'] ?>')"> <i class="fa fa-trash"></i> Delete</a>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>

                <?php
                if (empty($data['groups'])) {
                  echo ' <div class="text-center text-red ">No data found! </div>';
                }
                ?>
              </div>
            </div>
            <div class="box-footer">
              <div class="row">

                <div class="col-md-6">
                  <?php
                  echo $data['groups']['paginateInfo'];
                  ?>
                </div>

                <div class="col-md-6">
                  <?php
                  echo $data['groups']['paginateNav'];
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