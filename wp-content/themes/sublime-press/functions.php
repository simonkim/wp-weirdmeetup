<?php

/**
 * Slightly Modified Options Framework
 */

require_once ('admin/index.php');
 

/*-----------------------------------------------------------------------------------*/
/*	Images
/*-----------------------------------------------------------------------------------*/
if (function_exists( 'add_theme_support')) {
	 add_theme_support( 'automatic-feed-links' );
	 add_theme_support( 'custom-background');
	 add_editor_style();
	 add_theme_support( 'post-thumbnails');
	 add_theme_support( 'custom-header');
	
	if ( function_exists('add_image_size')) {
		add_image_size( 'full-size',  9999, 9999, false );
		add_image_size( 'slider',  980, 9999, false );
		add_image_size( 'small-thumb',  50, 50, true );
		add_image_size( 'grid-thumb',  230, 180, true );
	}
}


// register navigation menus
register_nav_menus(
	array(
	'menu'=>__('Header Menu'),		
//	'footer-menu'=>__('Footer Menu'),
	)
);


/*-----------------------------------------------------------------------------------*/
/*	Javascsript
/*-----------------------------------------------------------------------------------*/

add_action('wp_enqueue_scripts','sublime_scripts_function');
function sublime_scripts_function() {
	//get theme options
	global $options;
	
	wp_enqueue_script('jquery');	
	
	// Site wide js
	wp_enqueue_script('hoverIntent', get_template_directory_uri() . '/js/jquery.hoverIntent.minified.js');
	wp_enqueue_script('uniform', get_template_directory_uri() . '/js/jquery.uniform.js');
	wp_enqueue_script('responsify', get_template_directory_uri() . '/js/jquery.responsify.init.js');
	wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js');
	wp_enqueue_script('classie', get_template_directory_uri() . '/js/classie.js');
	wp_enqueue_script('uisearch', get_template_directory_uri() . '/js/uisearch.js');
	wp_enqueue_script('modernizr.custom', get_template_directory_uri() . '/js/modernizr.custom.js');
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
}



/*-----------------------------------------------------------------------------------*/
/*Enqueue CSS
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts', 'sublime_enqueue_css');
function sublime_enqueue_css() {
	
	//main CSS

	wp_enqueue_style('style', get_template_directory_uri() . '/style.css', 'style');
	
	//responsive
	wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', 'style');
	
	//awesome font - icon fonts
	wp_enqueue_style('awesome-font', get_template_directory_uri() . '/css/awesome-font.css', 'style');

	//Search Bar 2
	wp_enqueue_style('component', get_template_directory_uri() . '/css/component.css', 'style');
	
}


add_action( 'admin_enqueue_scripts', 'sublime_enqueue_color_picker' );
function sublime_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('my-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

/*-----------------------------------------------------------------------------------*/
/*	Set Content Width
/*-----------------------------------------------------------------------------------*/


if ( ! isset( $content_width ) ) $content_width = 900;

/*-----------------------------------------------------------------------------------*/
/*	Remove Menus
/*-----------------------------------------------------------------------------------*/

/*
function remove_menus () {
global $menu;
	$restricted = array(__('Pages'), __('Comments'),);
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');
*/

// Limit Excerpt Word Count
add_filter('excerpt_length', 'sublime_new_excerpt_length');
function sublime_new_excerpt_length($length) {
	return 300;
}

//Replace Excerpt Link
add_filter('excerpt_more', 'sublime_new_excerpt_more');
function sublime_new_excerpt_more($more) {
       global $post;
	return '...';
}

//custom excerpts
function sublime_excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	return $excerpt;
}


//create featured image column
add_filter('manage_posts_columns', 'sublime_posts_columns', 5);
add_action('manage_posts_custom_column', 'sublime_posts_custom_columns', 5, 2);
function sublime_posts_columns($defaults){
    $defaults['riv_post_thumbs'] = __('Thumbs', 'powered');
    return $defaults;
}
function sublime_posts_custom_columns($column_name, $id){
	if($column_name === 'riv_post_thumbs'){
        echo the_post_thumbnail( 'small-thumb' );
    }
}

// functions run on activation --> important flush to clear rewrites
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {
	$wp_rewrite->flush_rules();
}

/*-----------------------------------------------------------------------------------*/
/*	Sidebars
/*-----------------------------------------------------------------------------------*/

//Register Sidebars
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar',
		'id' => 'sidebar',
		'description' => __('Widgets in this area will be shown in the leftsidebar.','sublime'),
		'before_widget' => '<div class="sidebar-box clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4><span>',
		'after_title' => '</span></h4>',
));
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Comments Footer One',
		'id' => 'comments-footer-one',
		'description' => __('Widgets in this area will be shown in the leftsidebar.','sublime'),
		'before_widget' => '<div class="sidebar-box clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4><span>',
		'after_title' => '</span></h4>',
));
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Comments Footer Two',
		'id' => 'comments-footer-two',
		'description' => __('Widgets in this area will be shown in the right sidebar.','sublime'),
		'before_widget' => '<div class="sidebar-box clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4><span>',
		'after_title' => '</span></h4>',
));
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Comments Footer Three',
		'id' => 'comments-footer-three',
		'description' => __('Widgets in this area will be shown in the right sidebar.','sublime'),
		'before_widget' => '<div class="sidebar-box clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4><span>',
		'after_title' => '</span></h4>',
));
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Comments Footer Four',
		'id' => 'comments-footer-four',
		'description' => __('Widgets in this area will be shown in the right sidebar.','sublime'),
		'before_widget' => '<div class="sidebar-box clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4><span>',
		'after_title' => '</span></h4>',
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer One',
		'id' => 'footer-one',
		'description' => __('Widgets in this area will be shown in the first footer area.','sublime'),
		'before_widget' => '<div class="footer-widget clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
));
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer Two',
		'id' => 'footer-two',
		'description' => __('Widgets in this area will be shown in the second footer area.','sublime'),
		'before_widget' => '<div class="footer-widget clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
));
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer Three',
		'id' => 'footer-three',
		'description' => __('Widgets in this area will be shown in the third footer area.','sublime'),
		'before_widget' => '<div class="footer-widget clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
));
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Footer Four',
		'id' => 'footer-four',
		'description' => __('Widgets in this area will be shown in the fourth footer area.','sublime'),
		'before_widget' => '<div class="footer-widget clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
));
?>