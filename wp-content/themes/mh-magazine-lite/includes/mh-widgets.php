<?php

/***** Register Widgets *****/	
   
function register_mh_widgets() {
	register_widget('mh_facebook_widget');
	register_widget('mh_custom_posts_widget');
	register_widget('mh_slider_hp_widget');
}
add_action('widgets_init', 'register_mh_widgets'); 

/***** Facebook Likebox Widget *****/

class mh_facebook_widget extends WP_Widget {
    function mh_facebook_widget () {
        $widget_ops = array('classname' => 'mh_facebook', 'description' => __('Widget to display a Facebook likebox in your sidebar', 'mh'));
        $this->WP_Widget('mh_facebook', __('MH Facebook Likebox', 'mh'), $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $facebook_url = $instance['facebook_url'];
        $width = $instance['width'];
        $height = $instance['height'];
        
        echo $before_widget;
        
        if (!empty( $title)) { echo $before_title . $title . $after_title; }
        if ($facebook_url) { ?>
	    <div class="fb-like-box" data-href="<?php echo $facebook_url; ?>" data-width="<?php echo $width; ?>" data-height="<?php echo $height; ?>" data-show-faces="true" data-show-border="false" data-stream="false" data-header="false"></div>
	    <?php }
        
        echo $after_widget;      
    }    
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook_url'] = strip_tags($new_instance['facebook_url']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['height'] = strip_tags($new_instance['height']);
        return $instance;     
    }   
    function form($instance) {
        $defaults = array('title' => __('Connect with us on Facebook', 'mh'), 'facebook_url' => 'https://www.facebook.com/MHthemes', 'width' => '300', 'height' => '190');
        $instance = wp_parse_args((array) $instance, $defaults); ?>
        
        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('facebook_url'); ?>"><?php _e('Facebook Page URL:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['facebook_url']); ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" id="<?php echo $this->get_field_id('facebook_url'); ?>" />
	    </p>
        <p>
	    	<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['width']); ?>" name="<?php echo $this->get_field_name('width'); ?>" id="<?php echo $this->get_field_id('width'); ?>" />
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['height']); ?>" name="<?php echo $this->get_field_name('height'); ?>" id="<?php echo $this->get_field_id('height'); ?>" />
	    </p>    
    <?php    
    }
}

/***** Custom Posts Widget *****/	

class mh_custom_posts_widget extends WP_Widget {
    function mh_custom_posts_widget () {
        $widget_ops = array('classname' => 'mh_custom_posts', 'description' => __('Custom Posts Widget to display posts based on categories or tags', 'mh'));
        $this->WP_Widget('mh_custom_posts', __('MH Custom Posts', 'mh'), $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $tags = empty($instance['tags']) ? '' : $instance['tags'];
        $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 0;
        
        if ($category) {
        	$cat_url = get_category_link($category);
	        $before_title = $before_title . '<a href="' . esc_url($cat_url) . '" class="widget-title-link">';
	        $after_title = '</a>' . $after_title;
        }  
               
        echo $before_widget;
        
        if (!empty( $title)) { echo $before_title . $title . $after_title; } ?>
        
        <ul class="cp-widget row clearfix">
        <?php
		$args = array('posts_per_page' => $postcount, 'cat' => $category, 'tag' => $tags, 'offset' => $offset, 'orderby' => 'date', 'ignore_sticky_posts' => $sticky);
		$counter = 1;
		$widget_loop = new WP_Query($args);
		while ($widget_loop->have_posts()) : $widget_loop->the_post(); ?>		
			<li class="cp-wrap clearfix">				
				<div class="cp-thumb">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php if (has_post_thumbnail()) { the_post_thumbnail('cp_small'); } else { echo '<img src="' . get_template_directory_uri() . '/images/noimage_70x53.png' . '" alt="No Picture" />'; } ?>
					</a>
				</div>
				<div class="cp-data">
					<p class="cp-widget-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></p>
					<p class="meta"><?php $date = get_the_date(); echo $date; ?> // <?php comments_number(__('0 Comments', 'mh'), __('1 Comment', 'mh'), __('% Comments', 'mh')); ?></p>				
				</div>									
			</li>
		<?php
		endwhile; 
		wp_reset_postdata(); ?>
        </ul>
        <?php
        
        echo $after_widget;      
    }    
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['category'] = $new_instance['category'];
        $instance['tags'] = strip_tags($new_instance['tags']);
        $instance['postcount'] = strip_tags($new_instance['postcount']);
        $instance['offset'] = strip_tags($new_instance['offset']);
        $instance['sticky'] = $new_instance['sticky'];
        return $instance;     
    }   
    function form($instance) {
        $defaults = array('title' => '', 'category' => '', 'tags' => '', 'postcount' => '5', 'offset' => '0', 'sticky' => 0);
        $instance = wp_parse_args((array) $instance, $defaults); ?>
        
        <p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
        <p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select a Category:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
				<option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'mh'); ?></option>
				<?php
				$categories = get_categories(array('type' => 'post'));
				foreach($categories as $cat) {
					echo '<option value="' . $cat->cat_ID . '"';
					if ($cat->cat_ID == $instance['category']) { echo ' selected="selected"'; }
					echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
					echo '</option>';
				}
				?>
			</select>
		</p>
		<p>
        	<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Filter Posts by Tags (e.g. lifestyle):', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['tags']); ?>" name="<?php echo $this->get_field_name('tags'); ?>" id="<?php echo $this->get_field_id('tags'); ?>" />
	    </p>
        <p>
        	<label for="<?php echo $this->get_field_id('postcount'); ?>"><?php _e('Show:', 'mh'); ?></label>
			<input type="text" size="2" value="<?php echo esc_attr($instance['postcount']); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" id="<?php echo $this->get_field_id('postcount'); ?>" /> <?php _e('Posts', 'mh'); ?>
	    </p>  
	    <p>
        	<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Skip:', 'mh'); ?></label>
			<input type="text" size="2" value="<?php echo esc_attr($instance['offset']); ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" /> <?php _e('Posts', 'mh'); ?>
	    </p>
	    <p>
      		<input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
	  		<label for="<?php echo $this->get_field_id('sticky'); ?>"><?php _e('Ignore Sticky Posts', 'mh'); ?></label>
    	</p> 
      
    <?php    
    }
}

/***** Slider Widget (Homepage) *****/	

class mh_slider_hp_widget extends WP_Widget {
    function mh_slider_hp_widget () {
        $widget_ops = array('classname' => 'mh_slider_hp', 'description' => __('Slider widget for use on homepage templates', 'mh'));
        $this->WP_Widget('mh_slider_hp', __('MH Slider Widget (Homepage)', 'mh'), $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $tags = empty($instance['tags']) ? '' : $instance['tags'];
        $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 0; 
               
        echo $before_widget; ?>
        
        <section id="slider" class="flexslider">
			<ul class="slides">
			<?php
			$args = array('posts_per_page' => $postcount, 'cat' => $category, 'tag' => $tags, 'offset' => $offset, 'ignore_sticky_posts' => $sticky);
			$slider = new WP_query($args);
			while ($slider->have_posts()) : $slider->the_post(); ?>
				<li>
					<article class="slide-wrap">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php if (has_post_thumbnail()) { the_post_thumbnail('content'); } else { echo '<img src="' . get_template_directory_uri() . '/images/noimage_620x264.png' . '" alt="No Picture" />'; } ?>
						</a>
						<div class="slide-caption">
							<div class="slide-data">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><h2 class="slide-title"><?php the_title(); ?></h2></a>
								<div class="slide-excerpt"><?php the_excerpt(); ?></div>
							</div>
						</div>										
					</article>	
				</li>		
			<?php endwhile; wp_reset_postdata(); ?>
		</ul>
		</section>
        <?php 
        
        echo $after_widget;            
    }    
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['category'] = $new_instance['category'];
        $instance['tags'] = strip_tags($new_instance['tags']);
        $instance['postcount'] = strip_tags($new_instance['postcount']);
        $instance['offset'] = strip_tags($new_instance['offset']);
        $instance['sticky'] = $new_instance['sticky'];
        return $instance;     
    }   
    function form($instance) {
        $defaults = array('category' => '', 'tags' => '', 'postcount' => '5', 'cats' => '', 'offset' => '0', 'sticky' => 0);
        $instance = wp_parse_args((array) $instance, $defaults); ?>
        
        <p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select a Category:', 'mh'); ?></label>
			<select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
				<option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'mh'); ?></option>
				<?php
				$categories = get_categories(array('type' => 'post'));
				foreach($categories as $cat) {
					echo '<option value="' . $cat->cat_ID . '"';
					if ($cat->cat_ID == $instance['category']) { echo ' selected="selected"'; }
					echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
					echo '</option>';
				}
				?>
			</select>
		</p>
		<p>
        	<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Filter Posts by Tags (e.g. lifestyle):', 'mh'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['tags']); ?>" name="<?php echo $this->get_field_name('tags'); ?>" id="<?php echo $this->get_field_id('tags'); ?>" />
	    </p>
        <p>
        	<label for="<?php echo $this->get_field_id('postcount'); ?>"><?php _e('Show:', 'mh'); ?></label>
			<input type="text" size="2" value="<?php echo esc_attr($instance['postcount']); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" id="<?php echo $this->get_field_id('postcount'); ?>" /> <?php _e('Posts', 'mh'); ?>
	    </p>   
	    <p>
        	<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Skip:', 'mh'); ?></label>
			<input type="text" size="2" value="<?php echo esc_attr($instance['offset']); ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" /> <?php _e('Posts', 'mh'); ?>
	    </p>
		<p>
      		<input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
	  		<label for="<?php echo $this->get_field_id('sticky'); ?>"><?php _e('Ignore Sticky Posts', 'mh'); ?></label>
    	</p>
      
    <?php    
    }
}

?>