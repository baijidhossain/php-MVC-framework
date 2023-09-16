$(document).ready(function() {
    $('#sidebar-wrapper').theiaStickySidebar({
        additionalMarginTop: 50,
    });

    $('.slide-menu-toggle').on('click', function() {
        $('body').toggleClass('nav-active');
    });

    $('.submenu-toggle').on('click', function() {

        $(this).parent().toggleClass('show');
        $(this).prev('.m-sub').toggle();
    });

    $('.show-search').on('click', function() {
        $('#nav-search').fadeIn().find('input').focus();
    });

    $('.hide-search').on('click', function() {
        $('#nav-search').fadeOut();
    });

    $('.owl-carousel').owlCarousel({
        loop: true,
        autoplay: true,
        margin: 10,
        nav: false,
        dots: true,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 2,
            },
            1000: {
                items: 3,
            },
        },
    });

    $("#myBtn").click(function() {
        $("#myModal").show();
    });



    $(".close").click(function() {

        $("#myModal").hide();

    });


    $(".thread-toggle").click(function(event) {
        event.preventDefault();
        $(this).toggleClass("thread-collapsed thread-expanded");
        $(this).siblings('.thread-chrome').toggleClass("thread-collapsed thread-expanded");
    });


    $('#main-menu-nav li').find(`a[href='${location.origin+location.pathname}']`).parent('li').addClass('menu_active');



});



//var modal = document.getElementById("myModal");



// window.onclick = function(event) {

//     if (event.target == modal) {
//         modal.style.display = "none";
//     }

// }