<?php
/* ACF settings */

// Register ACF options page under "Theme Settings"
if ( function_exists( 'acf_add_options_page' ) ) {
    acf_add_options_page( [
        'page_title'  => 'Theme General Settings',
        'menu_title'  => 'Theme Settings',
        'menu_slug'   => 'theme-general-settings',
        'capability'  => 'edit_posts',
        'redirect'    => false
    ] );
}

// Style ACF repeater even rows based on admin color scheme
function acf_repeater_even() {
    $scheme = get_user_option( 'admin_color' );
    $colors = [
        'fresh'     => '#0073aa',
        'light'     => '#d64e07',
        'blue'      => '#52accc',
        'coffee'    => '#59524c',
        'ectoplasm' => '#523f6d',
        'midnight'  => '#e14d43',
        'ocean'     => '#738e96',
        'sunrise'   => '#dd823b'
    ];

    $color = $colors[ $scheme ] ?? '#777';

    echo '<style>
		.acf-repeater > table > tbody > tr:nth-child(even) > td.order {
			color: #fff !important;
			background-color: ' . esc_html( $color ) . ' !important;
			text-shadow: none;
		}
	</style>';
}
add_action( 'admin_footer', 'acf_repeater_even' );

// Show ACF admin menu only to users with 'manage_options' capability
add_filter( 'acf/settings/show_admin', function( $show ) {
    return current_user_can( 'manage_options' );
});