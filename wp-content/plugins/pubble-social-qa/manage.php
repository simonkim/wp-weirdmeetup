<?php

require(ABSPATH . 'wp-includes/version.php');

if ( !current_user_can('moderate_comments') ) {
    die();
}

// HACK: For old versions of WordPress
if ( !function_exists('wp_nonce_field') ) {
    function wp_nonce_field() {}
}


function pb_i($message) {
		return $message;
	}


// Clean-up POST parameters.
foreach ( array('pb_appID', 'pb_layout','pb_page_qa','pb_sameqs') as $key ) {
    if ( isset($_POST[$key]) ) { $_POST[$key] = strip_tags($_POST[$key]); }
}


echo($_POST['pb_sameqs']);
echo(get_option('pb_sameqs'));

// Handle advanced options.
if ( isset($_POST['pb_page_qa']) || isset($_POST['pb_sameqs'])  || isset($_POST['pb_appID']) || isset($_POST['pb_layout']) ) {

    update_option('pb_appID', $_POST['pb_appID']);
    update_option('pb_layout', $_POST['pb_layout']);
	update_option('pb_ssoKey', $_POST['pb_ssoKey']);
	
if($_POST['pb_page_qa'] == 'page') 
{
   update_option('pb_page_qa', $_POST['pb_page_qa']);
}
else
{
   update_option('pb_page_qa', '');
}	 


if($_POST['pb_sameqs'] == 'true') 
{

   update_option('pb_sameqs', $_POST['pb_sameqs']);
}
else
{
   update_option('pb_sameqs', '');
}	

    echo('<script>alert("Your settings have been changed");</script>');
}

if (isset($_GET['active'])) {
    update_option('pubble_active', ($_GET['active'] == '1' ? '1' : '0'));
}

$show_advanced = (isset($_GET['t']) && $_GET['t'] == 'adv');

?>
<div class="wrap" id="pb-wrap">
 
  
<?php
    $pb_appID = get_option('pb_appID');
    $pb_layout = strtolower(get_option('pb_layout'));
	$pb_page_qa= get_option('pb_page_qa');
	$pb_sameqs=get_option('pb_sameqs');
	$pb_ssoKey = get_option('pb_ssoKey');
?>
    <!-- Settings -->
    <div id="pb-advanced" class="pb-content pb-advanced"<?php if (!$show_advanced) echo ' style="display:block;"'; ?>>
        <h2><?php echo 'Settings'; ?></h2>
        <?php
        if (get_option('pubble_active') === '0') {
            echo pb_i('<p class="status">Pubble QA are currently disabled. (<a href="?page=pubble&amp;active=1">Enable</a>)</p>');
        } else {
            echo pb_i('<p class="status">Pubble QA are currently enabled. (<a href="?page=pubble&amp;active=0">Disable</a>)</p>');
        }
        ?>

        <form method="POST">
        <?php wp_nonce_field('pb-advanced'); ?>
        <table class="form-table">
            
            <tr>
                <th scope="row" valign="top"><?php echo pb_i('<h3>General</h3>'); ?></th>
            </tr>
            <tr>
                <th scope="row" valign="top"><?php echo pb_i('Pubble appID'); ?></th>
                <td>
                    <input type="text" name="pb_appID" value="<?php echo esc_attr($pb_appID); ?>"/>
                    <br />
                    <?php echo pb_i('Please input your pubble APPID'); ?>
                </td>
            </tr>

            <tr>
                <th scope="row" valign="top"><?php echo pb_i('Manual Q&A'); ?></th>
                <td>

				<input type="checkbox" name="pb_page_qa" value="page" <?php if('page'==$pb_page_qa){echo 'checked';}?>>

                    <br />
                     <?php echo pb_i('If selected, You need to add Q&A to selected pages manually. In default, Q&A enabled wherever you enable commenting on your site '); ?>
                </td>
            </tr>

			 <tr>
                <th scope="row" valign="top"><?php echo pb_i('QA layout'); ?></th>
                <td>
                    <select name="pb_layout" tabindex="1" class="">
                        <option value="embed" <?php if('modalbox'!==$pb_layout){echo 'selected';}?>><?php echo pb_i('Embed'); ?></option>
                        <option value="modalbox" <?php if('modalbox'==$pb_layout){echo 'selected';}?>><?php echo pb_i('Modalbox'); ?></option>
                    </select>
                    <br />
                     <?php echo pb_i('Please Choose your QA style'); ?>
                </td>
            </tr>

			<tr>
                <th scope="row" valign="top"><?php echo pb_i('List same question across site'); ?></th>
                <td><input type="checkbox" name="pb_sameqs" value="true" <?php if('true'==$pb_sameqs){echo 'checked';}?>>
					
                    <br />  <?php echo pb_i('If selected, QA widget will list same questions on all site pages. Non-Manual Q&A option.'); ?>
                     
                </td>
            </tr>


			<tr>
                <th scope="row" valign="top"><?php echo pb_i('SSO Key'); ?></th>
                <td>
					<input type="text" name="pb_ssoKey" value="<?php echo esc_attr($pb_ssoKey); ?>"/>
                    <br /><?php echo pb_i('SSO Key, provided by pubble team'); ?>
                   
                </td>
            </tr>


        </table>
        <p class="submit" style="text-align: left">
            <input name="submit" type="submit" value="Save" class="button-primary button" tabindex="4">
        </p>
        </form>

<div style="clear:both"></div>
<textarea style="width:90%; height:200px;">
PHP Version: <?php echo phpversion(); ?>  \n
WP Version: <?php echo $wp_version; ?> \n
Active Theme: <?php $theme = get_theme(get_current_theme()); echo $theme['Name'].' '.$theme['Version']; ?> 


</textarea><br/>
    </div>
</div>
