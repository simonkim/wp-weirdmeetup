<!DOCTYPE html>
<?php $options = get_option('mh_options'); ?>
<html class="no-js<?php if (isset($options['full_bg']) && $options['full_bg'] == 1) { echo ' fullbg'; } ?>" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<title><?php wp_title('|', true, 'right'); ?></title>
<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Droid+Sans|Droid+Serif'>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" media="screen">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>> 
<div class="container">
<header class="header-wrap">
	<?php mh_logo(); ?>
	<nav class="main-nav clearfix">
		<?php wp_nav_menu(array('theme_location' => 'main_nav')); ?>
	</nav>
</header>