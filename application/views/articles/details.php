<?php include_once VIEW_PATH . '_common/frontend/header.php'; ?>

<?php $this->addScript( "https://cdnjs.cloudflare.com/ajax/libs/sticky-kit/1.1.2/sticky-kit.min.js", 2 ); ?>


    <div class="main-container container mt-56" id="main-container">
        <!-- Content -->
        <div class="row">
            <!-- post content -->
            <div class="col-lg-8 blog__content mb-72">
                <!-- standard post -->
                <article class="entry">
                    <div class="entry__article-wrap mt-0">
                        <!-- Share -->
                        <div class="entry__share position-relative">
                            <div class="sticky-col">
                                <div class="socials socials--rounded socials--large">
                                    <a class="social social-rss copy-me" href="<?= APP_URL ?>/article/<?= $this->data['articleDetails']['path'] ?>" title="Copy Link" aria-label="facebook">
                                        <i class="ri-links-line"></i>
                                    </a>
                                    <a class="social social-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= APP_URL ?>/article/<?= $this->data['articleDetails']['path'] ?>" title="facebook" target="_blank" aria-label="facebook">
                                        <i class="ri-facebook-fill"></i>
                                    </a>
                                    <a class="social social-twitter" href="https://twitter.com/intent/tweet?url=<?= APP_URL ?>/article/<?= $this->data['articleDetails']['path'] ?>&text=<?= urlencode( $this->data['articleDetails']['title'] ) ?>" title="twitter" target="_blank" aria-label="twitter">
                                        <i class="ri-twitter-fill"></i>
                                    </a>
                                    <a class="social social-linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= APP_URL ?>/article/<?= $this->data['articleDetails']['path'] ?>" title="Linkedin" target="_blank" aria-label="linkedin">
                                        <i class="ri-linkedin-fill"></i>
                                    </a>
                                    <a class="social social-pinterest" href="https://pinterest.com/pin/create/button/?url=<?= APP_URL ?>/article/<?= $this->data['articleDetails']['path'] ?>" title="pinterest" target="_blank" aria-label="pinterest">
                                        <i class="ri-pinterest-fill"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- share -->

                        <div class="entry__article">
                            <div class="mb-4">
                                <a class="widget-title text-decoration-underline" href="<?= APP_URL ?>/category/<?= $this->data['articleCategory'][0]['path'] ?>"><?= $this->data['articleCategory'][0]['name'] ?></a>
                            </div>
                            <!-- Entry Image -->
                            <div class="thumb thumb--size-6">
                                <div class="entry__img-holder thumb__img-holder" style="background-image: url('<?= APP_URL ?>/public/images/article/thumbnail/<?= $this->data['articleDetails']['thumb'] ?>');">
                                    <div class="bottom-gradient"></div>
                                    <div class="thumb-text-holder thumb-text-holder--2">
                                        <h1 class="thumb-entry-title single-post__thumb-entry-title">
											<?= $this->data['articleDetails']['title'] ?>
                                        </h1>
                                        <div class="entry__meta-holder">
                                            <ul class="ps-0 entry__meta">
                                                <li class="entry__meta_author"><?= $this->data['articleDetails']['name'] ?></li>
                                                <li class="entry__meta-date"><?= Util::BanglaTimeDiff( $this->data['articleDetails']['created'] ) ?></li>
                                            </ul>
                                            <ul class="entry__meta">
                                                <li class="entry__meta-views">
                                                    <i class="ri-eye-line"></i>
                                                    <span><?= Util::BanglaINT( $this->data['articleDetails']['hits'] ?? "" ) ?></span>
                                                </li>
                                                <li class="entry__meta-comments">
                                                    <i class="ri-question-answer-line"></i>
													<?= Util::BanglaINT( count( $this->data['comments'] ?? [] ) ) ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="article-body">
								<?= $this->data['articleDetails']['body'] ?? "" ?>
                            </div>

                            <!-- tags -->
                            <div class="entry__tags">

								<?php

								if ( ! empty( $this->data['tags'] ) ) {

									echo '<i class="ri-price-tag-3-fill"></i>
                                        <span class="entry__tags-label">Tags:</span>';

									foreach ( $this->data['tags'] as $tag ) { ?>
                                        <a href="<?= APP_URL ?>/article/?tag=<?= $tag['name'] ?>" rel="tag"><?= $tag['name'] ?></a>
									<?php }
								}
								?>
                            </div>
                            <!-- end tags -->
                        </div>
                        <!-- end entry article -->
                    </div>

					<?php if ( ! empty( $this->data['readMorePosts'] ) ) { ?>
                        <!-- Related Posts -->
                        <section class="section related-posts mt-40 mb-0">
                            <div class="title-wrap title-wrap--line title-wrap--pr">
                                <h3 class="section-title">আরো পড়ুন</h3>
                            </div>

                            <div class="row">
								<?php foreach ( $this->data['readMorePosts'] as $morepost ) { ?>

                                    <div class="col-md-4">
                                        <article class="entry thumb thumb--size-1">
                                            <div class="entry__img-holder thumb__img-holder" style="background-image: url('<?= APP_URL ?>/public/images/article/thumbnail/<?= $morepost['thumb'] ?>');">
                                                <div class="bottom-gradient"></div>
                                                <div class="thumb-text-holder">

                                                    <h2 class="thumb-entry-title">
                                                        <a href="<?= APP_URL ?>/article/<?= $morepost['path'] ?>"><?= $morepost['title'] ?></a>
                                                    </h2>

                                                </div>
                                                <a href="<?= APP_URL ?>/article/<?= $morepost['path'] ?>" class="thumb-url"></a>
                                            </div>
                                        </article>
                                    </div>

								<?php } ?>

                            </div>
                        </section>
                        <!-- end related posts -->
					<?php } ?>
                </article>
                <!-- end standard post -->

                <!-- Comments -->
                <div class="entry-comments">
                    <div class="title-wrap title-wrap--line">
                        <h3 class="section-title"><?= count( $this->data['comments'] ?? [] ) ?> টি মন্তব্য</h3>
                    </div>
                    <ul class="comment-list">

						<?php foreach ( $this->data['comments'] as $comment ) : ?>
                            <li class="comment">

                                <div class="comment-body">
                                    <div class="comment-avatar">
                                        <img alt="comment user profile" src="<?= APP_URL . $comment['photo'] ?>" loading="lazy" width="45" height="45" style="width:45px;height:45px;object-fit: cover;"/>
                                    </div>

                                    <div class="comment-text">
                                        <h6 class="comment-author"><?= $comment['name'] ?></h6>
                                        <div class="comment-metadata">
                                            <a class="comment-date"><?= Util::BanglaTimeDiff( $comment['updated'] ?: $comment['created'] ) ?> </a>
                                        </div>
                                        <div>
                                            <p class="comment-content">
												<?= $comment['comment'] ?>
                                            </p>
                                        </div>

                                        <!-- if the comment was done by the logged-in user-->
										<?php if ( ! empty( $_SESSION['userid'] ) && $comment['uid'] == $_SESSION['userid'] ) : ?>
                                            <a href="#" class="comment-edit me-2" data-article-id="<?= $comment['article_id'] ?> ">Edit</a>
										<?php endif; ?>

                                        <!-- if the comment was done by the logged-in user-->
										<?php if ( ! empty( $_SESSION['userid'] ) && $comment['uid'] == $_SESSION['userid'] ) : ?>

                                            <form class="comment-form comment-edit-form mt-3" method="post" action="" style="display: none">
												<?= $this->request->verifier ?>
                                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">

                                                <p class="comment-form-comment">
                                                    <label for="comment">মন্তব্য</label>
                                                    <textarea name="comment" rows="3"><?= $comment['comment'] ?></textarea>
                                                </p>
                                                <p class="comment-form-submit">
                                                    <input type="submit" class="btn btn-lg btn-color btn-button rounded-0" value="মন্তব্য প্রকাশ করুন">
                                                </p>
                                            </form>
										<?php endif; ?>

                                    </div>
                                </div>

                            </li>
						<?php endforeach; ?>

                    </ul>

                </div>
                <!-- end comments -->

				<?php if ( ! empty( $_SESSION['userid'] ) ): ?>
                    <!-- Comment Form -->
                    <div id="respond" class="comment-respond">

                        <div class="title-wrap">
                            <h5 class="comment-respond__title section-title">
                                মন্তব্য করুন
                            </h5>

                        </div>

                        <style>
                            .alert.alert-dismissible {
                                padding-right: 15px !important;
                            }

                            .alert.alert-dismissible a {
                                float: right;
                            }
                        </style>

						<?php $this->getMessage(); ?>
                        <form class="comment-form" method="post" action="">
                            <p class="comment-form-comment">
                                <label for="comment">মন্তব্য</label>
                                <textarea id="comment" name="comment" rows="5"></textarea>
                            </p>
							<?= $this->request->verifier ?>
                            <p class="comment-form-submit">
                                <input type="submit" class="btn btn-lg btn-color btn-button rounded-0" value="মন্তব্য প্রকাশ করুন" id="submit-message"/>
                            </p>
                        </form>
                    </div>
                    <!-- end comment form -->
				<?php else: ?>

                    <div class="alert alert-warning">
                        <strong>মন্তব্য করতে <a href="<?= APP_URL ?>/account/login">লগইন করুন</a></strong>
                    </div>

				<?php endif; ?>
            </div>
            <!-- end post content -->

            <!-- sidebar-->
            <aside class="col-lg-4 sidebar sidebar--right">
                <div class="ad-container mb-40 thumb--size-2 thumb-container">
                </div>
                <!-- Widget Popular Posts -->
                <aside class="widget widget-popular-posts">
                    <h4 class="widget-title">জনপ্রিয় পোস্ট</h4>
                    <ul class="post-list-small">
						<?php
						foreach ( $this->data['popularPosts'] as $popularPost ) { ?>

                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder post-list-left">
                                        <div class="thumb-container thumb-100">
                                            <a href="<?= APP_URL ?>/article/<?= $popularPost['path'] ?>">
                                                <img src="<?= APP_URL ?>/public/images/article/thumbnail/<?= $popularPost['thumb'] ?>" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="entry__title text-truncate-3">
                                            <a href="<?= APP_URL ?>/article/<?= $popularPost['path'] ?>"> <?= $popularPost['title'] ?? "" ?></a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date"><?= Util::BanglaTimeDiff( $popularPost['created'] ) ?>

                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>

						<?php } ?>
                    </ul>
                </aside>
                <!-- end widget popular posts -->
            </aside>
            <!--end sidebar-->
        </div>
        <!--  end main container-->
    </div>

<?php include_once VIEW_PATH . '_common/frontend/footer.php'; ?>