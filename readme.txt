=== coreSocial: Social Networks Sharing ===
Contributors: GDragoN
Donate link: https://www.dev4press.com/plugins/coresocial/
Tags: dev4press, social sharing, social share, profiles, blocks
Stable tag: 1.1
Requires at least: 6.1
Tested up to: 6.6
Requires PHP: 7.4
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Add popular social networks share buttons to posts and pages, lists social network profiles with customizable styling and full block editor support.

== Description ==
coreSocial plugin implements two major features in one plugin: sharing posts or pages on social networks, and linking social networks and website profiles. coreSocial includes blocks for use in the Block Editor for adding share posts and list of profiles with wide range of options for styling.

= Social Networks Sharing =
Plugin can add a group of buttons for sharing posts, pages, or any URL on the website via social networks of your choice. The Plugin supports eight social networks that are popular today. Plugin doesn't support a wider range of networks, because they are rarely used, but over time, plugin may get more supported networks too. Plugin tracks in the database each time any button is clicked, so it can display these counts inside the buttons. These counts don't guarantee that someone actually shared anything, only that the button is clicked, and sharing was initialized. For some networks, plugin can get real counts from the networks, but that is not widely supported by every social network.

Supported networks:
* Twitter / X (with option to add account to link and hashtags)
* Facebook (with support for getting actual share counts via Facebook API)
* Tumblr (with support for getting actual share counts)
* Pinterest (with support for getting actual share counts)
* Yummly (with support for getting actual share counts)
* Reddit
* LinkedIn
* Mix

Additional share buttons:
* MailTo (link with the open mail to format)
* Print (link to open the native print dialog)

= Adding Share Buttons =
Plugin can add share buttons automatically via plugin settings for selected post-types. But if you want, you can use Block to add share buttons anywhere, supporting the Full Site Editing templates too. With the use of block, share buttons can be added anywhere on the website where the block editor is used.

= Profiles Linking =
If you want to add links to your social network profiles or website, with the colorful buttons, with icons; followers counts (added manually), different layouts, coreSocial supports close to 40 different networks and other relevant methods and websites.

= Blocks =
Plugin adds two blocks for both features, and these have a huge number of settings and customizations to make the buttons look the way you want them.

= Plugin Home Page =
* Learn more about the plugin: [coreSocial Home Page](https://www.dev4press.com/plugins/coresocial/)
* Plugin knowledge base: [SweepPress on Dev4Press](https://www.dev4press.com/kb/product/coresocial/)
* Support for the Lite version: [Support Forum on Dev4Press](https://support.dev4press.com/forums/forum/plugins-lite/coresocial/)

= coreSocial Pro =
coreSocial Lite edition, available on WordPress.org is a fully functional plugin with no limits to its operations. But coreSocial Pro contains some additional features not available in the Lite version:

* Like Button: integrate Like button inside the share block
* QR Code Button: integrate QR Code button inside the share block and display QR Code for current post or page
* Floating Bar: add share block on every page, sticking to the left or right side of the screen
* bbPress Integration: direct integration into bbPress topics and replies

More exclusive Pro features will be coming with future updates. [Upgrade to coreSocial Pro](https://plugins.dev4press.com/coresocial/buy/).

== Installation ==
= General Requirements =
* PHP: 7.4 or newer

= PHP Notice =
* Plugin doesn't work with PHP 7.3 or older versions.

= WordPress Requirements =
* WordPress: 6.0 or newer

= WordPress Notice =
* Plugin doesn't work with WordPress 5.9 or older versions.

= Basic Installation =
* Plugin folder in the WordPress plugins should be `coresocial`.
* Upload `coresocial` folder to the `/wp-content/plugins/` directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* Plugin adds new top level menu called 'coreSocial'.
* Check all the plugin settings before using the plugin.
* In the Block Editor, you can add blocks listed under coreSocial category.

== Frequently Asked Questions ==
= Where can I configure the plugin? =
The plugin adds top level 'coreSocial' panel in WordPress Admin menu.

== Changelog ==
= 1.1 (2024.08.19) =
* New: option to choose the `Twitter/X` share URL
* New: notice about Mix sharing no longer working
* Edit: many improvements to getting proper values for page context
* Edit: various changes in underlying `Network` share class
* Edit: improvements in rendering of share buttons
* Edit: reverted blocks packages dependencies and build to WP 6.1
* Edit: updated links to the Dev4Press website
* Edit: Dev4Press Library 5.0.1
* Fix: invalid URL for the `Pinterest` share button
* Fix: few issues related to the getting of valid page title

= 1.0 (2024.05.06) =
* New: first lite version based on 3.0 Pro version
* Edit: Dev4Press Library 4.8

== Upgrade Notice ==
= 1.1 =
Various improvements and fixes.

= 1.0 =
First plugin release.

== Screenshots ==
1. Share Block Examples in the Editor
2. Network profiles with Share Buttons
3. Plugin Dashboard
4. Shared Items Data
5. Shared Items Log
