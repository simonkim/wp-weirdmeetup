<?php
/**
 * nightwatch functions and definitions
 *
 * @package nightwatch
 * @since nightwatch 1.0.2.1
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since nightwatch 1.0.2
 */
if ( ! isset( $content_width ) )
	$content_width = 880; /* pixels */

if ( ! function_exists( 'nightwatch_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since nightwatch 1.0.2
 */
function nightwatch_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on nightwatch, use a find and replace
	 * to change 'nightwatch' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'nightwatch', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable post thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'nightwatch-index', 1000, 400, true );

	add_editor_style();
	/**
	 * Custom backgrounds
	 */
	
	$args = array(
		'default-color' => 'f0f0f0',
		'default-image' => get_template_directory_uri() . '/img/bg.png',
	);
	add_theme_support( 'custom-background', $args );
	
	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'nightwatch' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // nightwatch_setup
add_action( 'after_setup_theme', 'nightwatch_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since nightwatch 1.0.2
 */
function nightwatch_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'nightwatch' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'nightwatch_widgets_init' );

/**
 * Validates the_category
 */
add_filter('the_category', 'remove_category_rel');
function remove_category_rel($string)
{
    return str_replace('', '', $string);
}
/**
 * Enqueue scripts and styles
 */
function nightwatch_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'nightwatch_scripts' );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );
