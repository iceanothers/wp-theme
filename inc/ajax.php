<?php

// Renders the posts markup only — no wp_die(). Safe to call both from the
// AJAX handler below and directly from a template for the initial page load.
function render_posts_ajax( $paged = 1, $category = null ) {
    $category = $category ?? ( isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : null );
    $paged    = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : absint( $paged );
    if ( $paged < 1 ) {
        $paged = 1;
    }

    $tax_query = [];

    if ( $category && $category !== '*' ) {
        $tax_query[] = [
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => [ $category ]
        ];
    }

    $args = [
        'posts_per_page' => 10,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'paged'          => $paged,
    ];

    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'tpl-parts/post-item' );
        }
        wp_reset_postdata();

        if ( $paged < $query->max_num_pages ) {
            echo '<div class="load_more_holder">
                    <a class="button load_more__posts"
                       data-href="' . esc_attr( $paged + 1 ) . '"
                       data-cat="' . esc_attr( $category ) . '"
                       aria-label="Load page ' . esc_attr( $paged + 1 ) . '"
                       href="#">Load More</a>
                  </div>
                  <div class="loader_holder">' . wp_kses( get_loader(), $GLOBALS['allowed_loader'] ) . '</div>';
        }
    } else {
        echo '<div><h3 class="custom_coming_soon">Oops! Nothing found.</h3></div>';
    }
}

// AJAX handler (wp_ajax_*) — terminates the request, unlike render_posts_ajax().
function load_posts_ajax() {
    render_posts_ajax();
    wp_die();
}
add_action( 'wp_ajax_load_posts_ajax', 'load_posts_ajax' );
add_action( 'wp_ajax_nopriv_load_posts_ajax', 'load_posts_ajax' );
