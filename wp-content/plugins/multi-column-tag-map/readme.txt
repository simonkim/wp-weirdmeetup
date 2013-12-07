=== Multi-column Tag Map ===
Contributors: tugbucket
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GX8RH7F2LR74J
Tags: tags, lists, expandable, column, alphabetical, toggleable, index, appendix, glossary, tag cloud alternative
Requires at least: 2.1
Tested up to: 3.5.1
Stable tag: 12.0.4

Multi-column Tag Map can display a columnized and alphabetical (English) listing of all tags, categories, pages or single taxonomies used in your site.

== Description ==

Multi-column Tag Map displays a columnized and alphabetical (English) listing of all tags, categories, pages or single taxonomies used in your site similar to the index page of a book. This makes it easier for your visitors to quickly search for topics that might intrest them. 

= Features =
* Can be set to display all tags, all categories, all pages or single taxonomies
* Can also list child categories
* Alphabetically lists all tags used in the site
* Display tags in one to five columns
* The initial amount of tags from each letter can be limited
* jQuery Show/Hide effect 
* jQuery toogle effect
* Customizable "show more" link 
* Customizable "show less" link
* Can show tags with no posts related to them
* Can show tags belonging to private posts
* Optionally list tags of peoples names last name first
* Can display the number of posts that share a tag
* Can exclude tags you do not want to appear in the lists
* Can display the tag description
* Can set the width of the columns in the shortcode
* Can add your own custom CSS to your theme's directory
* Can show a list of tags by a specific category

== Installation ==

= Shortcode Installation Example =

`[mctagmap columns="3" hide="yes" num_show="3" more="more &#187;" toggle="&#171; less" show_empty="yes" name_divider="|" tag_count="yes" exclude="2009, exposition" descriptions="yes" width="170" equal="yes" manual="" basic ="no" basic_heading="no"]`

= Defaults = 

* columns = 2 (possible values: 1-5)
* hide = no (possible values: yes or no)
* num_show = 5
* more = View more
* toggle = no (possible values: 'YOUR TEXT' or no)
* show_empty = no (possible values: yes or no)
* name_divider = | (vertical pipe)
* tag_count = no (possible values: yes or no)
* exclude = 
* descriptions = no (possible values: yes or no)
* width = 190 (do not use 'px', 'em', etc)
* equal = no (possible values: yes or no)
* manual =  (possible values: blank or a comma seperated list eg: "d, g, t")
* basic = no (possible values: yes or no)
* basic_heading = no (possible values: yes or no)
* show_categories = no (possible values: yes or no)
* child_of = 0 (values are numeric)
* from_category = (values are numeric)
* show_pages = no (possible values: yes or no)
* page_excerpt = no (possible values: yes or no)
* taxonomy = 
* group_numbers = no (possible values: yes or no)
* show_navigation = no (possible values: yes or no)

= Explanation of options =

* **columns**: This sets the number of columns to display your tags in. **NOTE**: if you have less letters than your set number of columns, the plug in will end up inserting an extra closing tag. this will mess up your layout. **as of v12.0.4, this should not be an issue**
* **hide**: This tells the list of tags for each letter to cut the list off at the point specified in the 'num_show' option.
* **num_show**: This tells the plug-in how many list items to show on page load.
* **more**: This will be the text of the link to dispaly more links. Only visible if 'hide' is set to 'yes' and 'num_show' is less than the total number of tags shown in each list.
* **toggle**: If set to anything except 'no', this will tell the 'more' link to become a toggle link. The text you set for 'toggle' will be the 'hide' link text.
* **show_empty**: If set to 'yes', this will display tags in the lists that currently do not have posts associated with them. **NOTE**: If a post is set to private the tag will still show up in the list but, clicking the link will go to an empty archive unless the user is logged in. This is the same behavoir as clicking a tag link where there is no post to go to. This is not a bug.
* **name_divider**: This allows for multi-word tags to be sorted by words other than the first word eg. "Edgar Allen Poe" would be sorted under the "E"s. If you write your tag "Edgar Allen | Poe" it will now produce "Poe, Edgar Allen" and be sorted with the "P"s.
* **tag_count**: If this option is set to "yes", the number of posts that share that tag will be displayed like "(3)". The count is wrapped in a span with a class of "mctagmap_count" so that the count can be styled individually in the CSS if desired. 
* **exclude**: A comma seperated, case sensitive list of the tags you do not wish to appear in the lists.
* **descriptions**: If set to yes, the plugin will create a span and populate it with the tags description. By default the text is set to 90% italics.
* **width**: The default width (190px) can be set in the shortcode without any need to alter the CSS. 
* **equal**: What this does is makes the horizontal sections equal height based on the tallest in the row. This is only recommended if you are using the "hide" option. Look at the first image in the <a href=\"http://wordpress.org/extend/plugins/multi-column-tag-map/screenshots/\">screenshots page</a> for a better example. 
* **manual**: Each letter will create the begining of a new column. Exmple: 'manual="e, h, t".' This will create four columns (a-d, e-g, h-s and t-z+numbers). Writing "a, e, h, t" will have the same effect. The "columns" option is ignored as the "manual" setting creates the columns based on your input.
* **basic**: This removes all the alphanumeric headings. It then splits your columns by the number of tags rather than the number of headings. See screenshot <a href=\"http://wordpress.org/extend/plugins/multi-column-tag-map/screenshots/\">"The 'basic' option"</a>. The "more", "hide", "num_show", "toggle", and "equal" are ignored when using the "basic" option.
* **basic_heading**: Turns the alphanumeric headings on or off in the basic mode.
* **show_categories**: if set to "yes", the plugin will list categories instead of tags.
* **taxonomy**: If you enter a taxonomy, the plugin will only display tags from that taxonomy.
* **group_numbers**: If set to "yes", this will group all tags beginning with a number together. They will then be put under one heading of "#" in the list.
* **show_navigation**: If set to "yes", a div will be added before your lists with jump links to the corresponding heading. See <a href=\"http://wordpress.org/extend/plugins/multi-column-tag-map/screenshots/\">screen shot #7</a>.
* **child_of**: if show_categories is set to "yes", you can input a comma delimited list of category IDs eg, "2, 215, 209" and so on. 
* **from_category**: You can enter a single numeric ID of a category and it will only sort tags from that category. *See "Theme Addition" under Additional Options*.
* **show_pages**: If set to "yes" this will list pages instead of tags.
* **page_excerpt**: If set to yes and you have set a page excerpt, this will display the excerpt in the same way you can display tag descriptions.

= Note =
You must be using jQuery in order to use the show, hide and equal feature.

= Additional Options =
* If you wish to make CSS changes, make a folder named "multi-column-tag-map" in your theme's directory. Move a copy of the plugin's "mctagmap.css" into that folder. There you can make style changes that will not be overwritten when you update the mctagmap plugin.
* If you wish to make JavaScript changes, make a folder named "multi-column-tag-map" in your theme's directory. Move a copy of the plugin's "mctagmap.js" into that folder. There you can make JavaScript changes that will not be overwritten when you update the mctagmap plugin.
* If you wish to make PHP changes, make a folder named "multi-column-tag-map" in your theme's directory. Move a copy of the plugin's "mctagmap_functions.php" into that folder. There you can make PHP changes that will not be overwritten when you update the mctagmap plugin.
* There is a reverse exclude feature. You can add exclude="&#42;!er" and will only list tags that include "er" in them. Example: exclude="&#42;!tion" will show only tags that include "tion" and so on. You can only use one reverse exclude this way.

= Theme Addition =
If you are using the "from_category" option, you will have to modify your theme to display the tag archives correctly. Below is an example of what you can try before your loop in your themes tag archive page. In my test theme, there is a "tag.php" file that displays the archives for tags.  Each theme can be totally different so this is only an example. **I can not give specific advice on how to implement this on any specific theme**. 
`
<?php if(isset($_GET['mctmCatId']) && isset($_GET['mctmTag'])){
query_posts('cat='.$_GET['mctmCatId'].'&tag='.$_GET['mctmTag']);
} ?>
`

== Frequently Asked Questions ==

= The plugin doesn't work correctly in [insert non-english] language, can you fix it? =

Currently the plugin only displays and groups non-English words. It does not sort these words alphabetically. If someone would like to help translate it into another language, it would be appreciated.

= Does the plugin work in [insert theme name]? =

mctagmap does nothing to the core functions of Wordpress. There should be no reason that a theme changes the default functions as to how Wordpress handles tags. Knowing that, there shouldn't be any reason why the plugin does not work in your theme. The CSS might get overwritten due the the hierarchy of your themes CSS but, that can be changed by editing the mctagmap.css.

= The map is displaying in a "stair case" fashion =

See screenshot <a href=\"http://wordpress.org/extend/plugins/multi-column-tag-map/screenshots/\">"pre-code error"</a>.

In your admin panel for the page, switch to HTML view. Notice your theme is wrapping the shortcode in:

`&lt;pre>&lt;code> &lt;/code>&lt;/pre>`

Please remove that. That should fix it up.

= Can the plugin include tags from [insert plugin name]? =

Multi-column Tag Map looks for the tags created by Wordpress. Most other plugins (NextGen, The Events Calendar, etc...) create tags but, they are not stored in the database the same way as Wordpress does. Combining those tags into Multi-column Tag Map is possible but, any method of doing this is a hack and is not supported out of the box. I will not add this functionality to the plugin as a default since I have no control over the other plugins and cannot make any guarantee that the other plugins will not change how they structure and handle tags in the future.


== Screenshots ==

1. Using all options. `/trunk/screenshot-1.gif`
2. In use at <a href=\"http://www.noveleats.com/ingredient/\">Novel Eats</a>. `/trunk/screenshot-2.gif`
3. In use at the <a href=\"http://soctheory.iheartsociology.com/index/\">Department of Sociology of Occidental College</a>. `/trunk/screenshot-3.gif`
4. Some extra styling added at <a href=\"http://blog.caplin.com/index/\">Caplin's blog</a>. `/trunk/screenshot-4.gif`
5. The common "pre-code error". `/trunk/screenshot-5.gif`
6. The "basic" option. `/trunk/screenshot-6.gif`
7. The "show_navigation" option. `/trunk/screenshot-7.gif`

== Changelog ==

* v12.0.4 - Added a fix to the errors with the "Standard" theme's functions.php
* v12.0.3 - Cleaned up some PHP notifications from undeclared variables, replaced attribute_escape() with esc_attr() and eliminated the extra div issue when there are more columns assigned than tags availabble.
* v12.0.2 - Sort issue with show_pages (uasort) addressed.
* v12.0.1 - Sort issue with show_pages addressed.
* v12.0 - Fixed the numberposts issue for showing categories. Fixed the way scripts were loaded for SSL use. Fixed the "flatten" function conflict. You can now use your own CSS, JS and PHP if desired.
* v11.0.3 - Fixed a duplicate problem and archives issue on "from_category"
* v11.0.2 - Fixed show_categories and tuned a PHP 4.x issue.
* v11.0.1 - Corrected plugin conflict.
* v11.0 - Fixed a PHP 4 issue, added "show_pages", "page_excerpt", "&#42;!", "from_category", "child_of"
* v10.0.1 - Fixed "show_navigation" issue.
* v10.0 - Removed the old hardcode version completely. Options "show_categories". "taxonomy", "group_numbers", and "show_navigation" added.
* v9.0 - Added the "manual", "basic" and "basic_heading" options.
* v8.0 - Added the ability to equalize the heights of the individual letters sections, the use of a custom CSS within the theme's folder and added to the FAQ.
* v7.0 - Added the ablity to display tag descriptions and set the column width in the shortcode. Cleaned up some of the code that was being inserted in the head section.
* v6.0.2 - upload error. 
* v6.0.1 - upload error. 
* v6.0 - Added language display support and the ability to exclude tags.
* v5.1 - cleaned up the tag_count function.
* v5.0 - Fixed a small issue with the name_divider addition. Added the tag_count option.
* v4.2 - Fixed function conflict and added to the FAQ.
* v4.1 - oops
* v4.0.1 - Typos
* v4.0 - Deprecated hardcode support. Added name_divider shortcode option.
* v3.0 - Added toggleability to the lists, the ability to show empty posts and the ability to use special characters in the links.
* v2.2 - Fixed shorcode placement issue.
* v2.1 - Fixed a sorting coflict with the defaults.
* v2.0 - Added shortcode functionality.
* v1.3.1 - Fixed a conflict in jQuery for the show/hide to work.
* v1.2 - Updated the plugin PHP to correct the CSS path.
* v1.0 - It's a boy!
























