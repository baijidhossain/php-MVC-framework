<?php include_once VIEW_PATH . '_common/frontend/header.php'; ?>

<div class="main-container container mt-56" id="main-container">
    <section class="section mb-24">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="title-wrap pb-3 border-bottom">
                    <h3 class="section-title"><?= $this->data['category']['name'] ?></h3>
                </div>
            </div>
            <div class="col-md-7">

				<?php if ( ! empty( $this->data['articles']['items'] ) ): ?>

                    <ul class="mb-32 post-list-small">
						<?php foreach ( $this->data['articles']['items'] as $article ): ?>

                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-70">
                                            <a href="<?= APP_URL . '/article/' . $article['path'] ?>">
                                                <img src="<?= APP_URL . '/public/images/article/thumbnail/' . $article['thumb'] ?>" alt="<?= $article['title'] ?>" loading="lazy">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="entry-title fw-normal">
                                            <a href="<?= APP_URL . '/article/' . $article['path'] ?>" class="text-truncate-3"><?= $article['title'] ?> </a>
                                        </h3>
                                        <div class="entry__excerpt">
                                            <p class="text-truncate-3"><?= ! empty( $article['intro'] ) ? $article['intro'] : $article['body'] ?></p>
                                        </div>
                                        <ul class="entry__meta mt-3">
                                            <li class="entry__meta-date"><?= $article['name']?></li>
                                            <li class="entry__meta-date"><?= Util::BanglaTimeDiff( $article['published'] ) ?></li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
						<?php endforeach; ?>

                    </ul>
				<?php else: ?>
                    <div class="alert alert-warning">
                        No posts found.
                    </div>

				<?php endif; ?>

                <div class="row mt-4">
                    <div class="col-md-6 my-2 text-center text-md-start"><?= $this->data['articles']['paginateInfo'] ?></div>

                    <div class="col-md-6">
                       
							<?= $this->data['articles']['paginateNav'] ?>
                        
                    </div>

                </div>

            </div>

        </div>


    </section>

</div>
<?php include_once VIEW_PATH . '_common/frontend/footer.php'; ?>


                