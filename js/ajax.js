// self-contained: don't rely on logic.js's `$ = jQuery;` assignment running
// first — ES module imports execute before the importing module's own body.
const $ = window.jQuery;

// Load posts via AJAX
function load_posts_ajax(paged = 1, category = '*') {
    const ajaxContent = $('.posts__container');

    $.ajax({
        type: 'POST',
        url: $('body').data('a'),
        data: {
            action: 'load_posts_ajax',
            paged: paged,
            category: category
        },
        success: function (html) {
            $('.loader_holder').remove();

            if (paged !== 1) {
                ajaxContent.append(html);
            } else {
                ajaxContent.html(html);
                $('html, body').animate({ scrollTop: ajaxContent.offset().top - 50 }, 400);
            }

            $('.show_box').removeClass('is_loading');
        }
    });

    return false;
}

$(document).ready(function () {
    'use strict';

    const postsFilters = $('.posts__filters a');
    const postsDropdown = $('.posts__dropdown');

    // Filter by category - desktop
    postsFilters.on('click', function () {
        const cat = $(this).attr('href');
        $('.posts__filtering .show_box').addClass('is_loading');

        load_posts_ajax(1, cat);

        postsFilters.removeClass('is_filtered');
        $(this).addClass('is_filtered');

        window.location.hash = cat;
        return false;
    });

    // Apply filter from URL hash - desktop
    postsFilters.each(function () {
        if ($(this).attr('href') === window.location.hash) {
            $(this).trigger('click');
        }
    });

    // Filter by category - mobile
    postsDropdown.on('change', function () {
        const cat = $(this).val();
        $('.posts__filtering .show_box').addClass('is_loading');

        load_posts_ajax(1, cat);
        window.location.hash = cat;
    });

    // Apply filter from URL hash - mobile
    postsDropdown.find('option').each(function () {
        if ($(this).val() === window.location.hash) {
            $(this).prop('selected', true).trigger('change');
        }
    });

    // Load More
    $(document).on('click', '.load_more__posts', function () {
        const btn = $(this);
        const nextPage = btn.data('href');
        const category = btn.data('cat');

        $('.posts__container .show_box').addClass('is_loading');

        load_posts_ajax(nextPage, category);
        btn.parent().remove();

        return false;
    });
});