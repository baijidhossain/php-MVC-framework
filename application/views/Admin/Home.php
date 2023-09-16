<?php include_once(VIEW_PATH . '_common/header.php'); ?>
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

 <?php include_once(VIEW_PATH . '_common/admin_top.php'); ?>
 <?php include_once(VIEW_PATH . '_common/navigation.php'); ?>

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

   <div class="row">
    <div class="col-md-8">
     <div class="box box-primary">
      <div class="box-header with-border">
       <h3 class="box-title">Demo</h3>
      </div>
      <div class="box-body">
       <h3 class="m-0">Simple Dashboard</h3>
      </div>
      <!-- /.box-body -->
     </div>
     <!-- /.box -->
    </div>
   </div>

  </section>
 </div>

 <?php include_once(VIEW_PATH . '_common/footer.php'); ?>