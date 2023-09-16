<?php

$topCategoryList = [ 'সমাজ-সংস্কৃতি', 'অর্থ-অনর্থ', 'নীতির রাজা', 'বিনোদন', 'খেলা', 'জীবনধারা', 'স্ব-বিশেষ', 'মাল্টিমিডিয়া' ];

$topCategories = $this->db->query( "SELECT blog_category.*, du.path FROM `blog_category` JOIN dynamic_url AS du ON du.item_id = blog_category.id WHERE du.controller = 'Category'  AND du.method = 'Details' AND status = 1 AND blog_category.name IN ('" . implode( "','", $topCategoryList ) . "')" )->fetchAll();

$metas = [
	'title'       => !empty($this->data['articleDetails']['title']) ? $this->data['articleDetails']['title'] : $this->data['page_title'],
	'description' => !empty($this->data['meta_description']) ? $this->data['meta_description'] : 'বাংলাদেশের সর্বশেষ সংবাদ শিরোনাম, সমাজ-সংস্কৃতি, বিশ্লেষণ, খেলাধুলা, বিনোদন, জীবনধারা, মাল্টিমিডিয়া এবং ব্যবসার বাংলা নিউজ দেখুন ভালোদেশ.কম',
	'keywords'    => !empty($this->data['meta_keywords']) ? $this->data['meta_keywords'] : '',
	'image'       => !empty($this->data['articleDetails']['thumb']) ? APP_URL . '/public/images/article/thumbnail/' . $this->data['articleDetails']['thumb'] : APP_URL . '/public/frontend/images/bhalodesh.svg',
];

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->data['page_title'] . ' | ' . SITE_TITLE ?></title>
    <!-- Primary Meta Tags -->
    <meta name="title" content="<?= $metas['title'] ?>">
    <meta name="description" content="<?= $metas['description'] ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= urldecode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>">
    <meta property="og:title" content="<?= $metas['title'] ?>">
    <meta property="og:description" content="<?= $metas['description'] ?>">
    <meta property="og:image" content="<?= $metas['image'] ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="<?= $metas['image'] ?>">
    <meta property="twitter:url" content="<?= APP_URL ?>">
    <meta property="twitter:title" content="<?= $metas['title'] ?>">
    <meta property="twitter:description" content="<?= $metas['description'] ?>">
    <meta property="twitter:image" content="<?= $metas['image'] ?>">

    <!-- favicon link -->
    <link rel="shortcut icon" href="<?= APP_URL ?>/public/frontend/images/favicon.png" type="image/x-icon">
    <!-- BootstrapV4 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0/css/bootstrap.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/frontend/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/frontend/css/custom.css">

    <!-- scripts -->
	<?php

	$this->addScript( "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js", 1 );
	$this->addScript( "https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js", 2 );
	$this->addScript( "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js", 2 );
	$this->addScript( APP_URL . "/public/frontend/js/scripts.js", 10 );

	?>

</head>

<body class="home">
<!-- Preloader -->
<div class="loader-mask">
    <div class="loader">
        <div></div>
    </div>
</div>
<!-- Bg Overlay -->
<div class="content-overlay"></div>

<!-- Sidenav -->
<?php include_once( VIEW_PATH . '_common/frontend/sidebar.php' ); ?>
<!-- end sidenav -->

<main class="main oh" id="main">

    <!-- Header -->
    <header class="header d-lg-block d-none">
        <div class="container">
            <div class="flex-parent">
                <div class="flex-child">
                    <!-- Side Menu Button -->
                    <button class="nav-icon-toggle" id="nav-icon-toggle" aria-label="Open side menu">
              <span class="nav-icon-toggle__box">
                <span class="nav-icon-toggle__inner"></span>
              </span>
                    </button>
                    <div class="time">
                        <p><?= Util::BanglaINT( date( 'd' ) ) ?> <?= Util::BanglaMonth( date( 'F' ) ) ?> <?= Util::BanglaINT( date( 'Y' ) ) ?></p>
                    </div>
                </div>

                <div class="flex-child text-center">
                    <!-- Logo -->
                    <a href="/" class="logo">
                        <img class="logo__img" src="<?= APP_URL ?>/public/frontend/images/bhalodesh.svg" alt="logo" width="300" height="65"/>
                    </a>
                </div>

                <!-- Nav Right -->
                <div class="flex-child">
                    <div class="nav__right">
                        <div class="me-5">
	                        <?php if (isset($_SESSION['userid'])): ?>
                                <a href="<?= APP_URL ?>/account/logout" data-no-instant class="login fw-bold">লগআউট</a>
	                        <?php else: ?>
                                <a href="<?= APP_URL ?>/account/login" class="login fw-bold">লগইন</a>
	                        <?php endif; ?>
                        </div>
                        <!-- Search -->
                        <div class="nav__right-item nav__search">
                            <a href="#" class="nav__search-trigger" id="nav__search-trigger">
                                <i class="ri-search-line nav__search-trigger-icon"></i>
                            </a>
                            <div class="nav__search-box" id="nav__search-box">
                                <form class="nav__search-form" action="<?= APP_URL ?>/article/">
                                    <input name="search" type="text" placeholder="Search an article" class="nav__search-input" value="<?= $_GET['search'] ?? '' ?>"/>
                                    <button type="submit" class="search-button btn rounded-0 btn-color btn-button">
                                        <i class="ri-search-line nav__search-icon"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- end nav right -->
                </div>
            </div>
        </div>
        <!-- end container -->
    </header>
    <!-- end header -->

    <!-- Navigation -->
    <header class="nav--1">
        <div class="nav__holder nav--sticky">
            <div class="container relative h-100">
                <div class="flex-parent">
                    <!-- Nav-wrap -->
                    <nav class="flex-child nav__wrap d-none d-lg-block">
                        <ul class="nav__menu">
							<?php foreach ( $topCategories as $category ): ?>
                                <li>
                                    <a href="<?= APP_URL . '/category/' . $category['path'] ?>"><?= $category['name'] ?></a>
                                </li>
							<?php endforeach; ?>

                            <li class="nav__dropdown">
                                <a href="#">আরও</a>
                                <ul class="nav__dropdown-menu">
                                    <li>
                                        <a href="<?= APP_URL ?>/category/অবিশ্বাস্য/">অবিশ্বাস্য</a>
                                    </li>
                                    <li>
                                        <a href="<?= APP_URL ?>/category/জেনারেশন/">জেনারেশন</a>
                                    </li>
                                    <li>
                                        <a href="<?= APP_URL ?>/category/বিজ্ঞান/">বিজ্ঞান</a>
                                    </li>
                                    <li>
                                        <a href="<?= APP_URL ?>/category/প্রাণ-প্রকৃতি/">প্রাণ-প্রকৃতি</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!-- end menu -->
                    </nav>
                    <!-- end nav-wrap -->

                    <div class="flex-child d-lg-none">
                        <!-- Side Menu Button -->
                        <button class="nav-icon-toggle" id="nav-icon-toggle_side" aria-label="Open side menu">
                <span class="nav-icon-toggle__box">
                  <span class="nav-icon-toggle__inner"></span>
                </span>
                        </button>
                    </div>
                    <!-- Logo Mobile -->
                    <a href="/" class="logo logo-mobile d-lg-none">
                        <img class="logo__img" src="<?= APP_URL ?>/public/frontend/images/bhalodesh.svg" loading="lazy" srcset="<?= APP_URL ?>/public/frontend/images/bhalodesh.svg 1x, <?= APP_URL ?>/public/frontend/images/bhalodesh.svg 2x" alt="logo" style="width: 200px"/>
                    </a>

                    <div class="flex-child d-lg-none text-right">
	                    <?php if (isset($_SESSION['userid'])): ?>
                            <a href="<?= APP_URL ?>/account/logout" data-no-instant class="login fw-bold"><i class="ri-logout-box-r-line" style="position: relative; top: 2px;"></i></a>
	                    <?php else: ?>
                            <a href="<?= APP_URL ?>/account/login/" class="icon">
                                <i class="ri-user-fill" style="font-size: 1.5rem;"></i>
                            </a>
	                    <?php endif; ?>
                    </div>
                </div>
                <!-- end flex-parent -->
            </div>
        </div>
    </header>
    <!-- end navigation -->