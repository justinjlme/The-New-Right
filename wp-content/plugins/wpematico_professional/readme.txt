=== WPeMatico Professional ===
Contributors: etruel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=B8V39NWK3NFQU
Tags: RSS, Post, Posts, Feed, Feeds, RSS to Post, Feed to Post, admin, aggregation, atom, autoblogging, bot, content, syndication, writing
Requires at least: 4.1
Tested up to: 4.8
Stable tag: 1.6.2

WPeMatico is for autoblogging, automatically creating posts from the RSS/Atom feeds you choose. PRO Version extends WPeMatico free plugin.

== Description ==

WPeMatico PRO adds following features to WPeMatico.

Support Custom taxonomies for Custom Post Types.
Just activate the Professional plugin and your campaign will show the metaboxes to select the custom taxonomies for the Custom Post Type selected.

Fix and correct wrong HTML on content.
You can enable this on txt config file when you get the content through Full content feature.
		
Delete last HTML tag option.
Many sites add own custom data on the last html tag. May be <p> or <div> or <span>, anyway, you can take off here.
		
Strip HTML from content.
You can strip all HTML tags from content and saves it as pure text.
		
Import the URL feed list into a campaign.
If you have a list of feed that you want to add into a campaign, you can import all of them with a few simple clicks pasting the list as txt format.
		
Automatic assign 'per feed' author name.
Automatic assigns author names based on source feed or custom typed author.
		
Words counters filters.
Strip the HTML and count how many words or letters are in content and allows assign a category, cut the content or skip the post.
		
Keywords filtering. Regular expressions supported.
You can determine if skip or publish the post for certain words in title or content.
		
Ramdom Rewrites
Replace words by synonyms ramdomly.

Custom title with/out counter.
PRO Version allow change the title of original post and also you can add a counter in the title name to don’t get duplicated titles.

Extra filters to check Duplicates with Custom titles.
PRO Version allow enable an extra query when fetching to check the titles before insert the new post in database to skip inserting the post if gets duplicated titles.

AUTO Tags Feature.
Generate tags automatically taken from content words. You can filter bad tags and how many tags do you want on every post. (Also you can see our Cats2Tags Add-on, getting 50% discount buying PRO version)
		
Custom fields with dynamic values.
Feature Custom fields with dynamic values allow you to add as many fields as you want with the possibility of add values as word templates replaced on the fly when the campaign is running. This allow add custom fields values like permalink, images urls, etc.
		
Default Featured image.
You can set the URL of a default Featured image if not found image on content.
		
Pro Options for images.
Overwrite, rename or keep the duplicated images by names.
		
Filter images by width or height.
You can set the min o max width or height to set the Featured image. Also Filter and delete images in posts content just by width or height of every image.

Import and export single campaign.
This feature allow you to export and download a file from a single campaign, then later you can upload and import the campaign in another or same wordpress with WPeMatico professional version installed.

== Installation ==

1. Unzip "wpematico_pro" archive and put the folder into your plugins folder (/wp-content/plugins/).
1. Same version of WPeMatico FREE must be installed and activated.
1. Activate PRO version through the 'Plugins' menu in WordPress.

= Upgrading =

* You will be notify when there is a new version then you can automatically upgrade from wordpress plugins page.

== Changelog ==
= 1.6.2 =
* Fixes some issues with Default Featured image to work after other addons.
* Tweak: Added filtering unformatted images to strip from contents.

= 1.6.1 =
* Fixed some issues with custom fields tag vars.

= 1.6 =
* New Feature: Added Ramdom Rewrites. A Big SEO improvement.
* Tweak: New option to add rel="nofollow" to links.
* Tweak: New option to use tags from feeds with <tag> if exists in the items.
* Tweak: Added bulk campaign import/export feature on bulk actions above the campaigns list.
* Tweak: Strip All HTML Tags, Strip Links and rel="nofollow" to links. 
* Cleans all the help strings in the campaign screen and added all tips to the Wordpress standard Help tab.
* Tweak: The old plugin updater was removed.
* Fixes the format for the first three campaigns in the debug file.
* Tweak: Added new license status on debug file.

= 1.5.2 =
* Fixes an issue in featured images.

= 1.5.1 =
* Fixes an issue with <author> tag to work also with emails as author names.
* Updated Updater class to 1.6.10.

= 1.5 =
* Added new feature for multipaged Feeds like https://etruel.com/feed/?paged=2.
* Added popup to add advanced options for each feed.
* Added new feature to allow strip text from a phrase till the end of the content.
* Added the option to get the author from the feed item <author> tag. If not exist, the user is created as author.
* Tweaks on "Discard the Post if NO Images in Content" to check also Featured images and added a filter to execute after Thumbnail Scratcher addon.
* Fixes a bug on showing the Image Renamer div area when activate it.
* Added Own debug info to the WPeMatico Debug Info file.
* Added 3 Campaign data to the WPeMatico Debug Info file.
* Some tweaks on texts.

= 1.4.1 =
* Fixes a bug in the keyword filter logic.
* Fixes the ajax in licenses page.
* Updated Updater class.

= 1.4 =
* Improves the Pro options for Images Metabox.
* Improves some filters to make featured the RSS images.
* Added an option to try to handle cases where images are delivered through a script and the correct file extension isn't available.
* Fixes the Image rename feature when the image extension is missed, by adding '.jpg'
* Fixes by adding the Featured Image as empty string to the post content when there is not a featured image.
* Improvements on Custom function for uploads.
* New feature to overwrite, rename or keep the duplicated images by names.

= 1.3.8.1 =
* Improves some filters to skip posts.
* Fixes an error on menus with custom taxonomies.
* Fixes an issue in the custom title counter.
* Fixes the permalink tag used in custom fields.
* Fixes a warning that break the screen when imports a campaign.
* Fixes the license key activation in some cases that failed.

= 1.3.8 =
* Adds a new feature: Rename the images uploaded to the website.
* Adds a new feature: Keyword Filter for source item categories.
* Many improvements on the Keywords filters when it's fetching sources.
* Some tweaks on Pro Options For images.
* Fixes [STRICT NOTICE] non-static method discardifnoimage called statically.
* Fixes PHP notice on exporting a campaign.

= 1.3.7.1 =
* Fixes the timeout error calling a function with cron in auto mode.

= 1.3.7 =
* New improved function to add the custom featured image.
* Improves the behaviour on cutting text with featured images.
* Fixes double display of categories in Quick edit actions.
* Fixes adding the featured images to custom fields.
* Updated Plugin Updater class.

= 1.3.6 =
* Fixes an error that avoid activate the plugin in some singular cases.

= 1.3.5 =
* Fixes to don't show Export campaign quick action in Trash.
* Fixes a function name to check duplicated custom titles.
* Uses Danger Zone Options to delete the Professional options when uninstall

= 1.3.4 =
* Fixes a debug notice with enclosure images.
* Some cosmetic tweaks on Custom Fields metabox.

= 1.3.3 =
* Added a feature to Keywords Filters to take one or all words.
* Added compatibility for free 1.3.3 version.
* Colored meta boxes titles on campaign editing.
* Fixes - when saving KeyWord filters for bad strip / slashes.
* Some cosmetic tweaks to Keywords filters Metabox.
* Some other tweaks and improvements.

= other versions =
* See on Free version
