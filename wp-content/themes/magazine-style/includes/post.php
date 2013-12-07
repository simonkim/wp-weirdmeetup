<?php if(has_post_thumbnail()) : ?>
	
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<div class="thumbnail">
		<?php if ( has_post_thumbnail() ) {	the_post_thumbnail('defaultthumb');} ?>
	</div>			<div class="entry">
<?php magazine_excerpt('magazine_excerptlength_index', 'magazine_excerptmore'); ?>
			</div>
			<a href="<?php the_permalink(); ?>"><span class="readmore">Continue reading &raquo;</span></a>


	</article>
<?php else : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			
		<div class="entry">
		<?php magazine_excerpt('magazine_excerptlength_index', 'magazine_excerptmore'); ?>
		</div>
				<a href="<?php the_permalink(); ?>"><span class="readmore">Continue reading &raquo;</span></a>


	</article>
<?php endif; ?>
<span class="postmeta_box">
		<?php get_template_part('/includes/postmeta'); ?>
	</span><!-- .entry-header -->




