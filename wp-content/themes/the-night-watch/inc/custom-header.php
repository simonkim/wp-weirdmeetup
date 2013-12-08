<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * @package nightwatch
 * @since nightwatch 1.0.2
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 *
 * @uses nightwatch_header_style()
 * @uses nightwatch_admin_header_style()
 * @uses nightwatch_admin_header_image()
 *
 * @package nightwatch
 */
$defaults = array(
	'default-image' => get_template_directory_uri() . '/img/header.jpg',
	'random-default'         => false,
		'width'                  => 1000,
		'height'                 => 400,
	'flex-height'            => true,
	'flex-width'             => true,
	'default-text-color'     => '',
	'header-text'            => false,
	'uploads'                => true,
	'wp-head-callback'       => '',
	'admin-head-callback'    => '',
	'admin-preview-callback' => '',
);
add_theme_support( 'custom-header', $defaults );