<?php
/* ACF settings */

// add custom tab (group) for common ACF fields
if(function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title'    => 'Theme General Settings',
		'menu_title'    => 'Theme Settings',
		'menu_slug'     => 'theme-general-settings',
		'capability'    => 'edit_posts',
		'redirect'      => false
	));
}

function my_acf_init() {
	// g-map API key
	acf_update_setting('google_api_key', 'AIzaSyAO77hGcvxmsvOn1RSjDFQMI4YUnW89MDo');

}
add_action('acf/init', 'my_acf_init');

// ACF repeater styles
function acf_repeater_even() {
	$scheme = get_user_option( 'admin_color' );
	$color = '';
	if($scheme == 'fresh') {
		$color = '#0073aa';
	} else if($scheme == 'light') {
		$color = '#d64e07';
	} else if($scheme == 'blue') {
		$color = '#52accc';
	} else if($scheme == 'coffee') {
		$color = '#59524c';
	} else if($scheme == 'ectoplasm') {
		$color = '#523f6d';
	} else if($scheme == 'midnight') {
		$color = '#e14d43';
	} else if($scheme == 'ocean') {
		$color = '#738e96';
	} else if($scheme == 'sunrise') {
		$color = '#dd823b';
	}
	echo '<style>.acf-repeater > table > tbody > tr:nth-child(even) > td.order {color: #fff !important;background-color: '.esc_html($color).' !important; text-shadow: none}</style>';
}
add_action('admin_footer', 'acf_repeater_even');

// remove ACF menu item from
add_filter('acf/settings/show_admin', 'my_acf_show_admin');
function my_acf_show_admin( $show ) {
	return current_user_can('manage_options');
}