$ = jQuery;
$(document).ready(function () {
    var width = document.body.clientWidth;

    $("#menuOpen").click(function (e) {
        $(this).toggleClass("opened");
        $("body").toggleClass("is_overflow");
    });

    if (width >= 1081) {

    } else {
        $("#mainMenu .menu-item-has-children > a").append("<span></span>");
        $("#mainMenu .menu-item-has-children span").click(function () {
            $(this).parent().next().slideToggle(300);
            $(this).toggleClass("active");
            return false;
        });
        $('p').each(function () {
            var $this = $(this);
            if ($this.html().replace(/\s|&nbsp;/g, '').length == 0)
                $this.remove();
        });
    }

    // swiper - block__custom_slider
    $('.slider').each(function () {
        let slider_holder = $(this),
            swiper_instance = slider_holder.find('.swiper'),
            prev = slider_holder.find('.swiper-prev'),
            next = slider_holder.find('.swiper-next');
            pagination = slider_holder.find('.swiper-pagination');

        let block_slider = new Swiper(swiper_instance[0], {
            spaceBetween: 20,
            slidesPerView: 1,
            watchOverflow: true,
            autoHeight: true,
            navigation: {
                nextEl: next[0],
                prevEl: prev[0]
            },
            pagination: {
                el: pagination[0],
                type: 'bullets',
                clickable: true
            },
            grabCursor: true,
            effect: "creative",
            creativeEffect: {
                prev: {
                    translate: ["-20%", 0, -1],
                },
                next: {
                    translate: ["100%", 0, 0],
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
    });

    // custom select
    if($('select').length > 0) {
        $('select').selectric({
            disableOnMobile: false,
            nativeOnMobile: false,
            arrowButtonMarkup: '<span class="select_arrow"></span>'
        });
        // $('select.wpcf7-form-control').each(function () {
        //     $(this).find('option').first().val('');
        // });
    }

    //WPCF7
    $(this).on('click', '.wpcf7-not-valid-tip', function () {
        $(this).prev().trigger('focus');
        $(this).fadeOut(500, function () {
            $(this).remove();
        });
    });

    $("iframe").wrap("<div class='fullframe'></div>");

    $(window).bind("resize", function () {

    });


});