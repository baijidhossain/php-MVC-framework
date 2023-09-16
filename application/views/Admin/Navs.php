<?php include_once(VIEW_PATH . '_common/header.php'); ?>
<?php if ($data['view_type'] == 'view') : ?>
 <!-- iCheck -->
 <link rel="stylesheet" href="<?= APP_URL ?>/public/css/icheck_blue.css">
 <style>
  .nav-table .draglist {
   border-radius: 2px;
   padding: 10px;
   background: #f4f4f4;
   margin-bottom: 2px;
   border-left: 2px solid #e6e7e8;
   color: #444;
  }

  .nav-table,
  .nav-table ul {
   list-style: none;
   padding: 0;
  }

  .nav-table>li .tools {
   float: right;
   color: #dd4b39;
  }

  .nav-table>li .tools i {
   margin-right: 10px;
  }

  .draglist:hover {
   background-color: #ddd;
   cursor: move;
  }

  .nav-table .nav_icon {
   margin-right: 8px;
  }

  .nav-table .nav_name,
  .nav-table .nav_path {
   width: 300px;
   display: inline-block;
  }

  .nav-table ul {
   margin-left: 35px;
  }

  .sub_nav li .nav_name {
   width: calc(300px - 35px);
  }

  .ui-sortable {
   position: relative;
  }

  .drag-placeholder {
   background-color: #f9f979 !important;
   box-sizing: border-box;
   -moz-box-sizing: border-box;
   min-height: 30px;
   min-width: 30px;
   /*border: 1px dashed #000;*/
  }

  .ui-sortable-helper>.sub_nav {
   min-height: unset !important;
   border: none !important;
  }

  .tools a {
   white-space: nowrap;
  }
 </style>
 <div class="wrapper">

  <?php include_once(VIEW_PATH . '_common/admin_top.php'); ?>
  <?php include_once(VIEW_PATH . '_common/navigation.php'); ?>
  <link rel="stylesheet" href="<?= APP_URL ?>/public/css/select2.min.css">
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
       <div class="box-header">
        <h3 class="box-title bn">All Navigations</h3>
        <div class="box-tools pull-right">
         <a href="<?= APP_URL ?>/admin/navs/add/" data-toggle="modal" data-target="#myModal" class="box-top btn btn-sm btn-primary py-0004"><i class="fa fa-plus"></i>
          Add New Menu</a>
        </div>
       </div>
       <div class="box-body">
        <?php
        if (count($data['navs']) < 1) : ?>
         <li>Not Found</li>
        <?php else : ?>

         <?php function buildMenuFromArray(
          $menu_array,
          $groups,
          $parent_id = 0,
          $parentClass = "",
          $subClass = ""
         ) {
          $childItems = [];
          foreach ($menu_array as $key => $item) {
           if ($item['parent_id'] == $parent_id) {
            $childItems[] = $item;
            unset($menu_array[$key]);
           }
          }

          if ($childItems) {
           echo "<ul class='{$parentClass}'>";

           foreach ($childItems as $key => $item) : ?>
            <li id="<?= $item['id'] ?>" data-parent_id="<?= $item['parent_id'] ?>">
             <div class='draglist'>
              <span class="nav_icon">
               <i class="<?= $item['nav_icon'] ?>"></i>
              </span>
              <span class="nav_name"><?= $item['nav_name'] ?></span>
              <span class="nav_path"><?= $item['nav_path'] ?></span>
              <?php
              if (isset($item['group_id'])) {
               foreach ($item['group_id'] as $gid) {
                echo "<span class='badge nav_group' style='margin-right: 5px;' >{$groups[$gid]}</span>";
               }
              }
              ?>
              <div class="tools">
               <a data-target="#myModal" data-toggle="modal" href='<?= APP_URL .
                                                                    "/admin/navs/edit/$item[id]" ?>'>
                <i class='fa fa-edit'></i>
               </a>
               <a href="javascript:deleteItem('<?= $item['id'] ?>' , '<?= APP_URL ?>/admin/navs/delete')">
                <i class='fa fa-trash-o text-danger'></i>
               </a>
              </div>

             </div>

             <?php buildMenuFromArray(
              $menu_array,
              $groups,
              $item['id'],
              $subClass,
              $subClass
             ); ?>

            </li>
        <?php endforeach;

           echo '</ul>';
          } else {
           echo "<ul class='{$subClass}'></ul>";
          }
         }

         buildMenuFromArray(
          $data['navs'],
          $data['groups'],
          0,
          'nav-table ui-sortable',
          'sub_nav'
         );
        endif;
        ?>


       </div>
       <div class="box-footer clearfix">
        <a href="javascript:updateNav()" class="btn btn-primary pull-right">
         Save</a>
       </div>
      </div>
     </div>
    </div>

   </section>
  </div>
  <form style="display:none;" id="hidden_form" action="" method="post">
   <input type="hidden" name="id" id="hidden_input" value="">
  </form>

  <?php include_once(VIEW_PATH . '_common/footer.php'); ?>
  <script>
   function updateNav() {
    var id = [];
    var parent_id = [];
    // find all li and store id and parent_id to variables
    $('.nav-table .ui-sortable-handle').each(function() {
     id.push($(this).attr('id'));
     parent_id.push($(this).data('parent_id'));
    });
    // clear undefined values from array
    id = id.filter(Boolean);
    parent_id = parent_id.filter(function(element) {
     return element !== undefined;
    });

    // send values to controller
    $.ajax({
     url: "/admin/navs/UpdateNav/",
     method: "POST",
     data: {
      id,
      parent_id
     },
     success: function(data) {
      window.location.reload();
     }
    });
   }

   function deleteItem(id, action) {
    var conf = confirm("Are you sure want to remove this data?");
    if (conf) {
     $("#hidden_input").val(id);
     $("#hidden_form").attr('action', action).submit();
    }
   }
  </script>
  <!-- iCheck -->
  <script src="<?= APP_URL ?>/public/js/icheck.min.js"></script>
  <script src="<?= APP_URL ?>/public/js/select2.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script>
   $(document).ready(function() {
    $(".ui-sortable.nav-table").sortable({
     connectWith: '.ui-sortable.nav-table, .sub_nav',
     cursor: "move",
     axis: "y",
     placeholder: 'drag-placeholder',
     start: function(e, ui) {
      ui.placeholder.height(ui.item.height());
      $('.sub_nav').css({
       'minHeight': '20px',
       'border': '1px dashed lightgray',
       'padding': '10px 0'
      });
      $('.sub_nav .sub_nav').css({
       'minHeight': '20px',
       'border': '1px dashed green',
       'padding': '10px 0'
      });
     },
     update: function(event, ui) {
      setIds();
     },
     stop: function(event, ui) {
      $('.sub_nav').css({
       'minHeight': 'unset',
       'border': 'none',
       'padding': '0px'
      });
      $('.sub_nav .sub_nav').css({
       'minHeight': 'unset',
       'border': 'none',
       'padding': '0px'
      });
     }
    });

    $(".ui-sortable .sub_nav").sortable({
     connectWith: '.ui-sortable.nav-table, .sub_nav',
     cursor: "move",
     axis: "y",
     placeholder: 'drag-placeholder',
     start: function(e, ui) {
      ui.placeholder.height(ui.item.height());
      $('.nav-table').css({
       'minHeight': '20px',
       'border': '1px dashed lightgray',
       'padding': '10px 0'
      });
      $('.sub_nav').css({
       'minHeight': '20px',
       'border': '1px dashed lightgray',
       'padding': '10px 0'
      });
      $('.sub_nav .sub_nav').css({
       'minHeight': '20px',
       'border': '1px dashed green',
       'padding': '10px 0'
      });
     },
     update: function(event, ui) {
      setIds();
     },
     stop: function(event, ui) {
      $('.nav-table').css({
       'minHeight': 'unset',
       'border': 'none',
       'padding': '0px'
      });
      $('.sub_nav').css({
       'minHeight': 'unset',
       'border': 'none',
       'padding': '0px'
      });
      $('.sub_nav .sub_nav').css({
       'minHeight': 'unset',
       'border': 'none',
       'padding': '0px'
      });
     }
    });

    function setIds() {
     // set direct children attr parent_id 0
     $('.nav-table > li').each(function() {
      $(this).attr('data-parent_id', '0')
     });

     // set sub ul parent_id as his parent id attr
     $('.sub_nav').each(function() {
      let pid = $(this).parent().attr('id');
      if (pid !== undefined) {
       $(this).children().attr('data-parent_id', pid)
      }
     });

    }
   });
  </script>
 <?php elseif ($data['view_type'] == 'modalEdit') : ?>
  <div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
   </button>
   <h4 class="modal-title"><?= $data['page_title']; ?></h4>
  </div>
  <form action="" method="post" autocomplete="off">
   <div class="modal-body row">
    <input type="hidden" name="id" value="<?= $data['navinfo']['id'] ?>">
    <div class="col-md-10 col-md-offset-1">
     <div class="form-group">
      <label>Icon</label>
      <select style="cursor:pointer;" class="form-control fa" name="nav_icon" id="select2">
       <?php
       foreach ($data['icons'] as $icon => $content) {
        echo "<option value='fa $icon' data-icon='fa $icon' " . ($data['navinfo']['nav_icon'] == ('fa ' . $icon) ? 'selected' : '') . ">&#x$content; $icon</option>";
       }
       ?>
      </select>
     </div>

     <div class="form-group">
      <label>Parent</label>
      <select class="form-control" name="parent_id">
       <option value="0">- No Parent -</option>
       <?php
       foreach ($data['navParents'] as $parent) {
        echo "<option value='$parent[id]'" .
         ($data['navinfo']['parent_id'] == $parent['id']
          ? 'selected' : '') . " >$parent[nav_name]</option>";
       }
       ?>
      </select>
     </div>
     <div class="form-group">
      <label>Name</label>
      <input name="nav_name" id="nav_name" type="text" class="form-control" value="<?= $data['navinfo']['nav_name'] ?>" required>
     </div>
     <div class="form-group">
      <label>Path</label>
      <input name="nav_path" id="nav_path" type="text" class="form-control" value="<?= $data['navinfo']['nav_path'] ?>" required>
     </div>
     <div class="col-md-6">
      <div class="form-group">
       <b>View Permission</b>
       <?php
       foreach ($data['groups'] as $group) {
        echo "
                                    <div class='checkbox icheck'>
                                        <label>
                                            <input class='icheckInput' type='checkbox' name='group_id[]' value='$group[id]'";
        if (isset($data['navinfo']['group_id'])) {
         foreach ($data['navinfo']['group_id'] as $gid) {
          echo $gid === $group['id'] ? 'checked' : '';
         }
        }
        echo "> <span style='vertical-align: middle; margin-left: 2px;'>$group[group_name]</span>
                                        </label>
                                    </div>
                                ";
       }
       ?>
      </div>
     </div>
     <div class="col-md-6">
      <?php
      if (strpos($_SERVER['REQUEST_URI'], 'add') !== false) : ?>
       <div class="form-group">
        <b>Access Control</b>
        <?php
        foreach ($data['groups'] as $group) {
         echo "
                        <div class='checkbox icheck'>
                            <label>
                                <input class='icheckInput' type='checkbox' name='permission[]' value='$group[id]'";
         foreach ($data['navinfo']['pid'] as $pid) {
          echo $pid === $group['id'] ? 'checked' : '';
         }
         echo "> <span style='vertical-align: middle; margin-left: 2px;'>$group[group_name]</span>
                            </label>
                        </div>
                    ";
        }
        ?>
       </div>
      <?php endif; ?>

     </div>

    </div>
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" name="update" class="btn btn-primary">Confirm</button>
   </div>
  </form>
  <script>
   $(function() {
    $('.icheckInput').iCheck({
     checkboxClass: 'icheckbox_square-blue',
     radioClass: 'iradio_square-blue',
     increaseArea: '20%' /* optional */
    });

    function formatText(icon) {
     return $('<span><i class="' + $(icon.element).data('icon') + '"></i> ' + $(icon.element).data('icon') + '</span>');
    }

    $('#select2').select2({
     width: "100%",
     templateSelection: formatText,
     templateResult: formatText,
     dropdownParent: $("#myModal")
    });
   });
  </script>
 <?php elseif ($data['view_type'] == 'modalAdd') : ?>
  <div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
   </button>
   <h4 class="modal-title"><?= $data['page_title']; ?></h4>
  </div>
  <form action="" method="post" autocomplete="off">
   <div class="modal-body row">
    <div class="col-md-10 col-md-offset-1">
     <div class="form-group">
      <label for="select2">Icon</label>
      <select style="cursor:pointer;" class="form-control fa" name="nav_icon" id="select2">
       <?php
       foreach ($data['icons'] as $icon => $content) {
        echo "<option value='fa $icon' data-icon='fa $icon'>&#x$content; $icon</option>";
       }
       ?>
      </select>
     </div>

     <div class="form-group">
      <label>Parent</label>
      <select class="form-control" name="parent_id">
       <option value="0">- No Parent -</option>
       <?php foreach ($data['navParents'] as $parent) {
        echo "<option value='$parent[id]'>$parent[nav_name]</option>";
       }
       ?>
      </select>
     </div>
     <div class="form-group">
      <label>Name</label>
      <input name="nav_name" id="nav_name" type="text" class="form-control" required>
     </div>
     <div class="form-group">
      <label>Path</label>
      <input name="nav_path" id="nav_path" type="text" class="form-control" required>
     </div>
     <div class="col-md-6">
      <div class="form-group">
       <b>View Permission</b>
       <?php
       foreach ($data['groups'] as $group) {
        echo "
                                    <div class='checkbox icheck'>
                                        <label>
                                            <input class='icheckInput' type='checkbox' name='group_id[]' value='$group[id]'> 
                                            <span style='vertical-align: middle; margin-left: 2px;'>$group[group_name]</span>
                                        </label>
                                    </div>
                                    ";
       }
       ?>
      </div>
     </div>

    </div>
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" name="add" class="btn btn-primary">Confirm</button>
   </div>
  </form>
  <script>
   $(function() {
    $('.icheckInput').iCheck({
     checkboxClass: 'icheckbox_square-blue',
     radioClass: 'iradio_square-blue',
     increaseArea: '20%' /* optional */
    });

    function formatText(icon) {
     return $('<span><i class="' + $(icon.element).data('icon') + '"></i> ' + $(icon.element).data('icon') + '</span>');
    }

    $('#select2').select2({
     width: "100%",
     templateSelection: formatText,
     templateResult: formatText,
     dropdownParent: $("#myModal")
    });

   });
  </script>
 <?php endif; ?>