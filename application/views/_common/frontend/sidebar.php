<?php

$categories = $this->db->query("SELECT blog_category.*, du.path FROM `blog_category` JOIN dynamic_url AS du ON du.item_id = blog_category.id WHERE du.controller = 'Category' AND du.method = 'Details' AND status = 1")->fetchAll();

?>



<header class="sidenav" id="sidenav">
  <!-- close -->
  <div class="sidenav__close">
    <button class="sidenav__close-button" id="sidenav__close-button" aria-label="close sidenav">
      <i class="ri-close-line sidenav__close-icon"></i>
    </button>
  </div>

  <!-- Nav -->
  <nav class="sidenav__menu-container">
    <ul class="sidenav__menu" role="menubar">
      <!-- Categories -->
      <?php foreach($categories as $category): ?>
      <li>
        <a href="<?= APP_URL.'/category/'.$category['path'] ?>" class="sidenav__menu-url"><?= $category['name'] ?></a>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="socials sidenav__socials">
    <a class="social social-facebook" href="#" target="_blank" aria-label="facebook">
      <i class="ri-facebook-fill"></i>
    </a>
    <a class="social social-twitter" href="#" target="_blank" aria-label="twitter">
      <i class="ri-twitter-fill"></i>
    </a>

    <a class="social social-youtube" href="#" target="_blank" aria-label="youtube">
      <i class="ri-youtube-fill"></i>
    </a>
    <a class="social social-instagram" href="#" target="_blank" aria-label="instagram">
      <i class="ri-instagram-fill"></i>
    </a>
  </div>
</header>