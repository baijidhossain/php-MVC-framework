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
        <li><a href="<?= APP_URL ?>/admin/account/onAuthenticate"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?= $data['page_title'] ?></li>
      </ol>
    </section>

    <section class="content">

      <?php $this->getAlert(); ?>


      <div style="margin-top: 20px;" class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border ">
              <h3 class="box-title">List contact</h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/contacts/addmodal" class="btn btn btn-sm btn-primary " data-toggle="modal" data-target="#myModal">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New Contact
                </a>
              </div>

            </div>


            <div class="box-header ">

              <form action="<?= APP_URL ?>/admin/contacts/index" method="get" class="ajax_search bottom15 pull-right">
                <div class="input-group input-group-sm">
                  <input type="search" name="search" class="form-control" placeholder="Search" style="width: 170px;" value="<?= (!empty($_GET['search'])) ? $_GET['search'] : ''; ?>">

                  <select name="group" class="form-control input-sm" style="width: 130px;">
                    <option value="">-Selected-</option>
                    <?php
                    foreach ($data['allgroup'] as $group) { ?>
                      <option value="<?= $group['id'] ?>" <?php
                                                          if (isset($_GET['group'])) {
                                                            if ($group['id'] == $_GET['group']) {
                                                              echo "selected";
                                                            }
                                                          }
                                                          ?>>
                        <?= $group['name'] ?>
                      </option>
                    <?php
                    }
                    ?>
                  </select>
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
                      <th><i class="fa fa-user"></i> id</th>
                      <th><i class="fa fa-user"></i> Name</th>
                      <th class="text-center"><i class="fa fa-camera"></i> Photo</th>
                      <th><i class="fa fa-envelope"></i> Email</th>
                      <th><i class="fa fa-phone"></i> Phone</th>
                      <th><i class="fa fa-map-marker"></i> Address</th>
                      <th><i class="fa fa-users"></i> Group</th>
                      <th><i class="fa fa-wrench"></i> Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    foreach ($data['contacts']['paginateData'] as $contacts) { ?>
                      <tr>
                        <td><?php echo $contacts['id'] ?></td>
                        <td><?php echo $contacts['name'] ?></td>
                        <td class="text-center ">
                          <img class="img-thumbnail " width="50" height="50" src="
                           <?php
                            $img_path = !$contacts['photo'] ? "no-profile.jpg" : "contactimg/" . $contacts['photo'];
                            echo APP_URL . '/public/images/' . $img_path;
                            ?>
                        " alt="" srcset="">
                        </td>
                        <td><?php echo $contacts['email'] ?></td>
                        <td><?php echo $contacts['phone'] ?></td>
                        <td><?php echo $contacts['address'] ?></td>
                        <td>
                          <?php
                          foreach ($data['groups'] as $gAndcgr) {

                            if ($contacts['id'] == $gAndcgr['contact_id']) {

                          ?>
                              <span class=" badge bg-teal"><?php echo $gAndcgr['name']; ?></span>

                          <?php
                            }
                          }
                          ?>
                        </td>
                        <td>
                          <a href="<?= APP_URL ?>/admin/contacts/editModal/<?php echo $contacts['id'] ?>" data-toggle="modal" data-target="#myModal"> <i class="fa fa-pencil"></i> Edit</a>
                          &nbsp;

                          <a class="text-red" href="javascript:runDelete('<?= APP_URL ?>/admin/contacts/delete/<?php echo $contacts['id'] ?>')"> <i class="fa fa-trash"></i> Delete</a>

                        </td>
                      </tr>
                    <?php
                    }
                    ?>

                  </tbody>
                </table>
                <?php
                if (empty($data['contacts'])) {
                  echo ' <div class="text-center text-red ">No data found! </div>';
                }
                ?>
              </div>
            </div>
            <div class="box-footer">
              <div class="row">

                <div class="col-md-6">
                  <?php
                  echo $data['contacts']['paginateInfo'];
                  ?>
                </div>

                <div class="col-md-6">
                  <?php
                  echo $data['contacts']['paginateNav'];
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

  