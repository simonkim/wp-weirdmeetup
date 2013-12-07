<!DOCTYPE html>

<!-- Get Options Start -->
<?php
global $data; //fetch options stored in $data
?>
<!-- Get Options End -->

<html <?php language_attributes(); ?>>
<head>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<!-- Mobile Specific
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<!-- Title Tag
================================================== -->
<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' |'; } ?> <?php bloginfo('name'); ?></title>
    
<?php if(!empty($options['favicon'])) { ?>
<!-- Favicon
================================================== -->
<link rel="icon" type="image/png" href="<?php echo $options['favicon']; ?>" />
<?php } ?>


<?php $content_text_color = get_option('content_text_color'); ?>
<style> #single-title-home { color:  <?php echo $content_text_color; ?>; } </style>
<!-- WP Head -->

<?php wp_head(); ?>


</head>
<body <?php body_class(); ?>>

	<div class="clearfix"></div>    
        	
    <!-- Start Header-->
    <header id="masterhead-container" class="clearfix"> 
    <div id="masterhead" class="clearfix">  



<!-- Start Search Box-->
<?php if ( of_get_options('search_bar') ) : ?>
    <div id="search-container" class="clearfix">
                <div class="column">
                    <div id="sb-search" class="sb-search">
                        <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                            <input class="sb-search-input" placeholder="Search for....." type="text" value="" name="s" id="search">
                            <input class="sb-search-submit" type="submit" value="Search">
                            <span class="sb-icon-search"></span>
                        </form>
                    </div>
                </div>         
    </div>
<?php endif; ?> 
<!-- End Search Box-->
 

<!-- Start logo -->   
           <div id="logo-containter" class="clearfix">
            <div id="logo" class="clearfix">
                    <a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'name' ) ?>"><?php bloginfo( 'name' ); ?></a>
            </div>
<!-- END logo -->
           
         
<!-- Start description -->
<?php if ( of_get_options('site_description') ) : ?>
            <div id="site-description">
            <?php echo get_bloginfo ( 'description' );  ?><br />
               </div>
                </div>
<?php endif; ?> 
<!-- END description -->


<!-- Start Nav -->
<nav id="masternav" class="clearfix">
                <?php wp_nav_menu( array(
                    'theme_location' => 'menu',
                    'sort_column' => 'menu_order',
                    'menu_class' => 'sublime-menu',
                    'fallback_cb' => 'default_menu'
                )); ?>
</nav>
<!-- End Nav -->

<script>
new UISearch( document.getElementById( 'sb-search' ) );
</script>

</div>
</header>

<!-- End Header -->

<div id="wrap" class="clearfix">
<div id="main" class="clearfix">
