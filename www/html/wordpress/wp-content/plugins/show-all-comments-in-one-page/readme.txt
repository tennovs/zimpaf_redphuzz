=== Show All Comments ===
Contributors: biztechc
Tags: All Comments in one page, manage all comments in one page, show all comments, eazy comments management,comments filter,search comment,filter comment.comment search
Requires at least: 3.6.1
Tested up to: 5.8
Stable tag: 7.0.0
License: GPLv2 or later

This plugin displays all the comments received on your various posts in a single page with filter, enabling the readers to read all the comments in a single page.

== Description ==

Plugin's settings will display at Settings > BT Comments

1. This plugin is useful for displaying all comments in one single page.
2. You can displaying all the comments which are in posts or pages. For that you must choose pages or Posts at settings.
3. Using related short code all the comments will displaying on assigned page.
4. Short code is : [bt_comments]
5. You can override admin setings in different page by using parameters with shortcode like:  pagination=yes/no , comments_per_page={number} and display_filter=yes/no.
6. You can apply this short code into page/post's editor or also can add into PHP file. 
   Like 
   `<?php echo do_shortcode('[bt_comments]');?>`
7. You can also exclude pages or posts for which you dont want to show comments.
8. Comments will displaying into pagination format if you select pagination option into setting. 
9. On front side there will be filter to search comments by post/categories on front side.You can enable/disable this filter from admin settings page.
    
== Installation ==

1. Copy the entire /bt-comments/ directory into your /wp-content/plugins/ directory.
2. Activate the plugin.
3. New Tab called BT Comments will be genereate.
4. You can add set setting.
5. Use short code at any pages/posts e.g.[bt_comments] 

== Frequently Asked Questions ==
Is this plugin prepared for multisites? Yes.

== Screenshots ==

1. screenshot-1.png
1. screenshot-2.png

== Changelog ==
= 7.0.0 =
* Sloving Filter issue.
* Compatibility with WordPress version 5.8

= 6.0.0 =
* Compatibility with WordPress version 5.5

= 5.0.1 =
* Compatibility with WordPress version 5.4

= 5.0.0 =
* Compatibility with WordPress version 5.3

= 4.1.2 =
* Bug fixing for comments ordering

= 4.1.1 =
* Bug fixing for PHP 7.1

= 4.1.0 =
* Added new feature.(Selection for 'Title Link and Go to comment Link'.)

= 4.0.3 =
* Remove admin side notice.

= 4.0.2 =
* Translation support.

= 4.0.1 =
* minor bug fixing in ordering.

= 4.0.0 =
* shortcode support parameters for pagination,comment per page and enable/disable filter.

= 3.0.0 =
* Search filter for comments by post type/posts.

= 2.0.1 =
* Resolved issue on Wordpress 4.4.1 version now.

= 2.0.0 =
* We have added new features.(Selection for adding date, open comments into new TAB, displaying comments into order as per selection.)

= 1.0.5 =
* Solve issue.Comments will display where shortcode stands.

= 1.0.4 =
* Add new feature that is admin can set Avtar image.

= 1.0.3 =
* Add upgradation notification

= 1.0.2 =
* Resolved issue for activation warning

= 1.0.1 =
* Stable Version release

== Upgrade Notice ==
