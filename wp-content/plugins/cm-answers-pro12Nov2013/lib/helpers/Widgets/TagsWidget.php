<?php
class CMA_TagsWidget extends WP_Widget
{

    public function CMA_TagsWidget()
    {
        $widget_ops = array('classname' => 'CMA_TagsWidget', 'description' => 'Show CM Tags');
        $this->WP_Widget('CMA_TagsWidget', 'CM Tags', $widget_ops);
    }

    public static function getInstance()
    {
        return register_widget(get_class());
    }

    /**
     * Widget options form
     * @param WP_Widget $instance
     */
    public function form($instance)
    {
        if(isset($instance['title']))
        {
            $title = $instance['title'];
        }
        else
        {
            $title = __('Popular Tags', 'cm-answers-pro');
        }
        $limit = isset($instance['limit']) ? $instance['limit'] : 10;
        ?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name('limit'); ?>"><?php _e('Count:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
        </p>

        <?php
    }

    /**
     * Update widget options
     * @param WP_Widget $new_instance
     * @param WP_Widget $old_instance
     * @return WP_Widget
     */
    public function update($new_instance, $old_instance)
    {
        $instance          = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['limit'] = (!empty($new_instance['limit']) ) ? strip_tags($new_instance['limit']) : '10';

        return $instance;
    }

    /**
     * Render widget
     *
     * @param array $args
     * @param WP_Widget $instance
     */
    public function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        $title = apply_filters('widget_title', $instance['title']);
        $limit = $instance['limit'];

        echo $before_widget;
        if(!empty($title)) echo $before_title . $title . $after_title;
        ?>
        <div class="cma-tags-container">
            <?php
            $terms = self::get_terms_by_post_type(array('post_tag'), array('cma_thread'));
            if(!empty($terms))
            {
                $qs = '?';
                if(isset($_GET["sort"])) $qs .= 'sort=' . $_GET["sort"] . "&";
                if(isset($_GET["s"])) $qs .= 's=' . $_GET["s"] . "&";
                foreach($terms as $term)
                {
                    echo '<div>';
                    echo '<a href="' . home_url() . '/' . CMA_AnswerThread::getAnswersPermalink() . '/' . $qs . 'cmatag=' . $term->name . '">' . $term->name . "</a><span>&nbsp&nbsp&nbspx&nbsp&nbsp" . $term->cnt . "</span><br />";
                    echo '</div>';
                    if(--$limit <= 0) break;
                }
            }
            else
            {
                echo 'None tags exist';
            }
            ?>
        </div>
        <?php
        echo $after_widget;
    }

    static public function get_terms_by_post_type($taxonomies, $post_types)
    {
        global $wpdb;
        $query   = $wpdb->prepare("SELECT t.*, COUNT(*) as cnt from $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s') AND p.post_status='publish' GROUP BY t.term_id ORDER BY cnt DESC", join("', '", $post_types), join("', '", $taxonomies));
        $results = $wpdb->get_results($query);
        return $results;
    }

}
add_action('widgets_init', 'register_tags_widget');

function register_tags_widget()
{
    register_widget('CMA_TagsWidget');
}
