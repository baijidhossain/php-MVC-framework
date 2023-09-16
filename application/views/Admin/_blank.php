<?php include_once( VIEW_PATH . '_common/header.php' ); ?>

<body class="hold-transition sidebar-mini <?= SKIN_COLOR ?>">
<div class="wrapper">

	<?php include_once( VIEW_PATH . '_common/panel_top.php' ); ?>
	<?php include_once( VIEW_PATH . '_common/sidebar.php' ); ?>

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
				<div class="col-md-8">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Demo</h3>
						</div>
						<div class="box-body">
							<h3 class="m-0">Simple Card</h3>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>

		</section>

	</div>

	<?php require_once( VIEW_PATH . '_common/footer.php' ); ?>

