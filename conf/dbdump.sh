#!/bin/sh
MY_USERNAME=kaoma
MY_DBNAME=kaoma

mysqldump --add-drop-table -h localhost -u $MY_USERNAME -p $MY_DBNAME wp_weirdmeetup_commentmeta wp_weirdmeetup_comments    wp_weirdmeetup_links  wp_weirdmeetup_options wp_weirdmeetup_pluginSL_shorturl wp_weirdmeetup_postmeta    wp_weirdmeetup_posts  wp_weirdmeetup_term_relationships    wp_weirdmeetup_term_taxonomy    wp_weirdmeetup_terms  wp_weirdmeetup_usermeta    wp_weirdmeetup_w3tc_cdn_queue   wp_wiki_subscriptions  | bzip2 -c > weirdmeetup.db.sql.bz2

# excluded _users table for security reason
#wp_weirdmeetup_users  
