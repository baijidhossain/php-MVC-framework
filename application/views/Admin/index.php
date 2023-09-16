<?php include_once(VIEW_PATH . '_common/header.php'); ?>

<body class="hold-transition sidebar-mini <?= SKIN_COLOR ?>">
  <div class="wrapper">

    <?php include_once(VIEW_PATH . '_common/panel_top.php'); ?>
    <?php include_once(VIEW_PATH . '_common/sidebar.php'); ?>

    <div class="content-wrapper">

      <section class="content-header">
        <h1><?= $this->data['page_title'] ?></h1>
        <ol class="breadcrumb">
          <li><a href="<?= APP_URL ?>/account/onAuthenticate"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active"><?= $this->data['page_title'] ?></li>
        </ol>
      </section>

      <section class="content">

        <?php $this->getMessage(); ?>

        <div class="row">

          <div class="col-md-12">

            <div class="row">

              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-list"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Categories</span>
                    <span class="info-box-number"><?= $this->data['allCount']['total_categories'] ?? "0" ?></span>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-red">
                    <i class="fa fa-newspaper-o"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Posts</span>
                    <span class="info-box-number"><?= $this->data['allCount']['total_posts'] ?? "0" ?></span>
                  </div>
                </div>
              </div>

              <div class="clearfix visible-sm-block"></div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-green"><i class="fa fa-comments"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Comments</span>
                    <span class="info-box-number"><?= $this->data['allCount']['total_comments'] ?? "0" ?></span>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Users</span>
                    <span class="info-box-number"><?= $this->data['allCount']['total_users'] ?? "0" ?></span>
                  </div>
                </div>
              </div>

            </div>
            <!-- /.box -->
          </div>

          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Recent Posts</h3>
              </div>

              <div class="box-body">
                <div class="table-responsive mb-0">
                  <table class="table table-bordered table-striped mb-0 v-align-middle">
                    <thead>

                      <tr>

                        <th style="width: 300px;"><i class="fa fa-paragraph" aria-hidden="true"></i> Title</th>
                        <th style="width: 200px;"><i class="fa fa-list-alt"></i> Category</th>
                        <th style="width: 50px;"><i class="fa fa-clock-o" aria-hidden="true"></i> Hits</th>
                        <th style="width: 100px;"><i class="fa fa-cog"></i> Status</th>
                        <th style="width: 150px;"><i class="fa fa-calendar" aria-hidden="true"></i> Created</th>
                        <th style="width:123px;">Action</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php

                      if (empty($this->data['articles'])) {
                        echo "<tr><td colspan='10' class='text-center'>No Article Found</td></tr>";
                      } else {


                        foreach ($this->data['articles'] as $article) {
                      ?>
                          <tr>

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
                              <?= date_create($article['created'])->format('d M, Y h:i A') ?>
                            </td>

                            <td style=" white-space: nowrap;">

                              <a href="<?= APP_URL ?>/admin/article/edit/<?php echo $article['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>&nbsp;
                              <a class="text-red" href="javascript:articleDelete(<?= $article['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>

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

            </div>
          </div>

          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Recent Comments</h3>
                <div class="box-tools">

                </div>
              </div>

              <div class="box-body">
                <div class="table-responsive mb-0">
                  <table class="table table-bordered table-striped mb-0 v-align-middle">

                    <thead>
                      <tr>
                        <th style="width: 130px;"><i class="fa fa-user"></i> User</th>
                        <th style="width: 275px;"><i class="fa fa-comments" aria-hidden="true"></i> Comment</th>
                        <th style="width: 350px;"><i class="fa fa-list-alt"></i> In response to</th>
                        <th style="width: 120px;"><i class="fa fa-cog " aria-hidden="true"></i> Status</th>
                        <th style="width: 150px;"><i class="fa fa-clock-o"></i> DateTime</th>
                        <th style="width:150px;">Action</th>

                      </tr>
                    </thead>

                    <tbody>

                      <?php

                      if (empty($this->data['comments'])) { ?>
                        <tr>
                          <td class="text-center" colspan="10">No Data Found</td>
                        </tr>
                        <?php } else {

                        foreach ($this->data['comments'] as $comment) { ?>
                          <tr>
                            <td> <a href="<?= APP_URL ?>/admin/comments/?user=<?= $comment['uid'] ?>"><?= $comment['name'] ?></a> </td>
                            <td><?= $comment['comment'] ?></td>
                            <td>
                              <a href="<?= APP_URL ?>/article/<?= $comment['path'] ?>" target="_blank"><?= $comment['article_title'] ?></a>
                            </td>
                            <td>
                              <?php
                              if ($comment['status'] == 1) {
                                echo "<span class='text-success'> Approved </span>";
                              }
                              if ($comment['status'] == 0) {
                                echo "<span class='text-danger'> Pending </span>";
                              }

                              ?>
                            </td>

                            <td>
                              <?= !empty($comment['updated'])
                                ? date_create($comment['updated'])->format("d M, Y H:i A")
                                : date_create($comment['created'])->format("d M, Y H:i A") ?>
                            </td>


                            <td>

                              <?php
                              if ($comment['status'] == 0) { ?>
                                <a class="text-success me-2" href="javascript:approve(<?= $comment['id'] ?>)"><i class="fa fa-check-square-o"></i> Approve</a>&nbsp;
                              <?php } ?>

                              <a class="me-2" data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/comments/edit/<?= $comment['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>
                              <a class="text-red" href="javascript:commentDelete(<?= $comment['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>
                            </td>

                          </tr>


                      <?php }
                      } ?>

                    </tbody>

                  </table>

                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

            </div>
          </div>

        </div>

      </section>

    </div>

    <?php require_once(VIEW_PATH . '_common/footer.php'); ?>


    <script>
      function articleDelete(id) {
        let conf = confirm('Are you sure want to delete this?');
        if (conf) {
          window.location = "<?= APP_URL ?>/admin/article/delete/" + id;
        }
      }


      function approve(id) {
        let conf = confirm('Are you sure want to approve this?');
        if (conf) {
          window.location = "<?= APP_URL ?>/admin/comments/approve/" + id;
        }
      }

      function commentDelete(id) {
        let conf = confirm('Are you sure want to delete this?');
        if (conf) {
          window.location = "<?= APP_URL ?>/admin/comments/delete/" + id;
        }
      }
    </script>