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

            <div class="box-body">
              <div class="row">

                <div class="col-md-4">
                  <img class="img-thumbnail" width="300" height="300" src="<?= APP_URL ?>/public/images/product_image/<?php echo $data['view']['image'] ?>">
                  <br>
                  <br>

                  <?php
                  foreach ($data['sub_image'] as $key => $value) { ?>

                    <img class="img-thumbnail border" width="60" height="60" src="<?= APP_URL ?>/public/images/product_sub_image/<?php echo $data['sub_image'][$key]['name'] ?>" alt="">
                  <?php
                  }
                  ?>

                </div>

                <div class="col-md-8">
                  <h3>Product name: <?php echo $data['view']['name'] ?></h3>
                  <h3>Brand: <?php echo $data['view']['company'] ?></h3>
                  <h3>Available Color:

                    <?php
                    foreach ($data['color'] as $key => $value) { ?>

                      <span class="badge "><?php echo $data['color'][$key]['name']; ?></span>
                    <?php
                    }

                    ?>

                  </h3>
                </div>


                <div class="col-md-12 ">

                  <h2>Description</h2>
                  <?php echo nl2br($data['view']['description'])  ?>

                </div>

              </div>

            </div>
          </div>

        </div>

      </div>

    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>