<?php include_once(VIEW_PATH . '_common/header.php'); ?>
<!--index view-->
<?php if ($data['view_type'] == 'view') : ?>
 <div class="wrapper">
  <style>
   .tools a {
    white-space: nowrap;
   }

   .text-green {
    color: #198754 !important;
   }
  </style>
  <?php include_once(VIEW_PATH . '_common/admin_top.php'); ?>
  <?php include_once(VIEW_PATH . '_common/navigation.php'); ?>

  <div class="content-wrapper">
   <section class="content-header">
    <h1>
     <?= $data['page_title']; ?>
    </h1>
    <ol class="breadcrumb">
     <li><a href="/admin/"><i class="fa fa-dashboard"></i> Home</a></li>
     <li class="active"><?= $data['page_title']; ?></li>
    </ol>
   </section>

   <section class="content">
    <?php $this->getAlert(); ?>

    <div class="row">
     <div class="col-md-12">
      <div class="box box-primary">
       <div class="box-header with-border">
        <h3 class="box-title bn">All Permissions</h3>
       </div>
       <div class="box-body">
        <div class="table-responsive">
         <table class="table table-striped table-inverse table-responsive">
          <?php
          if (count($data['paths']) < 1) : ?>
           <td colspan="15" class="notdata">No data found</td>
          <?php else : ?>
           <thead class="thead-inverse">
            <tr>
             <th style="width: 50%">Action</th>
             <th style="width: 50%">Permissions</th>
            </tr>
           </thead>
           <tbody>
            <?php
            foreach ($data['paths'] as $nav) {
             echo "
                                            <tr>
                                                <td><a data-target='#myModal' data-toggle='modal' href='/admin/permissions/edit/" . urlencode($nav["action"]) . "' 
                                                class='" . (isset($nav['id']) ? 'text-green' : 'text-black') . "' >$nav[action]</a></td><td>";
             if (!empty($nav['pid'])) {
              foreach ($nav['pid'] as $pid) {
               echo "<span class='badge nav_group' style='margin-right: 5px;'>{$data['groups'][$pid]}</span>";
              }
             }
            }
            ?>
           <?php endif; ?>
           </tbody>
         </table>
        </div>
       </div>
      </div>
     </div>
    </div>
   </section>
  </div>
  <?php include_once(VIEW_PATH . '_common/footer.php'); ?>
  <!--    if modal view-->
 <?php elseif ($data['view_type'] == 'modal') : ?>
  <div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   <h4 class="modal-title"><?= $data['modal_title']; ?></h4>
  </div>
  <form action="" method="post" autocomplete="off">
   <div class="modal-body row">
    <div class="col-md-10 col-md-offset-1">
     <input type="hidden" name="id" value="<?= $data['navinfo']['id'] ?? '' ?>">
     <div class="form-group">
      <label>Path</label>
      <input name="nav_path" id="nav_path" type="text" class="form-control" value="<?= urldecode($data['navinfo']['action']) ?>" required>
     </div>
     <div class="form-group">
      <b>Access Control</b>
      <?php
      foreach ($data['groups'] as $group) {
       echo "
                        <div class='checkbox icheck'>
                            <label>
                                <input class='icheckInput' type='checkbox' name='permissions[]' value='$group[id]'";
       foreach ($data['navinfo']['pid'] as $pid) {
        echo $pid == $group['id'] ? 'checked' : '';
       }
       echo "> <span style='vertical-align: middle; margin-left: 2px;'>$group[group_name]</span>
                            </label>
                        </div>
                    ";
      }
      ?>
     </div>
    </div>
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" name="<?= (isset($data['navinfo']['id']) && !empty($data['navinfo']['id']) ? 'update' : 'store') ?>" class="btn btn-primary">Confirm
    </button>
   </div>
  </form>

 <?php endif; ?>

 <!-- iCheck -->
 <link rel="stylesheet" href="<?= APP_URL ?>/public/css/icheck_blue.css">
 <!-- iCheck -->
 <script src="<?= APP_URL ?>/public/js/icheck.min.js"></script>
 <script>
  $(function() {
   $('.icheckInput').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' /* optional */
   });
  });
 </script>