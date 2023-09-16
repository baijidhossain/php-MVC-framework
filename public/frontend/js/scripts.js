(function ($) {
    "use strict";

    var $window = $(window);

    $window.on('load', function () {
        $window.trigger("resize");
    });

    // Preloader
    $('.loader').fadeOut();
    $('.loader-mask').delay(350).fadeOut('slow');


    // Init
    // initOwlCarousel();


    $window.on('resize', function () {
        hideSidenav();
        megaMenu();
    });


    /* Detect Browser Size
    -------------------------------------------------------*/
    var minWidth;
    if (Modernizr.mq('(min-width: 0px)')) {
        // Browsers that support media queries
        minWidth = function (width) {
            return Modernizr.mq('(min-width: ' + width + 'px)');
        };
    } else {
        // Fallback for browsers that does not support media queries
        minWidth = function (width) {
            return $window.width() >= width;
        };
    }


    /* Mobile Detect
    -------------------------------------------------------*/
    if (/Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent || navigator.vendor || window.opera)) {
        $("html").addClass("mobile");
        $('.dropdown-toggle').attr('data-toggle', 'dropdown');
    } else {
        $("html").removeClass("mobile");
    }

    /* IE Detect
    -------------------------------------------------------*/
    if (Function('/*@cc_on return document.documentMode===10@*/')()) {
        $("html").addClass("ie");
    }


    /* Sticky Navigation
    -------------------------------------------------------*/
    $window.scroll(function () {

        scrollToTop();
        var $stickyNav = $('.nav--sticky');

        if ($(window).scrollTop() > 190) {
            $stickyNav.addClass('sticky');
        } else {
            $stickyNav.removeClass('sticky');
        }

        if ($(window).scrollTop() > 200) {
            $stickyNav.addClass('offset');
        } else {
            $stickyNav.removeClass('offset');
        }

        if ($(window).scrollTop() > 500) {
            $stickyNav.addClass('scrolling');
        } else {
            $stickyNav.removeClass('scrolling');
        }

    });


    /* Mobile Navigation
    -------------------------------------------------------*/
    var $sidenav = $('#sidenav'),
        $mainContainer = $('#main-container'),
        $navIconToggle = $('.nav-icon-toggle'),
        $navHolder = $('.nav__holder'),
        $contentOverlay = $('.content-overlay'),
        $htmlContainer = $('html'),
        $sidenavCloseButton = $('#sidenav__close-button');


    $navIconToggle.on('click', function (e) {
        e.stopPropagation();
        $(this).toggleClass('nav-icon-toggle--is-open');
        $sidenav.toggleClass('sidenav--is-open');
        $contentOverlay.toggleClass('content-overlay--is-visible');
    });

    function resetNav() {
        $navIconToggle.removeClass('nav-icon-toggle--is-open');
        $sidenav.removeClass('sidenav--is-open');
        $contentOverlay.removeClass('content-overlay--is-visible');
    }

    function hideSidenav() {
        if (minWidth(992)) {
            resetNav();
            setTimeout(megaMenu, 500);
        }
    }

    $contentOverlay.on('click', function () {
        resetNav();
    });

    $sidenavCloseButton.on('click', function () {
        resetNav();
    });


    var $dropdownTrigger = $('.nav__dropdown-trigger'),
        $navDropdownMenu = $('.nav__dropdown-menu'),
        $navDropdown = $('.nav__dropdown');


    if ($('html').hasClass('mobile')) {

        $('body').on('click', function () {
            $navDropdownMenu.addClass('hide-dropdown');
        });

        $navDropdown.on('click', '> a', function (e) {
            e.preventDefault();
        });

        $navDropdown.on('click', function (e) {
            e.stopPropagation();
            $navDropdownMenu.removeClass('hide-dropdown');
        });
    }


    /* Sidenav Menu
    -------------------------------------------------------*/
    $('.sidenav__menu-toggle').on('click', function (e) {
        e.preventDefault();

        var $this = $(this);

        $this.parent().siblings().removeClass('sidenav__menu--is-open');
        $this.parent().siblings().find('li').removeClass('sidenav__menu--is-open');
        $this.parent().find('li').removeClass('sidenav__menu--is-open');
        $this.parent().toggleClass('sidenav__menu--is-open');

        if ($this.next().hasClass('show')) {
            $this.next().removeClass('show').slideUp(350);
        } else {
            $this.parent().parent().find('li .sidenav__menu-dropdown').removeClass('show').slideUp(350);
            $this.next().toggleClass('show').slideToggle(350);
        }
    });


    /* Nav Seacrh
    -------------------------------------------------------*/
    (function () {
        var navSearchTrigger = $('.nav__search-trigger'),
            navSearchTriggerIcon = navSearchTrigger.find('i'),
            navSearchBox = $('.nav__search-box'),
            navSearchInput = $('.nav__search-input');

        navSearchTrigger.on('click', function (e) {
            e.preventDefault();
            navSearchTriggerIcon.toggleClass('ui-close');
            navSearchBox.slideToggle();
            navSearchInput.focus();
        });
    })();


    /* Mega Menu
    -------------------------------------------------------*/
    function megaMenu() {
        $('.nav__megamenu').each(function () {
            var $this = $(this);

            $this.css('width', $('.container').width());
            var offset = $this.closest('.nav__dropdown').offset();
            offset = offset.left;
            var containerOffset = $(window).width() - $('.container').outerWidth();
            containerOffset = containerOffset / 2;
            offset = offset - containerOffset - 15;
            $this.css('left', -offset);
        });
    }


    /* Tabs
    -------------------------------------------------------*/
    $('.tabs__trigger').on('click', function (e) {
        var currentAttrValue = $(this).attr('href');
        $('.tabs__content-trigger ' + currentAttrValue).stop().fadeIn(1000).siblings().hide();
        $(this).parent('li').addClass('tabs__item--active').siblings().removeClass('tabs__item--active');
        e.preventDefault();
    });


    /* Owl Carousel
    -------------------------------------------------------*/
    function initOwlCarousel() {

        // Hero Slider
        $("#owl-hero-slider").owlCarousel({
            center: true,
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            margin: 8,
            lazyLoad: true,
            navSpeed: 500,
            navText: ['<i class="ui-arrow-left">', '<i class="ui-arrow-right">'],
            responsive: {
                1200: {
                    items: 4
                },
                768: {
                    items: 2
                },
                540: {
                    items: 2
                }
            }
        });

        // Posts Carousel
        $("#owl-posts").owlCarousel({
            center: false,
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            margin: 30,
            lazyLoad: true,
            navSpeed: 500,
            navText: ['<i class="ui-arrow-left">', '<i class="ui-arrow-right">'],
            responsive: {
                768: {
                    items: 4
                },
                540: {
                    items: 3
                }
            }
        });

        // Related Posts
        $("#owl-posts-3-items").owlCarousel({
            center: false,
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            margin: 20,
            lazyLoad: true,
            navSpeed: 500,
            navText: ['<i class="ui-arrow-left">', '<i class="ui-arrow-right">'],
            responsive: {
                768: {
                    items: 3
                },
                540: {
                    items: 2
                }
            }
        });

        // Headlines
        $("#owl-headlines").owlCarousel({
            items: 1,
            loop: true,
            nav: false,
            dots: false,
            lazyLoad: true,
            navSpeed: 500,
            navText: ['<i class="ui-arrow-left">', '<i class="ui-arrow-right">']
        });

        // Single Image
        $("#owl-single").owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            lazyLoad: true,
            navSpeed: 500,
            navText: ['<i class="ui-arrow-left">', '<i class="ui-arrow-right">']
        });

        // Single Post Gallery
        $("#owl-single-post-gallery").owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: true,
            lazyLoad: true,
            navSpeed: 500,
            navText: ['<i class="ui-arrow-left">', '<i class="ui-arrow-right">']
        });

        // Custom nav
        var owlCustomNav = $('#owl-headlines').owlCarousel();

        $(".owl-custom-nav__btn--prev").on('click', function () {
            owlCustomNav.trigger('prev.owl.carousel');
        });

        $(".owl-custom-nav__btn--next").on('click', function () {
            owlCustomNav.trigger('next.owl.carousel');
        });
    }


    /* Sticky Socials
    -------------------------------------------------------*/
    (function () {
        var $stickyCol = $('.sticky-col');
        if ($stickyCol.length > 0) {
            $stickyCol.stick_in_parent({
                offset_top: 80
            });
        }
    })();


    /* Lightbox popup
    -------------------------------------------------------*/
    (function () {
        let $gallery = $(".lightbox-gallery");
        let $video = $(".lightbox-video");

        if ($gallery.length > 0) {
            $('.lightbox-gallery').magnificPopup({
                // delegate: 'a',
                type: 'image',
                tLoading: 'Loading image #%curr%...',
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0, 1]
                },
                image: {
                    verticalFit: true,
                    titleSrc: function (item) {
                        return item.el.attr('title');
                    }
                },
                zoom: {
                    enabled: true,
                    duration: 300, // don't foget to change the duration also in CSS
                    opener: function (element) {
                        return element.find('img');
                    }
                }
            });
        }


        if ($video.length > 0) {
            $video.magnificPopup({
                type: 'iframe',
                iframe: {
                    patterns: {
                        youtube: {
                            index: 'youtube.com',
                            id: 'v=',
                            src: '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
                        }
                    }
                }
            });
        }

    })();


    /* Scroll to Top
    -------------------------------------------------------*/
    function scrollToTop() {
        var scroll = $window.scrollTop();
        var $backToTop = $("#back-to-top");
        if (scroll >= 50) {
            $backToTop.addClass("show");
        } else {
            $backToTop.removeClass("show");
        }
    }

    $('a[href="#top"]').on('click', function () {
        $('html, body').animate({scrollTop: 0}, 1000, "easeInOutQuint");
        return false;
    });

    // edit comment
    $('.comment-edit').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        let $comment = $this.closest('.comment');
        let $commentForm = $comment.find('.comment-edit-form').eq(0);
        $commentForm.slideToggle();
    });

    // reply comment
    $('.comment-reply').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        let $comment = $this.closest('.comment');
        let $commentForm = $comment.find('.comment-reply-form').eq(0);
        $commentForm.slideToggle();
    });


})(jQuery);

// copy me script
let copyText = document.querySelector("a.copy-me");

if (copyText) {
    copyText.addEventListener("click", function (evt) {
        evt.preventDefault();
        navigator.clipboard.writeText(copyText.getAttribute('href')).then(() => {
            /* clipboard successfully set */
            copyText.innerHTML = "<small>copied</small>";
            setTimeout(() => {
                copyText.innerHTML = "<i class=\"ri-links-line\"></i>";
            }, 2500);
        }, () => {
            /* clipboard write failed */
        });
    });
}