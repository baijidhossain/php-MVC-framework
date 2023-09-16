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

		<?php $this->getMessage();?>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">List of Page</h3>
              <div class="box-tools">
                <a href="<?= APP_URL ?>/admin/pages/add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Page</a>
              </div>
            </div>
            <div class="box-body ">

              <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                  <thead>
                    <tr>
                        <th style="width: 35%;">Title</th>
                        <th style="width: 35%;">Path</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php

                    if ( ! isset( $this->data['pages']['items'][0] ) ) {
	                    echo "<tr><td colspan='10' class='text-center'>No Page Found</td></tr>";
                    } else {

	                    foreach ( $this->data['pages']['items'] as $page ) { ?>
                            <tr>
                                <td><?= $page['title'] ?></td>
                                <td><?= $page['path'] ?></td>
                                <td>
				                    <?php
				                    if ( $page['status'] == 1 ) {
					                    echo ' <span class="badge label-success">Published</span>';
				                    } elseif ( $page['status'] == 2 ) {

					                    echo '<span class="badge" >Trashed</span>';
                            } else {
                              echo ' <span class="badge label-danger" >Unpublished</span>';
                            }
                            ?>
                          </td>
                          <td style=" white-space: nowrap;">
                            <a href="<?= APP_URL ?>/admin/pages/edit/<?= $page['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>&nbsp;&nbsp;
                            <a class="text-red" href="javascript:runDelete(<?= $page['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>
                          </td>
                        </tr>
                    <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="box-footer">
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
        window.location = "<?= APP_URL ?>/admin/pages/delete/" + id;
      }
    }
  </script>