=== WP Basic Elements ===
Contributors: WebKreativ
Tags: optimization, cleanup, performance, admin, disable
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AJABLMWDF4RR8&source=url
Requires at least: 6.9
Tested up to: 6.9
Stable tag: 6.0.0
Requires PHP: 8.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Disable unnecessary WordPress features, clean up your markup, and simplify the admin. Everything is opt-in — nothing changes until you say so.

== Description ==
WP Basic Elements gives you full control over the features WordPress loads by default. Disable what you don't need, clean up the front-end markup, and streamline the admin interface — all from a single, modern settings page.

Every option is **off by default**. You choose exactly what to disable, nothing more.

= What you can do =

* **Admin Bar** — Remove individual items like the WP logo, New Content, Customize, Updates, Comments, Search, and more
* **Dashboard Widgets** — Hide Welcome Panel, At a Glance, Activity, Site Health, Quick Draft, WP Events & News, WooCommerce and Yoast widgets
* **Admin Footer** — Replace or hide the left and right footer text
* **User Profile** — Hide profile fields (Rich Editing, Color Scheme, Application Passwords, etc.) and remove individual contact methods (Facebook, Instagram, LinkedIn, Twitter/X, and more)
* **Comments** — Disable the entire comment system with a single toggle
* **Gutenberg Editor** — Turn off Welcome Guide, Fullscreen Mode, Focus Mode, Distraction Free Mode, and Top Toolbar
* **Meta Tags & Head Cleanup** — Remove the generator tag, oEmbed links, shortlinks, WLW manifest, adjacent post links, REST API links, RSS feeds, emojis, and pingbacks/trackbacks
* **REST API** — Restrict access for non-logged-in users with a configurable whitelist

= Why WP Basic Elements? =

* Lightweight — no bloat, no frameworks, no external dependencies
* Opt-in only — safe to activate on any site without side effects
* Modern UI — clean settings page with sidebar navigation and toggle switches
* Translatable — ships with 10 languages out of the box
* Secure — all output escaped, all input sanitized

== Installation ==
1. Upload the `wp-basic-elements` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to **Settings > WPBE Elements**
4. Enable the options you need and save

== Translations ==
WP Basic Elements plugin can be translated to any language. Currently available translations are listed below.

* English
* Swedish (sv_SE)
* Norwegian Bokmål (nb_NO)
* Serbian (sr_RS)
* Danish (da_DK)
* Croatian (hr)
* German (de_DE)
* Polish (pl_PL)
* Italian (it_IT)
* Spanish (es_ES)
* French (fr_FR)

== Frequently Asked Questions ==
= Will this break my site? =
No. Every option is off by default. Nothing changes until you explicitly enable a feature.

= Where are the settings? =
Go to **Settings > WPBE Elements** in your WordPress admin.

= Can I use this with WooCommerce? =
Yes. WooCommerce REST API endpoints and dashboard widgets are handled separately so you can safely disable what you don't need without affecting your store.

= How do I report a bug or request a feature? =
Use the [support forum](https://wordpress.org/support/plugin/wp-basic-elements/) on wordpress.org.

== Screenshots ==
1. Settings page — sidebar navigation with toggle switches
2. Meta Tags & Head Cleanup — granular control over what loads in the head
3. Admin Bar — choose which items to remove

== Changelog ==
= 6.0.0 =
* Complete rewrite of plugin
* New modern settings UI with sidebar navigation, toggle switches, and per-section cards
* "Toggle All" button in each section to quickly enable/disable all options
* Active tab persisted via localStorage across page loads and saves
* All translation strings escaped with esc_html__ or esc_attr__
* All output properly escaped (esc_html, esc_attr, wp_kses_post)
* $_SERVER['REQUEST_URI'] sanitized with sanitize_text_field(wp_unslash())
* JSON in inline scripts hardened with JSON_HEX_TAG | JSON_HEX_AMP flags
* wp_redirect changed to wp_safe_redirect for internal redirects
* Added option to hide Right Footer Text Entirely: removes the right side completely
* Improved whitelist field for custom REST API endpoints accessible to non-logged-in users
* Made a default whitelist displayed in collapsible details section
* Added, Danish, Croatian, German, Polish, Italian, Spanish, French languages
* Modified English, Swedish, Norwegian and Serbian languages

= 5.4.5 =
* Tested with WordPress 6.8.2
* Escaped all translatable text

= 5.4.4 =
* Tested with WordPress 6.7.0

= 5.4.3 =
* Tested with WordPress 6.4.0
* Small fix of comments in code

= 5.4.2 =
* Added GZIP compression option in Core section
* Fixed variable bug on wpbe_disable_wp_file_mods option
* Fixed a whitelist check for wpbe_disable_wp_rest_api namespaces
* Small design fixes for input fields
* Update of language files

= 5.4.1 =
* Fixed some styling of the settings in admin

= 5.4.0 =
* Major redesign and refactoring of code
* Improvements of Code documentation
* Added the ability to disable updates of Core, Plugins and Themes in the Wordpress Admin via DISALLOW_FILE_MODS
* Added the ability to disable Gutenberg Top toolbar mode
* Added the ability to disable Gutenberg Distraction free mode
* Added the ability to disable Gutenberg Spotlight mode
* Removed option to add a menu sidemenu option of the plugin
* Removed option for disabling WP notifications in Admin
* Modified and improved the Gutenberg JS to be non-dependent on jQuery
* Modified and improved the Profile JS to be non-dependent on jQuery
* Update of language files
* Tested with WordPress 6.2.0

= 5.3.0 =
* Tested with WordPress 6.1.1
* Fixed CSRF injection vulnerability
* Update of language files

= 5.2.15 =
* Added more options to remove from the user profile in admin
* Fixed bug with website url not removing from user profile
* Modified the functionality for removing elements from admin profile page
* Update of language files

= 5.2.14 =
* Fixed better check of WP Rest API namespaces that needs to be whitelisted when disabling Rest API for non-leogged in users

= 5.2.13 =
* Fixed bug with JS and CSS files

= 5.2.12 =
* Comp check with WordPress 5.7.2
* Added possibility to whitelist WP Rest API namespaces for specific reasons
* Update of language files

= 5.2.11 =
* Made sure that the unserialize function gets data to prevent Warnings

= 5.2.10 =
* Small redesign and better user experience
* Added possibility to disable auto updates of plugins and themes
* Fixed code comments to macth functions what they really do

= 5.2.9 =
* Made sure that comments stylesheet properly gets disabled

= 5.2.8 =
* Added Gutenberg options
* Added removal of generator tag for Product Filters plugins
* Fixed better translations
* Comp check with WP 5.5

= 5.2.7 =
* Added donation link for patreon
* Modified readme.txt
* Fixed translations

= 5.2.6 =
* Tested with WordPress 5.4.0
* Fixed so xmlrpc_rsd_apis is disabled in <head> with other Rest API links
* Fixed so WP Rest API is disabled for non loggedin users due to security
* Fixed better translations of some settings
* Changed minimum required WP version to 4.7 due to Rest API filter function

= 5.2.5 =
* Bug: Fixed a bug where wp_mail() would break if the plugin was active and no email was set in the options

= 5.2.4 =
* Added option to the Core section to disable WP Cron
* Added option to the Core section to disable automatic updating of WordPress and plugins
* Added option to the Core section to enable removal of image edits to save space on the server
* Fixed so Disable pings also inlcudes disabling of self pinging
* Updated translations

= 5.2.3 =
* Fixed Bug with WP languages not beeing removed on backend on profile page
* Fixed simple WP Editor's to the admin footer section

= 5.2.2.1 =
* Bumped a quote sign that may have caused some issues on some installs

= 5.2.2 =
* Redesigned the interface
* Fixed Emoji filter callback
* Fixed javascript improvements
* Fixed styling improvements
* Fixed better html in admin
* Cleaned the html output
* Improved naming and translations

= 5.2.1 =
* Fixed language bug where languages would not load correctly

= 5.2.0 =
* Made index file silenced
* Created a new main index file
* Major redesign of the code and improvements programatically
* Created new section Gutenberg (will be supported in the near future)
* Created new section Plugins (will list options for major plugins only)
* Removed ability for shortcode support in text widgets due to new WP HTML Widget support by default
* Removed WP Editor for textareas shortly (will be back in the near future)
* Improved shortcode support to excerpts
* Improved disabling of Emoji's
* Added more WordPress options
* Updated Swedish translation
* Added Norwegian translation

= 5.1.2 =
* Simplified the code and reduced calls against WordPress functions
* Added more options to WP Users section
* Fixed a styling bug with tables

= 5.1.1 =
* Wrapped <tr> within <tbody> due to unexpected behavior in some cases

= 5.1.0 =
* Removed unnecessary code
* Removed GZIP compression (it would break sites on some servers and it should not be neccessary if the server is configured properly)
* Removed plugin checkups, want to keep it simple to WordPress deafult and to major plugins only
* Removed Jabber, AIM checks from WP Users section (Non deafult wordpress)
* Added disabling of website url on users profile
* Improved registering of settings
* Major redesign of the layout
* Moved disabling of Emojis to WP Head section
* Updated text domain for langauges
* Updated Swedish, English language
* Tested on latest WordPress 5.1

= 5.0.4 =
* Enqueue of style
* Tested on latest WordPress 4.9.1

= 5.0.3 =
* Added option in WP Core to remove REST API links in header
* Added option in WP Core to remove WP Oembed discovery links in header
* Added option in WP Users section to disable Facebook url
* Added option in WP Users section to disable Twitter username

= 5.0.2 =
* Added ABSPATH to check if the files are called directly or not
* Small bug fixes

= 5.0.1 =
* Fixed a bug with translation files
* Fixed some misspelling in the code
* Fixed a properly preparation for localization of languages
* Added Swedish translation

= 5.0.0 =
* Added WP Editors for changing admin footer texts
* Restyled the interface
* Better optimisation of plugin code
* Refactored the uninstall process so all the options are deleted in the DB on uninstall
* Merged some options into one for better optimisation

= 4.0.3 =
* Added option in WP Users section to disable WordPress language chooser

= 4.0.2 =
* Improvement of Emoji disabling

= 4.0.1 =
* Bug fix of WP-Zoom dashboard removal

= 4.0.0 =
* Added option in WP Dashboard to remove WP-Zoom dasbhoard latest reviews
* Major refactoring of the code for better optimisation

= 3.0.8 =
* Added option in WP Dashboard to remove WooCommerce latest reviews
* Tested on latest WordPress 4.4
* Removed unused code snippets

= 3.0.7 =
* Added option in WP Core to remove q-Translate-X generator meta tag

= 3.0.6 =
* Small text changes
* Added option in WP Admin Toolbar section to remove WP Customize
* Added option in WP Admin Toolbar section to remove WP Edit

= 3.0.5 =
* Font color change
* Added option in WP Core to disable Emoji icons if not in use
* Added option in WP Dashboard to remove Yoast SEO Posts overview

= 3.0.4 =
* Small renaming of labels
* Added option in WP Core section to add shortcode capability for manual excerpts
* Added option in WP Core section remove Plugin, WordPress and Themes update notifications for non-admins
* Added option in WP Admin Toolbar section to remove WP Sitename
* Added option in WP Admin Toolbar section to remove WP New Content

= 3.0.3 =
* Added option in WP Dasboard section do remove BuddyPress Right Now meta-box
* Removed plugin image at plugins section

= 3.0.2 =
* Added new section WP Dashboard
* Update of POT file for translations

= 3.0.1 =
* Compatibility fix

= 3.0 =
* Major redesign of the interface
* Corrections of the comments in the code
* Corrections of the english language in interface
* Added the possibility to remove NextGen Gallery link in the admin toolbar
* Added serbian translation thanks to [Ognjend Djuraskovic](https://www.firstsiteguide.com/)
* Added icon on plugins section
* Update to Readme.txt

= 2.1.9 =
* Added new section WP Mail
* Added new tool in WP Admin Toolbar / Plugins section
* Removed unused code snippet
* Update on Readme.txt file

= 2.1.8 =
* Removed WPB Elements from admin sidebar and put it in the Settings region
* Added an option to display the WPB Elements in the main admin menu instead
* Update on Installation text
* Small optimisation of code

= 2.1.7 =
FIX: Plugin URI was wrong

= 2.1.6 =
* Added new section WP Users with new cool tools

= 2.1.5 =
* Added ability to remove Yoast SEO from admin toolbar

= 2.1.4 =
* New WP Optimisation tools added
* New WP Toolbar tools added
* Added Plugins sub category for WP Toolbar tools
* Readme.txt updated

= 2.1.3 =
* Updated tags
* Updated information
* FAQ section updated
* New WP Optimisation tools added
* Information button added

= 2.1.2 =
* Better version control in code
* Removed unused code snippets
* Interface improvement
* .POT file added to language folder

= 2.1.1 =
* Changelog improvement
* License URI added
* Version number incremented

= 2.1 =
* Small update to interface

= 2.0 =
* New interface
* Uninstall file added
* Description update
* Tags update
* Donation Link added

= 1.0 =
* Initial release

== Upgrade Notice ==
= 6.0.0 =
Complete rewrite. All settings have been reset — please visit Settings > WPBE Elements to re-configure your preferences after updating.
