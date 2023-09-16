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

        <form action="<?= APP_URL ?>/admin/article/update" method="post" enctype="multipart/form-data">

            <div class="row bg-equa">
                <div class="col-md-8">
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">Article</h3>
                        </div>
                        <input name="id" value="<?= ! empty( $this->data['article']['id'] ) ? $this->data['article']['id'] : '' ?>" type="hidden">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input name="title" value="<?= $this->data['article']['title'] ?? '' ?>" type="text" class="form-control" maxlength="200">
                            </div>

                            <div class="form-group">
                                <label for="intro">Intro</label>
                                <textarea name="intro" style="resize:vertical" class="form-control" id="intro" rows="5" placeholder="Intro"><?= $this->data['article']['intro'] ?? '' ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="body">Body</label>
                                <textarea name="html_body" id="editor" class="form-control" type="text" id="body" rows="5" placeholder="editor"><?= $this->data['article']['body'] ?? '' ?></textarea>
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

                                <input name="inputfile" style="display:none;" type="file" class="inputfile">

                                <div class="dragArea  <?= ! empty( $this->data['article']['thumb'] ) ? 'dragAreaActive' : '' ?>  ">
									<?php
									if ( ! empty( $this->data['article']['thumb'] ) ) { ?>
                                        <img style="width:100%;" src="<?= APP_URL ?>/public/images/article/thumbnail/<?= $this->data['article']['thumb'] ?>" alt="">
                                        <a class="btn btn-primary change"> <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a>
										<?php
									} else { ?>

                                        <span class="dragHeader">
                        <i class="fa fa-download"></i>
                        <br>
                        <strong style="font-weight: 600 !important;"> Choose a file</strong> or drag it here.
                      </span>
										<?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="box box-primary">

              <div class="box-header with-border">
                <h3 class="box-title">Publishing</h3>
              </div>

              <div class="box-body">
                <!-- category -->
                <div class="form-group">
                  <label for="category">Category</label>
                  <select name="category[]" data-placeholder="Select  Category" id="" class="select2 form-control" multiple>

                    <?php

                    function showCategories($categories, $relationcategory, $parent_id = 0, $char = '')
                    {
                      foreach ($categories as $key => $item) {
                        $selectedCate = "";

                        foreach ($relationcategory as $category_id) {
                          if ($item['id'] == $category_id['category_id']) {
                            $selectedCate = "selected";
                          }
                        }

                        if ($item['parent_id'] == $parent_id) : ?>
                          <option <?= $selectedCate ?> value="<?= $item['id'] ?>"><?= $char . $item['name'] ?></option>
                    <?php

                          unset($categories[$key]);
                          showCategories($categories, $relationcategory, $item['id'], $char . '— ');

                        endif;
                      }
                    }
                    showCategories($this->data['categories'], $this->data['categoryRelation']);
                    ?>

                  </select>
                </div>

                <!-- tag -->
                <div class="form-group">
                  <label for="tag">Tag</label>

                  <select name="tag[]" data-placeholder="Select  Tag" id="" class="select2 form-control" multiple>
                    <?php

                    foreach ($this->data['tags'] as $tag) {
                      $selectedTag = "";
                      foreach ($this->data['tagRelation'] as $key => $tagrelations) {
                        if ($tag['id'] == $tagrelations['tag_id']) {
                          $selectedTag = "selected";
                        }
                      }

                    ?>
                      <option <?= $selectedTag ?> value="<?= $tag['id'] ?>"><?= $tag['name'] ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>

                  <!-- published -->
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="status">Published</label>


                              <input name="published" type="date" value="<?= date_create( $this->data['article']['published'] )->format( "Y-m-d" ) ?>" class="form-control">
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="status">Time</label>

                              <input name="time" type="time" value="<?= date_create( $this->data['article']['published'] )->format( "H:i" ) ?>" class="form-control">

                          </div>
                      </div>
                  </div>

                  <!-- path -->
                  <div class="form-group">
                      <label for="status">Path</label>
                      <input name="path" value="<?= ! empty( $this->data['article']['path'] ) ? $this->data['article']['path'] : '' ?>" class="form-control" type="text" placeholder="Path">
                  </div>

                  <!-- meta key words -->
                  <div class="form-group">
                      <label for="status">Meta Keyword</label>
                      <textarea name="meta_keyword" style="resize:vertical" class="form-control" type="text" id="meta_keyword" rows="2" placeholder="Meta Keyword"><?= $this->data['article']['meta_keyword'] ?></textarea>
                  </div>
                  <!-- meta description -->
                  <div class="form-group">
                      <label for="status">Meta Description</label>
                      <textarea name="meta_description" style="resize:vertical" class="form-control" type="text" id="meta_description" rows="2" placeholder="Meta Description"><?= $this->data['article']['meta_description'] ?></textarea>
                  </div>

                  <!-- status -->
                  <div class="form-group">
                      <label for="status">Status</label>
                      <select name="status" class="form-control" id="status">
                          <option <?= $this->data['article']['status'] == 1 ? 'selected' : '' ?> value="1">Published</option>
                          <option <?= $this->data['article']['status'] == 0 ? 'selected' : '' ?> value="0">Unpublished</option>
                          <option <?= $this->data['article']['status'] == 2 ? 'selected' : '' ?> value="2">Trashed</option>
                      </select>

                  </div>
              </div>
            </div>
                </div>

            </div>
            <div class="form-group float-left">
                <button style="display:block" name="update_article" class="btn btn-primary px-5 m-auto" type="submit">Update Article</button>
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
        CKEDITOR.plugins.addExternal('codemirror', '<?= APP_URL ?>/public/codemirror/', 'plugin.js');
        CKEDITOR.plugins.addExternal('youtube', '<?= APP_URL ?>/public/youtube/', 'plugin.js');

        CKEDITOR.replace('html_body', {
            height: 567,
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
                    items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
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

        $('.select2').select2();

    });
</script>