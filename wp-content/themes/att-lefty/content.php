<?php
/**
 * The default template for displaying content. Used for both single and index/archive.
 *
 * @package WordPress
 * @subpackage Authentic Themes
 * @since 1.0
 */
 
 

/******************************************************
 * Single Posts
 * @since 1.0
*****************************************************/

if ( is_singular() && is_main_query() ) {
     
	if( of_get_option('blog_single_thumbnail' ) == '1' && has_post_thumbnail() ) { ?>
		<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" title="<?php the_title_attribute(); ?>" class="prettyphoto-link" id="post-thumbnail"><img src="<?php echo aq_resize( wp_get_attachment_url( get_post_thumbnail_id() ), att_img('blog_post_width'),  att_img('blog_post_height'),  att_img('blog_post_crop') ) ?>" alt="<?php echo the_title(); ?>" /></a>
	<?php }

}

/******************************************************
 * Entries
 * @since 1.0
*****************************************************/
else { ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('loop-entry clr'); ?>>
    
		<?php if ( has_post_thumbnail() ) { ?>
            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="loop-entry-img-link">
                <img src="<?php echo aq_resize( wp_get_attachment_url( get_post_thumbnail_id() ), att_img('blog_entry_width'),  att_img('blog_entry_height'),  att_img('blog_entry_crop') ) ?>" alt="<?php echo the_title(); ?>" />
            </a>
        <?php } ?>
<!--
메인페이지 커스텀. 그라바타 이미지를 보여준다. @minieetea
-->
        <div class="loop-entry-details">
            
            <header> 
 
<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <?php echo get_avatar( get_the_author_meta('ID'), 60, $default, $alt ); ?></a>

<h2><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

<ul class="meta single-meta clr">
            <li><i class="icon-user"></i><?php the_author_posts_link(); ?></li>
            <li><i class="icon-time"></i><?php the_date(); ?></li>    
	    <li><i class="icon-folder-open"></i>
	            <?php if ( of_get_option('blog_tags', '1' ) =='1' ) : ?>
			<?php the_tags(''); ?>
			<?php endif; ?></li>
                        <?php // @haruair 구독수 추가 ?>
                        <li><i class="icon-bookmark"></i><?php echo get_post_meta( $post->ID, 'jetpack-post-views', true ); ?> read</li>
            	</ul>
       

</header>

            <div class="loop-entry-excerpt">
                <?php // @haruair 요약 깔끔 돋게 처리 ?>
            	<?php echo wp_trim_words( get_the_excerpt(), '40');?>
                <?php // @haruair 더 읽기 링크 추가 ?>
<?php
$synd = get_post_meta( $post->ID, 'syndication_permalink', true);
if($synd != false){
    $link = $synd;
    $target = "_blank";
}else{
    $link = get_permalink( $post->ID );
    $target = "";
}
?>
                <div class="link-readmore"><a href="<?php echo $link;?>" target="<?php echo $target;?>">[Read original post]</a></div>
            </div><!-- .loop-entry-excerpt -->
            
        </div><!-- .loop-entry-details -->
        </ul><!-- .meta -->
    </article><!-- .loop-entry-entry -->

<?php } ?>