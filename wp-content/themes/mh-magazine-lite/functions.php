<?php

/***** Include Custom Functions *****/ 

require_once('includes/mh-options.php');
require_once('includes/mh-widgets.php');
require_once('includes/mh-shortcodes.php');

/***** Set Content Width *****/	

if (!isset($content_width)) $content_width = 620;

/***** Theme Setup *****/	

function mh_themes_setup() {	
	$header = array(
    'default-image'	=> get_template_directory_uri() . '/images/logo.png',
    'width'         => 300,
    'flex-width'    => true,
    'height'        => 100,
    'flex-height'   => true,
    'header-text'   => false,
	);
	
	load_theme_textdomain('mh', get_template_directory() . '/languages');	
	add_theme_support('automatic-feed-links');
	add_theme_support('custom-background');		
	add_theme_support('custom-header', $header);
	add_theme_support('post-thumbnails');
	add_image_size('content', 620, 264, true);
	add_image_size('loop', 174, 131, true);
	add_image_size('cp_small', 70, 53, true);
	add_editor_style();
	register_nav_menus(array('main_nav' => __('Main Navigation', 'mh')));
}
add_action('after_setup_theme', 'mh_themes_setup');

/***** Enable Shortcodes inside Widgets	*****/

add_filter('widget_text', 'do_shortcode');

/***** Load JavaScript & CSS *****/

function mh_scripts() {
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
	wp_enqueue_script('jquery migrate', 'http://code.jquery.com/jquery-migrate-1.2.1.js');
	wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.js');
	wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'));
	wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'));
	wp_enqueue_script('prettyphoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array('jquery'));
	wp_enqueue_style('prettyphoto', get_template_directory_uri() . '/css/prettyPhoto.css');
	if (!is_admin()) {
		if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'mh_scripts');

/***** Register Widget Areas / Sidebars	*****/

function mh_widgets_init() {
	register_sidebar(array('name' => __('Sidebar', 'mh'), 'id' => 'sidebar', 'description' => __('Sidebar', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
	register_sidebar(array('name' => __('Home 1', 'mh'), 'id' => 'home-1', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
	register_sidebar(array('name' => __('Home 2', 'mh'), 'id' => 'home-2', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
	register_sidebar(array('name' => __('Home 3', 'mh'), 'id' => 'home-3', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
	register_sidebar(array('name' => __('Home 4', 'mh'), 'id' => 'home-4', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
	register_sidebar(array('name' => __('Home 5', 'mh'), 'id' => 'home-5', 'description' => __('Widget area on homepage', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));    	
	register_sidebar(array('name' => __('Posts 1', 'mh'), 'id' => 'posts-1', 'description' => __('Widget area above single post content', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
	register_sidebar(array('name' => __('Posts 2', 'mh'), 'id' => 'posts-2', 'description' => __('Widget area below single post content', 'mh'), 'before_widget' => '<div class="sb-widget">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));	
	register_sidebar(array('name' => __('Footer 1', 'mh'), 'id' => 'footer-1', 'description' => __('Footer widget area', 'mh'), 'before_widget' => '<div class="footer-widget">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
	register_sidebar(array('name' => __('Footer 2', 'mh'), 'id' => 'footer-2', 'description' => __('Footer widget area', 'mh'), 'before_widget' => '<div class="footer-widget">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
	register_sidebar(array('name' => __('Footer 3', 'mh'), 'id' => 'footer-3', 'description' => __('Footer widget area', 'mh'), 'before_widget' => '<div class="footer-widget">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
	register_sidebar(array('name' => __('Footer 4', 'mh'), 'id' => 'footer-4', 'description' => __('Footer widget area', 'mh'), 'before_widget' => '<div class="footer-widget">', 'after_widget' => '</div>', 'before_title' => '<h6 class="footer-widget-title">', 'after_title' => '</h6>'));
}
add_action('widgets_init', 'mh_widgets_init');

/***** wp_title Output *****/

function mh_wp_title($title, $sep) {
	global $paged, $page;
	
	if (is_feed())
		return $title;
		
	$title .= get_bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	
	if ($site_description && (is_home() || is_front_page()))
		$title = "$title $sep $site_description";	
	if ($paged >= 2 || $page >= 2)
		$title = "$title $sep " . sprintf(__('Page %s', 'mh'), max($paged, $page));
		
	return $title;
}
add_filter('wp_title', 'mh_wp_title', 10, 2);

/***** Page Title Output *****/

function mh_page_title() {	
	echo '<h1 class="page-title">';
	if (is_home()) {
		echo get_the_title(get_option('page_for_posts', true));		
	} elseif (is_author()) {
		echo __('Articles by ', 'mh') . get_the_author();
	} elseif (is_category()) {
		echo single_cat_title("", false);
	} elseif (is_tag()) {
		echo single_tag_title("", false);
	} elseif (is_day()) {
		echo get_the_date();
	} elseif (is_month()) {
		echo get_the_date('F Y');
	} elseif (is_year()) {
		echo get_the_date('Y');
	} elseif (is_404()) {
		echo __('Page not found (404)', 'mh');	
	}
	echo '</h1>' . "\n";
}

/***** Logo / Header Image Fallback *****/

if (!function_exists('mh_logo')) {
	function mh_logo() {
		$header_img = get_header_image();	
		echo '<div class="logo-wrap" role="banner">' . "\n";
		if ($header_img) {
			echo '<a href="' . esc_url(home_url('/')) . '" title="' . get_bloginfo('name') . '" rel="home"><img src="' . $header_img . '" height="' . get_custom_header()->height . '" width="' . get_custom_header()->width . '" alt="' . get_bloginfo('name') . '" /></a>' . "\n";
		} else {
			echo '<div class="logo">' . "\n";
			echo '<a href="' . esc_url(home_url('/')) . '" title="' . get_bloginfo('name') . '" rel="home">' . "\n";
			echo '<h1 class="logo-name">' . get_bloginfo('name') . '</h1>' . "\n";
			echo '<h2 class="logo-desc">' . get_bloginfo('description') . '</h2>' . "\n";
			echo '</a>' . "\n";
			echo '</div>' . "\n";
		}
		echo '</div>' . "\n";	
	}
}

/***** Custom Excerpts *****/

if (!function_exists('custom_excerpt')) {
	function custom_excerpt($text = '') {
		$raw_excerpt = $text;
		if ('' == $text) {
			global $post;
			$options = get_option('mh_options'); 
			$custom_length = empty($options['excerpt_length']) ? '35' : $options['excerpt_length'];
			$text = get_the_content('');
			$text = do_shortcode($text);
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]>', $text);
			$excerpt_length = apply_filters('excerpt_length', $custom_length);
			$excerpt_more = apply_filters('excerpt_more', ' ...');
			$text = wp_trim_words($text, $excerpt_length, $excerpt_more);
		}
		return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
	}
	remove_filter('get_the_excerpt', 'wp_trim_excerpt');
	add_filter('get_the_excerpt', 'custom_excerpt');
}

/***** Custom Commentlist *****/

if (!function_exists('mh_comments')) {
	function mh_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>">
				<div class="vcard meta">	
					<?php echo get_avatar($comment->comment_author_email, 30); ?>			
					<?php echo get_comment_author_link() ?> // 
					<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)) ?>"><?php printf(__('%1$s at %2$s', 'mh'), get_comment_date(),  get_comment_time()) ?></a> // 
					<?php if (comments_open() && $args['max_depth']!=$depth) { ?>		
					<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
					<?php } ?>
					<?php edit_comment_link(__('(Edit)', 'mh'),'  ','') ?>
				</div>
				<?php if ($comment->comment_approved == '0') : ?>
					<div class="comment-info"><?php _e('Your comment is awaiting moderation.', 'mh') ?></div>
				<?php endif; ?>
				<div class="comment-text">	
					<?php comment_text() ?>	
				</div>
			</div>
<?php }
}

/***** Custom Comment Fields *****/

function comment_fields($fields) {
	$commenter = wp_get_current_commenter();
		$req = get_option('require_name_email');
		$aria_req = ($req ? " aria-required='true'" : '');
		$fields =  array(
			'author'	=>	'<p class="comment-form-author"><label for="author">' . __('Name ', 'mh') . '</label>' . ($req ? '<span class="required">*</span>' : '') . '<br/><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
			'email' 	=>	'<p class="comment-form-email"><label for="email">' . __('Email ', 'mh') . '</label>' . ($req ? '<span class="required">*</span>' : '' ) . '<br/><input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
			'url' 		=>	'<p class="comment-form-url"><label for="url">' . __('Website', 'mh') . '</label><br/><input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>'
	);
	return $fields;
}
add_filter('comment_form_default_fields', 'comment_fields');

/***** Custom Meta Box *****/

add_action('add_meta_boxes', 'mh_add_meta_boxes');
add_action('save_post', 'mh_save_meta_boxes', 10, 2 );

if (!function_exists('mh_add_meta_boxes')) {
	function mh_add_meta_boxes() {
		add_meta_box('mh_post_details', __('Post options', 'mh'), 'mh_post_meta', 'post', 'normal', 'high');
	}
}

if (!function_exists('mh_post_meta')) {
	function mh_post_meta() {
		global $post;
		wp_nonce_field('mh_meta_box_nonce', 'meta_box_nonce'); 
		echo '<p>';
		echo '<label for="mh-subheading">' . __("Subheading (will be displayed below post title)", 'mh') . '</label>';
		echo '<br />';
		echo '<input class="widefat" type="text" name="mh-subheading" id="mh-subheading" placeholder="Enter subheading" value="' . esc_attr(get_post_meta($post->ID, 'mh-subheading', true)) . '" size="30" />';
		echo '</p>';
	}
}

if (!function_exists('mh_save_meta_boxes')) {
	function mh_save_meta_boxes($post_id, $post) {
		if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'mh_meta_box_nonce')) {
			return $post->ID;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        	return $post->ID;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post->ID;
			}
		} 
		elseif (!current_user_can('edit_post', $post_id)) {
			return $post->ID;
		}
		if ('post' == $_POST['post_type']) {
			$meta_data['mh-subheading'] = esc_attr($_POST['mh-subheading']);	
		}	
		foreach ($meta_data as $key => $value) {
			if ($post->post_type == 'revision') return;
			$value = implode(',', (array)$value);
			if (get_post_meta($post->ID, $key, FALSE)) {
				update_post_meta($post->ID, $key, $value);
			} else {
				add_post_meta($post->ID, $key, $value);
			}
			if (!$value) delete_post_meta($post->ID, $key);
		}
	}
}

/***** Author box *****/

if (!function_exists('mh_author_box')) {
	function mh_author_box() {
		$options = get_option('mh_options');
		if (get_the_author_meta('description')) {
			$author = get_the_author();
			echo '<section class="author-box clearfix">' . "\n";
			echo '<div class="author-box-avatar">' . get_avatar(get_the_author_meta('ID'), 80) . '</div>' . "\n";
			echo '<div class="author-box-desc">' . "\n";
			echo '<h5>' . __('About ', 'mh') . esc_attr($author) . '</h5>' . "\n";
			echo '<p>';
			echo the_author_meta('user_description') . ' ';
			echo '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '" title="' . __('More articles written by ', 'mh') . esc_attr($author) . '">' . __('More Posts', 'mh') . '</a>';	
			echo '</p>' . "\n";
			echo '</div>' . "\n";
			echo '</section>' . "\n";		
		}	
	}
}

/***** Post / Image Navigation *****/

if (!function_exists('mh_postnav')) {
	function mh_postnav() {	
		global $post;				
		$parent_post = get_post($post->post_parent);
		$attachment = is_attachment();
		$previous = ($attachment) ? $parent_post : get_adjacent_post(false, '', true);
		$next = get_adjacent_post(false, '', false);
	
		if (!$next && !$previous)
		return;	
		
		if ($attachment) {
			$attachments = get_children(array('post_type' => 'attachment', 'post_mime_type' => 'image', 'post_parent' => $parent_post->ID));	
			$count = count($attachments);
		}
		if ($attachment && $count == 1) {
			$permalink = get_permalink($parent_post);
			echo '<nav class="post-nav-wrap clearfix" role="navigation">' . "\n";
			echo '<div class="post-nav left">' . "\n";
			echo '<a href="' . $permalink . '">' . __('&larr; Back to post', 'mh') . '</a>';	
			echo '</div>' . "\n";
			echo '</nav>' . "\n";
		} elseif (!$attachment || $attachment && $count > 1) {			
			echo '<nav class="post-nav-wrap clearfix" role="navigation">' . "\n";
			echo '<div class="post-nav left">' . "\n";
			if ($attachment) {					
				previous_image_link('%link', __('&larr; Previous image', 'mh'));	
			} else {
				previous_post_link('%link', __('&larr; Previous post', 'mh'));	
			}
			echo '</div>' . "\n";
			echo '<div class="post-nav right">' . "\n";
			if ($attachment) {
				next_image_link('%link', __('Next image &rarr;', 'mh'));
			} else {
				next_post_link('%link', __('Next post &rarr;', 'mh'));
			}
			echo '</div>' . "\n";
			echo '</nav>' . "\n";		
		}	
				
	}
}

/***** Related Posts *****/

if (!function_exists('mh_related')) {
	function mh_related() {
		global $post;
		$tags = wp_get_post_tags($post->ID);
		if ($tags) {
			$tag_ids = array();  
			foreach($tags as $tag) $tag_ids[] = $tag->term_id;  
			$args = array('tag__in' => $tag_ids, 'post__not_in' => array($post->ID), 'posts_per_page' => 7, 'ignore_sticky_posts' => 1, 'orderby' => 'rand');
			$related = new wp_query($args);
			if ($related->have_posts()) {		
				echo '<section class="related-posts">' . "\n";
				echo '<h3 class="section-title">' . __('Related Posts', 'mh') . '</h3>' . "\n";
				echo '<div class="related-wrap clearfix">' . "\n";					
				while ($related->have_posts()) : $related->the_post();
					$permalink = get_permalink($post->ID); 
					echo '<div class="related-thumb">' . "\n";
					echo '<a href="' . $permalink . '" title="' . get_the_title() . '">' . "\n";
					if (has_post_thumbnail()) {
						the_post_thumbnail('cp_small'); 
					} else {
						echo '<img src="' . get_template_directory_uri() . '/images/noimage_70x53.png' . '" alt="No Picture" />' . "\n";
					}
					echo '</a>' . "\n";  
					echo '</div>' . "\n";
				endwhile;
				echo '</div>' . "\n";
				echo '</section>' . "\n";
				wp_reset_postdata();
			}	
		}			
	} 
}

/***** Pagination *****/

if (!function_exists('mh_pagination')) {
	function mh_pagination() {
		global $wp_query;
	    $big = 9999;
		echo paginate_links(array(
				'base' 		=> str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' 	=> '?paged=%#%',
				'current' 	=> max(1, get_query_var('paged')),
				'prev_next'	=> true,
				'prev_text'	=> __('&laquo;', 'mh'),
				'next_text' => __('&raquo;', 'mh'),
				'total' 	=> $wp_query->max_num_pages
		));			
	}
}

/***** Load social scripts *****/

if (!function_exists('mh_social_scripts')) {
	function mh_social_scripts() {
		if (is_active_widget('', '', 'mh_facebook')) {
			global $locale;			
			echo "<div id='fb-root'></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = 'https://connect.facebook.net/" . $locale . "/all.js#xfbml=1'; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>" . "\n";			
		}
	}
	add_action('wp_footer', 'mh_social_scripts');
}

/***** Automatically add rel="prettyPhoto" *****/

if (!function_exists('mh_add_prettyphoto')) {
	function mh_add_prettyphoto($content) {
    	global $post;
		$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
		$replacement = '<a$1href=$2$3.$4$5 rel="prettyPhoto">';
		$content = preg_replace($pattern, $replacement, $content);
		return $content;
	}
	add_filter('the_content', 'mh_add_prettyphoto');
}

/***** Custom Dashboard Widget *****/ 

function mh_info_widget() {
	?>
	<p>Thank you very much for downloading <strong>MH Magazine lite</strong> WordPress theme! If you need help with the theme setup or if you have any questions, please have a look at the:</p>
	<ul>
		<li><a href="http://www.mhthemes.com/documentation-mh-magazine-lite/" target="_blank"><strong>Quick Theme Documentation</strong></a></li>
		<li><a href="http://www.mhthemes.com/faq/" target="_blank"><strong>Frequently Asked Questions</strong></a></li>
		<li><a href="http://wordpress.org/support/theme/mh-magazine-lite" target="_blank"><strong>WordPress Forum</strong></a></li>
	</ul>
	<p>If you want your magazine to be fully responsive and if you need more professional options, you can <a href="http://www.mhthemes.com/themes/" target="_blank"><strong>purchase the premium version of MH Magazine</strong></a>. The premium version of MH Magazine has included a lot more features and excellent customer support in german and english language. You can also stay up-to-date by following us on <a href="https://www.facebook.com/MHthemes" target="_blank"><strong>Facebook</strong></a>, <a href="https://twitter.com/MHthemes" target="_blank"><strong>Twitter</strong></a> and/or <a href="https://plus.google.com/u/0/116858061109988982542/" target="_blank"><strong>Google+</strong></a>.</p>
	<?php
}

function mh_dashboard_widgets() {
	global $wp_meta_boxes;
	add_meta_box('mh_info_widget', __('Theme Support: Get started!', 'mh'), 'mh_info_widget', 'dashboard', 'normal', 'high');
}
add_action('wp_dashboard_setup', 'mh_dashboard_widgets');

?>