<!--
Theme Name: Sublime
Author: amplifiii
Author URI: http://www.amplifiii.com
-->

</div>
</div>


<!-- Get Options Start -->
<?php
global $data; //fetch options stored in $data
?>
<!-- Get Options End -->


<?php if ( of_get_options('footer_widget_section') ) : ?>
<div id="footer-wrap2">
<div id="footer-wrap">
<!-- /main -->
    <div id="footer" class="clearfix">
        <!-- /footer-widget-start -->
        <div id="footer-widget-wrap" class="clearfix"> 
           <div id="footer-one">
                <?php dynamic_sidebar('footer-one'); ?>
            </div>
            <!-- /footer-one -->
            
            <div id="footer-two">
                <?php dynamic_sidebar('footer-two'); ?>
            </div>
            <!-- /footer-two -->
            
            <div id="footer-three">
                <?php dynamic_sidebar('footer-three'); ?>
            </div>
            <!-- /footer-three -->
            
                        <div id="footer-four">
                <?php dynamic_sidebar('footer-four'); ?>
            </div>
            <!-- /footer-four -->
        </div>    
        <!-- /footer-widget-wrap -->
</div>
</div>
<?php endif; ?>

        <div id="footer-bottom-wrap2">
        <div id="footer-bottom-wrap">
		<div id="footer-bottom" class="clearfix">         

    <div id="social-buttons" class="clearfix">
    <div id="social-buttons-pad">
    <?php if ( of_get_options('facebook') ) : ?>
    <a href="<?php echo of_get_options('facebook_url') ?>" title="Facebook" target="_blank" rel="nofollow"> 
    <img src="<?php echo get_template_directory_uri(); ?>/images/facebook.png" width="250" height="45" alt="<?php bloginfo('name'); ?>- <?php bloginfo('description'); ?>" title="" /></a> 
    <?php endif; ?>

    <?php if ( of_get_options('google') ) : ?>
    <a href="<?php echo of_get_options('google_url') ?>" title="Google+" target="_blank" rel="nofollow"> 
    <img src="<?php echo get_template_directory_uri(); ?>/images/google+.png" width="250" height="45" alt="<?php bloginfo('name'); ?>- <?php bloginfo('description'); ?>" title="" /></a>
    <?php endif; ?>


    <?php if ( of_get_options('twitter') ) : ?>
    <a href="<?php echo of_get_options('twitter_url') ?>" title="Twitter" target="_blank" rel="nofollow"> 
    <img src="<?php echo get_template_directory_uri(); ?>/images/twitter.png" width="250" height="45" alt="<?php bloginfo('name'); ?>- <?php bloginfo('description'); ?>" title="" /></a>   
    <?php endif; ?>

    <?php if ( of_get_options('linkedin') ) : ?>
    <!--a title="Linked In" class="tooltip"-->
    <a href="<?php echo of_get_options('linkedin_url') ?>" title="LinkedIn" target="_blank" rel="nofollow">
    <img src="<?php echo get_template_directory_uri(); ?>/images/linkedin.png" width="250" height="45" alt="<?php bloginfo('name'); ?>- <?php bloginfo('description'); ?>" title="" /></a>
    <?php endif; ?>
    </div>
    </div>

        <div id="copyright">
        <div id="copyright-text">
                &copy; <?php echo date('Y'); ?>  

       

<?php bloginfo( 'name' ) ?> 

 <?php
        if (is_home()) {
        echo "// theme by ";
        echo "<a href=\"http://amplifiii.com\" title=\"amplifiii\" target=\"http://amplifiii.com\">amplifiii</a> ";   
} else {
        //do nothing
}
?>
// <a href="#toplink"><?php echo 'back up' ?> &uarr;</a>
        </div>
        </div>          
        </div>
        </div>
</div>
</div>

<?php wp_footer(); ?>

</body>
</html>