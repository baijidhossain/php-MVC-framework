<body class="hold-transition sidebar-mini <?= SKIN_COLOR ?>">
  <header class="main-header">
    <!-- Logo -->
    <a href="<?= APP_URL ?>/account/onAuthenticate/" class="logo bg-blue-active">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">MVC</span>
      <!-- logo for regular state and mobile devices -->
      <b>MVC</b> Framework
    </a>
    <?php

    ?>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top bg-blue" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle " data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= ($_SESSION['avatar'] ? APP_URL . '/public/images/user_img/' . $_SESSION['avatar'] : APP_URL . '/public/images/no-profile.jpg'); ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?= $_SESSION['name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?= ($_SESSION['avatar'] ? APP_URL . '/public/images/user_img/' . $_SESSION['avatar'] : APP_URL . '/public/images/no-profile.jpg'); ?>" class="img-circle" alt="User Image">
                <p>
                  <?= $_SESSION['name']; ?>
                  <small><?= $_SESSION['login']; ?></small>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?= APP_URL . '/Account/' ?>" class="btn btn-default btn-flat">My Account</a>
                </div>
                <div class="pull-right">
                  <form action="<?= APP_URL . '/Account/Logout/' ?>" method="post">
                    <input type="submit" class="btn btn-default btn-flat" value="Logout" name="logout" />
                  </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>