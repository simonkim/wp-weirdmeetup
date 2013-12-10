<?php
/**
 * File contains User controller
 * @package Library
 * @subpackage Controllers
 */
/**
 * User controller
 *
 * @author SP
 * @version 1.0
 * @copyright Copyright (c) 2013, REC
 * @package Library
 * @subpackage Controllers
 */
class CMA_ContributorController extends CMA_BaseController
{

    public static function initialize()
    {
        add_filter('template_include', array(get_class(), 'overrideTemplate'));
        add_filter('body_class', array(get_class(), 'adjustBodyClass'), 20, 2);
        add_filter('query_vars', array(get_class(), 'addQueryVars'));
    }

    public static function addQueryVars($vars)
    {
        $vars[] = "contributor";
        return $vars;
    }

    public static function changeTheAuthor($author)
    {
        $contributorId = self::getContributorAndId();
        $user          = $contributorId['user'];
        return $user->display_name;
    }

    public static function changeTheAuthorLink($author)
    {
        $contributorId = self::getContributorAndId();
        $user          = $contributorId['user'];
        $url           = CMA_BaseController::getUrl('contributor', $user->user_nicename);
        return $url;
    }

    public static function overrideTemplate($template)
    {
        global $wp_query;

        if(is_404())
        {
            $template = self::locateTemplate(array(
                        '404'
                            ), $template);
        }
        else
        {
            $template = self::locateTemplate(array(
                        'page'
                            ), $template);
        }
        return $template;
    }

    public static function adjustBodyClass($wp_classes, $extra_classes)
    {
        foreach($wp_classes as $key => $value)
        {
            if($value == 'singular') unset($wp_classes[$key]);
        }
        if(!CMA_AnswerThread::isSidebarEnabled() || !is_active_sidebar('cm-answers-sidebar')) $extra_classes[] = 'full-width';
        return array_merge($wp_classes, (array) $extra_classes);
    }

    public static function indexAction()
    {
        $userId = self::getContributorAndId();
        $user   = $userId['user'];
        $id     = $userId['id'];

        $socialLinks = array();
        $providers   = array('google', 'linkedin', 'facebook');
        foreach($providers as $provider)
        {
            $link                   = get_user_meta($id, '_cma_social_' . $provider . '_url', true);
            if(!empty($link)) $socialLinks[$provider] = $link;
        }
        return array('name' => $user->display_name,
            'user_id' => $id,
            'link' => get_user_meta($id, '_cma_social_url', true),
            'socialLinks' => $socialLinks,
            'questions' => CMA_AnswerThread::getQuestionsByUser($id),
            'answers' => CMA_AnswerThread::getAnswersByUser($id));
    }

    public static function indexTitle()
    {
        global $wp_query;
        $userId = self::getContributorAndId();
        $user   = $userId['user'];

        if(!$user || !$user->ID)
        {
            $wp_query->set_404();
            return false;
        }
        else
        {
            add_filter('the_author', array(get_class(), 'changeTheAuthor'), 9999);
            add_filter('author_link', array(get_class(), 'changeTheAuthorLink'), 9999);

            return $user->display_name . ' - ' . __('Contributor Profile', 'cm-answers-pro');
        }
    }

    /**
     * Get the contributor and id
     * @return array('user'=>WP_User, 'id'=>int)
     */
    public static function getContributorAndId()
    {
        $id = get_query_var('contributor');
        if($id)
        {
            if(is_numeric($id))
            {
                $user = get_userdata($id);
            }
            else
            {
                $user = get_user_by('slug', $id);
                $id   = $user->ID;
            }
        }
        else
        {
            $user = wp_get_current_user();
            if($user)
            {
                $id = $user->ID;
            }
        }
        return array('user' => $user, 'id' => $id);
    }

}