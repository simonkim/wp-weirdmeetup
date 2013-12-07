<?php
/* Comments Template */

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if (post_password_required()) { 
	echo '<p class="no-comments">' . __('This post is password protected. Enter the password to view comments.', 'mh') . '</p>' . "\n";
	return;
}
if (have_comments()) {
	echo '<h4 class="section-title">'; echo comments_number(__('0 Comments', 'mh'), __('1 Comment', 'mh'), __('% Comments', 'mh')); echo __(' to ', 'mh') . '&#8220;' . get_the_title() . '&#8221;</h4>' . "\n";
	echo '<ol class="commentlist">' . "\n";
	echo wp_list_comments('callback=mh_comments');
	echo '</ol>' . "\n"; 
	if (get_comments_number() > get_option('comments_per_page')) {
		paginate_comments_links(array('prev_text' => __('&laquo;', 'mh'), 'next_text' => __('&raquo;', 'mh')));
	}
	if (!comments_open()) {
		echo '<p class="no-comments">' . __('Comments are disabled', 'mh') . '</p>' . "\n";
	}
}
if (comments_open()) {       
	$custom_args = array( 
    	'title_reply' => __('Leave a comment', 'mh'), 
        'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.', 'mh') . '</p>',
        'comment_notes_after'  => '', 
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . __('Comment', 'mh') . '</label><br/><textarea id="comment" name="comment" cols="45" rows="5" aria-required="true"></textarea></p>');
	comment_form($custom_args);        				
}

?>