<?php
/**
 * Weird Meetup custom post type for feed registration
 *
 * @package Weird Meetup
 * @subpackage Feed Registration
 * @version 0.1
 */
/*
Plugin Name: Weird Meetup Feed Registration
Description: 사용자의 피드를 등록받는 Feed Registration 플러그인
Author: haruair
Version: 0.1
Author URI: http://haruair.com/
*/

$wm_feed_registration = new WM_Feed_Registration();  

class WM_Feed_Registration {

    var $custom_meta_fields = array(
        array('id' => 'blog_url'),
        array('id' => 'rss_url'),
        array('id' => 'registration_status'),
        array('id' => 'registration_comment')
    );

    /**
     * constructor
     */
    function __construct() {
        add_action( 'init', array( & $this, 'create_post_type') );
        add_action( 'admin_init', array( & $this, 'init_meta_box') );
        add_action( 'admin_head', array( & $this, 'add_stylesheet') );
        add_action( 'admin_head', array( & $this, 'add_script') );
        add_action( 'save_post', array( & $this, 'save_custom_meta') );
        add_action( 'add_meta_boxes', array( & $this, 'add_custom_meta_box') );
        add_filter( 'post_row_actions', array( & $this, 'add_feed_row_action'), 10, 2 );

        add_action( 'pre_get_posts', array( & $this, 'filter_post_lists' ) );

        add_filter( 'manage_feed_posts_columns', array( & $this, 'init_status_column'), 100 );
        add_filter( 'manage_feed_posts_custom_column', array( & $this, 'init_status_column_data') );
        add_filter( 'views_edit-feed', array( & $this, 'remove_feed_counts') );

    }

    /**
     * Create custom post type for feed
     */
    function create_post_type() {
        $labels = array(
            'name'               => 'Feeds',
            'singular_name'      => 'Feed',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Feed',
            'edit_item'          => 'Edit Feed',
            'new_item'           => 'New Feed',
            'all_items'          => 'All Feeds',
            'view_item'          => 'View Feed',
            'search_items'       => 'Search Feeds',
            'not_found'          => 'No feeds found',
            'not_found_in_trash' => 'No feeds found in Trash',
            'parent_item_colon'  => '',
            'menu_name'          => 'Feeds'
        );

        register_post_type( 'feed',
            array(
                'labels' => $labels,
                'public' => true,
                'with_front' => false,
                'has_archive' => false,
                'capability_type' => 'post',
                'show_in_menu' => true,
                'show_ui' => true,
                'menu_position' => 50,
                'capabilities' => array(
                                'edit_post'              => 'edit_feed',
                                'read_post'              => 'read_feed',
                                'delete_post'            => 'delete_feed',
                                'edit_posts'             => 'edit_feeds',
                                'edit_others_posts'      => 'edit_others_feeds',
                                'publish_posts'          => 'publish_feeds',
                                'read_private_posts'     => 'read_private_feeds',
                                'delete_posts'           => 'delete_feeds',
                                'delete_private_posts'   => 'delete_private_feeds',
                                'delete_published_posts' => 'delete_published_feeds',
                                'delete_others_posts'    => 'delete_others_feeds',
                                'edit_private_posts'     => 'edit_private_feeds',
                                'edit_published_posts'   => 'edit_published_feeds',
                            ),
                'map_meta_cap'    => true,
                'supports'        => array( 'title', 'author' )

            )
        );
    }

    /**
     * Remove meta box in feed editor page
     */
    function init_meta_box() {
        remove_post_type_support('feed', 'title');
        remove_post_type_support('feed', 'author');

	/*
	 * FIXME: Please fix the following remove_meta_box() calls or remove tha cause php warning printed in the page and then make the page encoding ineffective
	  - simon
        remove_meta_box('slugdiv','feed');
        remove_meta_box('sharing_meta','feed');
        remove_meta_box('wpseo_meta','feed');
	*/
    }

    /**
     * Stylesheet for feed custom post type
     *
     * @todo move to external file
     */
    function add_stylesheet() {
        ?>
        <style>
            .post-type-feed #preview-action,
            .post-type-feed #minor-publishing {
                display: none !important;
            }
        </style>
        <?php
    }

    function add_script() {
        ?>
        <script>
        jQuery(function(){
            jQuery.each(['#sharing_meta-hide', '#wpseo_meta-hide'], function(key,value){
                if(jQuery(value).prop("checked"))jQuery(value).click();
            });
        });
        </script>
        <?php
    }

    /**
     * feed custom meta save in wordpress
     */
    function save_custom_meta() {
        global $post;
        $post_id = $post->ID;
        if($post->post_type == 'feed'){
            // loop through fields and save the data
            foreach ($this->custom_meta_fields as $field) {
                $old = get_post_meta($post_id, $field['id'], true);
                $new = $_POST[$field['id']];
                if (!current_user_can('administrator') && ($field['id'] == 'registration_status' || $field['id'] == 'registration_comment') ){
                    delete_post_meta($post_id, $field['id'], $old);
                }
                else if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
                } elseif ('' == $new && $old) {
                    delete_post_meta($post_id, $field['id'], $old);
                }
            } // end foreach
        }
    }

    function add_custom_meta_box() {
        global $post;
        add_meta_box( 'custom_rss', 'RSS feed Information', array( & $this, 'custom_meta_box_rss'), 'feed', 'normal');
        if (current_user_can('administrator') || $post->filter != 'raw'){
            add_meta_box( 'custom_userinfo', 'User Information', array( & $this, 'custom_meta_box_userinfo'), 'feed', 'normal');
            add_meta_box( 'custom_registration', 'Registration Status', array( & $this, 'custom_meta_box_registration'), 'feed', 'normal');
        }
    }

    /**
     * add meta box of registration status in edit page
     */
    function custom_meta_box_userinfo() {
        if (current_user_can('administrator')){
            $this->custom_meta_box_userinfo_admin();
        }
    }

    function custom_meta_box_userinfo_admin() {
        global $post;
        $user_id = $post->post_author;
        $user = get_user_by('id', $user_id);
        $nickname = get_user_meta($user_id, 'nickname');
        $description = get_user_meta($user_id, 'description');
        $twitter = get_user_meta($user_id, 'ts_fab_twitter');

        ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th>Username</th>
                    <td>
                        <?php echo $nickname[0];?>
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <?php echo $description[0] ? $description[0] : '<em>None</em>';?>
                    </td>
                </tr>
                <tr>
                    <th>Twitter</th>
                    <td>
                        <?php if($twitter[0]){?>
                            <a href="http://twitter.com/<?php echo $twitter[0];?>" target="_blank"><?php echo $twitter[0];?></a>
                        <?php }else {?>
                            <em>None</em>
                        <?php }?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    /**
     * add meta box of registration status in edit page
     */
    function custom_meta_box_registration() {
        if (current_user_can('administrator')){
            $this->custom_meta_box_registration_admin();
        }else{
            $this->custom_meta_box_registration_user();
        }
    }

    function custom_meta_box_registration_admin() {
        global $post;
        ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="registration_status">Status</label></th>
                    <td>
                        <select name="registration_status" id="registration_status">
                            <option <?php if(get_post_meta($post->ID, 'registration_status', true) == ''){ echo "selected "; }?>value="">등록 대기중</option>
                            <option <?php if(get_post_meta($post->ID, 'registration_status', true) == '자료 보충 필요'){ echo "selected "; }?>value="자료 보충 필요">자료 보충 필요</option>
                            <option <?php if(get_post_meta($post->ID, 'registration_status', true) == '등록 완료'){ echo "selected "; }?>value="등록 완료">등록 완료</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="registration_comment">Comment</label></th>
                    <td>
                        <textarea name="registration_comment" id="registration_comment" rows="5" cols="30" class="widefat"><?php echo get_post_meta($post->ID, 'registration_comment', true);?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php
        do_action('wm_after_custom_meta_box_registration_admin', & $this);
    }

    function custom_meta_box_registration_user() {
        global $post;
    ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="registration_status">Status</label></th>
                    <td>
                        <?php
                            $status = get_post_meta($post->ID, 'registration_status', true);
                            if($status){
                                echo $status;
                            }else{
                                $rss = get_post_meta($post->ID, 'rss_url', true);
                                if(!$rss)
                                    echo "<strong>RSS Feed 주소 확인</strong>";
                                else
                                    echo "<strong>등록 대기중</strong>";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="registration_comment">Comment</label></th>
                    <td>
                        <?php
                            $comment = get_post_meta($post->ID, 'registration_comment', true);
                            if($comment){
                                echo $comment;
                            }else{
                                $rss = get_post_meta($post->ID, 'rss_url', true);
                                if(!$rss)
                                    echo "해당 블로그를 피드에 등록하기 위해 RSS feed 항목이 필요합니다.";
                                else
                                    echo "등록 신청이 완료되었습니다. :)";
                            }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php
        do_action('wm_after_custom_meta_box_registration_user', & $this);
    }

    function custom_meta_box_rss(){
        global $post;
        ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="blog_url">Blog URL</label></th>
                    <td class="blog-url-field"><input type="text" name="blog_url" id="blog_url" value="<?php echo get_post_meta($post->ID, 'blog_url', true);?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="post_title">Blog Title</label></th>
                    <td><input type="text" name="post_title" id="post_title" value="<?php echo $post->post_title;?>" class="regular-text" autocomplete="off"></td>
                </tr>
                <tr>
                    <th><label for="post_content">Blog Description</label></th>
                    <td><textarea name="post_content" id="post_content" rows="5" cols="30" class="widefat"><?php echo $post->post_content;?></textarea></td>
                </tr>
                <tr>
                    <th><label for="rss_url">RSS feed</label></th>
                    <td class="rss-url-field"><input type="text" name="rss_url" id="rss_url" value="<?php echo get_post_meta($post->ID, 'rss_url', true);?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>
        <?php
        do_action('wm_after_custom_meta_box_rss', & $this);
    }

    function add_feed_row_action($actions, $post) {
        if($post->post_type == 'feed'){
            unset($actions['view']);
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }

    function init_status_column($columns) {
        $columns = array(
                    'cb' => '<input type="checkbox" />',
                    'title' => 'Title',
                    'author' => 'Register',
                    'status' => 'Status',
                    'date' => 'Date',
                );
        return $columns;
    }

    function init_status_column_data($name) {
        global $post;
        switch ($name) {
            case 'status':
                echo $this->get_post_status($post->ID);
        }
    }

    function get_post_status($post_id){

        $status_text = get_post_meta($post_id, 'registration_status', true);
        if($status_text){
            $result = '<strong>' . get_post_meta($post_id, 'registration_status', true) .'</strong><br />';
            $result .= get_post_meta($post_id, 'registration_comment', true);
        }else{

            $rss = get_post_meta($post_id, 'rss_url', true);
            if(!$rss)
                $result = "<strong>RSS Feed 주소 확인</strong><br />해당 블로그를 피드에 등록하기 위해 RSS feed 항목이 필요합니다.";
            else
                $result = "<strong>등록 대기중</strong><br />등록 신청이 완료되었습니다. :)";
        }
        return $result;
    }

    function filter_post_lists( $wp_query_obj ) 
    {
        global $current_user;

        // Front end, do nothing
        if( !is_admin() )
            return;

        // If the user is not administrator, filter the post listing
        if( !current_user_can( 'edit_private_feeds' ) )
            $wp_query_obj->set('author', $current_user->ID );
    }

    function remove_feed_counts($views){
        if( ! current_user_can( 'edit_private_feeds' ) )
            return '';
        else return $views;
    }

    static function activation() {

        $roles = array(
            get_role( 'subscriber' ),
            get_role( 'contributor' ),
            get_role( 'editor' ),
            get_role( 'author' ),
        );

        $caps = array('edit_feed', 'delete_published_feeds', 'edit_published_feeds','read_feed', 'delete_feed', 'publish_feed', 'edit_feeds', 'read_feeds', 'delete_feeds', 'publish_feeds');
        
        foreach($roles as $role){
            foreach($caps as $cap){
                $role->add_cap($cap);
            }
        }

        $role_admin = get_role( 'administrator' );
        $caps_admin = array('edit_feed', 'read_feed', 'delete_feed', 'edit_feeds', 'edit_others_feeds', 'publish_feeds', 'read_private_feeds', 'delete_feeds', 'delete_private_feeds', 'delete_published_feeds', 'delete_others_feeds', 'edit_private_feeds', 'edit_published_feeds',);
        foreach($caps_admin as $cap){
            $role_admin->add_cap($cap);
        }

    }
}


register_activation_hook( __FILE__, array( 'WM_Feed_Registration', 'activation' ) );
