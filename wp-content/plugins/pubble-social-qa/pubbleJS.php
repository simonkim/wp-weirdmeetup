<?php
/*
Plugin Name: Pubble Social QA plugin - 2.5.1
version: 2.5.1
Plugin URI: http://www.pubble.co
Description: The Pubble Social Q&A makes it simple and easy to build social interactions around your product or service.
Author: Pubble
Author URI: http://www.pubble.co/
*/

//wp-includes/load.php.



add_filter('comments_template', 'pb_comments_template');
add_shortcode('pubble','product_handler');
add_shortcode('pubblePage','page_handler');


function pb_options() {
    return array(
        'pb_active',
        'pb_appID',
        'pb_layout',
		'pb_page_qa',
		'pb_sameqs',
		'pb_sso'
    );
}

function pb_is_installed() {
    return get_option('pb_appID');
}

function pb_comments_open($open, $post_id=null) {
    global $EMBED;
    if ($EMBED) return false;
    return $open;
}
add_filter('comments_open', 'pb_comments_open');


function pb_add_pages() {
     add_submenu_page(
         'edit-comments.php',
         'Pubble',
         'Pubble',
         'moderate_comments',
         'pubble',
         'pb_manage'
     );
}
add_action('admin_menu', 'pb_add_pages', 10);


function pb_manage() {



   include_once(dirname(__FILE__) . '/manage.php');
 
}


//php add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function ); 


function getpara($text, $params=null) {
    if (!is_array($params))
    {
        $params = func_get_args();
        $params = array_slice($params, 1);
    }
    return vsprintf(__($text, 'pubble'), $params);
}


function pb_plugin_action_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        if (!pb_is_installed()) {
            $settings_link = '<a href="edit-comments.php?page=pubble">'.getpara('Configure').'</a>';
        } else {
            $settings_link = '<a href="edit-comments.php?page=pubble#adv">'.getpara('Settings').'</a>';    
        }
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'pb_plugin_action_links', 10, 2);


function pb_comments_template($content) {



 if ( !pb_is_installed()) {
        return $value;
  }

  if(get_option('pb_page_qa') !== 'page') 
{
    return dirname(__FILE__) . '/comments.php';

} 

}


/**
 * JSON ENCODE for PHP < 5.2.0
 * Checks if json_encode is not available and defines json_encode
 * to use php_json_encode in its stead
 * Works on iteratable objects as well - stdClass is iteratable, so all WP objects are gonna be iteratable
 */
if(!function_exists('cf_json_encode')) {
    function cf_json_encode($data) {
// json_encode is sending an application/x-javascript header on Joyent servers
// for some unknown reason.
//         if(function_exists('json_encode')) { return json_encode($data); }
//         else { return cfjson_encode($data); }
        return cfjson_encode($data);
    }

    function cfjson_encode_string($str) {
        if(is_bool($str)) {
            return $str ? 'true' : 'false';
        }

        return str_replace(
            array(
                '"'
                , '/'
                , "\n"
                , "\r"
            )
            , array(
                '\"'
                , '\/'
                , '\n'
                , '\r'
            )
            , $str
        );
    }

    function cfjson_encode($arr) {
        $json_str = '';
        if (is_array($arr)) {
            $pure_array = true;
            $array_length = count($arr);
            for ( $i = 0; $i < $array_length ; $i++) {
                if (!isset($arr[$i])) {
                    $pure_array = false;
                    break;
                }
            }
            if ($pure_array) {
                $json_str = '[';
                $temp = array();
                for ($i=0; $i < $array_length; $i++) {
                    $temp[] = sprintf("%s", cfjson_encode($arr[$i]));
                }
                $json_str .= implode(',', $temp);
                $json_str .="]";
            }
            else {
                $json_str = '{';
                $temp = array();
                foreach ($arr as $key => $value) {
                    $temp[] = sprintf("\"%s\":%s", $key, cfjson_encode($value));
                }
                $json_str .= implode(',', $temp);
                $json_str .= '}';
            }
        }
        else if (is_object($arr)) {
            $json_str = '{';
            $temp = array();
            foreach ($arr as $k => $v) {
                $temp[] = '"'.$k.'":'.cfjson_encode($v);
            }
            $json_str .= implode(',', $temp);
            $json_str .= '}';
        }
        else if (is_string($arr)) {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        else if (is_numeric($arr)) {
            $json_str = $arr;
        }
        else if (is_bool($arr)) {
            $json_str = $arr ? 'true' : 'false';
        }
        else {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        return $json_str;
    }
}


function pb_sso() {
    $key = get_option('pb_ssoKey');
    global $current_user;
    get_currentuserinfo();
    if ($current_user->ID) {
        $avatar_tag = get_avatar($current_user->ID);
        $avatar_data = array();
        preg_match('/(src)=((\'|")[^(\'|")]*(\'|"))/i', $avatar_tag, $avatar_data);
        $avatar = str_replace(array('"', "'"), '', $avatar_data[2]);


			if (current_user_can('manage_options')){

				$user_data = array(
					'name' => $current_user->display_name,
					'userID' => $current_user->ID,
					'avatar' => $avatar,
					'email' => $current_user->user_email,
					'userURL' => $current_user->user_url,
					'admin' => "true",
				);

			} else {
			
					$user_data = array(
					'name' => $current_user->display_name,
					'userID' => $current_user->ID,
					'avatar' => $avatar,
					'email' => $current_user->user_email,
					'userURL' => $current_user->user_url,
				);
			}
    }
    else {
        $user_data = array();
    }
    $user_data = base64_encode(cf_json_encode($user_data));
    $time = time();
    $hmac = pb_hmacsha1($user_data.' '.$time, $key);

    $payload = $user_data.' '.$hmac.' '.$time;

    return array('remote_auth_s2'=>$payload);
    
}


function pb_hmacsha1($data, $key) {
    $blocksize=64;
    $hashfunc='sha1';
    if (strlen($key)>$blocksize)
        $key=pack('H*', $hashfunc($key));
    $key=str_pad($key,$blocksize,chr(0x00));
    $ipad=str_repeat(chr(0x36),$blocksize);
    $opad=str_repeat(chr(0x5c),$blocksize);
    $hmac = pack(
                'H*',$hashfunc(
                    ($key^$opad).pack(
                        'H*',$hashfunc(
                            ($key^$ipad).$data
                        )
                    )
                )
            );
    return bin2hex($hmac);
}


function product_handler($args, $content=null){



 if(get_option('pb_page_qa') !== 'page') 
{
    return "";
} 





define("Pubble QA","1.0.5");


    global $current_user;
    get_currentuserinfo();

	

if(function_exists("is_comment")){ if(is_comment()){return "[js] is not allowed in comments";}}
	
/*Strip out the Poor Tags*/

$content =(htmlspecialchars($content,ENT_QUOTES)); $content = str_replace("&amp;#8217;","'",$content); $content = str_replace("&amp;#8216;","'",$content); $content = str_replace("&amp;#8242;","'",$content); $content = str_replace("&amp;#8220;","\"",$content); $content = str_replace("&amp;#8221;","\"",$content); $content = str_replace("&amp;#8243;","\"",$content); $content = str_replace("&amp;#039;","'",$content); $content = str_replace("&#039;","'",$content); $content = str_replace("&amp;#038;","&",$content); $content = str_replace("&amp;lt;br /&amp;gt;"," ", $content); $content = htmlspecialchars_decode($content); $content = str_replace("<br />"," ",$content); $content = str_replace("<p>"," ",$content); $content = str_replace("</p>"," ",$content); $content = str_replace("[br/]","<br/>",$content); $content = str_replace("\\[","&#91;",$content); $content = str_replace("\\]","&#93;",$content); 
	
//$content = str_replace("#theIdentifier#",the_ID(),$content); 

$content = str_replace("[","<",$content); $content = str_replace("]",">",$content); $content = str_replace("&#91;",'[',$content); $content = str_replace("&#93;",']',$content); $content = str_replace("&gt;",'>',$content); $content = str_replace("&lt;",'<',$content); 
	/*Buffer the output*/ ob_start();

 
// perform the search


$sso = pb_sso();



        if ($sso) {
            foreach ($sso as $k=>$v) {
                $key = $v;
            }
		};
        



if($content!==''){

				$position = strpos($content, ',');
				if($position=== false){

				echo  "<script src=\"https://www.pubble.co/resources/javascript/QAInit.js\"></script><script type='text/javascript'>var lpQA = lpQA({appID:\"".$content."\",genQ: \"false\",identifier:\"";
						echo the_ID();

				if ($current_user->ID && get_option('pb_ssoKey') !== '') {

					
					echo "\",auth_info:\"".$key."\"";

					if(get_option('pb_layout')!=='')echo ",layout:\"".get_option('pb_layout')."\"";
						

					echo "});</script>";
				}

				
				 else echo "\"});</script>";



			} else {

			


				$values=explode(",",$content);
				$appID=$values[0];
				$identifier=$values[1];

				echo  "<script src=\"http://www.pubble.co/resources/javascript/QAInit.js\"></script><script type='text/javascript'>var lpQA = lpQA({appID:\"".$appID."\",genQ: \"false\",identifier:\"".$identifier."\"";

				if ($current_user->ID && get_option('pb_ssoKey') !== '') {
				echo ",auth_info:\"".$key;
				echo "\"";
				}

				if(get_option('pb_layout')!=='')echo ",layout:\"".get_option('pb_layout')."\"";
				
				echo "});</script>";

				

			}


} else if(get_option('pb_appID') !== ''){


	echo  "<script src=\"https://www.pubble.co/resources/javascript/QAInit.js\"></script><script type='text/javascript'>var lpQA = lpQA({appID:\"". get_option('pb_appID')."\",genQ: \"false\",identifier:\"";
		echo the_ID();
	echo "\"";

    if ($current_user->ID && get_option('pb_ssoKey') !== '') {
	echo ",auth_info:\"".$key;
	echo "\"";
	}


	if(get_option('pb_layout')!=='')echo ",layout:\"".get_option('pb_layout')."\"";

	echo "});</script>";


}


	/*if($args['debug'] == 1){ $content =(htmlspecialchars($content,ENT_QUOTES));
	echo ("<pre>&lt;script type='text/javascript' &gt;".$content."&lt;script/&gt;</pre>"); }
	*/
	
	$returned = ob_get_clean();
	return $returned;
}


function page_handler($args, $content=null){
	
if(get_option('pb_page_qa') !== 'page') 
{
    return "";

} 


	define("Pubble QA","1.0.5");
	if(function_exists("is_comment")){ if(is_comment()){return "[js] is not allowed in comments";}}
	extract( shortcode_atts(array('debug' => 0), $args));  if($args['debug'] != 1){  error_reporting(E_NONE);  }	
	
	/*Strip out the Poor Tags*/$content =(htmlspecialchars($content,ENT_QUOTES)); $content = str_replace("&amp;#8217;","'",$content); $content = str_replace("&amp;#8216;","'",$content); $content = str_replace("&amp;#8242;","'",$content); $content = str_replace("&amp;#8220;","\"",$content); $content = str_replace("&amp;#8221;","\"",$content); $content = str_replace("&amp;#8243;","\"",$content); $content = str_replace("&amp;#039;","'",$content); $content = str_replace("&#039;","'",$content); $content = str_replace("&amp;#038;","&",$content); $content = str_replace("&amp;lt;br /&amp;gt;"," ", $content); $content = htmlspecialchars_decode($content); $content = str_replace("<br />"," ",$content); $content = str_replace("<p>"," ",$content); $content = str_replace("</p>"," ",$content); $content = str_replace("[br/]","<br/>",$content); $content = str_replace("\\[","&#91;",$content); $content = str_replace("\\]","&#93;",$content);
	$content = str_replace("[","<",$content); $content = str_replace("]",">",$content); $content = str_replace("&#91;",'[',$content); $content = str_replace("&#93;",']',$content); $content = str_replace("&gt;",'>',$content); $content = str_replace("&lt;",'<',$content); 
	/*Buffer the output*/ ob_start();


if($content!=='' ){

	echo  "<script src=\"https://www.pubble.co/resources/javascript/QAInit.js\"></script><script type='text/javascript'>var lpQA = lpQA({appID:\"".$content."\",genQ: \"true\",identifier:\"";
	echo the_ID();
	echo "\"});</script>";

} else if(get_option('pb_appID') !== ''){



	echo  "<script src=\"https://www.pubble.co/resources/javascript/QAInit.js\"></script><script type='text/javascript'>var lpQA = lpQA({appID:\"". get_option('pb_appID')."\",genQ: \"false\",identifier:\"";
		echo the_ID();
		echo "\"});</script>";
}



	/*if($args['debug'] == 1){ $content =(htmlspecialchars($content,ENT_QUOTES)); 
	echo ("<pre>&lt;script type='text/javascript' &gt;".$content."&lt;script/&gt;</pre>"); }
	*/
	$returned = ob_get_clean();
	return $returned;
}
?>