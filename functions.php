<?php

// Load core theme logic from external file
require_once get_template_directory() . '/inc/themer.php';

// Register navigation menus
register_nav_menus([
    'main_menu' => 'Main Menu',
    // 'footer_menu' => 'Footer Menu',
]);

// Register sidebars
$sidebars = [
    'blog_sidebar' => 'Blog Sidebar',
];

foreach ( $sidebars as $id => $name ) {
    register_sidebar([
        'name'          => __( $name, 'textdomain' ),
        'id'            => $id,
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgetTitle">',
        'after_title'   => '</h2>',
    ]);
}

// Register custom image size
// NOTE: 'full' is a reserved WP size name (means "original upload") — do not
// reuse it here, it silently breaks wp_get_attachment_image(..., 'full', ...)
// everywhere in the theme. Use a distinct name instead.
add_image_size( 'hero', 1920, 0, true );

// Return post terms as plain text
function custom_tax( $post_id, $taxonomy ) {
    $terms = get_the_terms( $post_id, $taxonomy );
    if ( is_array( $terms ) ) {
        $output = '';
        $count = count( $terms );
        foreach ( $terms as $i => $term ) {
            $output .= '<span class="tax_term">' . esc_html( $term->name ) . '</span>';
            if ( $i !== $count - 1 ) {
                $output .= '<span>,</span> ';
            }
        }
        return $output;
    }
    return '';
}

// Base slug used for taxonomy-linked output
const CUSTOM_TEMPLATE_SLUG = '/custom-post-type/';

// Return post terms as linked anchors
function custom_tax_linked( $post_id, $taxonomy, $template_slug = CUSTOM_TEMPLATE_SLUG ) {
    $terms = get_the_terms( $post_id, $taxonomy );
    if ( is_array( $terms ) ) {
        $output = '';
        $count = count( $terms );
        foreach ( $terms as $i => $term ) {
            $slug = esc_attr( $term->slug );
            $name = esc_html( $term->name );
            $output .= '<a href="' . esc_url( $template_slug . '#' . $slug ) . '" class="tax_term">' . $name . '</a>';
            if ( $i !== $count - 1 ) {
                $output .= '<span>,</span> ';
            }
        }
        return $output;
    }
    return '';
}