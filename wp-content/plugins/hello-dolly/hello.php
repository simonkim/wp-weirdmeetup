<?php
/**
 * @package Stalk.io
 * @version 2.0
 */
/*
Plugin Name: Stalk.io - live web chat service on your web pages
Description: Stalk.io 채팅 플러그인
Author: haruair
Version: 2.0
Author URI: http://haruair.com/
*/

function print_stalkio_script() {

	echo '<script src="http://www.stalk.io/stalk.js"></script><script>STALK.init();</script>';
}

add_action( 'wp_footer', 'print_stalkio_script' );
add_action( 'admin_footer', 'print_stalkio_script' );