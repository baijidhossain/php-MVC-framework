<?php include_once( VIEW_PATH . '_common/header.php' ); ?>

<body class="hold-transition sidebar-mini <?= SKIN_COLOR ?>">
<div class="wrapper">

<?php include_once( VIEW_PATH . '_common/panel_top.php' ); ?>
<?php include_once( VIEW_PATH . '_common/sidebar.php' ); ?>

<div class="content-wrapper">

    <section class="content-header">
        <h1><?= $this->data['page_title'] ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?= APP_URL ?>/admin/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= APP_URL ?>/admin/category/"> Categories</a></li>
            <li class="active"><?= $this->data['page_title'] ?></li>
        </ol>
    </section>

    <section class="content">

		<?php $this->getMessage(); ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Category</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="parent"> Parent Category</label>
                                <select name="parent_id" class="select2 form-control">
                                    <option value="0">-No Parent-</option>
									<?php function showCategories( $categories, $parent_id = 0, $char = '' ) {
										foreach ( $categories as $key => $item ) {

											if ( $item['parent_id'] == $parent_id ) { ?>

                                                <option value="<?= $item['id'] ?>"><?= $char . $item['name'] ?> </option>

												<?php unset( $categories[ $key ] );

												showCategories( $categories, $item['id'], $char . '— ' );
											}
										}
									}

									showCategories( $this->data['categories'] )
									?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="name"> Category Name</label>
                                <input type="text" class="form-control" name="name" required maxlength="50">
                            </div>

                            <div class="form-group">
                                <label for="name"> Description</label>
                                <textarea name="description" class="form-control" id="html_body" maxlength="255"></textarea>
                            </div>

                        </div>
                        <!-- /.box-body -->

                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-md-4">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Category Image</h3>
                        </div>

                        <div class="box-body">
                            <div class="drag">
                                <input name="image" style="display: none;" type="file" class="inputfile">
                                <span class="imagenameshow"></span>
                                <div class="dragArea">
                                    <span class="dragHeader">
                                      <i class="fa fa-download"></i>
                                      <br>
                                      <strong style="font-weight: 600 !important;"> Choose a file</strong> or drag it here.
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Publishing</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="path">Category Path</label>
                                <input type="text" name="path" class="form-control" id="">
                            </div>

                            <div class="form-group">
                                <label for="meta_keyword">Meta Keyword</label>
                                <textarea name="meta_keyword" id="" cols="30" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea name="meta_description" id="" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                            <label for="group"> Status</label><br>

                            <select name="status" class="form-control">
                                <option value="1">Published</option>
                                <option value="0">Unpublished</option>
                                <option value="2">Trashed</option>
                            </select>

                        </div>

                    </div>

                </div>

            </div>
            
            <button type="submit" class="btn btn-primary px-5 m-auto" style="display: block;" name="add">Add New Category</button>

        </form>

    </section>
</div>

<?php include_once( VIEW_PATH . '_common/footer.php' ); ?>

<script src="<?= APP_URL ?>/public/js/select2.min.js"></script>
<!-- ck editor -->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<!-- ckeditor -->

<script>

    $(document).ready(function () {

        $('.select2').select2();

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
                    items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight',
                        'JustifyBlock'
                    ]
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