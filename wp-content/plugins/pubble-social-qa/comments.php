<?php 




$sso = pb_sso();

global $current_user;

        if ($sso) {
            foreach ($sso as $k=>$v) {
                $key = $v;
            }
		};


if(strlen(get_option('pb_appID'))>0): ?>

<script src="https://www.pubble.co/resources/javascript/QAInit.js"></script><script type='text/javascript'>
var lpQA = lpQA(
{appID:"<?php echo get_option('pb_appID') ?>",
genQ: "false",
<?php
if (get_option('pb_sameqs')=='true') {
    echo "identifier:\"entry".get_option('pb_appID')."\",";
	}else {
     echo "identifier:\"".$post->ID."\",";
}

if ($current_user->ID && get_option('pb_ssoKey') !== '') {
 echo "auth_info:\"".$key;
 echo "\",";
}

?>
layout:"<?php get_option('pb_layout'); ?>"
});
</script>

<?php endif; ?>
