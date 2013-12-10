<?php
require_once CMA_PATH . '/lib/helpers/Widgets/QuestionsWidget.php';
class CMA_Shortcodes
{

    public static function init()
    {
        add_action('init', array(__CLASS__, 'add_rewrite_endpoint'));

        add_shortcode('cma-my-questions', array(__CLASS__, 'shortcode_my_questions'));
        add_shortcode('cma-my-answers', array(__CLASS__, 'shortcode_my_answers'));
        add_shortcode('cma-questions', array(__CLASS__, 'shortcode_questions'));

        add_action('widgets_init', array('CMA_QuestionsWidget', 'getInstance'));

        wp_enqueue_style('CMA-css', CMA_URL . '/views/resources/app.css');
    }

    public static function add_rewrite_endpoint()
    {
        add_rewrite_endpoint(
                CMA_AnswerThread::getAnswersPermalink()
                , EP_PERMALINK | EP_PAGES
        );
    }

    public static function shortcode_my_answers($atts)
    {
        if(!is_user_logged_in())
        {
            return '';
        }

        $limit = (isset($atts['limit'])) ? $atts['limit'] : -1;

        $answers = CMA_AnswerThread::getAnswersByUser(get_current_user_id(), $limit);
        return CMA_BaseController::_loadView('answer/widget/my-answers', compact('answers'));
    }

    public static function shortcode_my_questions($atts, $widget = false)
    {
        if(!is_user_logged_in())
        {
            return '';
        }

        if(!is_array($atts))
        {
            $atts = array();
        }

        $atts['limit']          = (isset($atts['limit'])) ? $atts['limit'] : -1;
        $atts['author']         = get_current_user_id();
        $atts['user_questions'] = true;
        $atts['statusinfo']     = true;

        return self::general_shortcode($atts, $widget);
//        $questions = CMA_AnswerThread::getQuestionsByUser(get_current_user_id(), $limit);
//        return CMA_BaseController::_loadView('answer/widget/my-questions', compact('questions'));
    }

    public static function shortcode_questions($atts, $widget = false)
    {
        return self::general_shortcode($atts, $widget);
    }

    protected static function general_shortcode($atts, $widget = false)
    {
        $atts = is_array($atts) ? $atts : array();
        $atts = self::sanitize_array($atts, array(
                    'limit' => array('int', 5),
                    'cat' => array('*', null),
                    'author' => array('int', null),
                    'sort' => array('string', 'newest'),
                    'tiny' => array('bool', false),
                    'form' => array('bool', false),
                    'displaycategories' => array('bool', false),
                    'pagination' => array('bool', true),
                    'hidequestions' => array('bool', false),
                    'search' => array('bool', true),
                    'votes' => array('bool', true),
                    'views' => array('bool', true),
                    'answers' => array('bool', true),
                    'updated' => array('bool', true),
                    'authorinfo' => array('bool', true),
                    'statusinfo' => array('bool', false),
        ));

        if($atts['tiny'])
        {
            $atts['pagination'] = false;
        }

        $search = esc_attr(get_query_var('search'));
        $paged  = esc_attr(get_query_var('paged'));

        $questionsArgs = array(
            'post_type' => CMA_AnswerThread::POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => $atts['limit'],
            'paged' => $paged,
            'orderby' => $atts['sort'],
            'order' => $atts['order'],
            'fields' => 'ids',
            'widget' => true,
            'tag' => isset($_GET["cmatag"]) ? $_GET["cmatag"] : '',
            'search' => $search
        );

        if($atts['user_questions'])
        {
            $questionsArgs['user_questions'] = $atts['user_questions'];
        }

        if(!empty($atts['author']))
        {
            $questionsArgs['author'] = $atts['author'];
        }

        if(!empty($atts['cat']))
        {
            $questionsArgs['tax_query'] = array(
                array(
                    'taxonomy' => CMA_AnswerThread::CAT_TAXONOMY,
                    'field' => 'slug',
                    'terms' => $atts['cat']
                )
            );

            $catId = get_term_by('slug', $atts['cat'], CMA_AnswerThread::CAT_TAXONOMY)->term_id;
        }

        $q           = CMA_AnswerThread::customOrder(new WP_Query($questionsArgs), $atts['sort']);
        $questions   = array_map(array('CMA_AnswerThread', 'getInstance'), $q->get_posts());
        $maxNumPages = $q->max_num_pages;
        $paged       = $q->query_vars['paged'];

        $displayOptions = array(
            'hideQuestions' => $atts['hideQuestions'],
            'tags' => !$atts['tiny'],
            'pagination' => !$atts['tiny'] && $atts['pagination'],
            'form' => $atts['form'],
            'categories' => $atts['categories'],
            'search' => $atts['search'],
            'votes' => $atts['votes'],
            'views' => $atts['views'],
            'answers' => $atts['answers'],
            'updated' => $atts['updated'],
            'authorinfo' => $atts['authorinfo'],
            'statusinfo' => $atts['statusinfo'],
        );

        return CMA_BaseController::_loadView('answer/widget/questions', array_merge($atts, compact('displayOptions', 'questions', 'catId', 'maxNumPages', 'paged', 'widget', 'search')));
    }

    /**
     * Saninitize array, convert types and filter keys
     * @param array $arr array to be sanitized
     * @param array $descriptors array of descriptors for <code>$arr</code> fields
     * @return array
     * @throws InvalidArgumentException
     */
    protected static function sanitize_array(array $arr, array $descriptors)
    {
        static $mappers = null;

        if($mappers === null)
        {
            $boolval = function_exists('boolval') ? 'boolval' : create_function('$b', 'return (boolean) $b;');
            $mappers = array(
                'integer' => 'intval',
                'int' => 'intval',
                'double' => 'doubleval',
                'float' => 'doubleval',
                'string' => 'strval',
                'trim' => 'trim',
                'boolean' => $boolval,
                'bool' => $boolval
            );
        }

        $result = array();

        foreach($descriptors as $key => $desc)
        {
            list($type, $default) = is_array($desc) ? $desc : array((string) $desc, null);

            if($type !== '*' && !array_key_exists($type, $mappers))
            {
                throw new InvalidArgumentException();
            }

            if(array_key_exists($key, $arr))
            {
                if($type === '*')
                {
                    $result[$key] = $arr[$key];
                }
                else
                {
                    $result[$key] = call_user_func($mappers[$type], $arr[$key]);
                }
            }
            else
            {
                $result[$key] = $default;
            }
        }

        return $result;
    }

}