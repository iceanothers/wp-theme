// Ensure global jQuery reference
$ = jQuery;

// AJAX posts filtering / load more (inc/ajax.php, tpl-parts/posts-filters.php)
import './ajax.js';

$(document).ready(function () {
    const width = document.body.clientWidth;
    // Toggle mobile menu
    $('#menuOpen').on('click', function () {
        $(this).toggleClass('opened');
        $('body').toggleClass('is_overflow');
    });

    // Mobile navigation dropdown
    if (width < 1081) {
        $('#mainMenu .menu-item-has-children > a').append('<span></span>');

        $('#mainMenu .menu-item-has-children span').on('click', function () {
            $(this).parent().next().slideToggle(300);
            $(this).toggleClass('active');
            return false;
        });

        // Remove empty <p> tags
        $('p').each(function () {
            const $this = $(this);
            if ($this.html().replace(/\s|&nbsp;/g, '').length === 0) {
                $this.remove();
            }
        });
    }

    /*// Swiper sliders initialization
    $('.slider').each(function () {
        const slider = $(this);
        const swiperEl = slider.find('.swiper')[0];
        const prevEl = slider.find('.swiper-prev')[0];
        const nextEl = slider.find('.swiper-next')[0];
        const paginationEl = slider.find('.swiper-pagination')[0];

        new Swiper(swiperEl, {
            spaceBetween: 20,
            slidesPerView: 1,
            watchOverflow: true,
            autoHeight: true,
            navigation: {
                nextEl: nextEl,
                prevEl: prevEl
            },
            pagination: {
                el: paginationEl,
                type: 'bullets',
                clickable: true
            },
            grabCursor: true,
            effect: 'creative',
            creativeEffect: {
                prev: {
                    translate: ['-20%', 0, -1],
                },
                next: {
                    translate: ['100%', 0, 0],
                },
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1025: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                }
            }
        });
    });*/

    // Custom select styling
    /*if ($('select').length > 0) {
        $('select').selectric({
            disableOnMobile: false,
            nativeOnMobile: false,
            arrowButtonMarkup: '<span class="select_arrow"></span>'
        });

        // Optional: reset first option for CF7 selects
        // $('select.wpcf7-form-control').each(function () {
        //     $(this).find('option').first().val('');
        // });
    }*/

    // Contact Form 7 - remove validation tip on click
    $(document).on('click', '.wpcf7-not-valid-tip', function () {
        $(this).prev().trigger('focus');
        $(this).fadeOut(500, function () {
            $(this).remove();
        });
    });

    // Make iframe responsive
    $('iframe').wrap("<div class='fullframe'></div>");

    // Window resize (empty, reserved for future logic)
    $(window).on('resize', function () {
        // Add responsive handling if needed
    });
});