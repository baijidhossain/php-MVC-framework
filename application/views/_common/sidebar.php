<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $this->auth->userinfo['photo'] ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $this->auth->userinfo['name']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="header">Navigation</li>
			<?php

			$menus = $this->db->query("SELECT m.* FROM navigation AS m JOIN nav_permission AS p ON p.nav_id=m.id WHERE p.group_id IN (?) ORDER BY m.sort,m.id", implode(",", $this->auth->userinfo['group_ids']))->fetchAll();

			$menu_items = [];

			foreach ($menus as $menu) {

				$menu_items[$menu['parent_id']][] = $menu;
			}

			getMenu($menu_items, 0, false);

			function getMenu($menu_items, $key, $sub = false)
			{
				echo($sub ? '<ul class="treeview-menu">' : '');

				foreach ($menu_items[$key] as $item) {

					$li_class = (isset($menu_items[$item['id']]) ? 'treeview' : '');

					$li_class .= ($item['nav_path'] == CUR_REQUEST_PATH ? ' active' : '');

					if (isset($menu_items[$item['id']])) {

						if (in_array(CUR_REQUEST_PATH, array_column($menu_items[$item['id']], 'nav_path'))) {

							$li_class .= ' active';
						}
					}

					$li_pull_icon = (isset($menu_items[$item['id']]) ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : '');

					if (stripos($item['nav_path'], "/Index") !== false) {

						$link = '/' . rtrim(strtolower($item['nav_path']), "index");

					} else {

						$link = '/' . strtolower($item['nav_path']);
					}


					echo '<li class="' . $li_class . '"> <a href="' . $link . '"> <i class="' . $item['nav_icon'] . '"></i> <span>' . $item['nav_name'] . '</span>' . $li_pull_icon . '</a>';

					if (isset($menu_items[$item['id']])) {

						getMenu($menu_items, $item['id'], true);
					}

					echo '</li>';
				}

				echo($sub ? '</ul>' : '');
			}


			?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>