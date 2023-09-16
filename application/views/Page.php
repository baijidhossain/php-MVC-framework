<?php include_once VIEW_PATH . '_common/frontend/header.php'; ?>

<div class="main-container container mt-56 normal_page" id="main-container">
  <style>
    .normal_page ul {
      list-style-type: initial;
      padding-left: 2rem;

    }

    .page-body p {
      font-size: 1.2rem;
      line-height: 1.8;
    }
  </style>
  <section class="section mb-24">
    <div class="row justify-content-center">
      <?php if (!empty($this->data['page'])) { ?>
        <div class="col-md-9">
          <div class="title-wrap pb-3 border-bottom">
            <h3 class="section-title"><?= $this->data['page_title'] ?></h3>
          </div>
        </div>
        <div class="col-md-7">
          <div class="page-body"><?= $this->data['page']['body'] ?></div>
        </div>

      <?php } else { ?>

        <p style="text-align: center;">No Page Found</p>

      <?php } ?>

    </div>
  </section>
</div>

<?php include_once VIEW_PATH . '_common/frontend/footer.php'; ?>