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
            <li><a href="<?= APP_URL ?>/admin/article/"> Articles</a></li>
            <li class="active"><?= $this->data['page_title'] ?></li>
        </ol>
    </section>

    <section class="content">

		<?php $this->getMessage(); ?>

        <form action="" method="post" enctype="multipart/form-data">

            <div class="row ">
                <div class="col-md-8">
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">Article</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input name="title" type="text" class="form-control" placeholder="Title" id="title" maxlength="200">
                            </div>

                            <div class="form-group">
                                <label for="intro">Intro</label>
                                <textarea name="intro" style="resize:vertical" cols="3" class="form-control" id="intro" rows="5" placeholder="Intro"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="body">Body</label>
                                <textarea name="body" id="html_body" class="form-control" rows="5" placeholder="Body"></textarea>
                            </div>


                        </div>
                    </div>
                </div>


                <div class="col-md-4">

                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">Thumbnail</h3>
                        </div>

                        <div class="box-body">

                            <div class="drag">

                                <input name="inputfile" style="display: none;" type="file" class="inputfile" accept="image/*">

                                <div class="dragArea">
                                    <span class="dragHeader">
                                      <i class="fa fa-download"></i> <br>
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
                                <label for="category">Category</label>
                                <select name="category[]" data-placeholder="Select Category" class="form-control select2" multiple id="status">
									<?php

									function showCategories( $categories, $parent_id = 0, $char = '' ) {
										foreach ( $categories as $key => $item ) {

											if ( $item['parent_id'] == $parent_id ) : ?>


                                                <option value="<?= $item['id'] ?>"><?= $char . $item['name'] ?></option>

												<?php
												unset( $categories[ $key ] );

												showCategories( $categories, $item['id'], $char . '— ' );
											endif;
										}
									}

									showCategories( $this->data['categories'] );
									?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tag">Tag</label>
                                <select name="tag[]" data-placeholder="Select  Tag" class="select2 form-control " multiple="multiple" id="tag">

									<?php foreach ( $this->data['tags'] as $tag ) { ?>
                                        <option value="<?= $tag['id'] ?>"><?= $tag['name'] ?></option>
									<?php } ?>

                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Published </label>
                                        <input name="published" type="date" min="<?= date( "Y-m-d" ) ?>" value="<?= date( "Y-m-d" ) ?>" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Time</label>

                                        <input name="time" type="time" value="<?= date( "H:i" ) ?>" class="form-control">

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status">Path</label>
                                <input name="path" class="form-control" type="text" placeholder="Path">
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
                                <select name="status" class="form-control" id="status">
                                    <option value="1">Published</option>
                                    <option value="0">Unpublished</option>
                                    <option value="2">Trashed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button style="display: block;" name="SaveArticle" class=" btn-primary btn px-5 m-auto" type="submit">Add new
                    article
                </button>
            </div>
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
        CKEDITOR.plugins.addExternal('youtube', '<?= APP_URL ?>/public/youtube/', 'plugin.js');

        CKEDITOR.replace('html_body', {
            height: 560,
            filebrowserBrowseUrl: '<?= APP_URL ?>/admin/article/images/?type=Files',
            filebrowserImageBrowseUrl: '<?= APP_URL ?>/admin/article/images/?type=Images',
            extraPlugins: 'codemirror,youtube',
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
                    items: ['Link', 'Unlink', 'Image', 'Youtube']
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