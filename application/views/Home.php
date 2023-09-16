<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home - <?= SITE_TITLE ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
          name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/bootstrap.min.css">

    <link rel="icon" href="<?= APP_URL ?>/public/images/logo.png" type="image/png"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
          rel="stylesheet">
</head>
<style>
    body {
        min-height: 100vh;
        margin: 0;
        background: linear-gradient(180deg, #b9c6d2 0%, #d0dde9 10.45%, #edf0f8 41.35%);
    }
</style>
<body>
<header>
    <div class="container">
        <div class="row">
            <nav class="navbar my-3">
                <div class="brand-logo">
                    <a href="<?= APP_URL ?>">
                        <img alt="Brand" class="img-responsive mx-auto"
                             src="<?= APP_URL ?>/public/images/brand.png" width="180">
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
</header>
<main style="min-height: calc(100vh - 10vh);display: flex;align-items: center;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron">
                    <h1 class="display-4">Welcome to <?= SITE_TITLE ?>!</h1>
                    <p class="lead">This is a simple hero unit, a simple jumbotron-style component
                        for calling extra
                        attention to featured content or information.</p>
                    <hr class="my-4">
                    <p>It uses utility classes for typography and spacing to space content out
                        within the larger
                        container.</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="<?= APP_URL ?>/admin" role="button">Dashboard</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</main>
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="text-center">
                © <?= date("Y") ?> <span class="theme-clr"><?= parse_url(APP_URL,
                        PHP_URL_HOST) ?> </span> All Rights Reserved.

            </div>
        </div>
    </div>
</footer>


<!-- jQuery 3 -->
<script src="<?= APP_URL ?>/public/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= APP_URL ?>/public/js/bootstrap.min.js"></script>

</body>

</html>