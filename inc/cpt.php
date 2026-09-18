<?php
// Register custom taxonomy and custom post type
add_action( 'init', 'register_cpts' );

function register_cpts() {

    // Define taxonomy arguments using helper
    $custom_tax_args = tax_args_arr( 'Taxonomy', 'Taxonomies' );

    // Register taxonomy for custom post type
    register_taxonomy( 'custom_taxonomy', 'custom_post_type', $custom_tax_args );

    // Register custom post type
    register_post_type( 'custom_post_type', [
        'labels' => [
            'name'          => 'Custom Post Type',
            'singular_name' => 'Custom Post Type Singular',
            'add_new'       => 'Add New',
            'add_new_item'  => 'Add New Item',
            'edit_item'     => 'Edit Item',
            'new_item'      => 'New Item',
            'view_item'     => 'View Item',
            'search_items'  => 'Search Items',
            'not_found'     => 'No items found',
            'not_found_in_trash' => 'No items found in Trash',
            'all_items'     => 'All Items'
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'show_in_nav_menus' => false,
        'menu_icon'         => 'dashicons-portfolio',
        'rewrite'           => [ 'slug' => 'permalink' ],
        'supports'          => [ 'title', 'editor', 'thumbnail', 'excerpt' ]
    ]);

}

// Flush rewrite rules once, only on theme activation — NOT on every 'init'
// (flush_rewrite_rules() rewrites the whole rules array and is expensive;
// running it on every request tanks performance).
add_action( 'after_switch_theme', function () {
    register_cpts();
    flush_rewrite_rules();
});

// Generate taxonomy arguments dynamically
function tax_args_arr( $tax_name, $tax_plural, $new_slug = null, $is_in_menu = true ) {
    $labels = [
        'name'                       => $tax_name,
        'singular_name'              => $tax_name,
        'search_items'               => 'Search ' . $tax_name,
        'popular_items'              => 'Popular ' . $tax_name,
        'all_items'                  => 'All ' . $tax_plural,
        'parent_item'                => 'Parent ' . $tax_name,
        'edit_item'                  => 'Edit ' . $tax_name,
        'update_item'                => 'Update ' . $tax_name,
        'add_new_item'               => 'Add New ' . $tax_name,
        'new_item_name'              => 'New ' . $tax_name,
        'separate_items_with_commas' => 'Separate ' . $tax_plural . ' with commas',
        'add_or_remove_items'        => 'Add or remove ' . $tax_plural,
        'choose_from_most_used'      => 'Choose from most used ' . $tax_plural
    ];

    return [
        'label'             => $tax_name,
        'labels'            => $labels,
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => $is_in_menu,
        'args'              => [ 'orderby' => 'term_order' ],
        'rewrite'           => [
            'slug'         => $new_slug ?? 'custom_taxonomy',
            'hierarchical' => true
        ]
    ];
}
