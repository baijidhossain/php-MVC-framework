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
          <div class="box box-primary">
            <div class="box-header with-border ">
              <h3 class="box-title"><?= $data['page_title'] ?></h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/users/addModal/" class="btn btn btn-sm btn-primary " data-toggle="modal" data-target="#myModal">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New User
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
                  <thead>
                    <tr>
                      <th style="width: 100px;">Name</th>
                      <th style="width: 100px;"> Group Name</th>
                      <th style="width: 80px;"> Email</th>
                      <th style="width: 80px;"> Phone</th>
                      <th style="width: 60px;"> Status</th>
                      <th style="width: 60px;">Created</th>
                      <th style="width: 100px;"> Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if (count($data['users']) < 1) : ?>

                      <tr>

                        <td colspan="15" class="notdata">No data found.</td>

                      </tr>

                    <?php else : ?>

                      <?php foreach ($data['users'] as $user) : ?>
                        <tr>
                          <td><?= $user['name'] ?></td>
                          <td><span title="<?= $user['group_name'] ?>" data-bs-toggle="tooltip"><?= $user['name'] ?></span></td>

                          <td><?= $user['email'] ?></td>

                          <td><?= $user['phone'] ?></td>
                          <td>
                            <?= ($user['status'] == 0 ? '<i class="fa fa-times-circle text_danger"></i>' : '<i class="fa fa-check-circle text-success"></i>'); ?>
                          </td>
                          <td><?= date_create($user['created'])->format('d M, Y'); ?></td>
                          <td>
                            <a data-bs-toggle="modal" data-bs-target="#ajaxModal" href="<?= APP_URL ?>/admin/users/ModifyModal/<?= $user['id'] ?>" class="text_skin me-1">
                              <i class="fa-regular fa-pen-to-square"></i> Modify
                            </a>

                            <a href='javascript:ChangeStatus(<?= "{$user['id']},{$user['status']}" ?>)' class="text_danger ms-2">
                              <?= ($user['status'] == 1  ? '<span class="text_danger"><i class="fa fa-times-circle"></i> Deactivate</span>'

                                : '<span class="text-success"><i class="fa fa-check-circle"></i> Activate</span>'); ?>
                            </a>
                          </td>
                        </tr>

                      <?php endforeach; ?>

                    <?php endif; ?>

                  </tbody>
                </table>

              </div>
            </div>
            <div class="box-footer">

            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>