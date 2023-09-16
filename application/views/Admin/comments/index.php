<?php include_once(VIEW_PATH . '_common/header.php'); ?>

<body class="hold-transition sidebar-mini <?= SKIN_COLOR ?>">
  <div class="wrapper">

    <?php include_once(VIEW_PATH . '_common/panel_top.php'); ?>
    <?php include_once(VIEW_PATH . '_common/sidebar.php'); ?>

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
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Search Comment</h3>

              </div>

              <div class="box-body">

                <form action="" method="get">
                  <div class="row ">

                    <div class="col-md-3 ">
                      <div class="form-group">
                        <label for="user">User</label>
                        <select name="user" class="form-control select2">
                          <option value="">--Select User--</option>
                          <?php
                          foreach ($this->data['users'] as $user) { ?>
                            <option <?= isset($_GET['user']) && $_GET['user'] == $user['id'] ? 'selected' : '' ?> value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                          <?php } ?>
                        </select>

                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="user">Status</label>
                        <select name="status" class="form-control">
                          <option value="">--Select Status--</option>
                          <option <?= isset($_GET['status']) && $_GET['status'] == "1" ? 'selected' : '' ?> value="1">Published</option>
                          <option <?= isset($_GET['status']) && $_GET['status'] == "0" ? 'selected' : '' ?> value="0">Unpublished</option>
                        </select>

                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="user">Article</label>
                        <input type="text" class="form-control" placeholder="Article" name="article" value="<?= $_GET['article'] ?? "" ?>">
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group text-center">
                        <label for="search">&nbsp;</label>
                        <input type="submit" class="btn btn-primary form-control" value="Search">
                      </div>
                    </div>

                  </div>

                </form>

              </div>
              <!-- /.box -->
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">List of Comments</h3>
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
                        <th style="width: 170px;"><i class="fa fa-wrench"></i> Action</th>
                      </tr>
                    </thead>

                    <tbody>

                      <?php

                      if (empty($this->data['comments']['items'])) { ?>
                        <tr>
                          <td class="text-center" colspan="10">No Data Found</td>
                        </tr>
                        <?php } else {

                        foreach ($this->data['comments']['items'] as $comment) { ?>
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
                              <a class="text-danger" href="javascript:runDelete(<?= $comment['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>
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

              <div class="box-footer clearfix">
                <?= $this->data['comments']['paginateInfo'] ?>
                <?= $this->data['comments']['paginateNav'] ?>
              </div>

            </div>
          </div>
        </div>
      </section>

    </div>

    <?php include_once(VIEW_PATH . '_common/footer.php'); ?>

    <script src="<?= APP_URL ?>/public/js/select2.min.js"></script>
    <script>
      $('.select2').select2({
        placeholder: 'Select User'
      });

      function approve(id) {
        let conf = confirm('Are you sure want to approve this?');
        if (conf) {
          window.location = "<?= APP_URL ?>/admin/comments/approve/" + id;
        }
      }

      function runDelete(id) {
        let conf = confirm('Are you sure want to delete this?');
        if (conf) {
          window.location = "<?= APP_URL ?>/admin/comments/delete/" + id;
        }
      }
    </script>