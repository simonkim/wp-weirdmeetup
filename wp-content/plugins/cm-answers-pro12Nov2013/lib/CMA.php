<?php
include_once CMA_PATH . '/lib/models/AnswerThread.php';
include_once CMA_PATH . '/lib/controllers/BaseController.php';
class CMA
{

    public static function init($pluginFilePath)
    {
        register_activation_hook($pluginFilePath, array(__CLASS__, 'install'));
        register_uninstall_hook($pluginFilePath, array(__CLASS__, 'uninstall'));

        CMA_AnswerThread::init();

        add_action('init', array('CMA_BaseController', 'bootstrap'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enable_scripts'));
        add_action('wp_head', array(__CLASS__, 'add_base_url'));
    }

    public static function install($networkwide)
    {
        global $wpdb;

        flush_rewrite_rules();

        if(function_exists('is_multisite') && is_multisite())
        {
            // check if it is a network activation - if so, run the activation function for each blog id
            if($networkwide)
            {
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogids  = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs}"));
                foreach($blogids as $blog_id)
                {
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
        flush_rewrite_rules();
    }

    public static function install_blog()
    {

    }

    public static function add_base_url()
    {
        echo '<link rel="baseurl" href="' . home_url() . '" />';
    }

    public static function enable_scripts()
    {
        wp_enqueue_script('jquery');
//        wp_enqueue_script('jquery-bbq', CMA_URL . '/views/resources/ajax/jquery.ba-bbq.min.js', array('jquery'));
//        wp_enqueue_script('cma-ajax', CMA_URL . '/views/resources/ajax/ajax.js', array('jquery', 'jquery-bbq'));
    }

    public static function getBacklink()
    {
        $referer = isset($_GET['backlink']) ? $_GET['backlink'] : '';
        if($referer && base64_decode($referer))
        {
            $backlink = esc_attr(base64_decode($referer));
            self::saveBacklink($backlink);
        }
        else
        {
            $backlink = self::restoreBacklink();
        }

        return $backlink;
    }

    private static function saveBacklink($backlink)
    {
        $_SESSION['CMA_BACKLINK'] = $backlink;
    }

    private static function restoreBacklink()
    {
        $backlink = isset($_SESSION['CMA_BACKLINK']) ? $_SESSION['CMA_BACKLINK'] : get_post_type_archive_link(CMA_AnswerThread::POST_TYPE);
        return $backlink;
    }

    public static function flushBacklink()
    {
        if(get_query_var('post_type') == CMA_AnswerThread::POST_TYPE || is_tax(CMA_AnswerThread::CAT_TAXONOMY) && isset($_SESSION['CMA_BACKLINK']))
        {
            unset($_SESSION['CMA_BACKLINK']);
        }
    }

}