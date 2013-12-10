<?php
class CMA_QuestionsWidget extends WP_Widget
{

    public function CMA_QuestionsWidget()
    {
        $widget_ops = array('classname' => 'CMA_QuestionsWidget', 'description' => 'Show CM Questions');
        $this->WP_Widget('CMA_QuestionsWidget', 'CM Questions', $widget_ops);
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
        $instance = wp_parse_args((array) $instance, array('title' => '', 'limit' => 5, 'sort' => 'newest',
            'cat' => '',
            'author' => '',
            'form' => false,
            'displaySearch' => false,
            'displayTags' => false,
            'displayCategories' => true,
            'displayViews' => false,
            'displayVotes' => false,
            'displayAnswers' => false,
            'displayUpdated' => true,
            'displayAuthorInfo' => true,
            'displayStatusInfo' => false,
        ));

        $title             = $instance['title'];
        $limit             = $instance['limit'];
        $sort              = $instance['sort'];
        $cat               = $instance['cat'];
        $author            = $instance['author'];
        $form              = $instance['form'];
        $displaySearch     = $instance['displaySearch'];
        $displayTags       = $instance['displayTags'];
        $displayCategories = $instance['displayCategories'];
        $displayViews      = $instance['displayViews'];
        $displayVotes      = $instance['displayVotes'];
        $displayAnswers    = $instance['displayAnswers'];
        $displayUpdated    = $instance['displayUpdated'];
        $displayAuthorInfo = $instance['displayAuthorInfo'];
        $displayStatusInfo = $instance['displayStatusInfo'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>">
                Limit: <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo attribute_escape($limit); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sort'); ?>">
                Sort: <select class="widefat" id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
                    <?php
                    $options           = array('newest' => 'Newest', 'hottest' => 'Hottest', 'views' => 'Most views', 'votes' => 'Most votes');
                    foreach($options as $key => $name)
                    {
                        echo '<option value="' . $key . '"';
                        if($key == $sort) echo ' selected="selected"';
                        echo '>' . $name . '</option>';
                    }
                    ?>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cat'); ?>">Category: <select class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>">
                    <option value="">All categories</option>
                    <?php
                    $options = get_terms(CMA_AnswerThread::CAT_TAXONOMY, array(
                        'orderby' => 'name',
                        'hide_empty' => 0
                    ));
                    foreach($options as $term)
                    {
                        echo '<option value="' . $term->term_id . '"';
                        if($term->term_id == $cat) echo ' selected="selected"';
                        echo '>' . $term->name . '</option>';
                    }
                    ?>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('author'); ?>">Author: <select class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>">
                    <option value="">All authors</option>
                    <?php
                    $options = get_users();
                    foreach($options as $user)
                    {
                        echo '<option value="' . $user->ID . '"';
                        if($user->ID == $author) echo ' selected="selected"';
                        echo '>' . $user->display_name . '</option>';
                    }
                    ?>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayCategories'); ?>">Display categories:<select class="widefat" id="<?php echo $this->get_field_id('displayCategories'); ?>" name="<?php echo $this->get_field_name('displayCategories'); ?>">
                    <option value="0"<?php if(!$displayCategories) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayCategories) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('form'); ?>">Show Form: <select class="widefat" id="<?php echo $this->get_field_id('form'); ?>" name="<?php echo $this->get_field_name('form'); ?>">
                    <option value="0"<?php if(!$form) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($form) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displaySearch'); ?>">Show Search: <select class="widefat" id="<?php echo $this->get_field_id('displaySearch'); ?>" name="<?php echo $this->get_field_name('displaySearch'); ?>">
                    <option value="0"<?php if(!$displaySearch) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displaySearch) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayTags'); ?>">Show Tags:
                <select class="widefat" id="<?php echo $this->get_field_id('displayTags'); ?>" name="<?php echo $this->get_field_name('displayTags'); ?>">
                    <option value="0"<?php if(!$displayTags) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayTags) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayViews'); ?>">Show Views:
                <select class="widefat" id="<?php echo $this->get_field_id('displayViews'); ?>" name="<?php echo $this->get_field_name('displayViews'); ?>">
                    <option value="0"<?php if(!$displayViews) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayViews) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayVotes'); ?>">Show Votes:
                <select class="widefat" id="<?php echo $this->get_field_id('displayVotes'); ?>" name="<?php echo $this->get_field_name('displayVotes'); ?>">
                    <option value="0"<?php if(!$displayVotes) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayVotes) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayAnswers'); ?>">Show Answers:
                <select class="widefat" id="<?php echo $this->get_field_id('displayAnswers'); ?>" name="<?php echo $this->get_field_name('displayAnswers'); ?>">
                    <option value="0"<?php if(!$displayAnswers) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayAnswers) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayUpdated'); ?>">Show Updated:
                <select class="widefat" id="<?php echo $this->get_field_id('displayUpdated'); ?>" name="<?php echo $this->get_field_name('displayUpdated'); ?>">
                    <option value="0"<?php if(!$displayUpdated) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayUpdated) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayAuthorInfo'); ?>">Show Author:
                <select class="widefat" id="<?php echo $this->get_field_id('displayAuthorInfo'); ?>" name="<?php echo $this->get_field_name('displayAuthorInfo'); ?>">
                    <option value="0"<?php if(!$displayAuthorInfo) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayAuthorInfo) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('displayStatusInfo'); ?>">Show Status:
                <select class="widefat" id="<?php echo $this->get_field_id('displayStatusInfo'); ?>" name="<?php echo $this->get_field_name('displayStatusInfo'); ?>">
                    <option value="0"<?php if(!$displayStatusInfo) echo ' selected="selected"'; ?>>No</option>
                    <option value="1"<?php if($displayStatusInfo) echo ' selected="selected"'; ?>>Yes</option>
                </select>
            </label>
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
        $instance                      = $old_instance;
        $instance['title']             = $new_instance['title'];
        $instance['limit']             = $new_instance['limit'];
        $instance['sort']              = $new_instance['sort'];
        $instance['cat']               = $new_instance['cat'];
        $instance['author']            = $new_instance['author'];
        $instance['form']              = $new_instance['form'];
        $instance['displayCategories'] = $new_instance['displayCategories'];
        $instance['displaySearch']     = $new_instance['displaySearch'];
        $instance['displayTags']       = $new_instance['displayTags'];
        $instance['displayVotes']      = $new_instance['displayVotes'];
        $instance['displayViews']      = $new_instance['displayViews'];
        $instance['displayAnswers']    = $new_instance['displayAnswers'];
        $instance['displayUpdated']    = $new_instance['displayUpdated'];
        $instance['displayAuthorInfo'] = $new_instance['displayAuthorInfo'];
        $instance['displayStatusInfo'] = $new_instance['displayStatusInfo'];
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

        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if(!empty($title))
        {
            echo $before_title . $title . $after_title;
        }

        foreach($instance as $instanceKey => $intanceValue)
        {
            if(preg_match('/(display)(\w+)/', $instanceKey, $matches) == 1)
            {
                $newKey            = strtolower($matches[2]);
                $instance[$newKey] = $intanceValue;
                unset($instance[$instanceKey]);
            }
        }

        $instance['tiny'] = true;
        // WIDGET CODE GOES HERE
        echo CMA_Shortcodes::shortcode_questions($instance, true);
        echo $after_widget;
    }
}