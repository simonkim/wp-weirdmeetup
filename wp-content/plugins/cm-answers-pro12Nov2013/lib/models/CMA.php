<?php

include_once CMA_PATH . '/lib/models/AnswerThread.php';
include_once CMA_PATH . '/lib/controllers/BaseController.php';

class CMA
{

    public static function init()
    {
        CMA_AnswerThread::init();
        add_action('init', array('CMA_BaseController', 'bootstrap'));
    }

    public static function install($networkwide)
    {
        global $wpdb;
        global $wp_rewrite;

        if (function_exists('is_multisite') && is_multisite()) {
            // check if it is a network activation - if so, run the activation function for each blog id
            if ($networkwide) {
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs}"));
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    self::install_blog();
                }
                switch_to_blog($old_blog);
                return;
            }
        }

        self::install_blog();
    }

    public static function uninstall()
    {
    }

    public static function install_blog()
    {

    }

}
