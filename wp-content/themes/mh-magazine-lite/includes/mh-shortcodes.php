<?php

/***** Raw Shortcode - http://www.wprecipes.com/disable-wordpress-automatic-formatting-on-posts-using-a-shortcode *****/

function my_formatter($content) {
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize(wpautop($piece));
		}
	}
	
	return $new_content;
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
add_filter('the_content', 'my_formatter', 99);

/***** Ad area *****/

function ad($atts, $content = null) {
	$ad_area  = '';
	$ad_area .= '<div class="ad-label">' . __('Advertisement', 'mh') . '</div>';
	$ad_area .= '<div class="ad-area">' . $content . '</div>';
	return $ad_area;
}
add_shortcode('ad', 'ad');

?>