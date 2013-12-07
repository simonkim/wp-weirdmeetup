<?php 
/* Default template for displaying content. Used for single and index/archive/search. */ 
?>
<?php $options = get_option('mh_options'); ?>
<?php if (is_single()) { ?>	
	<article <?php post_class(); ?>>
		<header class="post-header">			
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php if (get_post_meta($post->ID, "mh-subheading", true)) : ?>
			<h2 class="subheading"><?php echo esc_attr(get_post_meta($post->ID, "mh-subheading", true)); ?></h2>
			<?php endif; ?>		
			<p class="meta post-meta"><?php _e('Posted on ', 'mh'); ?><span class="updated"><?php the_date(); ?></span><?php _e(' by ', 'mh'); ?><span class="vcard author"><span class="fn"><?php the_author_posts_link(); ?></span></span><?php _e(' in ', 'mh') . the_category(', ') ?>  //  <?php comments_number(__('0 Comments', 'mh'), __('1 Comment', 'mh'), __('% Comments', 'mh'));?></p>
		</header>
		<?php dynamic_sidebar('posts-1'); ?>
		<div class="entry clearfix">
			<?php 
			$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'content');
			$full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			if (has_post_thumbnail()) : ?>
				<div class="post-thumbnail">
					<a href="<?php echo $full[0]; ?>" rel="prettyPhoto"><img src="<?php echo $thumbnail[0]; ?>" alt="<?php echo the_title(); ?>" title="<?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?>" /></a>
				</div>
			<?php endif; ?>
			<?php the_content(); ?>
		</div>
		<?php wp_link_pages(array(
			'before'           => '<p class="pagination">',
			'after'            => '</p>',
			'link_before'      => '<span class="pagelink">',
			'link_after'       => '</span>',
			'nextpagelink'     => __('&raquo;', 'mh'),
			'previouspagelink' => __('&laquo;', 'mh'),
			'pagelink'         => '%',
			'echo'             => 1
		)); ?>           
		<?php if (has_tag()) : ?>
			<div class="tags-wrap clearfix">
        		<?php the_tags('<ul class="tags"><li>','</li><li>','</li></ul>'); ?>
        	</div>
        <?php endif; ?>
        <?php dynamic_sidebar('posts-2'); ?>	
	</article>
<?php } else { ?>
	<article <?php post_class(); ?>>		
		<div class="loop-wrap clearfix">
			<div class="loop-thumb">
				<a href="<?php the_permalink()?>">
					<?php if (has_post_thumbnail()) { the_post_thumbnail('loop'); } else { echo '<img src="' . get_template_directory_uri() . '/images/noimage_174x131.png' . '" alt="No Picture" />'; } ?>
				</a>
			</div>
			<header>
				<div class="loop-data">
					<h3 class="loop-title"><a href="<?php the_permalink()?>" rel="bookmark"><?php the_title(); ?></a></h3>
					<p class="meta"><a href="<?php the_permalink()?>" rel="bookmark"><?php $date = get_the_date(); echo $date; ?></a> // <?php comments_number(__('0 Comments', 'mh'), __('1 Comment', 'mh'), __('% Comments', 'mh')); ?></p>
				</div>
			</header>		
			<div class="loop-excerpt"><?php the_excerpt(); ?></div>
		</div>
	</article>
<?php }	?>		