<?php include_once( VIEW_PATH . '_common/header.php' ); ?>

<!-- iCheck -->
<link rel="stylesheet" href="<?= APP_URL ?>/public/css/icheck_blue.css">

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="<?= APP_URL ?>">
            <img src="<?= APP_URL ?>/public/frontend/images/bhalodesh.svg" alt="Logo" width="200" loading="lazy">
        </a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= $this->data['page_heading']; ?></p>
		<?php
		if ( isset( $_SESSION['alerts'] ) ) {

			foreach ( $_SESSION['alerts'] as $alert ) {
				$type = ( $alert['type'] == "error" ? "danger" : $alert['type'] );
				echo '<p class="text-' . $type . '">' . $alert['msg'] . '</p>';
			}
			unset( $_SESSION['alerts'] );
		}
		?>

		<?php if ( isset( $_SESSION['reg_otp'] ) ): ?>

            <form style="margin-bottom:10px;" action="" method="post" autocomplete="off">
                <div class="form-group has-feedback <?= ( ! empty( $this->data['otp_err'] ) ? 'has-error' : '' ) ?>">
                    <input type="text" class="form-control" name="otp" placeholder="Enter your verification code"
                           required>
                    <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                    <span class="help-block"><?= $this->data['otp_err']; ?></span>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Verify</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

		<?php else: ?>

            <form style="margin-bottom:10px;" action="" method="post" autocomplete="off" id="reg_form">

                <div class="form-group has-feedback <?= ( ! empty( $this->data['mobile_err'] ) ? 'has-error' : '' ) ?>">
                    <input type="text" class="form-control" name="mobile" placeholder="Mobile"
                           value="<?= $this->data['mobile']; ?>"
                           required>
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    <span class="help-block"><?= $this->data['mobile_err']; ?></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="agree" required>
                                <span style="vertical-align: middle; margin-left: 2px;">I agree to the <a href="<?= APP_URL ?>/terms/" target="_blank">terms</a></span>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

		<?php endif; ?>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?= APP_URL ?>/public/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= APP_URL ?>/public/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?= APP_URL ?>/public/js/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });

        $('#reg_form').on('submit', function () {
            $('button[type=submit]').prop('disabled', true);
        });
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
</script>

</body>

</html>