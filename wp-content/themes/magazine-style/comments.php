<?php	/**	* @package WordPress	* @subpackage Default_Theme */
// Do not delete these lines	
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))		
die ('Please do not load this page directly. Thanks!');	
if ( post_password_required() ) { ?>		
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'magazine'); ?></p>	
<?php		
return;	}	
?>	
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	<h3 id="Comments"><?php _e('comments', 'magazine'); ?>comments</h3>
	<div class="navigation">
		<div class="alignleft">
			<?php previous_comments_link() ?>
		</div>
			<div class="alignright">
				<?php next_comments_link() ?>
			</div>
	</div>
		<ol class="commentlist">
			<?php wp_list_comments(); ?>
		</ol>
		<div class="navigation">
			<div class="alignleft">
				<?php previous_comments_link() ?>
			</div>
				<div class="alignright">
					<?php next_comments_link() ?>
				</div>
		</div>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?><!-- If comments are open, but there are no comments. -->
<?php else : // comments are closed ?><!-- If comments are closed.
<p class="nocomments">Comments are closed.</p> 		-->
<?php endif; ?>	<?php endif; ?><?php comment_form(); ?>