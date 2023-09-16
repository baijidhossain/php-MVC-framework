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
          <li><a href="<?= APP_URL ?>/admin/tag/"> Tags</a></li>
          <li class="active"><?= $this->data['page_title'] ?></li>
      </ol>
    </section>

    <section class="content">

      <?php $this->getMessage(); ?>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">List of Tags</h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/tag/add" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus " style="margin-right: 2px;"></i> Add Tags</a>

              </div>
            </div>

            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                  <thead>
                    <tr>
                      <th><i class="fa fa-tags" style="margin-right: 3px;"></i>Tag Name</th>
                      <th><i class="fa fa-newspaper-o" style="margin-right: 1px;"></i> Articles</th>
                      <th><i class="fa fa-calendar"></i> Created</th>
                      <th><i class="fa fa-wrench"></i> Action</th>
                    </tr>

                  </thead>
                  <tbody>
                    <?php
                    if (empty($this->data['tags']['items'])) {
                      echo "<tr><td colspan='10' class='text-center'>No Content Found</td></tr>";
                    } else {

                      foreach ($this->data['tags']['items'] as  $tag) { ?>
                        <tr>
                          <td><a href="<?= APP_URL ?>/admin/article/?tag=<?= $tag['id'] ?>"><?= $tag['name'] ?></a></td>
                          <td><?= $tag['total_articles'] ?></td>
                          <td>
	                          <?= date_create($tag['created'])->format('F j, Y, g:i a') ?>
                          </td>
                          <td>
                            <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/Tag/Edit/<?= $tag['id']; ?>"><i class="fa fa-pencil"></i> Edit</a>&nbsp;
                            <a class="text-red" href="javascript:runDelete(<?= $tag['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>
                          </td>
                        </tr>
                    <?php  }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="box-footer">

              <?php echo $this->data['tags']['paginateInfo'] ?>
              <?php echo $this->data['tags']['paginateNav'] ?>

            </div>
          </div>
        </div>
      </div>

    </section>
</div>

<?php include_once( VIEW_PATH . '_common/footer.php' ); ?>

<!-- delete -->
<script>
    function runDelete(id) {
        let conf = confirm('Are you sure want to delete this?');
        if (conf) {
            window.location = "<?= APP_URL ?>/admin/tag/delete/" + id;
        }
    }
</script>