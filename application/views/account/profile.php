<?php include_once(VIEW_PATH . '_common/header.php'); ?>

    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/profile.css">
    <div class="wrapper">

<?php
    include_once(VIEW_PATH . '_common/admin_top.php');

    include_once(VIEW_PATH . '_common/navigation.php');
?>


    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                <?= $data['page_title'] ?>
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
                <div class="col-md-3">
                    <div class="box box-primary profile">
                        <div class="box-body box-profile">
                            <a data-toggle="modal" data-target="#myModal"
                               href="<?= APP_URL ?>/account/uploadimg/" class="upload_container">
                                <img class="img-responsive img-circle"
                                     src="<?= $data['user']['photo'] ?>" alt="User profile picture">
                                <i class="fa fa-pencil fa-lg"></i>
                            </a>
                            <h3 class="profile-username text-center"
                                style="margin: 25px 0;"><?= $data['user']['name'] ?></h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Available Balance: <span class="pull-right"> </span></b>
                                </li>

                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title bn">Details</h3>
                        </div>
                        <div class="box-body">
                            <label class="font16">Name</label>
                            <p class="font16"><?= $data['user']['name'] ?></p>
                            <label class="font16">Phone</label>
                            <p class="font16"><?= $data['user']['phone'] ?></p>
                            <label class="font16">Email Address</label>
                            <p class="font16">
                                <?= $data['user']['email'] ?>
                                <?= ($data['user']['email_verified'] == 0
                                    ? '<i class="fa fa-times text-danger"></i>'
                                    : '<i class="fa fa-check text-success"></i>'); ?>
                            </p>
                            <label class="font16">Account Duration</label>
                            <p class="font16">
                                <?= date_diff(date_create($data['user']['created']),
                                    date_create(""))->format("%y Years %m Months %d Days ") ?>
                            </p>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="#" class="btn btn-primary pull-right"
                               disabled><b>Modify</b></a>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>


<?php include_once(VIEW_PATH . '_common/footer.php'); ?>