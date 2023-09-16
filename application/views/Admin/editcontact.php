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
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add contact</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="POST" enctype="multipart/form-data" action="<?php APP_URL ?>admin/contacts/update">
              <div class="box-body">
                <input hidden value="<?= (isset($data)) ? $data['editdata']['id'] : ''; ?>" type="text" name="id">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input value="<?= $data['editdata']['name']; ?>" name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Email</label>
                  <input value="<?= $data['editdata']['email']; ?>" name="email" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Phone</label>
                  <input value="<?= $data['editdata']['phone']; ?>" name="phone" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Phone">
                </div>

                <div class="form-group">
                  <select class="form-control select2" name="" id="" multiple>
                    <option disabled selected value=""> -select- </option>
                    <?php
                    foreach ($data['group'] as $group) {
                      $selected = "";
                      foreach ($data['relation'] as $relations) {
                        if ($relations['group_id'] == $group['id']) {
                          $selected = "selected";
                        }
                      }
                    ?>
                      <option <?= $selected; ?> value=""><?php echo $group['name'] ?></option>
                    <?php
                    }
                    ?>
                  </select>

                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>

      </div>

    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>