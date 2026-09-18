<?php

// shortcode button
function content_btn($atts,$content = null){
    $a = shortcode_atts(array(
        'text' => 'Learn More',
        'link' => site_url(),
        'class' => false,
        'target' => false,
        'popup' => false,
        'video' => false
    ), $atts );
    return '<a href="' . esc_url( $a['link'] ) . '" class="button'.($a['class']?' '.esc_attr($a['class']):'').'" '.($a['target']?'target="'.esc_attr($a['target']).'"  rel="noopener"':'').'
            '.($a['popup']?' data-fancybox="" data-src="#'.esc_attr($a['popup']).'"':'').' '.($a['video']?' data-fancybox=""':'').'>' . do_shortcode($content) . '</a>';
}
add_shortcode("button", "content_btn");

// shortcode social media
function so_me() {
	$so_me = get_field('so_me', 'option');
	$soc = '';
	if($so_me) {
		$soc .= '<ul class="so_me">';
		foreach($so_me as $sm) {
			$host = parse_url( $sm['link'] );
			if ( array_key_exists('host', $host ) ) {
				$parts = explode( '.', $host['host'] );
				$label = $parts[0] == 'www' ? $parts[1] : $parts[0];
			} else {
				$label = get_bloginfo();
			}
			$soc .= '<li><a href="'.esc_url( $sm['link'] ).'" class="i_'.esc_attr( $sm['icon'] ).'" target="_blank" rel="noopener" aria-label="'.esc_attr( $label ).'"></a></li>';
		}
		$soc .= '</ul>';
	}
	return $soc;
}
add_shortcode('social', 'so_me');

// remove <p> and <br /> from shortcodes
add_filter('the_content', 'shortcode_empty_paragraph_fix');
function shortcode_empty_paragraph_fix($content){
    $array = array (
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']'
    );
    $content = strtr($content, $array);
    return $content;
}