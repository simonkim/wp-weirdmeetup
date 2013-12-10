<?php

/**
 * File contains Authentication controller
 * @package Library
 * @subpackage Controllers
 */
require_once CMA_PATH . '/lib/helpers/Opauth/Opauth.php';

/**
 * Authentication controller
 *
 * @author SP
 * @version 0.1.0
 * @copyright Copyright (c) 2013, REC
 * @package Library
 * @subpackage Controllers
 */
class CMA_AuthController extends CMA_BaseController
{

    protected static $_authInstance = null;
    protected static $_redirectUri;

    public static function initialize()
    {
        add_filter('CMA_admin_settings', array(get_class(), 'addAdminSettings'));
    }

    public static function facebookHeader()
    {
        if (self::isProviderConfigured('facebook')) {
            self::_getAuthInstance();
            exit;
        }
    }

    public static function linkedinHeader()
    {
        self::_getAuthInstance();
        exit;
    }

    public static function googleHeader()
    {
        self::_getAuthInstance();
        exit;
    }

    public static function callbackHeader()
    {
        $auth = self::_getAuthInstance(false);
        /**
         * Fetch auth response, based on transport configuration for callback
         */
        $response = null;

        switch ($auth->env['callback_transport']) {
            case 'session':
                session_start();
                $response = $_SESSION['opauth'];
                unset($_SESSION['opauth']);
                break;
            case 'post':
                $response = unserialize(base64_decode($_POST['opauth']));
                break;
            case 'get':
                $response = unserialize(base64_decode($_GET['opauth']));
                break;
            default:
                die('<strong style="color: red;">Error: </strong>Unsupported callback_transport.' . "<br>\n");
                break;
        }
        /**
         * Check if it's an error callback
         */
        if (array_key_exists('error', $response)) {
            var_dump($response);
            die('<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.' . "<br>\n");
        }

        /**
         * Auth response validation
         * 
         * To validate that the auth response received is unaltered, especially auth response that 
         * is sent through GET or POST.
         */ else {
            if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
                die('<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.' . "<br>\n");
            } elseif (!$auth->validate(sha1(print_r($response['auth'], true)),
                            $response['timestamp'], $response['signature'],
                            $reason)) {
                die('<strong style="color: red;">Invalid auth response: </strong>' . $reason . ".<br>\n");
            } else {
                /**
                 * It's all good. Go ahead with your application-specific authentication logic
                 */
                self::_authenticate($response['auth']);
                if (!empty($_SESSION['cma']['redirectUri']))
                        wp_safe_redirect($_SESSION['cma']['redirectUri'], 303);
                else wp_redirect(home_url(), 303);
                unset($_SESSION['cma']['redirectUri']);
                exit;
            }
        }
    }

    protected static function _authenticate($auth = array())
    {
        global $wpdb;
        $provider = $auth['provider'];
        $uid = $auth['uid'];
        $email = $auth['info']['email'];
        $name = $auth['info']['name'];
        $wp_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '_cma_uid_%s' AND meta_value = %s",
                        strtolower($provider), $uid));

        if (empty($wp_user_id)) {
            // Look for a user with the same email
            $wp_user_obj = get_user_by('email', $email);

            // get the userid from the fb email if the query failed
            $wp_user_id = $wp_user_obj->ID;
        }
        if (!empty($wp_user_id)) {
            // We already have this user in the database
        } else {
            // Oh no, this user is not registered yet, we should create him an account
            $wp_user_id = wp_create_user($email, wp_generate_password(), $email);
        }
        if (!empty($wp_user_id) && !($wp_user_id instanceof WP_Error)) {
            $userinfo = get_userdata($wp_user_id);
            update_user_meta($wp_user_id, '_cma_uid_' . strtolower($provider),
                    $uid);
            wp_update_user(array(
                'ID' => $wp_user_id,
                'display_name' => $name,
                'user_nicename' => sanitize_title_with_dashes(remove_accents($name))
            ));
            update_user_meta($wp_user_id, '_cma_social_url', $auth['info']['urls'][strtolower($provider)]);
            update_user_meta($wp_user_id, '_cma_social_'.strtolower($provider).'_url', $auth['info']['urls'][strtolower($provider)]);
            wp_clear_auth_cookie();
            wp_set_auth_cookie($wp_user_id);
        }
    }

    protected static function _getAuthInstance($run = true)
    {
        if (empty(self::$_authInstance)) {
            self::$_redirectUri = !empty($_SESSION['cma']['redirectUri']) ? $_SESSION['cma']['redirectUri'] : self::_getParam('redirect');
            $_SESSION['cma']['redirectUri'] = self::$_redirectUri;
            self::$_authInstance = new Opauth(self::_getAuthConfig(), $run);
        }
        return self::$_authInstance;
    }

    protected static function _getAuthConfig()
    {
        $config = array(
            'path' => trailingslashit(parse_url(self::getUrl('auth', 'index'),
                            PHP_URL_PATH)),
            'callback_url' => '{path}callback',
            'security_salt' => 'LDFmiilYf8Fyw5W10rx4Wiquwe675Tuy5vJidQKDx8pMJbmw28R1C4m',
//            'callback_transport' => 'get',
            'Strategy' => array(
                // Define strategies and their respective configs here
                //TODO: get this from settings
                'Facebook' => array(
                    'app_id' => get_option('_cma_fb_app_id', ''),
                    'app_secret' => get_option('_cma_fb_app_secret', ''),
                    'scope' => 'email'
                ),
                'Google' => array(
                    'client_id' => get_option('_cma_google_client_id', ''),
                    'client_secret' => get_option('_cma_google_client_secret',
                            '')
                ),
                'LinkedIn' => array(
                    'api_key' => get_option('_cma_linkedin_api_key', ''),
                    'secret_key' => get_option('_cma_linkedin_secret_key', ''),
                    'scope' => 'r_emailaddress',
                    'profile_fields' => array('id', 'first-name', 'last-name',
                        'formatted-name',
                        'email-address',
                        'public-profile-url'),
                )
            )
        );
        return $config;
    }

    public static function isProviderConfigured($provider)
    {
        $config = self::_getAuthConfig();
        switch (strtolower($provider)) {
            case 'facebook':
                $result = (!empty($config['Strategy']['Facebook']['app_id']) && !empty($config['Strategy']['Facebook']['app_secret']));
                break;
            case 'google':
                $result = (!empty($config['Strategy']['Google']['client_id']) && !empty($config['Strategy']['Google']['client_secret']));
                break;
            case 'linkedin':
                $result = (!empty($config['Strategy']['LinkedIn']['api_key']) && !empty($config['Strategy']['LinkedIn']['secret_key']));
                break;
        }
        return $result;
    }

    public static function addAdminSettings($params = array())
    {
        if (self::_isPost()) {
            if (isset($_POST['fb_app_id']) && isset($_POST['fb_app_secret'])) {
                update_option('_cma_fb_app_id', $_POST['fb_app_id']);
                update_option('_cma_fb_app_secret', $_POST['fb_app_secret']);
            }
            if (isset($_POST['google_client_id']) && isset($_POST['google_client_secret'])) {
                update_option('_cma_google_client_id',
                        $_POST['google_client_id']);
                update_option('_cma_google_client_secret',
                        $_POST['google_client_secret']);
            }
            if (isset($_POST['linkedin_api_key']) && isset($_POST['linkedin_secret_key'])) {
                update_option('_cma_linkedin_api_key',
                        $_POST['linkedin_api_key']);
                update_option('_cma_linkedin_secret_key',
                        $_POST['linkedin_secret_key']);
            }
        }
        $params['fb_app_id'] = get_option('_cma_fb_app_id', '');
        $params['fb_app_secret'] = get_option('_cma_fb_app_secret', '');
        $params['google_client_id'] = get_option('_cma_google_client_id', '');
        $params['google_client_secret'] = get_option('_cma_google_client_secret',
                '');
        $params['linkedin_api_key'] = get_option('_cma_linkedin_api_key', '');
        $params['linkedin_secret_key'] = get_option('_cma_linkedin_secret_key',
                '');
        return $params;
    }

}

?>
