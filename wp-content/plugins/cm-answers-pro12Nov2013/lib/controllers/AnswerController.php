<?php
include_once CMA_PATH . '/lib/models/AnswerThread.php';
class CMA_AnswerController extends CMA_BaseController
{
    const OPTION_ADD_ANSWERS_MENU = 'cma_add_to_nav_menu';

    public static function initialize()
    {
        add_filter('posts_search', array(get_class(), 'alterSearchQuery'), 99, 2);
        add_filter('template_include', array(get_class(), 'overrideTemplate'));
        add_filter('manage_edit-' . CMA_AnswerThread::POST_TYPE . '_columns', array(get_class(), 'registerAdminColumns'));
        add_filter('manage_' . CMA_AnswerThread::POST_TYPE . '_posts_custom_column', array(get_class(), 'adminColumnDisplay'), 10, 2);
        do_action('CMA_custom_post_type_nav', CMA_AnswerThread::POST_TYPE);
        add_filter('CMA_admin_settings', array(get_class(), 'addAdminSettings'));
        add_action('parse_query', array(get_class(), 'processStatusChange'));
        add_filter('wp_nav_menu_items', array(get_class(), 'addMenuItem'), 1, 1);
        /* add_action('pre_get_posts', array(get_class(), 'registerStickyPosts'), 1, 1); */
        add_action('pre_get_posts', array(get_class(), 'registerCustomOrder'), 9999, 1);
        add_action('pre_get_posts', array(get_class(), 'registerTagFilter'), 9999, 1);
        add_action('pre_get_posts', array(get_class(), 'registerPageCount'), 9999, 1);
        add_action('pre_get_posts', array(get_class(), 'registerAsHomepage'), 99, 1);
        add_action('pre_get_posts', array(get_class(), 'checkIfDisabled'), 98, 1);
        add_filter('posts_where', array(get_class(), 'registerCommentsFiltering'), 1, 1);
        add_filter('', array(get_class(), 'registerCommentsFiltering'), 1, 1);

        add_action('wp_set_comment_status', array(get_class(), 'processAnwserStatusChange'), 999, 2);

        if(is_admin())
        {
            add_filter('page_row_actions', array(get_class(), 'pageRowActions'), 1, 2);
        }

        add_action('CMA_login_form', array(get_class(), 'showLoginForm'));

        register_sidebar(array(
            'id' => 'cm-answers-sidebar',
            'name' => 'CM Answers Sidebar',
            'description' => 'This sidebar is shown on CM Answers pages'
        ));

        // Function runs only once on script install or update
        self::postsStickyReset();
    }

    public static function pageRowActions($actions, $post)
    {

        if($post->post_type === CMA_AnswerThread::POST_TYPE)
        {
            $cmaPost = CMA_AnswerThread::getInstance($post->ID);
            if($cmaPost->isResolved())
            {
                $actions['unresolved'] = '<a class="" href="' . add_query_arg(array('cma-action' => 'unresolve', 'cma-id' => $post->ID)) . '" title="' . __('Mark as unresolved', 'cm-answers-pro') . '">' . __('Mark as unresolved', 'cm-answers-pro') . '</a>';
            }
            else
            {
                $actions['resolved'] = '<a class="" href="' . add_query_arg(array('cma-action' => 'resolve', 'cma-id' => $post->ID)) . '" title="' . __('Mark as resolved', 'cm-answers-pro') . '">' . __('Mark as resolved', 'cm-answers-pro') . '</a>';
            }
        }

        return $actions;
    }

    public static function postsStickyReset()
    {
        global $wpdb;
        if(get_option('cma_sticky_posts_updated', 0) == 0)
        {
            $wpdb->query("insert into {$wpdb->postmeta} (post_id, meta_key, meta_value)
                          select ID, '_sticky_post', '0' from $wpdb->posts");
            add_option('cma_sticky_posts_updated', 1);
        }
    }

    public static function alterSearchQuery($search, $query)
    {
        if($query->query_vars['post_type'] == CMA_AnswerThread::POST_TYPE && !$query->is_single && !$query->is_404 && !$query->is_author && isset($query->query['search']))
        {
            global $wpdb;
            $search_term = $query->query['search'];
            if(!empty($search_term))
            {
                $search           = '';
                $query->is_search = true;
                // added slashes screw with quote grouping when done early, so done later
                $search_term      = stripslashes($search_term);
                preg_match_all('/".*?("|$)|((?<=[\r\n\t ",+])|^)[^\r\n\t ",+]+/', $search_term, $matches);
                $terms            = array_map('_search_terms_tidy', $matches[0]);

                $n         = '%';
                $searchand = ' AND ';
                foreach((array) $terms as $term)
                {
                    $term = esc_sql(like_escape($term));
                    $search .= "{$searchand}(($wpdb->posts.post_title LIKE '{$n}{$term}{$n}') OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}'))";
                }
                add_filter('get_search_query', create_function('$q', 'return "' . $search_term . '";'), 99, 1);
                remove_filter('posts_request', 'relevanssi_prevent_default_request');
                remove_filter('the_posts', 'relevanssi_query');
            }
        }
        return $search;
    }

    public static function addMenuItem($items)
    {
        if(self::addAnswersMenu())
        {
            $link = self::_loadView('answer/meta/menu-item', array('listUrl' => self::addAnswersMenu() ? get_post_type_archive_link(CMA_AnswerThread::POST_TYPE) : null));
            return $items . $link;
        }
        return $items;
    }

    public static function showLoginForm()
    {
        echo self::_loadView('answer/widget/login');
    }

    public static function processStatusChange()
    {
        if(is_admin() && get_query_var('post_type') == CMA_AnswerThread::POST_TYPE && isset($_REQUEST['cma-action']))
        {
            switch($_REQUEST['cma-action'])
            {
                case 'approve':
                    $id = $_REQUEST['cma-id'];
                    if(is_numeric($id))
                    {
                        $thread = CMA_AnswerThread::getInstance($id);
                        $thread->approve();
                        add_action('admin_notices', create_function('$q', 'echo "<div class=\"updated\"><p>Question: \"' . $thread->getTitle() . '\" has been succesfully approved</p></div>";'));
                    }
                    break;
                case 'trash':
                    $id = $_REQUEST['cma-id'];
                    if(is_numeric($id))
                    {
                        $thread = CMA_AnswerThread::getInstance($id);
                        $thread->trash();
                        add_action('admin_notices', create_function('$q', 'echo "<div class=\"updated\"><p>Question: \"' . $thread->getTitle() . '\" has been succesfully moved to trash</p></div>";'));
                    }
                    break;
                case 'resolve':
                    $id = $_REQUEST['cma-id'];
                    if(is_numeric($id))
                    {
                        $thread = CMA_AnswerThread::getInstance($id);
                        $thread->setResolved(true);
                        add_action('admin_notices', create_function('$q', 'echo "<div class=\"updated\"><p>Question: \"' . $thread->getPost()->post_title . '\" has been succesfully marked as resolved</p></div>";'));
                    }
                    break;
                case 'unresolve':
                    $id = $_REQUEST['cma-id'];
                    if(is_numeric($id))
                    {
                        $thread = CMA_AnswerThread::getInstance($id);
                        $thread->setResolved(false);
                        add_action('admin_notices', create_function('$q', 'echo "<div class=\"updated\"><p>Question: \"' . $thread->getPost()->post_title . '\" has been succesfully marked as unresolved</p></div>";'));
                    }
                    break;
            }
        }
    }

    public static function processAnwserStatusChange($commentId, $status)
    {
        /*
         * Get the comment, author, thread
         */
        $comment = get_comment($commentId);

        /*
         * Comment not found - abort
         */
        if(!$comment)
        {
            return false;
        }

        $thread = CMA_AnswerThread::getInstance($comment->comment_post_ID);
        $thread->updateThreadMetadata(array());
    }

    public static function registerCustomOrder($query)
    {
        if($query->query_vars['post_type'] == CMA_AnswerThread::POST_TYPE && $query->query_vars['widget'] !== true && !$query->is_single && !$query->is_404 && !$query->is_author && isset($_GET['sort']))
        {
            if(!$query->get('widget') && !$query->get('user_questions'))
            {
                $query         = CMA_AnswerThread::customOrder($query, $_GET['sort']);
                $query->is_top = true;
            }
        }
    }

    public static function registerTagFilter($query)
    {
        if($query->query_vars['post_type'] == CMA_AnswerThread::POST_TYPE && $query->query_vars['widget'] !== true && !$query->is_single && !$query->is_404 && !$query->is_author && isset($_GET['cmatag']))
        {
            if(!$query->get('widget') && !$query->get('user_questions'))
            {
                $query = CMA_AnswerThread::tagFilter($query, $_GET['cmatag']);
            }
        }
    }

    public static function registerPageCount($query)
    {
        if($query->query_vars['post_type'] == CMA_AnswerThread::POST_TYPE && $query->query_vars['widget'] !== true && !$query->is_single && !$query->is_404 && !$query->is_author)
        {
            if(!$query->get('widget') && !isset($_GET["pagination"]) && !$query->get('user_questions'))
            {
                $query->set('posts_per_page', CMA_AnswerThread::getItemsPerPage());
            }
        }
    }

    public static function registerAsHomepage($query)
    {
        if(is_main_query() && is_home() && CMA_AnswerThread::answersAsHomepage() && empty($query->query_vars['post_type']) && !$query->is_page)
        {
            $query->set('post_type', CMA_AnswerThread::POST_TYPE);
            $query->is_archive           = true;
            $query->is_page              = false;
            $query->is_post_type_archive = true;
            $query->is_home              = true;
        }
    }

    public static function checkIfDisabled($query)
    {
        if(is_main_query() && CMA_AnswerThread::isAnswerPageDisabled() && !is_single() && $query->query_vars['post_type'] == CMA_AnswerThread::POST_TYPE)
        {
            $query->is_404 = true;
        }
    }

    /* public static function registerStickyPosts($query) {
      if ($query->query_vars['post_type'] == CMA_AnswerThread::POST_TYPE && $query->query_vars['widget'] !== true && !$query->is_single && !$query->is_404 && !$query->is_author) {
      $query->set('meta_query',) ;
      $query->set('meta_query' ,array(
      array(
      'key' => '_sticky_post',
      'value' => 1,
      )
      )
      );
      }
      } */

    public static function registerCommentsFiltering($sql)
    {
        if(isset($_GET['question_type']))
        {
            global $wpdb;

            if($_GET['question_type'] == 'all') return $sql;

            $expr = '=0 ';
            if($_GET['question_type'] == 'ans') $expr = ' >0 ';

            $sql .= " AND {$wpdb->posts}.comment_count " . $expr;
        }
        return $sql;
    }

    public static function overrideTemplate($template)
    {
        if(get_query_var('post_type') == CMA_AnswerThread::POST_TYPE || is_tax(CMA_AnswerThread::CAT_TAXONOMY))
        {
            wp_enqueue_style('CMA-css', CMA_URL . '/views/resources/app.css');

            if(is_404() || CMA_AnswerThread::getUserLoggedOnly() && !is_user_logged_in())
            {
                if(CMA_AnswerThread::isAnswerPageDisabled())
                {
                    $template = self::locateTemplate(array(
                                '404'
                                    ), $template);
                }
                else
                {
                    $template = self::locateTemplate(array(
                                'permissions'
                                    ), $template);
                }
                return $template;
            }

            if(self::_isPost()) self::processQueryVars();

            $cmaVariables = array('messages' => array(
                    'thanks_for_voting' => __('Thank you for voting!', 'cm-answers'))
            );
            wp_enqueue_script('cma-script', CMA_RESOURCE_URL . 'script.js', array('jquery'), false, true);
            wp_localize_script('cma-script', 'CMA_Variables', $cmaVariables);

            if(is_single())
            {
                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-toast', CMA_URL . '/views/resources/toast/js/jquery.toastmessage.js', array('jquery'), false, true);
                wp_enqueue_style('jquery-toast-css', CMA_URL . '/views/resources/toast/resources/css/jquery.toastmessage.css', array(), false);

                if(CMA_AnswerThread::showSocial())
                {
                    wp_enqueue_script('cma-twitter', 'https://platform.twitter.com/widgets.js', array(), false, true);
                    wp_enqueue_script('cma-linkedin', 'https://platform.linkedin.com/in.js', array(), false, true);
                }

                $template = self::locateTemplate(array('answer/single'), $template);

                if(!self::_isPost())
                {
                    self::_processViews();
                }
            }
            else
            {
                CMA::flushBacklink();
                $template = self::locateTemplate(array('answer/index'), $template);
            }
            add_filter('body_class', array(get_class(), 'adjustBodyClass'), 20, 2);
        }
        return $template;
    }

    protected static function _processViews()
    {
        global $wp_query;
        $post   = $wp_query->post;
        $thread = CMA_AnswerThread::getInstance($post->ID);
        $thread->addView();
    }

    protected static function _processAddCommentToThread()
    {
        global $wp_query;
        $post      = $wp_query->post;
        $thread    = CMA_AnswerThread::getInstance($post->ID);
        $content   = $_POST['content'];
        $notify    = (bool) $_POST['thread_notify'];
        $resolved  = (bool) $_POST['thread_resolved'];
        $author_id = get_current_user_id();
        $error     = false;
        $messages  = array();
        try
        {
            $comment_id = $thread->addCommentToThread($content, $author_id, $notify, $resolved);
        }
        catch(Exception $e)
        {
            $messages = unserialize($e->getMessage());
            $error    = true;
        }
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        {

            header('Content-type: application/json');
            echo json_encode(array('success' => (int) (!$error), 'comment_id' => $comment_id,
                'commentData' => CMA_AnswerThread::getCommentData($comment_id), 'message' => $messages));
            exit;
        }
        else
        {
            if($error)
            {
                foreach((array) $messages as $message)
                {
                    self::_addMessage(self::MESSAGE_ERROR, $message);
                }
            }
            else
            {
                $autoApprove = CMA_AnswerThread::isAnswerAutoApproved() || CMA_AnswerThread::isAuthorAutoApproved(get_current_user_id());

                if($autoApprove)
                {
                    $msg = __('Your answer has been succesfully added.', 'cm-answers-pro');
                    self::_addMessage(self::MESSAGE_SUCCESS, $msg);
                    if(!empty($_POST['cma-referrer'])) wp_redirect($_POST['cma-referrer'], 303);
                    else wp_redirect(get_permalink($post->ID) . '/#comment-' . $comment_id, 303);
                }
                else
                {
                    $msg = __('Thank you for your answer, it has been held for moderation.', 'cm-answers-pro');
                    self::_addMessage(self::MESSAGE_SUCCESS, $msg);
                    if(!empty($_POST['cma-referrer'])) wp_redirect($_POST['cma-referrer'], 303);
                    else wp_redirect(get_permalink($post->ID), 303);
                }
                exit;
            }
        }
    }

    protected static function _processAddThread()
    {
        global $wp_query;
        $post    = $wp_query->post;
        $title   = $_POST['thread_title'];
        $content = $_POST['thread_comment'];
        $tags    = $_POST['thread_tags'];
        $notify  = (bool) $_POST['thread_notify'];
        $cat     = $_POST['thread_category'];

        $author_id = get_current_user_id();
        $error     = false;
        $messages  = array();
        $data      = array(
            'title' => $title,
            'content' => $content,
            'notify' => $notify,
            'author_id' => $author_id,
            'category' => $cat,
            'tags' => $tags
        );
        try
        {
            $thread    = CMA_AnswerThread::newThread($data);
            $thread_id = $thread->getId();
        }
        catch(Exception $e)
        {
            $messages = unserialize($e->getMessage());
            $error    = true;
        }
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        {
            header('Content-type: application/json');
            echo json_encode(array('success' => (int) (!$error), 'thread_id' => $thread_id,
                'message' => $messages));
            exit;
        }
        else
        {
            if($error)
            {
                foreach((array) $messages as $message)
                {
                    self::_addMessage(self::MESSAGE_ERROR, $message);
                }
                self::_populate($_POST);
                if(!empty($_POST['cma-referrer'])) wp_redirect($_POST['cma-referrer'], 303);
                else wp_redirect(get_post_type_archive_link(CMA_AnswerThread::POST_TYPE), 303);
            }
            else
            {
                $heldForModeration = $thread->wasHeldForModeration();
                if(!$heldForModeration)
                {
                    $msg = __('New question has been succesfully added.', 'cm-answers-pro');
                    self::_addMessage(self::MESSAGE_SUCCESS, $msg);
                }
                else
                {
                    $msg = __('Thank you for your question, it has been held for moderation.', 'cm-answers-pro');
                    self::_addMessage(self::MESSAGE_SUCCESS, $msg);
                }
                if(!empty($_POST['cma-referrer'])) wp_redirect($_POST['cma-referrer'], 303);
                else wp_redirect(get_post_type_archive_link(CMA_AnswerThread::POST_TYPE), 303);
            }

            exit;
        }
    }

    protected static function _processVote()
    {
        if(is_single())
        {
            global $wp_query;
            $post = $wp_query->post;
            if(!empty($post))
            {
                $thread  = CMA_AnswerThread::getInstance($post->ID);
                $comment = self::_getParam('cma-comment');
                if(!empty($comment))
                {
                    $response = array('success' => 0, 'message' => __('There was an error while processing your vote', 'cm-answers-pro'));
                    $votes    = 0;
                    if(!is_user_logged_in())
                    {
                        $response['success'] = 0;
                        $response['message'] = __('You have to be logged-in to vote', 'cm-answers-pro');
                    }
                    else
                    if($thread->isVotingAllowed($comment, get_current_user_id()))
                    {
                        $response['success'] = 1;
                        if(self::_getParam('cma-value') == 'up')
                        {
                            $response['message'] = $thread->voteUp($comment);
                        }
                        else $response['message'] = $thread->voteDown($comment);
                    } else
                    {
                        $response['message'] = __('You have already voted for this comment', 'cm-answers-pro');
                    }
                    header('Content-type: application/json');
                    echo json_encode($response);
                    exit;
                }
            }
        }
    }

    public static function processQueryVars()
    {
        $action = self::_getParam('cma-action');
        if(!empty($action))
        {
            switch($action)
            {
                case 'add':
                    if(is_single()) self::_processAddCommentToThread();
                    else self::_processAddThread();
                    break;
                case 'vote':
                    self::_processVote();
                    break;
            }
        }
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

    public static function registerAdminColumns($columns)
    {
        $columns['author']   = 'Author';
        $columns['views']    = 'Views';
        $columns['status']   = 'Status';
        $columns['comments'] = 'Answers';
        return $columns;
    }

    public static function adminColumnDisplay($columnName, $id)
    {
        $thread = CMA_AnswerThread::getInstance($id);
        if(!$thread) return;
        switch($columnName)
        {
            case 'author':
                echo $thread->getAuthor()->display_name;
                break;
            case 'views':
                echo $thread->getViews();
                break;
            case 'status':
                echo $thread->getStatus();
                if(strtolower($thread->getStatus()) == strtolower(__('pending', 'cm-answers-pro')))
                {
                    ?>
                    <a href="<?php
                    echo add_query_arg(array('cma-action' => 'approve',
                        'cma-id' => $id));
                    ?>">(Approve)</a>
                    <?php
                }
                break;
        }
    }

    public static function addAdminSettings($params = array())
    {
        if(self::_isPost())
        {
            CMA_AnswerThread::setQuestionAutoApproved(isset($_POST['questions_auto_approve']) && $_POST['questions_auto_approve'] == 1);
            CMA_AnswerThread::setAnswerAutoApproved(isset($_POST['answers_auto_approve']) && $_POST['answers_auto_approve'] == 1);
            CMA_AnswerThread::setAuthorsAutoApproved(isset($_POST['authorsAutoApproved']) ? $_POST['authorsAutoApproved'] : array(
            ));
            CMA_AnswerThread::setDisclaimerApproved(isset($_POST['disclaimer_approve']) && $_POST['disclaimer_approve'] == 1);
            CMA_AnswerThread::setDisclaimerContent(stripslashes($_POST['disclaimer_content']));
            CMA_AnswerThread::setDisclaimerContentAccept(stripslashes($_POST['disclaimer_content_accept']));
            CMA_AnswerThread::setDisclaimerContentReject(stripslashes($_POST['disclaimer_content_reject']));
            CMA_AnswerThread::setRatingAllowed(isset($_POST['ratings']) && $_POST['ratings'] == 1);
            CMA_AnswerThread::setViewsAllowed(isset($_POST['views']) && $_POST['views'] == 1);
            CMA_AnswerThread::setViewsIncremented(isset($_POST['viewsIncremented']) && $_POST['viewsIncremented'] == 1);
            CMA_AnswerThread::setAnswersAllowed(isset($_POST['answers']) && $_POST['answers'] == 1);
            CMA_AnswerThread::setAuthorAllowed(isset($_POST['author']) && $_POST['author'] == 1);
            CMA_AnswerThread::setUpdatedAllowed(isset($_POST['updated']) && $_POST['updated'] == 1);
            CMA_AnswerThread::setAnswerPageDisabled(isset($_POST['answerPageDisabled']) && $_POST['answerPageDisabled'] == 1);
            CMA_AnswerThread::setAnswerAfterResolved(isset($_POST['answer_after_resolved']) && $_POST['answer_after_resolved'] == 1);
            CMA_AnswerThread::setQuestionDescriptionOptional(isset($_POST['question_description_optional']) && $_POST['question_description_optional'] == 1);

            CMA_AnswerThread::setShowGravatars(isset($_POST['showGravatars']) && $_POST['showGravatars'] == 1);
            CMA_AnswerThread::setShowSocial(isset($_POST['showSocial']) && $_POST['showSocial'] == 1);
            CMA_AnswerThread::setAnswerSortingBy(isset($_POST['answerSortingBy']) ? $_POST['answerSortingBy'] : null);
            CMA_AnswerThread::setAnswerSortingDesc(isset($_POST['answerSortingDesc']) && $_POST['answerSortingDesc'] == 1);
            CMA_AnswerThread::setNegativeRatingAllowed(isset($_POST['negative_ratings']) && $_POST['negative_ratings'] == 1);
            $attachments = explode(',', $_POST['attachmentAllowed']);
            foreach($attachments as $key => $val)
            {
                $attachments[$key] = trim($val);
            }
            CMA_AnswerThread::setAttachmentAllowed($attachments);
            CMA_AnswerThread::setAttachmentMaxSize((int) $_POST['attachmentMaxSize']);
            CMA_AnswerThread::setNewQuestionNotification(stripslashes($_POST['notification_new_questions']));
            CMA_AnswerThread::setNewQuestionNotificationTitle(stripslashes($_POST['new_question_notification_title']));
            CMA_AnswerThread::setNewQuestionNotificationContent(stripslashes($_POST['new_question_notification_content']));
            CMA_AnswerThread::setNotificationTitle(stripslashes($_POST['notification_title']));
            CMA_AnswerThread::setNotificationContent(stripslashes($_POST['notification_content']));
            CMA_AnswerThread::setVotesMode((int) $_POST['votes_mode']);
            CMA_AnswerThread::setShowUserStats(isset($_POST['show_user_stats']) && $_POST['show_user_stats'] == 1);
            CMA_AnswerThread::setSidebarEnabled(isset($_POST['sidebar_enable']) && $_POST['sidebar_enable'] == 1);
            CMA_AnswerThread::setSidebarMaxWidth((int) $_POST['sidebar_max_width']);

            CMA_AnswerThread::setCodeSnippetColor(stripslashes($_POST['code_snippet_color']));
            CMA_AnswerThread::setAnswersPermalink(stripslashes($_POST['answers_permalink']));
            CMA_AnswerThread::setVotesNo(isset($_POST['votesNo']) && $_POST['votesNo'] == 1);
            CMA_AnswerThread::setMarkupBox(isset($_POST['markupBox']) && $_POST['markupBox'] == 1);
            CMA_AnswerThread::setRichtextEditor(isset($_POST['richtextEditor']) && $_POST['richtextEditor'] == 1);
            CMA_AnswerThread::setItemsPerPage(stripslashes($_POST['itemsPerPage']));

            CMA_AnswerThread::setTagsSwitch(isset($_POST['tagsSwitch']) && $_POST['tagsSwitch'] == 1);
            CMA_AnswerThread::setUserCommentOnly(isset($_POST['userCommentOnly']) && $_POST['userCommentOnly'] == 1);
            CMA_AnswerThread::setUserLoggedOnly(isset($_POST['userLoggedOnly']) && $_POST['userLoggedOnly'] == 1);
            CMA_AnswerThread::setStickyQuestionColor(stripslashes($_POST['stickyQuestionColor']));
            CMA_AnswerThread::setAnswersAsHomepage(isset($_POST['answersAsHomepage']) && $_POST['answersAsHomepage'] == 1);
            if(!empty($_POST['questions_title']))
            {
                update_option(CMA_AnswerThread::OPTION_QUESTIONS_TITLE, stripslashes($_POST['questions_title']));
            }

            self::setAnswersMenu(isset($_POST['add_menu']) && $_POST['add_menu'] == 1);
            CMA_AnswerThread::setSpamFilter(isset($_POST['spamFilter']) && $_POST['spamFilter'] == 1);
            CMA_AnswerThread::setSimulateComment(isset($_POST['simulateComment']) && $_POST['simulateComment'] == 1);

            CMA_AnswerThread::setReferralEnabled(isset($_POST['referral_enable']) && $_POST['referral_enable'] == 1);
            if(!empty($_POST['affiliate_code']))
            {
                CMA_AnswerThread::setAffiliateCode(stripslashes($_POST['affiliate_code']));
            }

            if(!empty($_POST['custom_css']))
            {
                CMA_AnswerThread::setCustomCss(stripslashes($_POST['custom_css']));
            }
        }

        $params['questionDescriptionOptional']    = CMA_AnswerThread::isQuestionDescriptionOptional();
        $params['answerAfterResolved']            = CMA_AnswerThread::isAnswerAfterResolved();
        $params['ratings']                        = CMA_AnswerThread::isRatingAllowed();
        $params['views']                          = CMA_AnswerThread::isViewsAllowed();
        $params['viewsIncremented']               = CMA_AnswerThread::isViewsIncremented();
        $params['answers']                        = CMA_AnswerThread::isAnswersAllowed();
        $params['author']                         = CMA_AnswerThread::isAuthorAllowed();
        $params['updated']                        = CMA_AnswerThread::isUpdatedAllowed();
        $params['answerPageDisabled']             = CMA_AnswerThread::isAnswerPageDisabled();
        $params['negativeRatings']                = CMA_AnswerThread::isNegativeRatingAllowed();
        $params['questionAutoApproved']           = CMA_AnswerThread::isQuestionAutoApproved();
        $params['DisclaimerContent']              = CMA_AnswerThread::getDisclaimerContent();
        $params['DisclaimerContentAccept']        = CMA_AnswerThread::getDisclaimerContentAccept();
        $params['DisclaimerContentReject']        = CMA_AnswerThread::getDisclaimerContentReject();
        $params['DisclaimerApproved']             = CMA_AnswerThread::isDisclaimerApproved();
        $params['answerAutoApproved']             = CMA_AnswerThread::isAnswerAutoApproved();
        $params['authorsAutoApproved']            = CMA_AnswerThread::getAuthorsAutoApproved();
        $params['notificationNewQuestions']       = CMA_AnswerThread::getNewQuestionNotification();
        $params['newQuestionNotificationTitle']   = CMA_AnswerThread::getNewQuestionNotificationTitle();
        $params['newQuestionNotificationContent'] = CMA_AnswerThread::getNewQuestionNotificationContent();
        $params['notificationTitle']              = CMA_AnswerThread::getNotificationTitle();
        $params['notificationContent']            = CMA_AnswerThread::getNotificationContent();
        $params['votesMode']                      = CMA_AnswerThread::getVotesMode();
        $params['addMenu']                        = self::addAnswersMenu();
        $params['showUserStats']                  = CMA_AnswerThread::getShowUserStats();
        $params['showGravatars']                  = CMA_AnswerThread::showGravatars();
        $params['showSocial']                     = CMA_AnswerThread::showSocial();
        $params['answerSortingBy']                = CMA_AnswerThread::getAnswerSortingBy();
        $params['answerSortingDesc']              = CMA_AnswerThread::isAnswerSortingDesc();
        $params['attachmentAllowed']              = implode(',', CMA_AnswerThread::getAttachmentAllowed());
        $params['attachmentMaxSize']              = CMA_AnswerThread::getAttachmentMaxSize();
        $params['sidebarEnable']                  = CMA_AnswerThread::isSidebarEnabled();
        $params['sidebarMaxWidth']                = CMA_AnswerThread::getSidebarMaxWidth();
        $params['votesNo']                        = CMA_AnswerThread::getVotesNo();
        $params['markupBox']                      = CMA_AnswerThread::getMarkupBox();
        $params['richtextEditor']                 = CMA_AnswerThread::getRichtextEditor();
        $params['itemsPerPage']                   = CMA_AnswerThread::getItemsPerPage();
        $params['tagsSwitch']                     = CMA_AnswerThread::getTagsSwitch();
        $params['userCommentOnly']                = CMA_AnswerThread::getUserCommentOnly();
        $params['userLoggedOnly']                 = CMA_AnswerThread::getUserLoggedOnly();
        $params['stickyQuestionColor']            = CMA_AnswerThread::getStickyQuestionColor();

        $params['referralEnable'] = CMA_AnswerThread::isReferralEnabled();
        $params['affiliateCode']  = CMA_AnswerThread::getAffiliateCode();

        $params['customCSS'] = CMA_AnswerThread::getCustomCss();

        $params['codeSnippetColor']  = CMA_AnswerThread::getCodeSnippetColor();
        $params['answersPermalink']  = CMA_AnswerThread::getAnswersPermalink();
        $params['questions_title']   = CMA_AnswerThread::getQuestionsTitle();
        $params['answersAsHomepage'] = CMA_AnswerThread::answersAsHomepage();
        $params['spamFilter']        = CMA_AnswerThread::getSpamFilter();
        $params['simulateComment']   = CMA_AnswerThread::simulateComment();
        return $params;
    }

    public static function setAnswersMenu($value = false)
    {
        update_option(self::OPTION_ADD_ANSWERS_MENU, $value);
    }

    public static function addAnswersMenu()
    {
        return (bool) get_option(self::OPTION_ADD_ANSWERS_MENU);
    }

    public static function showPagination($arguments = array())
    {
        global $wp_query;

        $params = array(
            'maxNumPages' => isset($arguments['maxNumPages']) ? $arguments['maxNumPages'] : $wp_query->max_num_pages,
            'paged' => isset($arguments['paged']) ? $arguments['paged'] : $wp_query->query_vars['paged'],
            'add_args' => $arguments,
        );

        if(!empty($arguments['ajax']))
        {
            $params['ajax'] = 1;
        }

        $pagination = CMA_BaseController::_loadView('answer/widget/pagination', $params);
        return $pagination;
    }

}