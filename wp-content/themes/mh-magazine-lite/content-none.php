<?php
/** The template for displaying a "No posts found" message. */
?>
<?php
echo '<div class="entry sb-widget">';
if (is_search()) {
	echo '<p class="box alert">' . __( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'mh' ) . '</p>' . "\n";
} else {
	echo '<p class="box alert">' . __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'mh' ) . '</p>' . "\n";
}
get_search_form();
echo '</div>' . "\n";
$instance = array('title' => __('Latest posts', 'mh'), 'postcount' => '5');
$args = array('before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>');
the_widget('mh_custom_posts_widget', $instance , $args);
?>