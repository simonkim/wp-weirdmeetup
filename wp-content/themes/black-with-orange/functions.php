<?php
if ( ! isset( $content_width ) )
$content_width = 650;

add_action( 'after_setup_theme', 'black_with_orange_setup' );

function black_with_orange_setup() {

add_editor_style();
add_theme_support('automatic-feed-links');
add_theme_support('post-thumbnails');

set_post_thumbnail_size( 120, 120, true ); // Default size

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain('black_with_orange', get_template_directory() . '/languages');	
	
register_nav_menus(
	array(
	  'primary' => __('Header Menu', 'black_with_orange'),
	  'secondary' => __('Footer Menu', 'black_with_orange')
	)
);
	
}


function black_with_orange_widgets() {

register_sidebar(array(
	'name' => __( 'Sidebar Widget Area', 'black_with_orange'),
	'id' => 'sidebar-widget-area',
	'description' => __( 'The sidebar widget area', 'black_with_orange'),
	'before_widget' => '<div class="widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
));	
	
}

add_action ( 'widgets_init', 'black_with_orange_widgets' );


function black_with_orange_enqueue_comment_reply() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'black_with_orange_enqueue_comment_reply' );


function black_with_orange_page_menu() {
	if (is_page()) { $highlight = "page_item"; } else {$highlight = "menu-item current-menu-item"; }
	echo '<ul class="menu">';
	wp_list_pages('sort_column=menu_order&title_li=&link_before=&link_after=&depth=3');
	echo '</ul>';
}

function black_with_orange_page_menu_flat() {
	if (is_page()) { $highlight = "page_item"; } else {$highlight = "menu-item current-menu-item"; }
	echo '<ul class="menu">';
	wp_list_pages('sort_column=menu_order&title_li=&link_before=&link_after=&depth=1');
	echo '</ul>';
}

?>