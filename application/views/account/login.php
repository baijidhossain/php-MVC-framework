<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $data['page_title'] . ' | ' . SITE_TITLE ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
          name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/bootstrap.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/icheck_blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="<?= APP_URL ?>"><b>MVC</b> Framework</a>
    </div>
    <!-- /.login-logo -->
    <?php $this->getAlert(); ?>

    <?php if (isset($_SESSION['2FA'])) : ?>
        <div class="login-box-body">
            <p class="login-box-msg">Two Factor Authentication</p>
            <form style="margin-bottom:10px;" action="" method="post" autocomplete="off">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="2FA_otp"
                           placeholder="Enter 6-digit verification code"
                           required>
                    <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <a href="<?= APP_URL ?>/account/logout" class="text-center">Cancel</a>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat pull-right">
                            Sign In
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
    <?php else : ?>
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <?php if (isset($_SESSION['login_otp'])): ?>

                <form style="margin-bottom:10px;" action="" method="post" autocomplete="off">
                    <div class="form-group has-feedback <?= (! empty($data['otp_err']) ? 'has-error'
                        : '') ?>">
                        <input type="text" class="form-control" name="login_otp"
                               placeholder="Enter your verification code" required>
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                        <span class="help-block"><?= $data['otp_err']; ?></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-8">
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">
                                Verify
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            <?php else: ?>
                <form style="margin-bottom:10px;" action="" method="post" autocomplete="off">
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" name="login" placeholder="Email"
                               value="admin@admin.com">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password"
                               placeholder="Password" value="admin">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="remember_me"> <span
                                            style="vertical-align: middle; margin-left: 2px;">Remember Me</span>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign
                                In
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <?php if (ALLOW_FORGET_PASSWORD): ?>
                    <a href="<?= APP_URL ?>/account/recovery">I forgot my password</a>
                <?php endif; ?>
                <br>
                <?php if (ALLOW_REGISTRATION): ?>
                    <a href="<?= APP_URL ?>/account/register" class="text-center">Register a new
                        account</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
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
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
</script>
</body>
</html>
