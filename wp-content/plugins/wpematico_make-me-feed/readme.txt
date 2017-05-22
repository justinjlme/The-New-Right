=== Make Me Feed ===
Contributors: etruel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=B8V39NWK3NFQU
Tags: wpematico, Make me Feed, autoblog, rss, feed, read, matic
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 1.4

Addon for WPeMatico that allows to create RSS 2.0 feeds with content from external sites on your Wordpress blog, regardless of whether or not those have their own feed.

== Description ==
Make Me Feed Add-on allows to use WPeMatico from a Wordpress website and to send the read posts from each campaign to an email account.
 
= How it works =
 Addon for WPeMatico that allows to create feeds of content from external sites on your Wordpress blog, regardless of whether or not those have their own feed.

= Features =
* Creator of RSS 2.0 feeds from external pages indexes.
* It follows the standards of Wordpress editing and code. If you know how to upload a post in Wordpress, this is even easier.
* Unlimited pages of feeds from different sites as any Custom post type of wordpress with own template.
* Possibility to copy the template to your Wordpress theme directory to edit whatever you want.
* Feeds listed by assigned name.
* Possibility of making private feeds, to see them only if you are logged in on the site.
* An option in each feed to indicate the maximum number of items that you want to.
* Text to indicate the class or the attribute id in the document DOM that has the link and anchor text.
* Option to indicate to work with Full Content addon to extract the full contents and place it on each item in the feed. If the addon is not installed, will get the permanent links with the titles of the main page.

It does not modify the WPeMatico behavior.  It just make feed pages to be read by WPeMatico and can use the features of the Full Content add-on.
 
= Requirements =
As WPeMatico Add-on requires WPeMatico base plugin installed and activated.  
PHP 5.3 or higher
Works better with Full Content add-on but there are not required.

== Installation ==

You can either install it automatically from the WordPress admin, or do it manually:

1. Unzip plugin archive and put the folder into your plugins folder (/wp-content/plugins/).
2. Activate the plugin from the WPeMatico Extensions menu.

== Frequently Asked Questions ==

= Can I use this plug-in without WPeMatico plugin? =

May be in the future but not for now. It requires WPeMatico Free Version installed and activated.  

== Screenshots ==

1. Creating a feed. Just few fields to fill.

== Changelog ==

= 1.4 =
* Fix the item title to show single text in feed page template.
* Fixes the license key activation in some cases that failed.
* Updated Plugin Updater class to 165.

= 1.3 =
* Fixes an issue on wrong transform of relative to absolute URLs in some cases.
* Updated Plugin Updater class to 164.

= 1.2 =
* Tweaks on relative and absolute URLs when take the anchor links for the titles links.
* Added option to parse all relative paths of images and hyperlinks to absolute.
* Fixes showing one more item that what is saying in the option "Max. Items"

= 1.1 =
* Fixes the Help tab on feed editing screen.
* Fixes the menu behaviour when editing.

= 1.0 =
* initial release
