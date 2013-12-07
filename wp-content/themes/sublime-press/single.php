<!--
Theme Name: Sublime
Author: amplifiii
Author URI: http://www.amplifiii.com
-->

<?php get_header(); ?>

<!-- Get Options Start -->
<?php
global $data; //fetch options stored in $data
?>
<!-- Get Options End -->

<!--?php //fetch options stored in $data
echo $data['heeader_tracking_code']; //get $data['id'] then echo the value
?-->

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article class="post clearfix">
<article class="home-entry">
<div class="home-entry-description">


<?php if( of_get_options('featured_image') ) : ?>
<div class="center-entry">
<?php
if ( has_post_thumbnail() ) {the_post_thumbnail();
} 
 ?>
</div>
<?php endif; ?>


	<header>

        <h1 class="single-title"><?php the_title(); ?></h1>
<!-- @minieeta Original        
        <div class="post-meta">
            <span class="awesome-icon-calendar"></span><?php echo 'On' ?> <?php the_time('j'); ?> <?php the_time('M'); ?>, <?php the_time('Y'); ?>
            <span class="awesome-icon-user"></span><?php echo 'By' ?> <?php the_author_posts_link(); ?>
            <span class="awesome-icon-comments"></span><?php echo 'With' ?>  <?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?>
        </div>
-->
	 <div class="post-meta">
            <span class="awesome-icon-calendar"></span> <?php the_time('M'); ?> <?php the_time('j'); ?>, <?php the_time('Y'); ?>
            <span class="awesome-icon-user"></span> <?php the_author_posts_link(); ?>
            <span class="awesome-icon-comments"></span> <?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?>
        </div>

        <!-- /loop-entry-meta -->
    </header>

    <div class="entry clearfix">
		<?php the_content(); ?>
        <div class="clear"></div>
        
        <?php wp_link_pages(' '); ?>
         
        <div class="post-bottom">
        	<?php the_tags('<div class="post-tags"><span class="awesome-icon-tags"></span>',' , ','</div>'); ?>
        </div>
        <!-- /post-bottom -->


<!-- Twitter Start -->
<?php if( of_get_options('switch_ex1') ) : ?>
    <div class="twitterlinks">
    <a title="<?php the_title(); ?>" href="http://twitter.com/share?text=<?php the_title(); ?> - <?php the_permalink(); ?>" target="_blank" rel="nofollow">Tweet This Post</a></div>
    </div>
<?php endif; ?>
<!-- Twitter End -->


 <script type="text/javascript">
    function showme(id, linkid) {
        var divid = document.getElementById(id);
        var toggleLink = document.getElementById(linkid);
        if (divid.style.display == 'block') {
            toggleLink.innerHTML = 'Show Comments.....';
            divid.style.display = 'none';
        }
        else {
            toggleLink.innerHTML = 'Hide Comments';
            divid.style.display = 'block';
        }
    }
</script>


<div id="widget">
    <?php comments_template(); ?>

<div class="sidebar-container">
<aside id="sidebar-one">
    <?php dynamic_sidebar('comments-footer-one'); ?>
</aside>
<aside id="sidebar-two">    
    <?php dynamic_sidebar('comments-footer-two'); ?>
 </aside>
 <aside id="sidebar-three" class="clearfix">    
    <?php dynamic_sidebar('comments-footer-three'); ?>
 </aside>
  <aside id="sidebar-four" class="clearfix">    
    <?php dynamic_sidebar('comments-footer-four'); ?>
 </aside>
 </div>
</div>

<div class="clear"></div>

<div class="navigation">
<div class="alignleft"> <?php previous_post_link(); ?></div>
<div class="alignright"> <?php next_post_link(); ?></div>
</div>
</div> 
</div>

</div>
        <!-- /entry -->
	
		<!--?php comments_template(); ?-->

</article>
<!-- /post -->

<?php endwhile; ?>
<?php endif; ?>
    
<?php get_footer(); ?>