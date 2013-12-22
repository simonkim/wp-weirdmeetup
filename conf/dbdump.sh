#!/bin/sh
MY_USERNAME=weirdmeetuplive
MY_DBNAME=weirdmeetuplive

mysqldump --add-drop-table -h weirdmeetuplive.db.12209546.hostedresource.com -u $MY_USERNAME -p $MY_DBNAME wp_weirdmeetup_commentmeta wp_weirdmeetup_comments    wp_weirdmeetup_links  wp_weirdmeetup_options wp_weirdmeetup_pluginSL_shorturl wp_weirdmeetup_postmeta    wp_weirdmeetup_posts  wp_weirdmeetup_term_relationships    wp_weirdmeetup_term_taxonomy    wp_weirdmeetup_terms  wp_weirdmeetup_usermeta wp_wiki_subscriptions  | bzip2 -c > weirdmeetup.db.sql.bz2

# excluded _users table for security reason
#wp_weirdmeetup_users  
