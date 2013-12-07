<?php
/*
Plugin Name: Multi-column Tag Map 
Plugin URI: http://tugbucket.net/wordpress/wordpress-plugin-multi-column-tag-map/
Description: Multi-column Tag Map displays a columnized and alphabetical (English) listing of all tags used in your site similar to the index pages of a book.
Version: 12.0.4
Author: Alan Jackson
Author URI: http://tugbucket.net
*/

/*  Copyright 2009-2013  Alan Jackson (alan[at]tugbucket.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// **************************
//
// Long code removed completely as of version 10.0.1 - it was deprecated as of version 4.0
//
// **************************

	/* load the PHP*/
function sc_mcTagMap($atts, $content = null) {
	if(file_exists('./wp-content/themes/'.get_template().'/multi-column-tag-map/mctagmap_functions.php')){
		include('./wp-content/themes/'.get_template().'/multi-column-tag-map/mctagmap_functions.php');
	} else {
		include('mctagmap_functions.php');
	}
return $list;
}
add_shortcode("mctagmap", "sc_mcTagMap");
// end shortcode


// the JS and CSS

add_action('wp_head', 'mcTagMapCSSandJS');
function mcTagMapCSSandJS(){
		
$mctagmapVersionNumber = "12.0.4";
$mctagmapCSSpath = './wp-content/themes/'.get_template().'/multi-column-tag-map';
	
	echo "\n";
	/* load the CSS */
	if(file_exists($mctagmapCSSpath.'/mctagmap.css')){
		echo '<link rel="stylesheet" href="'.home_url('/').substr($mctagmapCSSpath, 1).'/mctagmap.css?mctm_ver='.$mctagmapVersionNumber.'" type="text/css" media="screen" />';
	} else {
		echo '<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/multi-column-tag-map/mctagmap.css?mctm_ver='.$mctagmapVersionNumber.'" type="text/css" media="screen" />';
	}
	echo "\n";
	
	/* load the JS */
	if(file_exists($mctagmapCSSpath.'/mctagmap.js')){
		wp_register_script (
  			'mctm_scripts', /* handle WP will know JS by */
  			home_url('/').substr($mctagmapCSSpath, 1).'/mctagmap.js?mctm_ver='.$mctagmapVersionNumber, /* source */
  			array('jquery'), /* requires jQuery */
  			/* 1.0, */ /* version 1.0 */
  			true /* can load in footer */
		);	
	} else {
		wp_register_script (
  			'mctm_scripts', /* handle WP will know JS by */
  			WP_PLUGIN_URL . '/multi-column-tag-map/mctagmap.js?mctm_ver='.$mctagmapVersionNumber, /* source */
  			array('jquery'), /* requires jQuery */
  			/* 1.0, */ /* version 1.0 */
  			true /* can load in footer */
		);
	}
	if (!is_admin()) {
  		wp_enqueue_script('mctm_scripts');
	}
	echo "\n\n";
}


function mctagmap_donate($links, $file) {
$plugin = plugin_basename(__FILE__);
// create link
if ($file == $plugin) {
return array_merge( $links, array( sprintf( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GX8RH7F2LR74J" target="_blank">Donate to mctagmap development</a>', $plugin, __('Donate') ) ));
}
return $links;
}
add_filter( 'plugin_row_meta', 'mctagmap_donate', 10, 2 );


// overwrite single_tag_title()
add_filter('single_tag_title', 'mctagmap_single_tag_title', 1, 2);
function mctagmap_single_tag_title($prefix = '') {
	global $wp_query;
	if ( !is_tag() )
		return;

	$tag = $wp_query->get_queried_object();

	if ( ! $tag )
		return;

	$my_tag_name = str_replace('|', '', $tag->name);
	if ( !empty($my_tag_name) ) {
		if ( $display )
			echo $prefix . $my_tag_name;
		else
			return $my_tag_name;
	}
}

// overwrite single_tag_title()
add_filter('the_tags', 'mctagmap_the_tags');
function mctagmap_the_tags($mctagmapTheTags) {
    return str_replace('|', '', $mctagmapTheTags);
}

/* show the except outside of the loop */
function mctm_get_the_excerpt_here($post_id){
  	global $wpdb;
 	$query = "SELECT post_excerpt FROM $wpdb->posts WHERE ID = $post_id LIMIT 1";
 	$result = $wpdb->get_results($query, ARRAY_A);
  	return $result[0]['post_excerpt'];
}


/* Page Excerpt by: Jeremy Massel */
//add_action( 'edit_page_form', 'mctm_pe_add_box');
add_action('init', 'mctm_pe_init');

function mctm_pe_init() {
	if(function_exists("add_post_type_support")){ //support 3.1 and greater
		add_post_type_support( 'page', 'excerpt' );
	}
}
function mctm_pe_page_excerpt_meta_box($post) {
?>
<label class="hidden" for="excerpt"><?php _e('Excerpt') ?></label><textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt"><?php echo $post->post_excerpt ?></textarea>
<p><?php _e('Excerpts are optional hand-crafted summaries of your content. You can <a href="http://codex.wordpress.org/Template_Tags/the_excerpt" target="_blank">use them in your template</a>'); ?></p>
<?php
}

function mctm_pe_add_box()
{
	if(!function_exists("add_post_type_support")) //legacy
	{		add_meta_box('postexcerpt', __('Page Excerpt'), 'mctm_pe_page_excerpt_meta_box', 'page', 'advanced', 'core');
	}
}
/* END - Page Excerpt by: Jeremy Massel */
?>