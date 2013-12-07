<?php 
/* Template Name: Homepage */ 
$options = get_option('mh_options'); 
?>
<?php get_header(); ?>
<div class="wrapper">
	<div class="row clearfix">
		<div class="col-2-3">
		    <?php dynamic_sidebar('home-1'); ?>
		    <?php if (is_active_sidebar('home-2') || is_active_sidebar('home-3')) : ?>
		    <div class="row clearfix">
		    <?php if (is_active_sidebar('home-2')) { ?>
		    	<div class="col-1-2 mq-sidebar">
			    	<?php dynamic_sidebar('home-2'); ?>
			    </div>
			<?php }; ?>
			<?php if (is_active_sidebar('home-3')) { ?>
		    	<div class="col-1-2 mq-sidebar">
			    	<?php dynamic_sidebar('home-3'); ?>
			    </div>
			<?php }; ?>
			</div>
			<?php endif; ?>
			<?php dynamic_sidebar('home-4'); ?>
        </div>
        <div class="col-1-3">
        	<?php dynamic_sidebar('home-5'); ?>     
        </div>
    </div>        
</div>
<?php get_footer(); ?>