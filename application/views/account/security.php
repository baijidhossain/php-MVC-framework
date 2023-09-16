<?php include_once(VIEW_PATH . '_common/header.php'); ?>
<div class="wrapper">
    <?php
        include_once(VIEW_PATH . '_common/admin_top.php');

        include_once(VIEW_PATH . '_common/navigation.php');
    ?>

    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                <?= $data['page_title']; ?>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= APP_URL ?>/account/onAuthenticate"><i class="fa fa-dashboard"></i>
                        Home</a></li>
                <li class="active"><?= $data['page_title']; ?></li>
            </ol>
        </section>

        <section class="content">

            <?php $this->getAlert(); ?>

            <div class="row">
                <div class="col-md-7">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title bn">2 Step Verification (2FA)</h3>
                        </div>
                        <div class="box-body">
                            <h5 class="font16">Stronger security for your Account. </h5>
                            <p style="font-size: 14px;">Two-factor authentication is an optional but
                                highly recommended security feature that adds an extra layer of
                                protection to your <?= SITE_TITLE ?> account.</p>
                            <p> With 2-Step Verification, you'll protect your account with both your
                                password and your
                                phone.</p>
                            <p style="font-size: 14px;">Once enabled, <?= SITE_TITLE ?> will require
                                a six-digit security code in addition to your password whenever you
                                sign in to <?= SITE_TITLE ?>.</p>
                            <p class="font16" style="margin-top: 20px;margin-bottom: 15px;">2-Step
                                Verification (2FA) is <strong
                                        class="text-<?= $data['2fa'] ? 'success'
                                            : 'danger' ?>"> <?= $data['2fa'] ? 'Enabled'
                                        : 'Disabled' ?></strong></p>
                            <?= $data['2fa_message'] ?>
                        </div>
                        <div class="box-footer clearfix">
                            <?php if ($data['2fa']): ?>
                                <a data-target="#myModal" data-toggle="modal"
                                   href="<?= APP_URL . '/account/twoFactorModal/disable/' ?>"
                                   class="btn btn-danger pull-right">Disable (2FA)</a>
                                <a data-target="#myModal" data-toggle="modal"
                                   href="<?= APP_URL . '/account/twoFactorModal/reset/' ?>"
                                   class="btn btn-default pull-right" style="margin-right: 10px;">Reset
                                    (2FA) Token</a>
                            <?php else: ?>
                                <a data-target="#myModal" data-toggle="modal"
                                   href="<?= APP_URL . '/account/twoFactorModal/enable/' ?>"
                                   class="btn btn-primary pull-right">Enable (2FA)</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="box box-primary">
                        <form style="margin-bottom:10px;" action="" method="post"
                              autocomplete="off">
                            <div class="box-header with-border">
                                <h3 class="box-title bn">Change Password</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group has-feedback <?= (! empty($data['cur_password_err'])
                                    ? 'has-error' : '') ?>">
                                    <label>Current Password</label>
                                    <input type="password" class="form-control" name="cur_password"
                                           required>
                                    <span class="help-block"><?= $data['cur_password_err']; ?></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group has-feedback <?= (! empty($data['password_err'])
                                            ? 'has-error' : '') ?>">
                                            <label>New Password</label>
                                            <input type="password" class="form-control"
                                                   name="password" id="pass1" required>
                                            <span class="help-block"><?= $data['password_err']; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-feedback <?= (! empty($data['confirm_password_err'])
                                            ? 'has-error' : '') ?>">
                                            <label>New Password (Repeat)</label>
                                            <input type="password" class="form-control"
                                                   name="password2" id="pass2" required>
                                            <span class="help-block"><?= $data['confirm_password_err']; ?></span>
                                            <span id="message"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer clearfix">
                                <button type="submit" class="btn btn-primary pull-right">Change
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <?php include_once(VIEW_PATH . '_common/footer.php'); ?>
    <script>
        let msg = $('#message');
        $('#pass1, #pass2').on('keyup', () => {
            $('#pass1').val() === $('#pass2').val() ? msg.html('Matching').css('color', 'green') : msg.html('Not Matching').css('color', 'red');
        });
    </script>