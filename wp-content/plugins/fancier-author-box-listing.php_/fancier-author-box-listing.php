<?php
/**
 * @package Fancier Author Box Listing Shortcode
 * @version 0.1
 */
/*
Plugin Name: Fancier Author Box Listing Shortcode (dev)
Description: fancier author box list plugin
Author: haruair
Version: 0.1
Author URI: http://haruair.com/
*/

function ts_fab_list_func($atts) {

    $result = '<div class="author-list">';

    $default_atts = array(
        'roles' => 'administrator',
        'hiddenusers' => 'weirdmeetup',
    );

    extract( shortcode_atts( $default_atts, $atts ) );

    $hiddenusers = explode(",", $hiddenusers);

    $user_query = new WP_User_Query( array( 'role' => $roles ) );
    $users = $user_query->get_results();

    foreach ($users as $user) {
        if( ! in_array($user->user_login, $hiddenusers) ){
            $result .= "<div class='author-item'>";

            $result .= '<div class="author-social-links">';
            
                // Twitter
                if( get_user_meta( $user->ID, 'ts_fab_twitter', true) )
                    $result .= '<a href="http://twitter.com/' . get_user_meta( $user->ID, 'ts_fab_twitter', true ) . '" title="Twitter"><img src="' . plugins_url( '/fancier-author-box/images/twitter.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Twitter profile', 'ts-fab' ) . '" /></a>';
                
                // Facebook
                if( get_user_meta( $user->ID, 'ts_fab_facebook', true) )
                    $result .= '<a href="http://facebook.com/' . get_user_meta( $user->ID, 'ts_fab_facebook', true ) . '" title="Facebook"><img src="' . plugins_url( '/fancier-author-box/images/facebook.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Facebook profile', 'ts-fab' ) . '" /></a>';
                
                // Google+
                if( get_user_meta( $user->ID, 'ts_fab_googleplus', true) )
                    $result .= '<a href="http://plus.google.com/' . get_user_meta( $user->ID, 'ts_fab_googleplus', true ) . '?rel=author" title="Google+"><img src="' . plugins_url( '/fancier-author-box/images/googleplus.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Google+ profile', 'ts-fab' ) . '" /></a>';
                
                // LinkedIn
                if( get_user_meta( $user->ID, 'ts_fab_linkedin', true) )
                    $result .= '<a href="http://www.linkedin.com/in/' . get_user_meta( $user->ID, 'ts_fab_linkedin', true ) . '" title="LinkedIn"><img src="' . plugins_url( '/fancier-author-box/images/linkedin.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My LinkedIn profile', 'ts-fab' ) . '" /></a>';

                // Instagram
                if( get_user_meta( $user->ID, 'ts_fab_instagram', true) )
                    $result .= '<a href="http://instagram.com/' . get_user_meta( $user->ID, 'ts_fab_instagram', true ) . '" title="Instagram"><img src="' . plugins_url( '/fancier-author-box/images/instagram.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Instagram profile', 'ts-fab' ) . '" /></a>';

                // Flickr
                if( get_user_meta( $user->ID, 'ts_fab_flickr', true) )
                    $result .= '<a href="http://www.flickr.com/photos/' . get_user_meta( $user->ID, 'ts_fab_flickr', true ) . '" title="Flickr"><img src="' . plugins_url( '/fancier-author-box/images/flickr.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Flickr profile', 'ts-fab' ) . '" /></a>';

                // Pinterest
                if( get_user_meta( $user->ID, 'ts_fab_pinterest', true) )
                    $result .= '<a href="http://pinterest.com/' . get_user_meta( $user->ID, 'ts_fab_pinterest', true ) . '" title="Pinterest"><img src="' . plugins_url( '/fancier-author-box/images/pinterest.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Pinterest profile', 'ts-fab' ) . '" /></a>';

                // Tumblr
                if( get_user_meta( $user->ID, 'ts_fab_tumblr', true) )
                    $result .= '<a href="http://' . get_user_meta( $user->ID, 'ts_fab_tumblr', true ) . '.tumblr.com/" title="Tumblr"><img src="' . plugins_url( '/fancier-author-box/images/tumblr.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Tumblr blog', 'ts-fab' ) . '" /></a>';

                // YouTube
                if( get_user_meta( $user->ID, 'ts_fab_youtube', true) )
                    $result .= '<a href="http://www.youtube.com/user/' . get_user_meta( $user->ID, 'ts_fab_youtube', true ) . '" title="YouTube"><img src="' . plugins_url( '/fancier-author-box/images/youtube.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My YouTube channel', 'ts-fab' ) . '" /></a>';

                // Vimeo
                if( get_user_meta( $user->ID, 'ts_fab_vimeo', true) )
                    $result .= '<a href="http://vimeo.com/' . get_user_meta( $user->ID, 'ts_fab_vimeo', true ) . '" title="Vimeo"><img src="' . plugins_url( '/fancier-author-box/images/vimeo.png', dirname(__FILE__) ) . '" width="24" height="24" alt="' . __( 'My Vimeo channel', 'ts-fab' ) . '" /></a>';
            
            $result .= '</div>';
            
            if( $user->user_url ) {
                $result .= '<h4><a href="' . $user->user_url . '">' . $user->display_name . '</a></h4>';
            } else {
                $result .= '<h4>' . $user->display_name . '</h4>';
            }
            
            if( get_user_meta( $user->ID, 'ts_fab_position', true) ) {
                $result .= '<div class="ts-fab-description"><span>' . get_user_meta( $user->ID, 'ts_fab_position', true) . '</span>';
                
                if( get_user_meta( $user->ID, 'ts_fab_company', true) ) {
                    if( get_user_meta( $user->ID, 'ts_fab_company_url', true) ) {
                        $result .= ' ' . __( 'at', 'ts-fab' ) . ' <a href="' . esc_url( get_user_meta( $user->ID, 'ts_fab_company_url', true) ) . '">';
                            $result .= '<span>' . get_user_meta( $user->ID, 'ts_fab_company', true) . '</span>';
                        $result .= '</a>';
                    } else {
                        $result .= ' ' . __( 'at', 'ts-fab' ) . ' <span>' . get_user_meta( $user->ID, 'ts_fab_company', true) . '</span>';
                    }
                }
                
                $result .= '</div>';
            }

            $result .= "<div class='author-item-avatar'>" . get_avatar( $user->ID, 80 ) . "</div>";
            $result .= "<div class='author-item-desc'>" . $user->user_description . "</div>";
            $result .= "</div>";
        }
    }

    $result .= '</div>';
    return $result;
}

add_shortcode( 'ts_fab_list', 'ts_fab_list_func' );