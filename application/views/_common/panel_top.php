<header class="main-header">
    <!-- Logo -->
    <a href="<?= APP_URL ?>/account/onAuthenticate/" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Bhalo</span>
        <!-- logo for regular state and mobile devices -->
        <b>Bhalo</b>Desh
    </a>
	<?php

	?>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <img src="<?= $this->auth->userinfo['photo']; ?>"
                             class="user-image" alt="User Image">
                        <span class="hidden-xs"><?= $this->auth->userinfo['name']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $this->auth->userinfo['photo']; ?>"
                                 class="img-circle" alt="User Image">
                            <p>
								<?= $this->auth->userinfo['name']; ?>
                                <small><?= $this->auth->userinfo['email']; ?></small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= APP_URL . '/Account/' ?>" class="btn btn-default btn-flat">My Account</a>
                            </div>
                            <div class="pull-right">
                                <form action="<?= APP_URL . '/Account/Logout/' ?>" method="post">
                                    <input type="submit" class="btn btn-default btn-flat" value="Logout" name="logout"/>
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>