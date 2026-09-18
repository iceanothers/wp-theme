<?php

/* Theme config params */

// defines
//define ('GOOGLEMAPS', TRUE);
define ('HOME_PAGE_ID', get_option('page_on_front'));
define ('BLOG_ID', get_option('page_for_posts'));
define ('POSTS_PER_PAGE', get_option('posts_per_page'));
// prevent file modifications
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

// recommended plugins installer
require_once 'plugins/installer.php';

// include custom assets
require_once('assets.php');

// custom admin area functions
require_once('admin-area/admin-area.php');

// custom shortcodes
require_once('shortcodes.php');

// custom ajax functions
require_once('ajax.php');

// custom posts duplicator
require_once('plugins/duplicator.php');

// cyr to lat slugs
require_once('plugins/cyr-to-lat.php');

// ACF settings
require_once('acf.php');

// uncomment if need CPT
//require_once('cpt.php');

// custom theme URL
function theme($filepath = NULL) {
	return preg_replace( '(https?://)', '//', get_stylesheet_directory_uri() . ($filepath?'/' . $filepath:'') );
}

//light function fo wp_get_attachment_image_src()
function image_src($id, $size = 'full', $background_image = false, $height = false) {
    if ($image = wp_get_attachment_image_src($id, $size, true)) {
        return $background_image ? 'background-image: url('.$image[0].');' . ($height?'min-height:'.$image[2].'px':'') : $image[0];
    }
}

// get alt or image name
function get_alt($id) {
	$c_alt = get_post_meta($id, '_wp_attachment_image_alt', true);
	$c_tit = get_the_title($id);
	return $c_alt?$c_alt:$c_tit;
}

// loading spinner markup, used by AJAX post filtering (inc/ajax.php, tpl-parts/posts-filters.php)
function get_loader() {
	return '<div class="loader"><span></span></div>';
}

// allowed tags for wp_kses() around get_loader()
$GLOBALS['allowed_loader'] = [
	'div'  => [ 'class' => true ],
	'span' => [ 'class' => true ],
];

// run this code on 'after_theme_setup', when plugins have already been loaded
add_action('after_setup_theme', 'wpa_activate_theme');
// this function loads the plugins & updates some WordPress options
function wpa_activate_theme() {
	update_option('image_default_link_type','none');
	// comment this before the build if uploads from the old website is being migrated with default year-month structure
	update_option('uploads_use_yearmonth_folders', 0);
}

// remove default menu classes + new custom classes
function wpa_discard_menu_classes($classes, $item) {
	$classes = array_filter(
		$classes, function($class) {return in_array( $class, array( "current-menu-item", "current-menu-parent", "current_page_parent", "menu-item-has-children" )); }
	);
	return array_merge(
		$classes,
		(array)get_post_meta( $item->ID, '_menu_item_classes', true )
	);
}

// new body classes
function wpa_body_classes( $classes ) {
    global $post;

    // 🧩 Add template name as class
    if ( is_page() ) {
        $template = basename( get_page_template() );
        if ( $template ) {
            $template_class = sanitize_html_class( str_replace( '.php', '', $template ) );
            $classes[] = $template_class;

            // 🔍 Remove redundant template-related classes
            $classes = array_filter( $classes, function( $class ) use ( $template_class ) {
                $remove = [
                    'page-template',
                    'page-template-default',
                    'woocommerce-page',
                    'page-template-' . $template_class,
                    'page-template-' . $template_class . '-php',
                ];
                return !in_array( $class, $remove, true );
            });
        }
    }

    // 📝 Cleanup post-related classes
    if ( is_single() && isset( $post ) ) {
        $format = get_post_format( $post->ID );
        $classes = array_filter( $classes, function( $class ) use ( $post, $format ) {
            return $class !== 'postid-' . $post->ID &&
                   $class !== 'single-format-' . ( $format ?: 'standard' );
        });
    }

    // 👥 Multiauthor blog
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    // 💻 OS detection
    $browser = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if ( stripos( $browser, 'Mac' ) !== false ) {
        $classes[] = 'macos';
    } elseif ( stripos( $browser, 'Windows' ) !== false ) {
        $classes[] = 'windows';
    } elseif ( stripos( $browser, 'Linux' ) !== false ) {
        $classes[] = 'linux';
    } else {
        $classes[] = 'unknown-os';
    }

    // 🌐 Browser detection (optional)
    // Modern Chromium Edge sends "Edg/" (desktop), "EdgA/" (Android), "EdgiOS/" (iOS) —
    // only legacy Edge used "Edge/". Both also contain "Chrome", so check Edge first.
    if ( preg_match( '/Edg(?:A|iOS)?\//', $browser ) || stripos( $browser, 'Edge' ) !== false ) {
        $classes[] = 'edge';
    } elseif ( stripos( $browser, 'Chrome' ) !== false ) {
        $classes[] = 'chrome';
        if ( preg_match( '/Chrome\/(\d+\.\d+)/', $browser, $matches ) ) {
            $classes[] = 'ch' . str_replace( '.', '-', $matches[1] );
        }
    } elseif ( stripos( $browser, 'Safari' ) !== false ) {
        $classes[] = 'safari';
        if ( preg_match( '/Version\/(\d+\.\d+)/', $browser, $matches ) ) {
            $classes[] = 'sf' . str_replace( '.', '-', $matches[1] );
        }
    } elseif ( stripos( $browser, 'Opera' ) !== false ) {
        $classes[] = 'opera';
        if ( preg_match( '/Opera\/(\d+\.\d+)/', $browser, $matches ) ) {
            $classes[] = 'op' . str_replace( '.', '-', $matches[1] );
        }
    } elseif ( stripos( $browser, 'MSIE' ) !== false ) {
        $classes[] = 'msie';
        if ( preg_match( '/MSIE\s(\d+\.\d+)/', $browser, $matches ) ) {
            $classes[] = 'ie' . intval( $matches[1] );
        }
    } elseif ( stripos( $browser, 'Firefox' ) !== false && stripos( $browser, 'Gecko' ) !== false ) {
        $classes[] = 'firefox';
        if ( preg_match( '/Firefox\/(\d+)/', $browser, $matches ) ) {
            $classes[] = 'ff' . $matches[1];
        }
    } else {
        $classes[] = 'unknown-browser';
    }

    return $classes;
}

add_filter('body_class', 'wpa_body_classes');

// custom SEO title
// wp_title() has been deprecated since WP 4.4 — use add_theme_support('title-tag')
// (below, in wpa_init()) + the 'document_title_parts' filter instead. WP then
// prints <title> itself via wp_head(), no manual echo needed in header.php.
add_filter( 'document_title_parts', function ( $parts ) {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return $parts; // Yoast already handles this
    }

    global $post;

    if ( is_404() ) {
        $parts['title'] = '404 Page not found';
    } elseif ( ( is_single() || is_page() ) && ! empty( $post->post_parent ) ) {
        $parts['title'] = get_the_title() . ' - ' . get_the_title( $post->post_parent );
    } elseif ( class_exists( 'Woocommerce' ) && function_exists( 'is_shop' ) && is_shop() ) {
        $parts['title'] = get_the_title( wc_get_page_id( 'shop' ) );
    }

    return $parts;
});

function wpa_init() {
    // Enable post thumbnails support
    add_theme_support( 'post-thumbnails' );

    // Auto <title> via wp_head() — see 'document_title_parts' filter above
    add_theme_support( 'title-tag' );

    // Remove unnecessary tags from wp_head
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'parent_post_rel_link', 10 );
    remove_action( 'wp_head', 'start_post_rel_link', 10 );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

    // Disable Emoji across the frontend, admin, feeds, emails and TinyMCE
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'embed_head', 'print_emoji_detection_script' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', function( $plugins ) {
        return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
    });
    if ( (int) get_option( 'use_smilies' ) === 1 ) {
        update_option( 'use_smilies', 0 );
    }

    // Customize menu classes output
    add_filter( 'nav_menu_css_class', 'wpa_discard_menu_classes', 10, 2 );
    add_filter( 'nav_menu_item_id', '__return_false', 10 );

    // Extend and clean up body classes
    add_filter( 'body_class', 'wpa_body_classes' );

    // Prevent automatic <p> and <br> tags in Contact Form 7
    add_filter( 'wpcf7_autop_or_not', '__return_false' );
}
add_action( 'init', 'wpa_init', 9999 );

//remove gallery styles
add_filter( 'use_default_gallery_style', '__return_false' );

function wpa_html_lang($echo = true) {
    $lang = get_locale();
    if(function_exists('qtranxf_getLanguage')) {
        $qconf = $GLOBALS['q_config'];
        $curr  = qtranxf_getLanguage();
        $lang  = $qconf['locale_html'][ $curr ];
        if(empty($lang)) {
            $lang = $qconf['locale'][ $curr ];
        }
    }
    if($echo) {
        echo $lang;
    } else {
        return $lang;
    }
}
