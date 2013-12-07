<?php
/*
Plugin Name: wp-markdown-syntaxhighlighter
Plugin URI: http://www.mattshelton.net
Description: This WordPress plugin attempts to properly tag code blocks for SyntaxHighlighter parsing
Version: 0.4
Author: Matt Shelton
Author URI: http://www.mattshelton.net
License: GPLv2 or later
*/

/*
	Copyright 2012 Matt Shelton <matthew.shelton@gmail.com>

 	This file is part of wp-markdown-syntaxhighlighter

	WP Markdown SyntaxHighlighter is distributed under the GNU General Public 
	License as published by the Free Software Foundation. You are free to 
	re-distribute it and/or modify it under those terms, or a any later version
	of the same license.
	
	Namespace for this plugin will be 'WMSH', in either all capitals or all
	lower-case.
*/

// Constants
define('WMSH_BRUSH','brush');
define('WMSH_AUTOLINKS','auto-links');
define('WMSH_CLASSNAME','class-name');
define('WMSH_COLLAPSE','collapse');
define('WMSH_FIRSTLINE','first-line');
define('WMSH_GUTTER','gutter');
define('WMSH_HIGHLIGHT','highlight');
define('WMSH_HTMLSCRIPT','html-script');
define('WMSH_SMARTTABS','smart-tabs');
define('WMSH_TABSIZE','tab-size');
define('WMSH_TOOLBAR','toolbar');
define('WMSH_RULER','ruler');
define('WMSH_TITLE','title');

// Install filter to run after markdown runs, which is priority 6
add_filter('the_content',     'wmsh_filter_markdown', 7);
add_filter('the_content_rss', 'wmsh_filter_markdown', 7);
add_filter('get_the_excerpt', 'wmsh_filter_markdown', 7);

function wmsh_filter_markdown( $text ) {
	$return = preg_replace_callback( 
		'|<pre><code>#!!([^\n]+)\n(.*?)</code></pre>|s', 
		function( $m ) {
			return wmsh_add_parameters($m[2], $m[1]);
		}, 
		$text
	);

	$return = preg_replace_callback( 
		'|<pre><code>#!([^\n]+)\n(.*?)</code></pre>|s', 
		function( $m ) {
			return wmsh_add_language($m[2],$m[1]);
		}, 
		$return
	);

	return $return;
}

/*
  parseParameters($params)
  $params needs to be a json_decoded object as an associative array
  (e.g. $params = json_decode($json,true)); )
*/
function parseParameters( $params ) {
	/*
	  Filter out empty elements
	  Hat Tip: http://hasin.me/2009/09/16/removing-empty-elements-from-an-array-the-php-way/
	*/
	$empty_elements = array_keys($params,"");
	foreach ($empty_elements as $e) {
		unset($params[$e]);
	}
	
	$return = "";
	if(array_key_exists(WMSH_BRUSH,$params)) {
		$return .= WMSH_BRUSH . ": " . $params[WMSH_BRUSH] . "; ";
	}	
	if(array_key_exists(WMSH_AUTOLINKS,$params)) {
		$return .= WMSH_AUTOLINKS . ": " . $params[WMSH_AUTOLINKS] . "; ";
	}
	if(array_key_exists(WMSH_CLASSNAME,$params)) {
		$return .= WMSH_CLASSNAME . ": " . $params[WMSH_CLASSNAME] . "; ";
	}
	if(array_key_exists(WMSH_COLLAPSE,$params)) {
		$return .= WMSH_COLLAPSE . ": " . $params[WMSH_COLLAPSE] . "; ";
	}
	if(array_key_exists(WMSH_FIRSTLINE,$params)) {
		$return .= WMSH_FIRSTLINE . ": " . $params[WMSH_FIRSTLINE] . "; ";
	}
	if(array_key_exists(WMSH_GUTTER,$params)) {
		$return .= WMSH_GUTTER . ": " . $params[WMSH_GUTTER] . "; ";
	}
	if(array_key_exists(WMSH_HIGHLIGHT,$params)) {
		$return .= WMSH_HIGHLIGHT . ": " . $params[WMSH_HIGHLIGHT] . "; ";
	}
	if(array_key_exists(WMSH_HTMLSCRIPT,$params)) {
		$return .= WMSH_HTMLSCRIPT . ": " . $params[WMSH_HTMLSCRIPT] . "; ";
	}
	if(array_key_exists(WMSH_SMARTTABS,$params)) {
		$return .= WMSH_SMARTTABS . ": " . $params[WMSH_SMARTTABS] . "; ";
	}
	if(array_key_exists(WMSH_TABSIZE,$params)) {
		$return .= WMSH_TABSIZE . ": " . $params[WMSH_TABSIZE] . "; ";
	}
	if(array_key_exists(WMSH_TOOLBAR,$params)) {
		$return .= WMSH_TOOLBAR . ": " . $params[WMSH_TOOLBAR] . "; ";
	}
	if(array_key_exists(WMSH_RULER,$params)) {
		$return .= WMSH_RULER . ": " . $params[WMSH_RULER] . "; ";
	}
	if(array_key_exists(WMSH_TITLE,$params)) {
		$return .= WMSH_TITLE . ": " . $params[WMSH_TITLE] . "; ";
	}

	return $return;
}

/*
  wmsh_add_language($code,$language)
  $code is the parsed code block from the given html input
  $language is the parsed language (brush) declaration
*/
function wmsh_add_language( $code, $language ) {
	return '<pre class="' . WMSH_BRUSH . ': '. $language . '; notranslate">' . $code . '</pre>';
}

/*
  wmsh_add_parameters($code,$parameters)
  $code is the parsed code block from the given html input
  $parameters is the JSON-formatted set of class references to add to the <pre> element
*/
function wmsh_add_parameters( $code, $parameters ) {

		// $parameters comes in full of escape characters, so we need to clean them up
		$cleanParams = str_replace('\\','',$parameters);

		// Decode JSON
		$params = json_decode($cleanParams,true);

		// We want to return the input to the original format if no language is chosen
		if(!array_key_exists(WMSH_BRUSH,$params)) {
			return "<pre><code>".$code."</pre></code>";
		}
		
		$class = parseParameters($params) . "notranslate";

		
		$output = "<pre class=\"" . $class . "\"";
		if(array_key_exists(WMSH_TITLE,$params)) {
			$output = $output . " title=\"" . $params[WMSH_TITLE] . "\"";
		}
		$output = $output . ">" . $code . '</pre>';

		return $output;
}
