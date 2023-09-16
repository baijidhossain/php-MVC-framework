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
              <h3 class="box-title"><?= $data['page_title'] ?></h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/state/add" class="btn btn btn-sm bg-color " data-toggle="modal" data-target="#myModal">
                  <i class="fa fa-plus"></i> &nbsp;
                  Add New State
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
                      <th style="width: 400px;">Country Name</th>
                      <th style="width: 400px;">State Name</th>
                      <th style="width: 100px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (empty($data['states'])) {
                      echo '<td colspan="12" class="text-danger">No Data Found</td>';
                    } else {
                      foreach ($data['states']['paginateData'] as  $state) { ?>
                        <tr>
                          <td><?= $state['country']; ?></td>
                          <td><?= $state['name']; ?></td>
                          <td>
                            <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/state/edit/<?= $state['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>
                            &nbsp;
                            <a href="javascript:runDelete(<?= $state['id'] ?? "" ?>)" class="text-red"><i class="fa fa-trash"></i> Delete</a>
                          </td>
                        </tr>

                    <?php
                      }
                    }
                    ?>

                  </tbody>
                </table>

              </div>
            </div>
            <div class="box-footer">
              <div class="row">

                <div class="col-md-6">
                  <?= $data['states']['paginateInfo']; ?>
                </div>

                <div class="col-md-6">
                  <?= $data['states']['paginateNav']; ?>
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
    function runDelete(id) {
      let conf = confirm('Are you sure want to delete this?');
      if (conf) {
        window.location = "<?= APP_URL ?>/admin/state/delete/" + id;
      }
    }




    $('.select2').select2({

      placeholder: 'Select an option'

    });

    $(document).ready(function() {

      $("select").change(function() {

        let place_id = $(this).find(":selected").val();

        let childClass = $(this).data().childClass;
        let methodName = $(this).data().methodName;

        console.log(methodName);

        $.ajax({
          type: "POST",
          url: "<?= APP_URL ?>/admin/customers/getPlace",
          data: {
            place_id: place_id,
            methodName: methodName
          },
          success: function(result) {
            console.log(result);
            $(`.${childClass}`).html(result);
          }
        });


      });

    })
  </script>