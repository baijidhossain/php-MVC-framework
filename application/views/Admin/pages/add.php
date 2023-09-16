<?php include_once( VIEW_PATH . '_common/header.php' ); ?>

<body class="hold-transition sidebar-mini <?= SKIN_COLOR ?>">
<div class="wrapper">

<?php include_once( VIEW_PATH . '_common/panel_top.php' ); ?>
<?php include_once( VIEW_PATH . '_common/sidebar.php' ); ?>

  <div class="content-wrapper">

    <section class="content-header">
      <h1><?= $this->data['page_title'] ?></h1>
      <ol class="breadcrumb">
        <li><a href="<?= APP_URL ?>/admin/pages/"><i class="fa fa-dashboard"></i> Page</a></li>
        <li class="active"><?= $this->data['page_title'] ?></li>
      </ol>
    </section>



    <section class="content">

      <?php $this->getMessage(); ?>

      <form action="<?= APP_URL ?>/admin/pages/add" method="post" enctype="multipart/form-data">

        <div class="row ">
          <div class="col-md-8">
            <div class="box box-primary">

              <div class="box-header with-border">
                <h3 class="box-title">Page Info</h3>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="title">Title</label>
                  <input name="title" type="text" class="form-control" placeholder="Title" id="title">
                </div>

                <div class="form-group">
                  <label for="body">Body</label>
                  <textarea name="body" id="html_body" class="form-control" rows="5" placeholder="Body" required></textarea>
                </div>

              </div>
            </div>
          </div>


          <div class="col-md-4">


            <div class="box box-primary">

              <div class="box-header with-border">
                <h3 class="box-title">Publishing</h3>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="status">Path</label>
                  <input name="path" class="form-control" type="text" required>
                </div>

                <div class="form-group">
                  <label for="status">Meta Keyword</label>

                  <textarea name="meta_keyword" style="resize:vertical" class="form-control" type="text" id="meta_KeyWord" rows="2" placeholder="Meta Keyword"></textarea>
                </div>


                <div class="form-group">
                  <label for="status">Meta Description</label>
                  <textarea name="meta_description" style="resize:vertical" class="form-control" type="text" id="meta_description" rows="2" placeholder="Meta Description"></textarea>
                </div>


                <div class="form-group">
                  <label for="status">Status</label>
                  <select name="status" class="form-control" id="status" required>
                    <option value="1">Published</option>
                    <option value="0">Unpublished</option>
                  </select>
                </div>
              </div>
            </div>
          </div>


        </div>

        <div>

          <button style="display: block;" class=" btn-primary btn px-5 m-auto" type="submit">Add New Page</button>
        </div>


      </form>

    </section>







  </div>

<?php include_once( VIEW_PATH . '_common/footer.php' ); ?>

  <!-- ck editor -->
  <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
  <!-- ckeditor -->

  <script>

    $(document).ready(function() {

        CKEDITOR.plugins.addExternal('codemirror', '<?= APP_URL ?>/public/codemirror/', 'plugin.js');

        CKEDITOR.replace('html_body', {
            height: 560,
            extraPlugins: 'codemirror',
            toolbar: [{
                name: 'document',
                items: ['Source']
            },
                {
                    name: 'clipboard',
                    items: ['PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                },
                {
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup'],
                    items: ['Bold', 'Italic', 'Underline', 'Strike']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align'],
                    items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                },
                {
                    name: 'insert',
                    items: ['Link', 'Unlink', 'Image']
                },

                {
                    name: 'styles',
                    items: ['Styles', 'Format', 'Font', 'FontSize']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                },
                {
                    name: 'tools',
                    items: ['Maximize']
                }
            ],


        });


    });
  </script>