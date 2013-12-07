<!--   
Package: Free Social Media Icons
Author: Konstantin 
Source: http://kovshenin.com/2011/freebies-a-simple-social-icon-set-gpl/

License: GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
-->

<ul class="spicesocialwidget">

<?php if(of_get_option('magazine_fb')) : ?>
<li class="facebook">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_fb'));?>" target="_blank" title="facebook">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_gp')) : ?>
<li class="googleplus">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_gp'));?>" target="_blank" title="googleplus">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_pinterest')) : ?>
<li class="pinterest">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_pinterest'));?>" target="_blank" title="pinterest">
</a></li>
<?php else : ?>
<?php endif; ?>
	<?php if(of_get_option('magazine_tw')) : ?>
<li class="twitter">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_tw'));?>" target="_blank" title="twitter">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_rss')) : ?>
<li class="rss">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_rss'));?>" target="_blank" title="rss">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_skype')) : ?>
<li class="skype">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_skype'));?>" target="_blank" title="Skype">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_vimeo')) : ?>
<li class="vimeo">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_vimeo'));?>" target="_blank" title="vimeo">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_dribbble')) : ?>
<li class="dribbble">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_dribbble'));?>" target="_blank" title="dribble">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_flickr')) : ?>
<li class="flickr">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_flickr'));?>" target="_blank" title="flickr">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_in')) : ?>
<li class="linkedin">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_in'));?>" target="_blank" title="linkedin">
</a></li>
<?php else : ?>
<?php endif; ?>
<?php if(of_get_option('magazine_youtube')) : ?>
<li class="youtube">
<a rel="nofollow" href="<?php echo esc_url(of_get_option('magazine_youtube'));?>" target="_blank" title="youtube">
</a></li>
<?php else : ?>
<?php endif; ?>
</ul>