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
        <li class="active"><?= $this->data['page_title'] ?></li>
      </ol>
    </section>

    <section class="content">

      <?php $this->getMessage(); ?>

      <div class="row">
        <div class="col-sm-12">
          <form action="" method="get">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Search Articles</h3>
              </div>
              <div class="box-body ">
                <div class="row my-3">

                  <div class="col-lg-3  col-md-6">
                    <div class="form-group">
                      <input name="title" value="<?= $_GET['title'] ?? '' ?>" type="text" class="form-control" placeholder="Title">
                    </div>

                  </div>



                  <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                      <select name="category" class="form-control select2" id="">
                        <option value="">--Select Category--</option>
                        <?php

                        function showCategories($categories, $parent_id = 0, $char = '')
                        {
                          foreach ($categories as $key => $item) {

                            if ($item['parent_id'] == $parent_id) : ?>


                              <option <?php
                                      if (isset($_GET['category'])) {
                                        if ($_GET['category'] == $item['id']) {
                                          echo "selected";
                                        }
                                      }

                                      ?> value="<?= $item['id'] ?>"><?= $char . $item['name'] ?></option>

                        <?php
                              unset($categories[$key]);

                              showCategories($categories, $item['id'], $char . '— ');
                            endif;
                          }
                        }
                        showCategories($this->data['categories']);
                        ?>

                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                      <select name="tag" class="form-control select2" id="">
                        <option value="">--Select Tag--</option>
                        <?php foreach ($this->data['tags'] as $tag) { ?>
                          <option value="<?= $tag['id'] ?>" <?= (isset($_GET['tag']) && $tag['id'] == $_GET['tag']) ? "selected" : '' ?>><?= $tag['name'] ?></option>
                        <?php  } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-4">
                    <div class="form-group">

                      <select name="status" class="form-control" >
                        <option value="">--Select status--</option>

                        <option <?= (isset($_GET['status']) && $_GET['status'] == 1) ?'selected' : ''?> name="status" value="1">Published</option>
                        <option <?= (isset($_GET['status']) && $_GET['status'] === '0') ?'selected' : ''?> name="status" value="0">Unpublished</option>
                        <option <?= (isset($_GET['status']) && $_GET['status'] == 2) ?'selected' : ''?> name="status" value="2">Trashed</option>
                      </select>

                    </div>

                  </div>

                  <div class="col-lg-1 col-md-4">
                    <div class="form-group">
                      <input value="Search" class="form-control btn btn-primary" type="submit">
                    </div>
                  </div>

                  <div class="col-lg-1 col-md-4">
                    <div class="form-group">
                      <input onclick="window.location.href = window.location.pathname" name="reset" value="Reset" class="form-control btn btn-default" type="Reset">
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->
            </div>
          </form>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">List of Articles</h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/article/add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Article</a>
              </div>
            </div>

            <div class="box-body">
              <div class="table-responsive mb-0">
                <table class="table table-bordered table-striped mb-0 v-align-middle">
                  <thead>
                    <tr>
                        <th style="width: 100px;"><i class="fa fa-picture-o "></i> Thumbnail</th>
                        <th style="width: 300px;"><i class="fa fa-paragraph" aria-hidden="true"></i> Title</th>
                        <th style="width: 200px;"><i class="fa fa-list-alt"></i> Category</th>
                        <th style="width: 50px;"><i class="fa fa-clock-o" aria-hidden="true"></i> Hits</th>
                        <th style="width: 100px;"><i class="fa fa-cog"></i> Status</th>
                        <th style="width: 150px;"><i class="fa fa-calendar" aria-hidden="true"></i> Created</th>
                        <th style="width: 150px;"><i class="fa fa-wrench"></i> Action</th>
                    </tr>
                  </thead>

                  <tbody>

                    <?php

                    if (empty($this->data['articles']['items'])) {
                      echo "<tr><td colspan='10' class='text-center'>No Article Found</td></tr>";
                    } else {


                      foreach ($this->data['articles']['items'] as $article) {
                    ?>
                        <tr>

                          <td class="text-center">
                            <?php
                            if (!empty($article['thumb'])) { ?>
                              <img class="thumbnail mb-0" style="display: inline;height: 100px; max-width:100px; width:100%;object-fit:cover;" src="<?= APP_URL ?>/public/images/article/thumbnail/<?= $article['thumb'] ?>" alt="">
                            <?php
                            } else {
                              echo '<i style="opacity: 0.4;font-size:80px;" class="fa fa-image mb-0"></i>';
                            }
                            ?>
                          </td>

                          <td>
                            <a href="<?= APP_URL ?>/admin/article/edit/<?php echo $article['id'] ?>">
                              <?= $article['title'] ?>
                            </a>
                          </td>

                          <td>
                            <?php
                            $article_categories = explode(',', $article['categories']);
                            foreach ($article_categories as $category) { ?>
                              <span class="badge bg-aqua"> <?= ucwords($category) ?></span>
                            <?php
                            }
                            ?>
                          </td>

                          <td>
                            <?= $article['hits'] ?>
                          </td>

                          <td>
                            <?php
                            if ($article['status'] == 1) {
                              echo ' <span class="badge label-success">Published</span>';
                            } elseif ($article['status'] == 2) {

                              echo '<span class="badge" >Trashed</span>';
                            } else {
                              echo ' <span class="badge label-danger" >Unpublished</span>';
                            }
                            ?>
                          </td>

                          <td style=" white-space: nowrap;">
	                          <?= date_create($article['created'])->format('d F, Y h:i A') ?>
                          </td>

                          <td style=" white-space: nowrap;">
                            <a href="<?= APP_URL ?>/admin/article/edit/<?php echo $article['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>&nbsp;
                            <a class="text-red" href="javascript:runDelete(<?= $article['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>
                          </td>

                        </tr>

                    <?php }
                    }
                    ?>
                  </tbody>
                </table>

              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <div class="box-footer clearfix">

              <?= $this->data['articles']['paginateNav'] ?>

              <?= $this->data['articles']['paginateInfo'] ?>

            </div>
          </div>
        </div>

    </section>
  </div>

<?php include_once(VIEW_PATH . '_common/footer.php'); ?>
<script src="<?= APP_URL ?>/public/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {

      $('.select2').select2();

    });
  </script>

  <!-- delete -->
  <script>
    function runDelete(id) {
      let conf = confirm('Are you sure want to delete this?');
      if (conf) {
        window.location = "<?= APP_URL ?>/admin/article/delete/" + id;
      }
    }
  </script>