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
		<article <?php post_class(); ?>>
			<header class="post-header">			
				<h1 class="post-title"><?php the_title(); ?></h1>
			</header>
			<?php dynamic_sidebar('posts-1'); ?>
			<div class="entry clearfix">
				<?php		
				if (wp_attachment_is_image($post->id)) { 
					$att_image = wp_get_attachment_image_src($post->id, 'full');
					echo '<a href="' . wp_get_attachment_url($post->id) . '" title="' . get_the_title() . '" rel="attachment" target="_blank"><img src="' . $att_image[0] . '" width="' . $att_image[1] . '" height="' . $att_image[2] . '" class="attachment-medium" alt="' . get_the_title() . '" /></a>' . "\n";
					echo '<p class="wp-caption-text">' . get_post(get_post_thumbnail_id())->post_excerpt . '</p>' . "\n";
					echo '<p>' . get_post(get_post_thumbnail_id())->post_content . '</p>' . "\n";
				}
				?>
			</div>
			<?php dynamic_sidebar('posts-2'); ?>	
		</article>	
		<?php 		
		endwhile;
		mh_postnav();
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