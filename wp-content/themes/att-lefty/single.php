<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Authentic Themes
 * @since 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<!-- @minieetea : 메타 영역에 날짜, 태그만 표시되도록 변경 -->
    <header class="page-header"> 
        <h1 class="page-header-title"><?php the_title(); ?></h1>
        <ul class="meta single-meta clr">
            <li><i class="icon-time"></i><?php the_date(); ?></li>    
            <li><i class="icon-user"></i><?php the_author_posts_link(); ?></li>
	    <li><i class="icon-folder-open"></i>
	            <?php if ( of_get_option('blog_tags', '1' ) =='1' ) : ?>
			<?php the_tags(''); ?>
			<?php endif; ?></li>
            
            	</ul><!-- .meta -->
<!-- @minieetea 네비게이션 좌우 버튼 제거
        <nav class="single-nav clr"> 
            <?php next_post_link('<div class="single-nav-left">%link</div>', '<i class="icon-chevron-left"></i>', false); ?>
            <?php previous_post_link('<div class="single-nav-right">%link</div>', '<i class="icon-chevron-right"></i>', false); ?>
        </nav>
-->
<!-- .page-header-title --> 

    </header><!-- .page-header -->
    
    <div id="primary" class="content-area clr">
		<div id="content" class="site-content" role="main">
    
    		<?php if ( !post_password_required() ) : ?>           
			<?php get_template_part('content', get_post_format() ); ?>            
            <?php endif; ?>
            
            <article class="entry clr">
		

                <?php the_content(); ?>
            </article><!-- /entry -->
            
           <?php wp_link_pages( array( 'before' => '<div class="page-links clr">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>

<!-- @minieetea 푸터의 태그영역 제거            
            <?php if ( of_get_option('blog_tags', '1' ) =='1' ) : ?>
				<?php the_tags('<div class="post-tags clr">','','</div>'); ?>
			<?php endif; ?>
-->            
            <?php if ( of_get_option('blog_related', '1' ) == '1' ) { ?>
            	<?php get_template_part( 'content', 'related-posts' ); ?>
            <?php } ?>
    
            <?php comments_template(); ?>
            
		</div><!-- #content -->
	</div><!-- #primary -->

<?php endwhile; ?>

<?php get_footer(); ?>