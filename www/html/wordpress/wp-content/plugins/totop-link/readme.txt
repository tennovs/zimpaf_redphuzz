=== ToTop Link ===
Contributors: DaoByDesign
Requires at least: 2.8
Tested up to: 4.2
Stable tag: 1.7
Donate link: http://www.daobydesign.com/buy-us-a-coffee/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: navigation, ui, ux, back to top, totop link, scroll, jquery, top link, usability

A simple plugin for WordPress that adds an unobtrusive "back to top" link to your site or blog.

== Description ==
A simple plugin for WordPress that adds an unobtrusive "back to top" link to your site or blog. The link uses WordPress' included jQuery to provide a slick UX, with the link subtly appearing after the page has been scrolled, and disappearing once the user returns to the top of the page. When clicked the page scrolls smoothly back to the top of the page.

You can easily customize the link's position, style and/or text. Additionally, you can either use the included images or define your own custom image to use.

Follow us on [Twitter](http://www.twitter.com/daobydesign) and on [Facebook](http://www.facebook.com/daobydesign) for updates and news.

Demo: Check out the [plugin's page](http://www.daobydesign.com/free-plugins/totop-link-for-wordpress/) for a demo.

== Installation ==
* Upload plugin files to your plugins folder, `/wp-content/plugins/`, or install using WordPress' "Add Plugin" feature — just search for "**ToTop Link**"
* Activate the plugin
* Go to Settings->ToTop Link in the WordPress admin area, and customize.

== Frequently Asked Questions ==
= Where can I get support or news about updates? =
The best place is at the official [support forum](https://wordpress.org/support/plugin/totop-link). You can also follow us on [Twitter](http://www.twitter.com/daobydesign) and on [Facebook](http://www.facebook.com/daobydesign) for updates and news. Also check out the [plugin's page](http://www.daobydesign.com/free-plugins/totop-link-for-wordpress/).

= Where can I see a demo of this in action? =
[Here](http://www.daobydesign.com/free-plugins/totop-link-for-wordpress/).

= I've enabled the plugin, why isn't it displaying? =
It could be that your theme doesn't call wp_footer() (as it should). This is a pretty essential part of a WordPress theme, so we recommend you contact the theme's designer and ask them to add it. Optionally, if you're comfortable editing your theme file, you can [add it yourself](http://codex.wordpress.org/Function_Reference/wp_footer).

= What is the plugin license? =
This plugin is released under a GPL license.

== Screenshots ==
Easier just to view the [plugin's page](http://www.daobydesign.com/free-plugins/totop-link-for-wordpress/) for a live demo.

== Changelog ==
= 1.7 =
Minor maintenance release

= 1.6 =
Major code overhaul, improved performance, added new default SVG icons, moved Javascript to footer for better page load speed.

= 1.5.3 =
Small maintenance release.

= 1.5.2 =
Added new option to customize scroll to top speed.

= 1.5 & 1.5.1 =
Fixed "Invalid argument supplied for foreach()" error.

= 1.4 =
Added a scroll offset to allow defining when the ToTop link appears.

= 1.3 =
A quick update to fix a bug with getimagesize. Also took the opportunity to clean up the settings page.

= 1.2 =
Added ability to fully-customize the position of the ToTop link, as well as add custom images for the link.

= 1.1 =
A quick update to fix a couple bugs and add a few extra positions. Thanks to those who commented.

= 1.0 =
This is the first version. The biggest change is that before this, the plugin was nothing.

== Upgrade Notice ==

= 1.0 =
N/A

= 1.1 =
Important upgrade for any users experiening a z-index bug whereby the ToTop Link is unclickable. This release fixes that.

= 1.6 =
Changes to setting storage. Options should migrate automatically. However, making a note of your ToTop Link options before updating is recommended.