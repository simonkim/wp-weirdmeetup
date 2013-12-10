=== Plugin Name ===
Name: CM Answers Pro
Contributors: CreativeMindsSolutions
Donate link: http://answers.cminds.com/
Tags: answers, forum, questions, comments, question and answer, forum, q&a, list, stackoverflow, splunkbase
Requires at least: 3.3
Tested up to: 3.7.1
Stable tag: 2.0.10

Allow users to post questions and answers (Q&A) in stackoverflow style


**Demo**

* Demo [Read Only mode](http://answers.cminds.com/).


**More About this Plugin**

You can find more information about CM Answers at [CreativeMinds Website](http://answers.cminds.com/).


**More Plugins by CreativeMinds**

* [CM Enhanced ToolTip Glossary](http://wordpress.org/extend/plugins/enhanced-tooltipglossary/) - Parses posts for defined glossary terms and adds links to the static glossary page containing the definition and a tooltip with the definition.

* [CM Multi MailChimp List Manager](http://wordpress.org/extend/plugins/multi-mailchimp-list-manager/) - Allows users to subscribe/unsubscribe from multiple MailChimp lists.

* [CM Invitation Codes](http://wordpress.org/extend/plugins/cm-invitation-codes/) - Allows more control over site registration by adding managed groups of invitation codes.

* [CM Email Blacklist](http://wordpress.org/extend/plugins/cm-email-blacklist/) - Block users from blacklists domain from registering to your WordPress site.


== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Manage your CM Download Manager from Left Side Admin Menu

Note: You must have a call to wp_head() in your template in order for the JS plugin files to work properly.  If your theme does not support this you will need to link to these files manually in your theme (not recommended).



== Changelog ==
= 2.0.10 =
* Fixed the FB warnings
* Fixed the bug with answers disappearing on the question page when answers sort setting was changed to "Votes"

= 2.0.9 =
* Small changes to the settings
* Fixed the bug with author page links
* Changed the source of jQuery UI to bundled

= 2.0.8 =
* Changes to the CSS to make the plugin suit into WP default themes
* Added the option to allow questions without content
* Fixed the rare bug with question's 'asked on' format
* Fixed the bug with contributor page author
* Fixed the bug with trashed answers being counted

= 2.0.7 =
* Fixed the CSS code for the <pre> tags in the answers
* Fixed the timestamp issues for the "last updated" section
* Added the option to include "Custom CSS"
* Added the option to enable answers even after the question is "Resolved"
* Added the option to reject the disclaimer

= 2.0.6 =
* Fixed bug with "Show Author" in the "Question Widget"
* Added new options for shortcodes
* Fixed some PHP bugs from previous releases
* Added the option to disable the main question's list

= 2.0.5 =
* Added the option to show/hide the link to the question's author page on the question's list
* Added the option to show/hide the information about the question's updates
* Added the options to hide author information and updates information for the "Questions Widget"
* Fixed the behavior of the content editor - so now it saves the line breaks
* Fixed the styling of the "Questions Widget" so the title of the question comes first if it's too narrow
* Added the affiliate programme
* Added the backlink for the contributor's page

= 2.0.4 =
* Fixed sidebar styles
* Added the options to hide Views/Votes/Answers for the "Questions Widget"

= 2.0.3 =
* Fixed the contributor's page
* Added the option to turn on richtext editor for question/answer content

= 2.0.2 =
* Fixed the bug with subscribers being unable to post comments
* Allowed users with 'author' role to post questions if the access is restricted
* Fixed a bug with the permalink setting
* Fixed a bug with the question listing title setting

= 2.0.1 =
* Removed the styles from Twenty Twelve which were causing conflicts
* Fixed the setting hiding the tags
* Fixed the incorrect question count on the tag widget
* Fixed some of the styles
* Fixed some PHP bugs

= 2.0.0 =
* Fixed many issues regarding shortcodes and AJAX
* Fixed the moderation options behavior
* Added new supported options for the shortcodes
* Added the table explaining the moderation options behavior on settings page
* Added the option to remove the markup box near question/answer form
* Added the option support for the [cma-my-questions] and [cma-my-answers]
* Tidied up the plugin's views CSS/HTML
* Show only approved comments on highest rating
* "Question marked as spam" issue resolved
* Fixed the css issues with some themes
* Fixed pagination on category pages bug
* Fixed the FB like button
* WP-admin links removed for subscribed
* Fixed the bug with which appeared when a random string was added to the answer's url
* Changed the way how "Moderation" options look and work
* Plugin now shows the user's name from the time when they posted the answer not from the profile
* Prefixed the styles

= 1.9.12 =
* Added option to remove search box and tags from questions widget
* Added option to remove number of answers from contributers widget
* Added Social share in question page


= 1.9.11 =
* Added option to edit author of question for administrators

= 1.9.10 =
* Fixed warning when no categories are added

= 1.9.9 =
* Fixed wpdb->prepare warning

= 1.9.8 =
* Fixed display for permissions warning in widget area and regular lists
* Fixed answer sorting in questions shortcode

= 1.9.7 =
* Added trigger for new questions to be filtered by comment spam filters
* Added option to hide questions/answers from not logged-in users

= 1.9.6 =
* Added category tree in dropdowns (only main and subcategories) for questions

= 1.9.5 =
* Fixed notify on follow email
* Fixed several problems with sticky questions
* Fixed problem with sorting in [cma-questions] shortcode
* Added Disclaimer support for first time users

= 1.9.4 =
* Questions listing title can be changed in settings

= 1.9.3 =
* Added option to set questions list as homepage
* Changed contributor link structure to /contributor/name

= 1.9.2 =
* Added 'remove_accents()' to sanitize_title function

= 1.9.1 =
* Added option to edit "Questions" listing title
* Fixed ajax search
* Fixed problem with login box appearing for resolved questions
* Fixed problem with logging in from shortcode single page

= 1.9.0 =
* Fixed bug with custom permalink
* Add tags support. Admin can control the appearance of tags
* Add tags widget and top contributors widget
* Add Admin control to restrict who can ask questions
* Add option to change plugin permalink
* Add support to sticky posts with admin defined background color
* Add support to code snippets background color
* Added filter for answered and not answered questions in questions list
* Add option to show question description in html title
* Add option to change 0 to no in number of views/answers
* Add support in setting for number of questions in page


= 1.8.3 =
* Fixed bug with wp_enqueue_script

= 1.8.2 =
* Fixed bug with not displaying last poster name for new threads
* Added option to disable sidebar or set its max-width

= 1.8.0 =
* All links added via [cma-questions] shortcode are now working via ajax without changing the page template
* Added user guide

= 1.7.0 =
* Corrected daysAgo calculation, added hours/minutes/seconds
* Corrected translations

= 1.6.6 =
* Fixed bug with Avatar user id
* Support plural and singular in french (views, votes, answers)

= 1.6.5 =
* Changed category submenu capability to manage_categories instead of  manage_options
* Replaced all <? with <?php
* Added support fopr French in number of Votes


= 1.6.4 =
* Fix time ago function

= 1.6.3 =
* Bug with <pre> code insertion
* Bug with hiding upload section


= 1.6.2 =
* Bug preventing question with no file to be sent
* Bug with empty categories not showing up


= 1.6.1 =
* Localization of frontend labels for German, Spanish, Polish
* Fixed renderDaysAgo function
* Fixed pagination to work with permalink structure without trailing slash
* Fixed comment direct link
* Fixed [author] shortcode
* Fixed status header in [cma-my-questions] shortcode
* Fixed problem with adding attachment from [cma-questions] shortcode
* Add question form is now populated with previous data when error occurs

= 1.6.0 =
* Added gravatar profile photos
* Added option to change default sorting for answers between ascending and descending
* Added possibility to add attachments to questions
* Added option to block views incrementation upon site refresh

= 1.5.1 =
* Removed unused admin.js

= 1.5 =
* Added option to hide categories in questions widget
* Fixed "back" link on question page

= 1.4 =
* Datetimes are now formatted according to wordpress general settings
* Dates use date_i18n function to produce localized names
* Fixed escaping for notification titles and contents
* Added images for social login links
* Fixed template
* Added category dropdown for new questions (active when there's at least one category)
* Added user profile pages
* Added category pages

= 1.3 =
* If user logged in via social login, his name will become link to his public profile
* Added shortcodes and widget for latest/hottest/most voted/viewed/categorized questions

= 1.2 =
* Added social login
* Added categories for questions
* Added options to show/hide views/votes/answers
* Added number of QA near each name
* Added tabs for settings

= 1.1 =
* Renamed main list from "Answers" to "Questions"
* fixed bug when sorting answers by votes didn't show answers without any votes (will work only for answers added after upgrade)
* Added validation for question (it's not possible to add empty one now)
* Minor fix in styling
* Added link to answers from admin menu

= 1.0 =
* Initial release

