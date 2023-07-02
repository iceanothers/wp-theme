<?php

// custom js/stylesheet
function tt_add_jscss() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'jquery' );
    }

    if ( defined( 'WPCF7_VERSION' ) ) {
        wp_deregister_style( 'contact-form-7' );
    }

    /*if(defined('GOOGLEMAPS')) {
        wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?v=3.exp&language=en&key=AIzaSyAO77hGcvxmsvOn1RSjDFQMI4YUnW89MDo', false, null, false);
    }*/

    wp_enqueue_script( 'jquery', get_stylesheet_directory_uri() . '/js/_jquery.js', false, null, false );
    wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/logic.js', array( 'jquery' ), null, true );

    wp_enqueue_style( 'fonts', get_stylesheet_directory_uri() . '/style/fonts.css', null, null );
    wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style/style.css', null, null );
    if ( is_front_page() ) {
        wp_enqueue_style( 'front-page', get_stylesheet_directory_uri() . '/style/templates/front-page.css', null, null );
    } elseif ( is_home() && ! is_front_page() || is_author() || is_search() || is_category() ) {
        wp_enqueue_style( 'blog', get_stylesheet_directory_uri() . '/style/templates/blog.css', null, null );
    } elseif ( is_singular( 'post' ) ) {
        wp_enqueue_style( 'blog-post', get_stylesheet_directory_uri() . '/style/templates/blog-post.css', null, null );
    }

}

add_action( 'wp_enqueue_scripts', 'tt_add_jscss' );


// disable Gutenberg style on Front
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}

add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
