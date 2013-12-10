<?php
include_once CMA_PATH . '/lib/models/PostType.php';
require_once CMA_PATH . '/lib/helpers/Widgets/TagsWidget.php';
require_once CMA_PATH . '/lib/helpers/Widgets/TopContributorsWidget.php';
require_once CMA_PATH . '/lib/helpers/StickyQuestion.php';
class CMA_AnswerThread extends CMA_PostType
{
    /**
     * Post type name
     */
    const POST_TYPE                                 = 'cma_thread';
    const CAT_TAXONOMY                              = 'cma_category';
    const UPLOAD_PATH                               = 'cma_attachments';
    const ADMIN_MENU                                = 'CMA_answers_menu';
    const OPTION_ANSWERS_AS_HOMEPAGE                = 'cma_answers_as_homepage';
    const OPTION_QUESTIONS_TITLE                    = 'cma_questions_title';
    const OPTION_DISCLAIMER_APPROVE                 = 'cma_disclaimer_approve';
    const OPTION_QUESTION_AUTO_APPROVE              = 'cma_question_auto_approve';
    const OPTION_ANSWER_AUTO_APPROVE                = 'cma_answer_auto_approve';
    const OPTION_ANSWER_SORTING_BY                  = 'cma_answer_sorting_by';
    const ANSWER_SORTING_BY_VOTES                   = 'votes';
    const ANSWER_SORTING_BY_DATE                    = 'newest';
    const OPTION_ANSWER_SORTING_DESC                = 'cma_answer_sorting_desc';
    const OPTION_AUTO_APPROVE_AUTHORS               = 'cma_answer_authors_auto_approved';
    const OPTION_RATING_ALLOWED                     = 'cma_rating_allowed';
    const OPTION_GRAVATARS_SHOW                     = 'cma_show_gravatars';
    const OPTION_SOCIAL_SHOW                        = 'cma_show_social';
    const OPTION_VIEWS_ALLOWED                      = 'cma_views_allowed';
    const OPTION_ANSWERS_ALLOWED                    = 'cma_answers_allowed';
    const OPTION_AUTHOR_ALLOWED                     = 'cma_author_allowed';
    const OPTION_UPDATED_ALLOWED                    = 'cma_updated_allowed';
    const OPTION_NEGATIVE_RATING_ALLOWED            = 'cma_negative_rating_allowed';
    const OPTION_ATTACHMENT_ALLOWED                 = 'cma_attachment_allowed';
    const OPTION_SHOW_USER_STATS                    = 'cma_show_user_stats';
    const OPTION_INCREMENT_VIEWS                    = 'cma_increment_views';
    const OPTION_VOTES_MODE                         = 'cma_votes_mode';
    const OPTION_ITEMS_SPER_PAGE                    = 'cma_items_per_page';
    const OPTION_STICKY_QUESTION_COLOR              = 'cma_sticky_color';
    const OPTION_USER_COMMENT_ONLY                  = 'cma_user_comments_olny';
    const OPTION_USER_LOGGED_ONLY                   = 'cma_user_logged_olny';
    const OPTION_TAGS_WIDGET_LIMIT                  = 'cma_tags_widget_limit';
    const OPTION_TAGS_SWITCH                        = 'cma_tags_switch';
    const OPTION_ATTACHMENT_MAX_SIZE                = 'cma_attachment_max_size';
    const OPTION_SIDEBAR_ENABLED                    = 'cma_sidebar_enabled';
    const OPTION_SIDEBAR_MAX_WIDTH                  = 'cma_sidebar_max_width';
    const OPTION_VOTES_NO                           = 'cma_votes_no';
    const OPTION_MARKUP_BOX                         = 'cma_markup_box';
    const OPTION_RICHTEXT_EDITOR                    = 'cma_richtext_editor';
    const OPTION_AFFILIATE_CODE                     = 'cma_affiliate_code';
    const OPTION_REFERRAL_ENABLED                   = 'cma_referral_enabled';
    const OPTION_ANSWER_PAGE_DISABLED               = 'cma_answer_page_disabled';
    const OPTION_ANSWER_AFTER_RESOLVED              = 'cma_answer_after_resolved';
    const OPTION_QUESTION_DESCRIPTION_OPTIONAL      = 'cma_question_description_optional';
    const OPTION_CUSTOM_CSS                         = 'cma_custom_css';
    const VOTES_MODE_COUNT                          = 1;
    const VOTES_MODE_HIGHEST                        = 2;
    const OPTION_DISCLAIMER_CONTENT                 = 'cma_disclaimer_content';
    const DEFAULT_DISCLAIMER_CONTENT                = 'Place here your disclaimer text';
    const OPTION_DISCLAIMER_CONTENT_ACCEPT          = 'cma_disclaimer_content_accept';
    const DEFAULT_DISCLAIMER_CONTENT_ACCEPT         = 'Accept Terms';
    const OPTION_DISCLAIMER_CONTENT_REJECT          = 'cma_disclaimer_content_reject';
    const DEFAULT_DISCLAIMER_CONTENT_REJECT         = 'Reject Terms';
    const OPTION_NEW_QUESTION_NOTIFICATION          = 'cma_new_question_notification';
    const OPTION_NEW_QUESTION_NOTIFICATION_TITLE    = 'cma_new_question_notification_title';
    const OPTION_NEW_QUESTION_NOTIFICATION_CONTENT  = 'cma_new_question_notification_content';
    const DEFAULT_NEW_QUESTION_NOTIFICATION_TITLE   = '[[blogname]] A new question has been asked by [author]';
    const DEFAULT_NEW_QUESTION_NOTIFICATION_CONTENT = 'A new question has been asked by [author]:
Title: [question_title]
Approval status: [question_status]

Click to see: [question_link]';
    const OPTION_THREAD_NOTIFICATION                = 'cma_thread_notification';
    const OPTION_THREAD_NOTIFICATION_TITLE          = 'cma_thread_notification_title';
    const DEFAULT_THREAD_NOTIFICATION               = 'Someone has posted a new answer on the topic you subscribed to

Topic: [question_title]
Click to see: [comment_link]';
    const DEFAULT_THREAD_NOTIFICATION_TITLE         = '[[blogname]] Someone has posted a new answer on the topic you subscribed to';
    const OPTION_ANSWERS_PERMALINK                  = 'cma_answers_permalink';
    const DEFAULT_ANSWERS_PERMALINK                 = 'answers'; // Default rewrite slug
    const OPTION_CODE_SNIPPET_COLOR                 = 'cma_code_snippet_color';
    const DEFAULT_CODE_SNIPPET_COLOR                = '#FFF';
    const OPTION_SPAM_FILTER                        = 'cma_spam_filter';
    const OPTION_SIMULATE_COMMENT                   = 'cma_simulate_comment';
    const YES                                       = 1;
    const NO                                        = 0;
    const VOTES_NO                                  = 0;
    const DEFAULT_ITEMS_PER_PAGE                    = 10;
    const DEFAULT_STICKY_QUESTION_COLOR             = '#EEE';
    const DEFAULT_USER_COMMENT_ONLY                 = 0;
    const DEFAULT_USER_LOGGED_ONLY                  = 0;
    const DEFAULT_TAGS_SWITCH                       = 0;

    /**
     * @var CMA_AnswerThread[] singletones cache
     */
    protected static $instances    = array();
    /**
     * @var array meta keys mapping
     */
    protected static $_meta        = array(
        'lastPoster' => '_last_poster',
        'views' => '_views',
        'listeners' => '_listeners',
        'resolved' => '_resolved',
        'highestRatedAnswer' => '_highest_rated_answer',
        'votes' => '_votes',
        'stickyPost' => '_sticky_post'
    );
    protected static $_commentMeta = array(
        'rating' => '_rating',
        'usersRated' => '_users_rated'
    );

    /**
     * Initialize model
     */
    public static function init()
    {
        if(strtolower($_SERVER['REQUEST_METHOD']) == 'post' && isset($_POST['answers_permalink']))
        {
            self::setAnswersPermalink(stripslashes($_POST['answers_permalink']));
        }

        $post_type_args = array(
            'has_archive' => TRUE,
//            'menu_position' => 4,
            'show_in_menu' => self::ADMIN_MENU,
            'rewrite' => array(
                'slug' => self::getAnswersPermalink(),
                'with_front' => FALSE,
            ),
            'supports' => array('title', 'editor', 'author'),
            'hierarchical' => TRUE
        );
        $plural         = self::getQuestionsTitle();

        self::registerPostType(self::POST_TYPE, 'Question', $plural, CMA_AnswerThread::getQuestionsTitle(), $post_type_args);

        add_filter('CMA_admin_parent_menu', create_function('$q', 'return "' . self::ADMIN_MENU . '";'));
        add_action('admin_menu', array(get_class(), 'registerAdminMenu'));

        $taxonomy_args = array(
            'rewrite' => array(
                'slug' => self::getAnswersPermalink() . '/categories',
                'with_front' => TRUE,
                'show_ui' => TRUE,
                'hierarchical' => FALSE,
            ),
        );
        self::registerTaxonomy(self::CAT_TAXONOMY, array(self::POST_TYPE), 'Category', 'Categories', $taxonomy_args);
        add_action('generate_rewrite_rules', array(get_class(), 'fixCategorySlugs'));
        require_once CMA_PATH . '/lib/helpers/Shortcodes.php';
        CMA_Shortcodes::init();
    }

    public static function getQuestionsTitle()
    {
        return get_option(self::OPTION_QUESTIONS_TITLE, 'Questions');
    }

    public static function fixCategorySlugs($wp_rewrite)
    {
        $wp_rewrite->rules = array(
            self::getAnswersPermalink() . '/categories/([^/]+)/?$' => $wp_rewrite->index . '?post_type=' . self::POST_TYPE . '&' . self::CAT_TAXONOMY . '=' . $wp_rewrite->preg_index(1),
            self::getAnswersPermalink() . '/categories/([^/]+)/page/?([0-9]{1,})/?$' => $wp_rewrite->index . '?post_type=' . self::POST_TYPE . '&' . self::CAT_TAXONOMY . '=' . $wp_rewrite->preg_index(1) . '&paged=' . $wp_rewrite->preg_index(2),
                ) + $wp_rewrite->rules;
    }

    /**
     * @static
     * @param int $id
     * @return CMA_AnswerThread
     */
    public static function getInstance($id = 0)
    {
        if(!$id)
        {
            return NULL;
        }
        if(!isset(self::$instances[$id]) || !self::$instances[$id] instanceof self)
        {
            self::$instances[$id] = new self($id);
        }
        if(self::$instances[$id]->post->post_type != self::POST_TYPE)
        {
            return NULL;
        }
        return self::$instances[$id];
    }

    public static function registerAdminMenu()
    {
        $current_user = wp_get_current_user();

        if(user_can($current_user, 'edit_posts'))
        {
            $page = add_menu_page('Questions', 'CM Answers Pro', 'edit_posts', self::ADMIN_MENU, create_function('$q', 'return;'));
            add_submenu_page(self::ADMIN_MENU, 'Answers', 'Answers', 'edit_posts', 'edit-comments.php?post_type=' . self::POST_TYPE);
            add_submenu_page(self::ADMIN_MENU, 'Categories', 'Categories', 'manage_categories', 'edit-tags.php?taxonomy=' . self::CAT_TAXONOMY . '&amp;post_type=' . self::POST_TYPE);
            if(isset($_GET['taxonomy']) && $_GET['taxonomy'] == self::CAT_TAXONOMY && isset($_GET['post_type']) && $_GET['post_type'] == self::POST_TYPE)
            {
                add_filter('parent_file', create_function('$q', 'return "' . self::ADMIN_MENU . '";'), 999);
            }
            add_submenu_page(self::ADMIN_MENU, 'Add New', 'Add New', 'edit_posts', 'post-new.php?post_type=' . self::POST_TYPE);
        }
    }

    /**
     * Get content of answer
     * @return string
     */
    public function getContent($charscount = 0, $striptags = false)
    {
        $content = $this->post->post_content;

        if($striptags) $content = strip_tags($content);

        if($charscount > 0 and strlen($content) > $charscount)
        {
            $content = substr($content, 0, $charscount) . "...";
        }
        return strip_shortcodes($content);
    }

    public function isSticky()
    {
        return get_post_meta($this->post->ID, '_sticky_post', true);
    }

    /**
     * Set content of question
     * @param string $_description
     * @param bool $save Save immediately?
     * @return CMA_AnswerThread
     */
    public function setContent($_content, $save = false)
    {
        $this->post->post_content = nl2br($_content);
        if($save) $this->savePost();
        return $this;
    }

    /**
     * Set status
     * @param string $_status
     * @param bool $save Save immediately?
     * @return CMA_AnswerThread
     */
    public function setStatus($_status, $save = false)
    {
        $this->post->post_status = $_status;
        if($save) $this->savePost();
        return $this;
    }

    public function getStatus()
    {
        $status = $this->post->post_status;
        if($status == 'draft') return __('pending', 'cm-answers-pro');
        elseif($status == 'publish') return __('approved', 'cm-answers-pro');
    }

    public function getAttachment()
    {
        $args        = array(
            'post_type' => 'attachment',
            'numberposts' => null,
            'post_status' => null,
            'post_parent' => $this->getId(),
            'orderby' => 'ID',
            'order' => 'DESC',
            'posts_per_page' => 1,
        );
        $attach      = array();
        $attachments = get_posts($args);
        if($attachments)
        {
            foreach($attachments as $attachment)
            {
                $attach['name'] = apply_filters('the_title', $attachment->post_title);
                $attach['link'] = wp_get_attachment_url($attachment->ID);
            }
        }
        return $attach;
    }

    /**
     * Get author ID
     * @return int Author ID
     */
    public function getAuthorId()
    {
        return $this->post->post_author;
    }

    /**
     * Get author
     * @return WP_User
     */
    public function getAuthor()
    {
        $data       = get_userdata($this->getAuthorId());
        $url        = CMA_BaseController::getUrl('contributor', $data->user_nicename);
//        $url = get_usermeta($this->getAuthorId(), '_cma_social_url');
        $data->name = $data->display_name;
        if(!empty($url))
        {
            $params             = array('backlink' => base64_encode($_SERVER['REQUEST_URI']), 'ajax' => 1);
            $permalink          = add_query_arg($params, $url);
            $data->display_name = '<a href="' . esc_url($permalink) . '" target="_blank">' . $data->name . '</a>';
        }
        return $data;
    }

    /**
     * Set author
     * @param int $_author
     * @param bool $save Save immediately?
     * @return CMA_AnswerThread
     */
    public function setAuthor($_author, $save = false)
    {
        $this->post->post_author = $_author;
        if($save) $this->savePost();
        self::updateQA($_author);
        return $this;
    }

    public function getLastPoster()
    {
        $lastPoster = $this->getPostMeta(self::$_meta['lastPoster']);
        if(empty($lastPoster) || get_userdata($lastPoster) == FALSE)
        {
            $lastPoster = $this->getAuthorId();
            $this->setLastPoster($lastPoster);
        }
        return $lastPoster;
    }

    public function getLastPosterName()
    {
        $userdata = get_userdata($this->getLastPoster());
        $url      = CMA_BaseController::getUrl('contributor', $userdata->user_nicename);
        if($url)
        {
            $params                 = array('backlink' => base64_encode($_SERVER['REQUEST_URI']), 'ajax' => 1);
            $permalink              = add_query_arg($params, $url);
            $userdata->display_name = '<a href="' . esc_url($permalink) . '" target="_blank">' . $userdata->display_name . '</a>';
        }
        return $userdata->display_name;
    }

    public function setLastPoster()
    {
        $lastComment = $this->getLastAnswer();
        if($lastComment)
        {
            $commentAuthor = get_user_by('slug', $lastComment->comment_author);
            $this->savePostMeta(array(self::$_meta['lastPoster'] => $commentAuthor->ID));
            self::updateQA($commentAuthor->ID);
        }
        return $this;
    }

    public function getViews()
    {
        return (int) $this->getPostMeta(self::$_meta['views']);
    }

    public function addView()
    {
        $increment = true;
        if(!self::isViewsIncremented())
        {
            $currentBlockedIds = isset($_COOKIE['cma_viewed_questions']) ? maybe_unserialize($_COOKIE['cma_viewed_questions']) : array(
            );
            if(in_array($this->getId(), $currentBlockedIds)) $increment         = false;
            else
            {
                $currentBlockedIds[] = $this->getId();
                setcookie('cma_viewed_questions', serialize($currentBlockedIds), time() + (3600 * 24 * 30));
            }
        }
        if($increment)
        {
            $views = $this->getViews();
            $this->savePostMeta(array(self::$_meta['views'] => $views + 1));
        }
        return $this;
    }

    public function getTitle()
    {
        $title = parent::getTitle();
        if($this->isResolved()) $title = '[' . __('RESOLVED', 'cm-answers-pro') . '] ' . $title;
        return $title;
    }

    public function getVotes()
    {
        if(self::getVotesMode() == self::VOTES_MODE_COUNT) return (int) $this->getPostMeta(self::$_meta['votes']);
        else return $this->getHighestRatedAnswer();
    }

    public function addVote()
    {
        $votes = $this->getVotes();
        $this->savePostMeta(array(self::$_meta['votes'] => $votes + 1));
        $this->refreshHighestRatedAnswer();
        return $this;
    }

    public function getHighestRatedAnswer()
    {
        return (int) $this->getPostMeta(self::$_meta['highestRatedAnswer']);
    }

    public function refreshHighestRatedAnswer()
    {
        global $wpdb;
        $sql     = $wpdb->prepare("SELECT MAX(m.meta_value*1) FROM {$wpdb->commentmeta} m JOIN {$wpdb->comments} c ON c.comment_ID=m.comment_id AND m.meta_key='%s' AND c.comment_post_ID='%d' AND c.comment_approved", self::$_commentMeta['rating'], $this->getId());
        $highest = (int) $wpdb->get_var($sql);
        $this->savePostMeta(array(self::$_meta['highestRatedAnswer'] => $highest));
        return $this;
    }

    public function isResolved()
    {
        return $this->getPostMeta(self::$_meta['resolved']) == 1;
    }

    public function setResolved($value = true)
    {
        $this->savePostMeta(array(self::$_meta['resolved'] => (int) $value));
        return $this;
    }

    public function getListeners()
    {
        return (array) $this->getPostMeta(self::$_meta['listeners']);
    }

    public function addListener($userId)
    {
        $listeners   = $this->getListeners();
        $listeners[] = $userId;
        $listeners   = array_unique($listeners);
        $this->savePostMeta(array(self::$_meta['listeners'] => $listeners));
        return $this;
    }

    /**
     * Get the date when the question was first asked
     * @param string $format
     * @return type
     */
    public function getCreationDate($format = '')
    {
        if(empty($format))
        {
            $format = get_option('date_format') . ' ' . get_option('time_format');
        }
        return date_i18n($format, strtotime($this->post->post_date));
    }

    /**
     * Returns the last comment object WP_Comment or null if no comments
     * @return null|WP_Comment
     */
    public function getLastAnswer()
    {
        $comments = get_comments(array('post_id' => $this->getId(), 'number' => 1, 'status' => 'approve'));
        if($comments)
        {
            return end($comments);
        }
        return null;
    }

    /**
     * Get when item was updated
     * @param string $format
     * @return string
     */
    public function getUpdatedDate($format = '')
    {
        if(empty($format))
        {
            $format = get_option('date_format') . ' ' . get_option('time_format');
        }

        $lastComment = $this->getLastAnswer();
        if($lastComment)
        {
            $dateString = $lastComment->comment_date;
        }
        else
        {
            $dateString = $this->post->post_modified;
        }

        return date_i18n($format, strtotime($dateString));
    }

    /**
     *
     * @param type $gmt
     * @return type
     */
    public function getUnixUpdated($gmt = false)
    {
        return get_post_modified_time('G', $gmt, $this->getPost());
    }

    /**
     *
     * @param type $gmt
     * @return type
     */
    public function getUnixDate($gmt = false)
    {
        return get_post_time('G', $gmt, $this->getPost());
    }

    public function setUpdated($date = null)
    {
        global $wpdb;

        if(empty($date))
        {
            $date = current_time('mysql');
        }

        $this->post->post_modified     = $date;
        $this->post->post_modified_gmt = $date;

        $wpdb->update($wpdb->posts, array('post_modified' => $date, 'post_modified_gmt' => get_gmt_from_date($date)), array('ID' => $this->post->ID));

        return $this;
    }

    public function getNumberOfAnswers()
    {
        return get_comments_number($this->getId());
    }

    public function getAnswers($sort = self::ANSWER_SORTING_BY_DATE)
    {
        $additionalArgs = array();

        if($sort === null)
        {
            $sort = self::getAnswerSortingBy();
        }

        if($sort == self::ANSWER_SORTING_BY_VOTES)
        {
            $additionalArgs = array(
                'orderby' => self::$_commentMeta['rating'],
                'meta_key' => self::$_commentMeta['rating'],
            );
        }

        $args        = array(
            'post_id' => $this->getId(),
            'status' => 'approve',
            'order' => self::isAnswerSortingDesc() ? 'DESC' : 'ASC',
            'fields' => 'ids,'.self::$_commentMeta['rating']
        );
        $args        = array_merge($args, $additionalArgs);
        $rawComments = get_comments($args);

//        }
        $comments    = array();
        if(!empty($rawComments))
        {
            foreach($rawComments as $row)
            {
                $comments[] = $this->getCommentData($row->comment_ID);
            }
        }
        return $comments;
    }

    public function isEditAllowed($userId)
    {
        return (user_can($userId, 'manage_options') || $this->getAuthorId() == $userId);
    }

    public static function newThread($data = array())
    {
        $title = trim(wp_kses($data['title'], array()));

        /*         * **Format Code Snippets ******************************* */
        $data['content'] = preg_replace_callback("/<pre>([\s\S]+?)<\/pre>/", function($matches) {

            $snippet = $matches[1];
            $snippet = htmlentities($snippet);
            $snippet = nl2br($snippet);

            return '<pre class="cma_snippet_background">' . $snippet . '</pre>';
        }, $data['content']);
        /*         * ******************************************************* */

        /*
         * use wp_kses only if there's no richtext editor
         */
        $wpKsedContent = CMA_AnswerThread::getRichtextEditor() ? wpautop($data['content'], false) : wp_kses(wpautop($data['content'], false), array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'em' => array(),
                    'strong' => array(),
                    'b' => array(),
                    'br' => array(),
                    'pre' => array(
                        'class' => array(),
                    ),
                    'p' => array()
        ));

        $content = trim($wpKsedContent);

        if(empty($title)) $errors[] = __('Title cannot be empty', 'cm-answers-pro');
        if(!CMA_AnswerThread::isQuestionDescriptionOptional() && empty($content))
        {
            $errors[] = __('Content cannot be empty', 'cm-answers-pro');
        }
        if(!self::isUploadSizeAllowed()) $errors[] = __('The file you uploaded is too big', 'cm-answers-pro');
        elseif(!self::isUploadAllowed()) $errors[] = __('The file you uploaded is not allowed', 'cm-answers-pro');

        if(!empty($errors))
        {
            throw new Exception(serialize($errors));
        }

        if(self::isQuestionAutoApproved() || self::isAuthorAutoApproved(get_current_user_id()))
        {
            $status = 'publish';
        }
        else
        {
            $status = 'publish';

            if(self::getSpamFilter() || self::simulateComment())
            {
                /** Hack, simulate comment adding to trigger spam filters * */
                $commentdata = array(
                    'comment_post_ID' => 0,
                    'comment_author' => wp_get_current_user()->first_name,
                    'comment_author_email' => wp_get_current_user()->user_email,
                    'comment_content' => $title . ' ' . $content,
                    'comment_type' => 'comment',
                    'user_ID' => get_current_user_id()
                );

//                if(self::getSpamFilter() && get_option('comment_moderation'))
//                {
//                    $checkComment = check_comment($commentdata['comment_author'], $commentdata['comment_author_email'], '', $commentdata['comment_content'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], 'comment');
//                    $blacklistCheck = wp_blacklist_check($commentdata['comment_author'], $commentdata['comment_author_email'], '', $commentdata['comment_content'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
//
//                    if(!$checkComment || !$blacklistCheck)
//                    {
//                        $status = 'draft';
//                    }
//                }

                if(self::simulateComment())
                {
                    if(get_option('comment_moderation'))
                    {
                        $status = 'draft';
                    }
                    else
                    {
                        $commentId       = wp_new_comment($commentdata);
                        $commentApproved = get_comment($commentId);
                        if($commentApproved->comment_approved == 'spam')
                        {
                            $status = 'draft';
                        }
                        wp_delete_comment($commentId, true);
                    }
                }
            }
        }

        $id = wp_insert_post(array(
            'post_status' => $status,
            'post_type' => self::POST_TYPE,
            'post_title' => $title,
            'post_content' => $content,
            'post_name' => sanitize_title_with_dashes(remove_accents($title)),
            'post_author' => get_current_user_id(),
        ));

        if(isset($data['tags']))
        {
            wp_set_post_tags($id, $data["tags"], true);
        }

        if($id instanceof WP_Error)
        {
            return $id->get_error_message();
        }
        else
        {
            $instance = self::getInstance($id);
            $instance->setUpdated()
                    ->setResolved(false)
                    ->setLastPoster(get_current_user_id());
            if($data['notify'] == 1) $instance->addListener(get_current_user_id());
            $instance->savePostMeta(array(self::$_meta['votes'] => 0));
            $instance->savePostMeta(array(self::$_meta['highestRatedAnswer'] => 0));
            $instance->savePostMeta(array(self::$_meta['stickyPost'] => 0));
            if(!empty($data['category']) && $data['category'] > 0)
            {
                wp_set_post_terms($id, array($data['category']), self::CAT_TAXONOMY);
            }
            $instance->savePost();

            if(!empty($_FILES) && isset($_FILES['attachment']))
            {
                $instance->setDownloadFile($_FILES['attachment']);
            }
            if($status == 'draft')
            {
                $instance->notifyModerator();
            }
            $instance->notifyAboutNewQuestion();
            return $instance;
        }
    }

    /**
     * Checks if the Thread has been held for moderation
     * @return boolean
     */
    public function wasHeldForModeration()
    {
        $held = $this->post->post_status === 'draft';
        return $held;
    }

    public static function getSpamFilter()
    {
        return get_option(self::OPTION_SPAM_FILTER, 1);
    }

    public static function setSpamFilter($value)
    {
        update_option(self::OPTION_SPAM_FILTER, (bool) $value);
    }

    public static function simulateComment()
    {
        return get_option(self::OPTION_SIMULATE_COMMENT, 1);
    }

    public static function setSimulateComment($value)
    {
        update_option(self::OPTION_SIMULATE_COMMENT, (bool) $value);
    }

    public function notifyModerator()
    {
        $link    = get_permalink($this->getId());
        $author  = $this->getAuthor()->name;
        $email   = $this->getAuthor()->user_email;
        $title   = $this->getTitle();
        $content = $this->getContent();

        $approveLink = admin_url('edit.php?post_status=draft&post_type=' . self::POST_TYPE . '&cma-action=approve&cma-id=' . $this->getId());
        $trashLink   = admin_url('edit.php?post_status=draft&post_type=' . self::POST_TYPE . '&cma-action=trash&cma-id=' . $this->getId());
        $pendingLink = admin_url('edit.php?post_status=draft&post_type=' . self::POST_TYPE);

        $emailTitle   = '[' . get_bloginfo('name') . '] Please moderate: "' . $title . '"';
        $emailContent = "A new question has been asked and is waiting for your approval {$link}

Author : {$author}
E-mail : {$email}
Title  : {$title}
Content:
{$content}


Approve it: {$approveLink}
Trash it: {$trashLink}
Please visit the questions moderation panel:
{$pendingLink}
";
        @wp_mail(get_option('admin_email'), $emailTitle, $emailContent);
    }

    public function notifyAboutNewQuestion()
    {
        $receivers = self::getNewQuestionNotification(false);
        if(!empty($receivers))
        {
            $author         = $this->getAuthor()->name;
            $questionTitle  = $this->getTitle();
            $questionLink   = get_permalink($this->getId());
            $questionStatus = $this->getStatus();
            $blogname       = get_bloginfo('name');
            $title          = self::getNewQuestionNotificationTitle();
            $content        = self::getNewQuestionNotificationContent();
            $title          = str_replace('[blogname]', $blogname, $title);
            $title          = str_replace('[author]', $author, $title);
            $title          = str_replace('[question_title]', $questionTitle, $title);
            $title          = str_replace('[question_status]', $questionStatus, $title);
            $title          = str_replace('[question_link]', $questionLink, $title);
            $content        = str_replace('[blogname]', $blogname, $content);
            $content        = str_replace('[author]', $author, $content);
            $content        = str_replace('[question_title]', $questionTitle, $content);
            $content        = str_replace('[question_status]', $questionStatus, $content);
            $content        = str_replace('[question_link]', $questionLink, $content);
            foreach($receivers as $receiver)
            {
                if(is_email($receiver))
                {
                    @wp_mail($receiver, $title, $content);
                }
            }
        }
    }

    public function delete()
    {
        return wp_delete_post($this->getId(), true) !== false;
    }

    public function approve()
    {
        $this->setStatus('publish', true);
    }

    public function trash()
    {
        $this->setStatus('trash', true);
    }

    public static function getCategoriesTree()
    {
        $cats   = array();
        $terms  = get_terms(self::CAT_TAXONOMY, array(
            'orderby' => 'name',
            'hide_empty' => 0
        ));
        $output = array();
        foreach($terms as $term)
        {
            if($term->parent == 0)
            {
                $cats['main'][$term->term_id] = $term->name;
            }
            else
            {
                $cats['sub'][$term->parent][$term->term_id] = $term->name;
            }
        }
        if(!empty($cats['main']))
        {
            foreach($cats['main'] as $id => $name)
            {
                $output[$id] = $name;
                if(isset($cats['sub'][$id]))
                {
                    foreach($cats['sub'][$id] as $subId => $name)
                    {
                        $output [$subId] = ' - ' . $name;
                    }
                }
            }
        }

        return $output;
    }

    public static function getCategories()
    {
        $cats  = array();
        $terms = get_terms(self::CAT_TAXONOMY, array(
            'orderby' => 'name',
            'hide_empty' => 0
        ));
        foreach($terms as $term)
        {
            $cats[$term->term_id] = $term->name;
        }
        return $cats;
    }

    public function getCategory()
    {
        $terms = wp_get_post_terms($this->getId(), self::CAT_TAXONOMY);
        $term  = reset($terms);
        if(!empty($term))
        {
            return '<a href="' . get_term_link($term, self::CAT_TAXONOMY) . '">' . $term->name . '</a>';
        }
        return null;
    }

    public static function getCommentData($comment_id)
    {
        $comment = get_comment($comment_id);
        $author  = get_userdata($comment->user_id);
        $url     = CMA_BaseController ::getUrl('contributor', $author->user_nicename);
//get_usermeta($comment->user_id, '_cma_social_url');

        if(!empty($comment->comment_author) && $comment->user_id != $comment->comment_author)
        {
            if(!empty($url)) $authorUrl = '<a href="' . $url . '" target="_blank">' . $comment->comment_author . '</a>';
            else $authorUrl = $comment->comment_author;
        } else
        {
            if(!empty($url)) $authorUrl = '<a href="' . $url . '" target="_blank">' . $author->display_name . '</a>';
            else $authorUrl = $author->display_name;
        }

        $retVal = array(
            'id' => $comment_id,
            'content' => $comment->comment_content,
            'authorId' => $comment->user_id,
            'author' => $author->display_name,
            'authorUrl' => $authorUrl,
            'date' => get_comment_date(get_option('date_format') . ' ' . get_option('time_format'), $comment_id),
            'daysAgo' => self::renderDaysAgo(get_comment_date('G', $comment_id)),
            'rating' => (int) get_comment_meta($comment_id, self::$_commentMeta['rating'], true),
            'status' => $comment->comment_approved == 1 ? __('approved', 'cm-answers-pro') : __('pending', 'cm-answers-pro'),
            'questionId' => $comment->comment_post_ID
        );
        return $retVal;
    }

    public function addCommentToThread($content, $author_id, $notify = false, $resolved = false)
    {
        $user = get_userdata($author_id);

        /*         * ** Format Code Snippets ******************************* */
        $content = trim(preg_replace_callback("/<pre>([\s\S]+?)<\/pre>/", function($matches) {

                    $snippet = $matches[1];
                    $snippet = htmlentities($snippet);
                    $snippet = nl2br($snippet);

                    return '<pre class="cma_snippet_background">' . $snippet . '</pre>';
                }, $content));

        /*         * ******************************************************* */
        /*
         * use wp_kses only if there's no richtext editor
         */
        $wpKsedContent = CMA_AnswerThread::getRichtextEditor() ? wpautop($content, false) : wp_kses(wpautop($content, false), array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'em' => array(),
                    'strong' => array(),
                    'br' => array(),
                    'b' => array(),
                    'pre' => array(
                        'class' => array(),
                    ),
                    'p' => array()
        ));

        if(empty($content)) $errors[] = __('Content cannot be empty', 'cm-answers-pro');
        if(!empty($errors))
        {
            throw new Exception(serialize($errors));
        }

        $approved = (self::isAnswerAutoApproved() || self::isAuthorAutoApproved(get_current_user_id())) ? 1 : 0;

        $data       = array(
            'comment_post_ID' => $this->getId(),
            'comment_author' => $user->display_name,
            'comment_author_email' => $user->user_email,
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'user_id' => $author_id,
            'comment_parent' => 0,
            'comment_content' => apply_filters('comment_text', $content),
            'comment_approved' => $approved,
            'comment_date' => current_time('mysql')
        );
        $comment_id = wp_insert_comment($data);

        $this->updateThreadMetadata(array(
            'commentId' => $comment_id,
            'authorId' => $author_id,
            'notify' => $notify,
            'resolved' => $resolved,
            'approved' => $approved,
        ));

        update_comment_meta($comment_id, self::$_commentMeta['rating'], 0);

        if($approved !== 1)
        {
            wp_notify_moderator($comment_id);
        }
        return $comment_id;
    }

    protected function _notifyOnFollow($lastCommentId)
    {
        $listeners = $this->getListeners();
        if(!empty($listeners))
        {
            $message = get_option(self::OPTION_THREAD_NOTIFICATION, self::DEFAULT_THREAD_NOTIFICATION);
            $title   = get_option(self::OPTION_THREAD_NOTIFICATION_TITLE, self::DEFAULT_THREAD_NOTIFICATION_TITLE);

            $postTitle   = $this->getTitle();
            $commentLink = get_permalink($this->getId()) . '/#comment-' . $lastCommentId;
            $blogname    = get_bloginfo('name');
            $title       = str_replace('[blogname]', $blogname, $title);
            $title       = str_replace('[question_title]', $postTitle, $title);
            $title       = str_replace('[comment_link]', $commentLink, $title);
            $message     = str_replace('[blogname]', $blogname, $message);
            $message     = str_replace('[question_title]', $postTitle, $message);
            $message     = str_replace('[comment_link]', $commentLink, $message);
            foreach($listeners as $user_id)
            {
                $user = get_userdata($user_id);
                if(!empty($user->user_email))
                {
                    wp_mail($user->user_email, $title, $message);
                }
            }
        }
    }

    public function updateThreadMetadata($array)
    {
        $authorId  = (isset($array['authorId'])) ? $array['authorId'] : null;
        $commentId = (isset($array['commentId'])) ? $array['commentId'] : null;

        $this->setLastPoster();

        if($authorId && isset($array['notify']) && $array['notify'])
        {
            $this->addListener($authorId);
        }

        if(isset($array['resolved']) && $array['resolved'])
        {
            $this->setResolved($array['resolved']);
        }

        if($commentId)
        {
            $this->_notifyOnFollow($commentId);
        }
    }

    public function getVoters($comment_id)
    {
        return (array) get_comment_meta($comment_id, self::$_commentMeta[
                        'usersRated'], true);
    }

    public function addVoter($comment_id, $user_id)
    {
        $voters   = $this->getVoters($comment_id);
        $voters[] = $user_id;
        $voters   = array_unique($voters);
        update_comment_meta($comment_id, self::$_commentMeta['usersRated'], $voters);

        return $this;
    }

    public function isVotingAllowed($comment_id, $user_id)
    {
        return !in_array($user_id, $this->getVoters(
                                $comment_id));
    }

    public function voteUp($comment_id)
    {
        $currentRating = (int) get_comment_meta($comment_id, self::$_commentMeta['rating'], true);
        update_comment_meta($comment_id, self::$_commentMeta['rating'], $currentRating + 1);
        $this->addVoter($comment_id, get_current_user_id())->addVote();
        return $currentRating + 1;
    }

    public function voteDown($comment_id)
    {
        $currentRating = (int) get_comment_meta($comment_id, self::$_commentMeta['rating'], true);
        update_comment_meta($comment_id, self::$_commentMeta['rating'], $currentRating - 1);

        $this->addVoter($comment_id, get_current_user_id())->addVote();
        return $currentRating - 1;
    }

    /**
     *
     * @param int $date Unix timestamp
     * @return string
     */
    public static function renderDaysAgo($date, $gmt = false)
    {
        if(!is_numeric($date))
        {
            $date = strtotime($date);
        }
        $current     = current_time('timestamp', $gmt);
        $seconds_ago = floor($current - $date);

        if($seconds_ago < 0)
        {
            return __('some time ago', 'cm-answers-pro');
        }
        else
        {
            if($seconds_ago < 60)
            {
                return sprintf(_n('1 second ago', '%d seconds ago', $seconds_ago, 'cm-answers-pro'), $seconds_ago);
            }
            else
            {
                $minutes_ago = floor($seconds_ago / 60);
                if($minutes_ago < 60)
                {
                    return sprintf(_n('1 minute ago', '%d minutes ago', $minutes_ago, 'cm-answers-pro'), $minutes_ago);
                }
                else
                {
                    $hours_ago = floor($minutes_ago / 60);
                    if($hours_ago < 24)
                    {
                        return sprintf(_n('1 hour ago', '%d hours ago', $hours_ago, 'cm-answers-pro'), $hours_ago);
                    }
                    else
                    {
                        $days_ago = floor($hours_ago / 24);
                        if($days_ago < 7)
                        {
                            return sprintf(_n('1 day ago', '%d days ago', $days_ago, 'cm-answers-pro'), $days_ago);
                        }
                        else
                        {
                            $weeks_ago = floor($days_ago / 7);
                            if($weeks_ago < 4)
                            {
                                return sprintf(_n('1 week ago', '%d weeks ago', $weeks_ago, 'cm-answers-pro'), $weeks_ago);
                            }
                            else
                            {
                                $months_ago = floor($weeks_ago / 4);
                                if($months_ago < 12)
                                {
                                    return sprintf(_n('1 month ago', '%d months ago', $months_ago, 'cm-answers-pro'), $months_ago);
                                }
                                else
                                {
                                    $years_ago = floor($months_ago / 12);
                                    return sprintf(_n('1 year ago', '%d years ago', $years_ago, 'cm-answers-pro'), $years_ago);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function setDisclaimerApproved($value = true)
    {
        update_option(self::OPTION_DISCLAIMER_APPROVE, (int) $value);
    }

    public static function setQuestionAutoApproved($value = true)
    {
        update_option(self::OPTION_QUESTION_AUTO_APPROVE, (int) $value);
    }

    public static function setAnswerAutoApproved($value = true)
    {
        update_option(self::OPTION_ANSWER_AUTO_APPROVE, (int) $value);
    }

    public static function setAttachmentAllowed($value = array('zip', 'pdf', 'doc'))
    {
        update_option(self::OPTION_ATTACHMENT_ALLOWED, (array) $value);
    }

    public static function isDisclaimerApproved()
    {
        return (bool) get_option(self::OPTION_DISCLAIMER_APPROVE);
    }

    public static function isQuestionAutoApproved()
    {
        return (bool) get_option(self::OPTION_QUESTION_AUTO_APPROVE);
    }

    public static function isAnswerAutoApproved()
    {
        return (bool) get_option(self::OPTION_ANSWER_AUTO_APPROVE);
    }

    public static function isAuthorAutoApproved($author_id)
    {
        return in_array($author_id, self::getAuthorsAutoApproved());
    }

    public static function setAuthorsAutoApproved($authors = array())
    {
        update_option(self::OPTION_AUTO_APPROVE_AUTHORS, $authors);
    }

    public static function getAuthorsAutoApproved()
    {
        return get_option(self::OPTION_AUTO_APPROVE_AUTHORS, array
            (1));
    }

    public static function isRatingAllowed()
    {
        $allowed = get_option(self ::OPTION_RATING_ALLOWED, 1);
        return (bool) $allowed;
    }

    public static function showGravatars()
    {
        $allowed = get_option(self ::OPTION_GRAVATARS_SHOW, 1);
        return (bool) $allowed;
    }

    public static function setShowGravatars($show)
    {
        update_option(self ::OPTION_GRAVATARS_SHOW, (int) $show);
    }

    public static function showSocial()
    {
        $allowed = get_option(self::OPTION_SOCIAL_SHOW, 1);
        return (bool) $allowed;
    }

    public static function setShowSocial($show)
    {
        update_option(self::OPTION_SOCIAL_SHOW, (int) $show);
    }

    public static function isViewsAllowed()
    {
        $allowed = get_option(self::OPTION_VIEWS_ALLOWED, 1);
        return (bool) $allowed;
    }

    public static function isVotesNo()
    {
        $allowed = get_option(self::OPTION_VOTES_NO, self::VOTES_NO);
        return (bool) $allowed;
    }

    public static function isAnswersAllowed()
    {
        $allowed = get_option(self ::OPTION_ANSWERS_ALLOWED, 1);
        return (bool) $allowed;
    }

    public static function isViewsIncremented()
    {
        $allowed = get_option(self ::OPTION_INCREMENT_VIEWS, 1);
        return (bool) $allowed;
    }

    public static function isAuthorAllowed()
    {
        $allowed = get_option(self ::OPTION_AUTHOR_ALLOWED, 1);
        return (bool) $allowed;
    }

    public static function isUpdatedAllowed()
    {
        $allowed = get_option(self ::OPTION_UPDATED_ALLOWED, 1);
        return (bool) $allowed;
    }

    /**
     * in bytes
     * @return type
     */
    public static function getAttachmentMaxSize()
    {
        return get_option(self::OPTION_ATTACHMENT_MAX_SIZE
                , 1048576);
    }

    public static function setAttachmentMaxSize($size)
    {
        update_option(self::OPTION_ATTACHMENT_MAX_SIZE, (int) $size);
    }

    public static function convertShorthandToBytes($shorthand)
    {
        if(!$shorthand || !is_string($shorthand))
        {
            return _e('NOT SET. Typically: 32768B(32MB)');
        }

        $val  = trim($shorthand);
        $last = mb_strtolower(mb_substr($val, -1));
// The 'G' modifier is available since PHP 5.1.0
        switch($last)
        {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
            default:
                break;
        }

        return $val;
    }

    public static function convertBytesToShorthand($bytes, $precision = 2)
    {
// human readable format -- powers of 1024
//
        $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

        return @round(
                        $bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision
                ) . ' ' .
                $unit[$i];
    }

    public static function getAttachmentAllowed()
    {
        $allowed = get_option(self::OPTION_ATTACHMENT_ALLOWED);
        return (array) $allowed;
    }

    public static function isAttachmentAllowed()
    {
        $allowed = self::getAttachmentAllowed();
        $array   = array_filter($allowed);

        return !
                empty($array);
    }

    public static function getAnswerSortingBy()
    {
        static $validValues = array(
            self::ANSWER_SORTING_BY_VOTES,
            self::ANSWER_SORTING_BY_DATE
        );

        $sortBy = trim(get_option(self::OPTION_ANSWER_SORTING_BY, self::ANSWER_SORTING_BY_DATE));

        if(!in_array($sortBy, $validValues))
        {
            self::setAnswerSortingBy($sortBy = self::ANSWER_SORTING_BY_DATE);
        }

        return $sortBy;
    }

    public static function isAnswerSortingDesc()
    {
        $allowed = get_option(self::OPTION_ANSWER_SORTING_DESC, 1);
        return (bool) $allowed;
    }

    public static function setAnswerSortingBy($sortBy)
    {
        static $validValues = array(
            self::ANSWER_SORTING_BY_VOTES,
            self::ANSWER_SORTING_BY_DATE
        );
        $sortBy             = trim(strval($sortBy));

        if(!in_array($sortBy, $validValues))
        {
            $sortBy = self::ANSWER_SORTING_BY_DATE;
        }

        update_option(self::OPTION_ANSWER_SORTING_BY, $sortBy);
    }

    public static function setAnswerSortingDesc($desc)
    {
        update_option(self::OPTION_ANSWER_SORTING_DESC, (int) $desc);
    }

    public static function isNegativeRatingAllowed()
    {
        $allowed = get_option(self::OPTION_NEGATIVE_RATING_ALLOWED, 1);
        return (bool) $allowed;
    }

    public static function setRatingAllowed($value = true)
    {
        update_option(self ::OPTION_RATING_ALLOWED, (int) $value);
    }

    public static function setViewsAllowed($value = true)
    {
        update_option(self::OPTION_VIEWS_ALLOWED, (int) $value);
    }

    public static function setAnswersAllowed($value = true)
    {
        update_option(self ::OPTION_ANSWERS_ALLOWED, (int) $value);
    }

    public static function setAuthorAllowed($value = true)
    {
        update_option(self ::OPTION_AUTHOR_ALLOWED, (int) $value);
    }

    public static function setUpdatedAllowed($value = true)
    {
        update_option(self ::OPTION_UPDATED_ALLOWED, (int) $value);
    }

    public static function setViewsIncremented($value = true)
    {
        update_option(self ::OPTION_INCREMENT_VIEWS, (int) $value);
    }

    public static function setNegativeRatingAllowed($value = true)
    {
        update_option(self::OPTION_NEGATIVE_RATING_ALLOWED, (int) $value);
    }

    public static function getShowUserStats()
    {
        $allowed = get_option(self ::OPTION_SHOW_USER_STATS, 1);
        return (bool) $allowed;
    }

    public static function setShowUserStats($value = true)
    {
        update_option(self ::OPTION_SHOW_USER_STATS, (int) $value);
    }

    public static function isSidebarEnabled()
    {
        $allowed = get_option(self ::OPTION_SIDEBAR_ENABLED, 1);
        return (bool) $allowed;
    }

    public static function setSidebarEnabled($value = true)
    {
        update_option(self ::OPTION_SIDEBAR_ENABLED, (int) $value);
    }

    public static function getSidebarMaxWidth()
    {
        $width = get_option(self::OPTION_SIDEBAR_MAX_WIDTH, 0);

        return (int) $width;
    }

    public static function setSidebarMaxWidth($value = 0)
    {
        update_option(self::OPTION_SIDEBAR_MAX_WIDTH, (int) $value);
    }

    public static function getVotesMode()
    {
        return get_option(self::OPTION_VOTES_MODE, self::VOTES_MODE_COUNT);
    }

    public static function setVotesMode($mode)
    {
        update_option(self::OPTION_VOTES_MODE, $mode);
    }

    public static function getVotesNo()
    {
        return get_option(self::OPTION_VOTES_NO, self

                ::VOTES_NO);
    }

    public static function setVotesNo($mode)
    {
        update_option(self::OPTION_VOTES_NO, $mode);
    }

    public static function getMarkupBox()
    {
        return get_option(self::OPTION_MARKUP_BOX, self::VOTES_NO);
    }

    public static function setMarkupBox($mode)
    {
        update_option(self::OPTION_MARKUP_BOX, $mode);
    }

    public static function getRichtextEditor()
    {
        return get_option(self::OPTION_RICHTEXT_EDITOR, self::VOTES_NO);
    }

    public static function setRichtextEditor($mode)
    {
        update_option(self::OPTION_RICHTEXT_EDITOR, $mode);
    }

    public static function isReferralEnabled()
    {
        return get_option(self::OPTION_REFERRAL_ENABLED, 0);
    }

    public static function setReferralEnabled($mode)
    {
        update_option(self::OPTION_REFERRAL_ENABLED, $mode);
    }

    public static function isAnswerPageDisabled()
    {
        return get_option(self::OPTION_ANSWER_PAGE_DISABLED, 0);
    }

    public static function setAnswerPageDisabled($mode)
    {
        update_option(self::OPTION_ANSWER_PAGE_DISABLED, $mode);
    }

    public static function getAffiliateCode()
    {
        return get_option(self::OPTION_AFFILIATE_CODE, '');
    }

    public static function setAffiliateCode($mode)
    {
        update_option(self::OPTION_AFFILIATE_CODE, $mode);
    }

    public static function getItemsPerPage()
    {
        return get_option(self ::OPTION_ITEMS_SPER_PAGE, self::DEFAULT_ITEMS_PER_PAGE);
    }

    public static function setItemsPerPage($mode)
    {
        update_option(self ::OPTION_ITEMS_SPER_PAGE, $mode);
    }

    public static function isAnswerAfterResolved()
    {
        return get_option(self::OPTION_ANSWER_AFTER_RESOLVED, 0);
    }

    public static function setAnswerAfterResolved($mode)
    {
        update_option(self::OPTION_ANSWER_AFTER_RESOLVED, $mode);
    }

    public static function isQuestionDescriptionOptional()
    {
        return get_option(self::OPTION_QUESTION_DESCRIPTION_OPTIONAL, 0);
    }

    public static function setQuestionDescriptionOptional($mode)
    {
        update_option(self::OPTION_QUESTION_DESCRIPTION_OPTIONAL, $mode);
    }

    public static function getCustomCss()
    {
        return get_option(self::OPTION_CUSTOM_CSS, '');
    }

    public static function setCustomCss($mode)
    {
        update_option(self::OPTION_CUSTOM_CSS, $mode);
    }

// *********** Tags methods *************
    public static function getTagsSwitch()
    {
        return get_option(self::OPTION_TAGS_SWITCH, self::DEFAULT_TAGS_SWITCH);
    }

    public static function setTagsSwitch($mode)
    {
        update_option(self::OPTION_TAGS_SWITCH, $mode);
    }

    public static function isTagsAllowed()
    {
        return get_option(self::OPTION_TAGS_SWITCH, self::DEFAULT_TAGS_SWITCH);
    }

// ***************************************

    public static function getUserCommentOnly()
    {
        return get_option(self::OPTION_USER_COMMENT_ONLY, self::DEFAULT_USER_COMMENT_ONLY);
    }

    public static function setUserCommentOnly($mode)
    {
        update_option(self::OPTION_USER_COMMENT_ONLY, $mode);
    }

    public static function getUserLoggedOnly()
    {
        return get_option(self::OPTION_USER_LOGGED_ONLY, self::DEFAULT_USER_LOGGED_ONLY);
    }

    public static function setUserLoggedOnly($mode)
    {
        update_option(self::OPTION_USER_LOGGED_ONLY, $mode);
    }

    public static function isUserPostAllowed()
    {
        return !get_option(self::OPTION_USER_COMMENT_ONLY, self::DEFAULT_USER_COMMENT_ONLY);
    }

    public static function getStickyQuestionColor()
    {
        return get_option(self::OPTION_STICKY_QUESTION_COLOR, self::DEFAULT_STICKY_QUESTION_COLOR);
    }

    public static function setStickyQuestionColor($mode)
    {
        update_option(self::OPTION_STICKY_QUESTION_COLOR, $mode);
    }

    public static function getNotificationTitle()
    {
        return get_option(self::OPTION_THREAD_NOTIFICATION_TITLE, self::DEFAULT_THREAD_NOTIFICATION_TITLE);
    }

    public static function getNewQuestionNotification($asString = true)
    {
        $receivers = get_option(self::OPTION_NEW_QUESTION_NOTIFICATION, array());
        if($asString) return implode(', ', $receivers);
        else return $receivers;
    }

    public static function setNewQuestionNotification($receivers)
    {
        if(!is_array($receivers))
        {
            $receiversArr = explode(',', $receivers);
            array_walk($receiversArr, 'trim');
            $receivers    = $receiversArr;
        }
        update_option(self::OPTION_NEW_QUESTION_NOTIFICATION, $receivers);
    }

    public static function getNotificationContent()
    {
        return get_option(self::OPTION_THREAD_NOTIFICATION, self::DEFAULT_THREAD_NOTIFICATION);
    }

    public static function setNotificationTitle($title)
    {
        update_option(self::OPTION_THREAD_NOTIFICATION_TITLE, $title);
    }

    public static function setNotificationContent($content)
    {
        update_option(self::OPTION_THREAD_NOTIFICATION, $content);
    }

    public static function getNewQuestionNotificationContent()
    {
        return get_option(self::OPTION_NEW_QUESTION_NOTIFICATION_CONTENT, self::DEFAULT_NEW_QUESTION_NOTIFICATION_CONTENT);
    }

    public static function getNewQuestionNotificationTitle()
    {
        return get_option(self::OPTION_NEW_QUESTION_NOTIFICATION_TITLE, self::DEFAULT_NEW_QUESTION_NOTIFICATION_TITLE);
    }

    public static function getDisclaimerContent()
    {
        return get_option(self::OPTION_DISCLAIMER_CONTENT, self::DEFAULT_DISCLAIMER_CONTENT);
    }

    public static function getDisclaimerContentAccept()
    {
        return get_option(self::OPTION_DISCLAIMER_CONTENT_ACCEPT, self::DEFAULT_DISCLAIMER_CONTENT_ACCEPT);
    }

    public static function getDisclaimerContentReject()
    {
        return get_option(self::OPTION_DISCLAIMER_CONTENT_REJECT, self::DEFAULT_DISCLAIMER_CONTENT_REJECT);
    }

    public static function setNewQuestionNotificationTitle($title)
    {
        update_option(self::OPTION_NEW_QUESTION_NOTIFICATION_TITLE, $title);
    }

    public static function getCodeSnippetColor()
    {
        return get_option(self::OPTION_CODE_SNIPPET_COLOR, self::DEFAULT_CODE_SNIPPET_COLOR);
    }

    public static function getAnswersPermalink()
    {
        return get_option(self::OPTION_ANSWERS_PERMALINK, self::DEFAULT_ANSWERS_PERMALINK);
    }

    public static function setCodeSnippetColor($color)
    {
        update_option(self::OPTION_CODE_SNIPPET_COLOR, $color);
    }

    public static function setAnswersPermalink($link)
    {
        update_option(self::OPTION_ANSWERS_PERMALINK, $link);
    }

    public static function setNewQuestionNotificationContent($content)
    {
        update_option(self::OPTION_NEW_QUESTION_NOTIFICATION_CONTENT, $content);
    }

    public static function setDisclaimerContent($content)
    {
        update_option(self::OPTION_DISCLAIMER_CONTENT, $content);
    }

    public static function setDisclaimerContentAccept($content)
    {
        update_option(self::OPTION_DISCLAIMER_CONTENT_ACCEPT, $content);
    }

    public static function setDisclaimerContentReject($content)
    {
        update_option(self::OPTION_DISCLAIMER_CONTENT_REJECT, $content);
    }

    public static function customOrder(WP_Query $query, $orderby)
    {
        switch($orderby)
        {
            case 'hottest':
                $query->set('orderby', 'modified');
                $query->set('order', 'DESC');
                break;
            case 'votes':
                if(self::getVotesMode() == self::VOTES_MODE_COUNT) $query->set('meta_key', self::$_meta['votes']);
                else $query->set('meta_key', self::$_meta['highestRatedAnswer']);
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
            case 'views':
                $query->set('meta_key', self::$_meta['views']);
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
            case 'newest':
            default:
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
        }


        return $query;
    }

    public static function tagFilter(WP_Query $query, $tag)
    {
        $query->set('tag', $tag);
        return $query;
    }

    public static function getQuestionsByUser($user_id, $limit = -1)
    {
        if(!$user_id)
        {
            return array();
        }

        $args                   = array(
            'author' => $user_id,
            'post_type' => self::POST_TYPE,
            'post_status' => array('publish', 'draft'),
            'fields' => 'ids',
            'orderby' => 'date',
            'order' => 'DESC',
            'user_questions' => true
        );
        $args['posts_per_page'] = $limit;
        $q                      = new WP_Query($args);
        $questions              = array();
        foreach($q->get_posts() as $id)
        {
            $questions[] = self::getInstance($id);
        }
        return $questions;
    }

    public static function getAnswersByUser($user_id, $limit = -1)
    {
        if(!$user_id)
        {
            return array();
        }

        $args = array(
            'user_id' => $user_id,
            'post_type' => self::POST_TYPE);

        if($limit > 1)
        {
            $args['number'] = $limit;
        }

        $rawComments = get_comments($args);
        $comments    = array();
        foreach($rawComments as $comment)
        {
            $comments[] = self::getCommentData($comment->comment_ID);
        }
        return $comments;
    }

    public static function getCountQuestionsByUser($user_id)
    {
        $answers = get_user_meta($user_id, '_cm_answers_questions', true);
        if(empty($answers))
        {
            self::updateQA($user_id);
        }
        return get_user_meta($user_id, '_cm_answers_questions', true);
    }

    public static function getCountAnswersByUser($user_id)
    {
        $answers = get_user_meta($user_id, '_cm_answers_answers', true);
        if(empty($answers))
        {
            self::updateQA($user_id);
        }
        return get_user_meta($user_id, '_cm_answers_answers', true);
    }

    public static function updateQA($userId)
    {
        global $wpdb;
        $sql       = $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts}   WHERE post_type=%s AND post_status='publish' AND post_author=%d", self::POST_TYPE, $userId);
        $questions = $wpdb->get_var($sql);
        update_user_meta($userId, '_cm_answers_questions', $questions);
        $sql2      = $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->comments} c JOIN {$wpdb->posts}   p ON p.ID=c.comment_post_ID AND p.post_type=%s AND p.post_status='publish' AND c.user_id=%d", self::POST_TYPE, $userId);
        $answers   = $wpdb->get_var($sql2);
        update_user_meta($userId, '_cm_answers_answers', $answers);
    }

    public function getPermalink($ajax = false, $append = '')
    {
        $permaBase = get_permalink($this->getId());
        if($append)
        {
            $permaBase .= $append;
        }

        $params = $ajax ? array('backlink' => base64_encode($_SERVER['REQUEST_URI']), 'ajax' => 1) : array();
        $perma  = add_query_arg($params, $permaBase);
        return $perma;
    }

    public static function getGravatarLink($userId)
    {
        $user        = get_userdata($userId);
        $email       = $user->user_email;
        $hash        = md5(trim($email));
        $profileLink = (is_ssl() ? 'https://secure' : 'http://www' ) . '.gravatar.com/' . $hash;

        return $profileLink;
    }

    public static function isUploadAllowed()
    {
        if(self::isAttachmentAllowed() && !empty($_FILES) && isset($_FILES['attachment']))
        {
            $name = $_FILES['attachment']['name'];
            $ext  = end(explode('.', $name));
            if($name != "") return (in_array($ext, self::getAttachmentAllowed()));
        }
        return true;
    }

    public static function isUploadSizeAllowed()
    {
        if(self::isAttachmentAllowed() && !empty($_FILES) && isset($_FILES['attachment']))
        {
            return $_FILES ['attachment']['size'] <= self::getAttachmentMaxSize();
        }
        return true;
    }

    public function setDownloadFile($_download_file)
    {
        $name   = time() . '_' . sanitize_file_name($_download_file['name']);
        $target = $this->getUploadPath() . $name;
        if(move_uploaded_file($_download_file['tmp_name'], $target))
        {
            $wp_filetype = $_download_file['type'];

            $attachment  = array(
                'guid' => $target,
                'post_mime_type' => $wp_filetype,
                'post_title' => sanitize_title_with_dashes($name),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id   = wp_insert_attachment($attachment, $target, $this->getId());
// you must first include the image.php file
// for the function wp_generate_attachment_metadata() to work
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $target);
            wp_update_attachment_metadata($attach_id, $attach_data);
        }
        return $this;
    }

    public function getUploadPath()
    {
        $uploadDir = wp_upload_dir();
        $baseDir   = $uploadDir ['basedir'] . '/' . self::UPLOAD_PATH . '/';
        if(!file_exists($baseDir)) mkdir($baseDir);
        return $baseDir;
    }

    static public function getTags($id, $ajax = false)
    {
        $content = '';
        if(CMA_AnswerThread::isTagsAllowed())
        {
            $content  = '<div class="cma-thread-tags">';
            $posttags = get_the_tags($id);
            if($posttags)
            {
                $content .= "Tags: ";
                foreach($posttags as $tag)
                {
//if (!$ajax) {
                    $url   = home_url() . '/' . CMA_AnswerThread::getAnswersPermalink() . '?cmatag=' . $tag->name;
                    /* }
                      else {
                      $url = '#tag='.$tag->name;
                      } */
                    $class = '';
                    if($ajax) $class = 'class="ajax_tag"';
                    $content .= '<a ' . $class . ' href="' . $url . '">' . $tag->name . '</a> ';
                }
            }
            $content .= '</div>';
        }
        return $content;
    }

    public static function answersAsHomepage()
    {
        $value = get_option(self::OPTION_ANSWERS_AS_HOMEPAGE, 0);
        return(bool) $value;
    }

    public static function setAnswersAsHomepage($value)
    {
        update_option(self::OPTION_ANSWERS_AS_HOMEPAGE, (int) $value);
    }

}