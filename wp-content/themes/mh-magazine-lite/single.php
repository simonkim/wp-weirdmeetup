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
		<?php 
		get_template_part('content', get_post_format());
		mh_author_box();
		endwhile;	
		mh_postnav();	
		mh_related();
        comments_template();
        endif;
        ?>
    </div>
    <?php if (isset($options['sb_position']) ? $options['sb_position'] == 'right' : true) : ?>
    <aside class="col-1-3">
    	<?php dynamic_sidebar('sidebar'); ?>     
    </aside>
    <?php endif; ?>
</div> 
<?php get_footer(); ?>