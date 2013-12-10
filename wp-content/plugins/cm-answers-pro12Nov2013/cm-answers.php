<?php

/*
  Plugin Name: CM Answers Pro
  Plugin URI: http://answers.cminds.com/
  Description: PRO Version! Allow users to post questions and answers in stackoverflow style
  Author: CreativeMindsSolutions
  Version: 2.0.10
 */

/*

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


function cm_lang_init() {
  load_plugin_textdomain( 'cm-answers-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

if (version_compare('5.3', PHP_VERSION, '>')) {
    die('We are sorry, but you need to have at least PHP 5.3 to run this plugin (currently installed version: '.PHP_VERSION.') - please upgrade or contact your system administrator.');
}

add_action('plugins_loaded', 'cm_lang_init');

//Define constants
define('CMA_PREFIX', 'CMA_');
define('CMA_PATH', WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)));
define('CMA_URL', plugins_url('', __FILE__));
define('CMA_RESOURCE_URL', CMA_URL.'/views/resources/');

/* AJAX check  */
define('CMA_AJAX', !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || !empty($_REQUEST['ajax']));


//Init the plugin
require_once CMA_PATH . '/lib/CMA.php';

CMA::init(__FILE__);