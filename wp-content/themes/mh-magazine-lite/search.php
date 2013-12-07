<?php $options = get_option('mh_options'); ?>
<?php get_header(); ?>
<div class="wrapper">
	<div class="row clearfix">
		<?php if (isset($options['sb_position']) ? $options['sb_position'] == 'left' : false) : ?>
		<aside class="col-1-3">
    		<?php dynamic_sidebar('sidebar'); ?>     
    	</aside>
    	<?php endif; ?>
		<section class="archive col-2-3">
			<?php 		
			echo '<header>' . "\n";
				echo '<div class="page-title-top"></div>' . "\n";
				echo '<h1 class="page-title">' . __('Search Results for ', 'mh') . get_search_query() . '</h1>' . "\n";
			echo '</header>' . "\n";
			if (have_posts()) {
				while (have_posts()) : the_post();
					get_template_part('content', get_post_format());
				endwhile;
				mh_pagination();
			} else { 
				get_template_part('content', 'none');
			} 
			?>	
		</section>
		<?php if (isset($options['sb_position']) ? $options['sb_position'] == 'right' : true) : ?>
		<aside class="col-1-3">
    		<?php dynamic_sidebar('sidebar'); ?>     
    	</aside>
    	<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>