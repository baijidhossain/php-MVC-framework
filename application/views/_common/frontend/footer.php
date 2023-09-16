<!-- Footer-->
<?php

$footerLinks = [
	[
		'title' => 'সমাজ-সংস্কৃতি',
		'path'  => APP_URL . '/category/সমাজ-সংস্কৃতি',
	],
    [
        'title' => 'অর্থ-অনর্থ',
        'path'  => APP_URL . '/category/অর্থ-অনর্থ',
    ],
    [
        'title' => 'নীতির রাজা',
        'path'  => APP_URL . '/category/নীতির-রাজা',
    ],
    [
        'title' => 'খেলা',
        'path'  => APP_URL . '/category/খেলা',
    ],
    [
        'title' => 'বিনোদন',
        'path'  => APP_URL . '/category/বিনোদন',
    ],
    [
        'title' => 'বিজ্ঞান',
        'path'  => APP_URL . '/category/বিজ্ঞান',
    ],

];

?>
<footer class="footer">
    <div class="container">
        <div class="footer__widgets pt-56 pb-32" style="
            border-top: 1px solid #e2e2e2;
            border-bottom: 1px solid #e2e2e2;
          ">
            <aside class="widget widget-logo pb-4">
                <a href="<?= APP_URL ?>">
                    <img src="<?= APP_URL ?>/public/frontend/images/bhalodesh.svg" srcset="<?= APP_URL ?>/public/frontend/images/bhalodesh.svg 1x, <?= APP_URL ?>/public/frontend/images/bhalodesh.svg 2x" class="logo__img" alt="bhalodesh.com" width="200" height="100" loading="lazy">
                </a>
            </aside>
            <div class="row">

				<?php foreach ( $footerLinks as $link ): ?>

                    <div class="col-xl-2 col-md-4">
                        <aside class="widget widget_categories">
                            <ul>
                                <li class="mb-2"><a style="font-size: 1.1rem;" href="<?= $link['path'] ?>"><?= $link['title'] ?> </a></li>
                            </ul>
                        </aside>
                    </div>
				<?php endforeach; ?>

            </div>
        </div>
    </div>
    <!-- end container -->
    <div class="footer__middle mb-24 mt-24">
        <div class="container">
            <p class="text-center">অনুসরণ করুন</p>
            <div class="socials socials--white-base socials--rounded justify-content-center">
                <a href="#" class="social social-facebook" aria-label="facebook"> <i class="ri-facebook-fill"></i></a>
                <a href="#" class="social social-twitter" aria-label="twitter"> <i class="ri-twitter-fill"></i></a>
                <a href="#" class="social social-youtube" aria-label="youtube"> <i class="ri-youtube-fill"></i></a>
                <a href="#" class="social social-instagram" aria-label="instagram"> <i class="ri-instagram-fill"></i></a>
            </div>
        </div>
    </div>
    <div class="footer__bottom footer__bottom--white py-1 border-top border-bottom">
        <div class="container text-center">
            <ul class="footer__nav-menu footer__nav-menu--1">
                <li><a href="<?= APP_URL ?>/about/">আমাদের সম্পর্কে</a></li>
                <li><a href="<?= APP_URL ?>/terms/">গোপনীয়তা নীতি</a></li>
                <li><a href="<?= APP_URL ?>/contact/">যোগাযোগ</a></li>
            </ul>
        </div>
    </div>
    <!-- end footer bottom -->
    <div class="container text-center">
        <p class="copyright mt-2 mb-4">
            © <?= date( 'Y' ) ?> Bhalodesh | Made by <a href="https://alpha.net.bd/" target="_blank">Alpha Net</a>
        </p>
    </div>
</footer>

<div id="back-to-top" class="show">
    <a href="#top" aria-label="Go to top"><i class="ri-arrow-up-s-line"></i></a>
</div>

</main>

<!-- jQuery Scripts -->
<?php $this->loadScript(); ?>

<script src="//instant.page/5.1.1" type="module" integrity="sha384-MWfCL6g1OTGsbSwfuMHc8+8J2u71/LA8dzlIN3ycajckxuZZmF+DNjdm7O6H3PSq"></script>

<script src="https://accounts.google.com/gsi/client" async defer></script>
<form action="<?= APP_URL ?>/account/OAuth" method="post" id="googleLoginForm">
    <input type="hidden" name="credentials">
</form>
<div id="g_id_onload"
     data-client_id="<?= GOOGLE_CLIENT_ID ?>"
     data-context="Sign in"
     data-callback="handleCredentialResponse"
     data-itp_support="true">
</div>
<script>
    function handleCredentialResponse(response) {
        document.getElementById("googleLoginForm").querySelector('input').value = response.credential;
        document.getElementById("googleLoginForm").submit();
    }
</script>
</body>

</html>