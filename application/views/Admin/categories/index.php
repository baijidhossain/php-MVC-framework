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
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <h3 class="box-title">List of Categories</h3>
                        <div class="box-tools">
                            <a href="<?= APP_URL ?>/admin/category/add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Category</a>
                        </div>


                    </div>
                    <div class="box-header with-border">

                        <form class="pull-right" autocomplete="off">
                            <div class="input-group input-group-sm">
                                <input type="search" name="search" class="form-control" placeholder="Search" style="width: 170px;" value="<?= $_GET['search'] ?? '' ?>">

                                <div class="input-group input-group-sm">
                                    <select name="status" id="" class="form-control">
                                        <option value="">--Select One--</option>
                                        <option <?php if ( isset( $_GET['status'] ) && $_GET['status'] == 1 ) {
											echo "selected";
										} ?> value="1">Published
                                        </option>
                                        <option <?php if ( isset( $_GET['status'] ) && $_GET['status'] === '0' ) {
											echo "selected";
										} ?> value="0">Unpublished
                                        </option>
                                        <option <?php if ( isset( $_GET['status'] ) && $_GET['status'] == 2 ) {
											echo "selected";
										} ?> value="2">Trashed
                                        </option>
                                    </select>
                                </div>

                                <div class="input-group-btn" style="width: 30px;">
                                    <button type="submit" class="btn btn-default" id="search-btn"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>


                    </div>
                    <div class="box-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">
                                <thead>

                                <tr>
                                    <th><i class="fa fa-list-alt"></i> Category Name</th>
                                    <th><i class="fa fa-external-link" aria-hidden="true"></i> Category Path</th>
                                    <th><i class="fa fa-newspaper-o" aria-hidden="true"></i> Total Articles</th>
                                    <th><i class="fa fa-cog"></i> Status</th>
                                    <th><i class="fa fa-wrench"></i> Action</th>
                                </tr>

                                </thead>
                                <tbody>
								<?php

								if ( empty( $this->data['categories'] ) ) {
									echo "<tr><td colspan='10' class='text-center'>No Category Found</td></tr>";
								} else {

									function showCategories( $categories, $parent_id = 0, $char = '' ) {

										foreach ( $categories as $item ) {

											if ($item['parent_id'] == $parent_id) { ?>

                                                <tr>
                                                  <td><a href="<?= APP_URL ?>/admin/category/edit/<?= $item['id'] ?>"><?= $char . $item['name'] ?></a></td>

                                                  <td><?= $item['CategoryPath'] ?></td>

                                                  <td><?= $item['total_articles'] ?></td>

                                                  <td>

                                                    <?php

                                                    if ($item['status'] == 1) { ?>

                                                      <span class="badge label-success">Published</span>

                                                    <?php } else if ($item['status'] == 2) { ?>

                                                      <span class="badge">Trashed</span>

                                                    <?php } else { ?>
                                                      <span class="badge label-danger">Unpublished</span>
                                                    <?php } ?>

                                                  </td>

                                                  <td>
                                                    <a href="<?= APP_URL ?>/admin/category/edit/<?= $item['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>&nbsp; &nbsp;
                                                    <a class="text-red" href="javascript:runDelete('<?= $item['id'] ?>')"><i class="fa fa-trash"></i> Delete</a>
                                                  </td>

                                                </tr>
                                        <?php
                                                showCategories($categories, $item['id'], $char . '— ');
                                              }
// TODO sub menu trash kora hole dekha jay na
                                            }
                                          }

									showCategories( $this->data['categories'] );
								} ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>

<?php include_once( VIEW_PATH . '_common/footer.php' ); ?>

<!-- delete -->
<script>
    function runDelete(path) {
        let conf = confirm('Are you sure want to delete this?');
        if (conf) {
            window.location = '<?= APP_URL ?>/admin/category/delete/' + path
        }
    }
</script>