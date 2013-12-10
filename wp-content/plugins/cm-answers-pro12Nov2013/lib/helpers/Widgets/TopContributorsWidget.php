<?php

class CMA_TopContributorsWidget extends WP_Widget {

    public function CMA_TopContributorsWidget() {
        $widget_ops = array('classname' => 'CMA_TopContributorsWidget', 'description' => 'Show CM Top contributors');
        $this->WP_Widget('CMA_TopContributorsWidget', 'CM Top contributors', $widget_ops);
    }

    public static function getInstance() {
        return register_widget(get_class());
    }

    /**
     * Widget options form
     * @param WP_Widget $instance 
     */
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Top contributors', 'cm-answers-pro');
        }
        
        $displayNumAnswers = isset($instance['displayNumAnswers'])?$instance['displayNumAnswers']:0;
        $limit = isset($instance['limit'])?$instance['limit']:10;
        ?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name('limit'); ?>"><?php _e('Count:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
        </p>
                 <p><label for="<?php echo $this->get_field_id('displayNumAnswers'); ?>">Display Number of Answers: <select class="widefat" id="<?php echo $this->get_field_id('displayNumAnswers'); ?>" name="<?php echo $this->get_field_name('displayNumAnswers'); ?>">
                            <option value="0"<?php if (!$displayNumAnswers) echo ' selected="selected"'; ?>>No</option>
                            <option value="1"<?php if ($displayNumAnswers) echo ' selected="selected"'; ?>>Yes</option>
                        </select></p>
 
 <?php
    }

    /**
     * Update widget options
     * @param WP_Widget $new_instance
     * @param WP_Widget $old_instance
     * @return WP_Widget 
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['limit'] = (!empty($new_instance['limit']) ) ? strip_tags($new_instance['limit']) : '10';
        $instance['displayNumAnswers'] = (!empty($new_instance['displayNumAnswers']) ) ? strip_tags($new_instance['displayNumAnswers']) : '0';

        return $instance;
    }

    /**
     * Render widget
     * 
     * @param array $args
     * @param WP_Widget $instance 
     */
    public function widget($args, $instance) {
        global $wpdb;
        extract($args, EXTR_SKIP);

        $title = apply_filters('widget_title', $instance['title']);
        $limit = $instance['limit'];
        $displayNumAnswers = $instance['displayNumAnswers'];

        echo $before_widget;
        if (!empty($title))
            echo $before_title . $title . $after_title;

        ?>
        
        <div class="cma-tags-container">
            <?php
                $contributors = $wpdb->get_results("SELECT wu.ID, wu.user_login, wu.user_nicename, count(wc.comment_ID) as cnt
                      FROM $wpdb->comments wc
                      
                      INNER JOIN $wpdb->users wu
                        ON wu.user_email=wc.comment_author_email

                      WHERE wc.comment_post_ID in (SELECT ID FROM $wpdb->posts wp WHERE wp.post_type='cma_thread')

                      GROUP BY comment_author ORDER BY cnt DESC
                      LIMIT {$limit}");
                      
                foreach ($contributors as $c) {
                    echo '<div>';
                    echo '<a href="'.home_url().'/contributor/'.$c->user_nicename.'">' . $c->user_login . "</a>";
					if ($displayNumAnswers) echo " <span>" . $c->cnt . " answers</span>";
                    echo '</div>';
                }
            ?>
        </div>
        <?php
        echo $after_widget;
    }

    static public function get_terms_by_post_type($taxonomies, $post_types) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT t.*, COUNT(*) as cnt from $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id WHERE p.post_type IN('" . join("', '", $post_types) . "') AND tt.taxonomy IN('" . join("', '", $taxonomies) . "') GROUP BY t.term_id ORDER BY cnt DESC");
        $results = $wpdb->get_results($query);
        return $results;
    }

}

add_action('widgets_init', 'register_topc_widget');

function register_topc_widget() {
    register_widget('CMA_TopContributorsWidget');
}