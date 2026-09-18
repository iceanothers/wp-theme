<?php

function tt_enqueue_assets() {
    // 🧹 Відключити стандартний jQuery на фронті
    if ( ! is_admin() ) {
        wp_deregister_script( 'jquery' );
        // in_footer=true — не блокує рендер у <head>
        wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/_jquery.js', [], null, true );
    }

    // 🧼 Відключити стилі Contact Form 7
    if ( defined( 'WPCF7_VERSION' ) ) {
        wp_deregister_style( 'contact-form-7' );
    }

    // 🧠 Автокеш-контроль через filemtime()
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    $core_css = '/dist/styles.css';
    $core_js  = '/dist/main.js';

    if ( file_exists( $dir . $core_css ) ) {
        wp_enqueue_style( 'theme-style', $uri . $core_css, [], filemtime( $dir . $core_css ) );
    }

    if ( file_exists( $dir . $core_js ) ) {
        wp_enqueue_script( 'theme-scripts', $uri . $core_js, [ 'jquery' ], filemtime( $dir . $core_js ), true );
    }

    // 🧩 Шрифти (якщо існують)
    $fonts_path = '/style/fonts.css';
    if ( file_exists( $dir . $fonts_path ) ) {
        wp_enqueue_style( 'theme-fonts', $uri . $fonts_path, [], filemtime( $dir . $fonts_path ) );
    }

    // 🎯 Шаблонні асети (автоматичні через функцію нижче)
    if ( is_front_page() ) {
        enqueue_tpl_asset('front-page');
    } elseif ( is_page_template( 'tpl-about.php' ) ) {
        enqueue_tpl_asset('about');
    } elseif ( is_page_template( 'tpl-contacts.php' ) ) {
        enqueue_tpl_asset('contacts');
    } elseif ( is_home() || is_author() || is_search() || is_category() ) {
        enqueue_tpl_asset('blog');
    } elseif ( is_singular( 'post' ) ) {
        enqueue_tpl_asset('single-post');
    }
}
add_action( 'wp_enqueue_scripts', 'tt_enqueue_assets' );


// 🧩 Функція для автоматичного підключення шаблонних асетів
function enqueue_tpl_asset( $name ) {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    $css_file = "/dist/{$name}_style.css";
    $js_file  = "/dist/{$name}_script.js";

    if ( file_exists( $dir . $css_file ) ) {
        wp_enqueue_style(
            "{$name}_style",
            $uri . $css_file,
            [],
            filemtime( $dir . $css_file )
        );
    }

    if ( file_exists( $dir . $js_file ) ) {
        wp_enqueue_script(
            "{$name}_script",
            $uri . $js_file,
            [],
            filemtime( $dir . $js_file ),
            true
        );
    }
}


// 🧼 Вимкнути стилі Gutenberg
function tt_remove_gutenberg_styles() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_print_styles', 'tt_remove_gutenberg_styles', 100 );