<?php

class CMA_StickyQuestion {

    function __construct() {
        add_action('add_meta_boxes', array(get_class(), 'register_sticky_box'));
        add_action('save_post', array(get_class(), 'save_postdata'));
        add_action('update_post', array(get_class(), 'save_postdata'));
        add_action('pre_get_posts', array(get_class(), 'add_sticky_routines' ));
    }

    public static function add_sticky_routines($query) {
        
        if ($query->get('post_type')=='cma_thread') {
            add_filter('posts_join', array(get_class(), 'join_sticky'));
            add_filter('posts_orderby', array(get_class(), 'orderby_sticky'));
        }
    }


    public static function register_sticky_box() {
        add_meta_box('cma-sticky-box', 'Question properties', array(get_class(), 'render_my_meta_box'), 'cma_thread', 'side');
        
    }

    public static function render_my_meta_box($post) {
        $sticky = get_post_meta($post->ID, '_sticky_post');

        $sticky_value = 0;
        if (isset($sticky[0]) && !empty($sticky[0]) && $sticky[0] == 1)
            $sticky_value = 1;
        echo '<label for="cma_sticky_box"><input type="checkbox" name="cma_sticky_box" id="cma_sticky_box" value="1" ' . ($sticky_value != 0 ? ' checked ' : '') . '>&nbsp;&nbsp;&nbsp;Sticky question</label>';
    }

    public static function save_postdata($post_id) {
        if ('cma_thread' != $_POST['post_type'])
            return;

        $sticky = 0;
        if (isset($_POST["cma_sticky_box"]) and (isset($_POST["cma_sticky_box"])))
            $sticky = 1;

        update_post_meta($post_id, '_sticky_post', $sticky);
    }

    public static function orderby_sticky($original_orderby_statement) { 
        remove_filter('posts_orderby', array(get_class(), 'orderby_sticky'));
        return " IFNULL(DD.sticky,0) DESC, " . $original_orderby_statement;
    }

    public static function join_sticky($wp_join) {
        global $wpdb;
        remove_filter('posts_join', array(get_class(), 'join_sticky'));
        $wp_join .= " LEFT JOIN (
				SELECT post_id, CAST(meta_value AS UNSIGNED) as sticky
				FROM $wpdb->postmeta
				WHERE meta_key =  '_sticky_post' ) AS DD
				ON $wpdb->posts.ID = DD.post_id ";
        return $wp_join;
    }

}

$sticky = new CMA_StickyQuestion();