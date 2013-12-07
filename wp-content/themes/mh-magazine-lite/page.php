<?php $options = get_option('mh_options'); ?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="wrapper row clearfix">
	<?php if (isset($options['sb_position']) ? $options['sb_position'] == 'left' : false) : ?>
	<aside class="col-1-3">
    	<?php dynamic_sidebar('sidebar'); ?>     
	</aside>
	<?php endif; ?>
    <div class="col-2-3">
    	<div class="page-title-top"></div>
	    <h1 class="page-title"><?php the_title(); ?></h1>   	
        <div <?php post_class(); ?>>
	    	<div class="entry clearfix">
				<?php the_content(); ?>
			</div>
		</div>
		<?php endwhile; ?>
			<?php if (isset($options['comments_pages']) ? !$options['comments_pages'] : true) : ?>
			<section>
        		<?php comments_template(); ?>
			</section>
			<?php endif; ?>
        <?php endif; ?>
    </div>
	<?php if (isset($options['sb_position']) ? $options['sb_position'] == 'right' : true) : ?>
	<aside class="col-1-3">
    	<?php dynamic_sidebar('sidebar'); ?>     
	</aside>
	<?php endif; ?>       
</div>
<?php get_footer(); ?>