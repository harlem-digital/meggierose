=== Plugin Name ===
Requires at least: 3.8
Stable tag: 3.7.4.1

== Changelog ==

= Version 3.7.4.1 (March 6, 2015) =

* Added: Backwards compatibility for icons.

= Version 3.7.4 (March 5, 2015) =

* Added: wptouch-icons-old module for compatibility with custom themes that haven't migrated their icon classnames.

= Version 3.7.3 (March 5, 2015) =

* Changed: Further refinement of icon sets, ensuring compatibility with Simple Social Icons plugin and other sources of icon fonts.
* Fixed: Reply link was being displayed beside comments with nested comments enabled, even when comments were closed.

= Version 3.7.2 (February 25, 2015) =

* Added: Desktop shortcode support now applies to content after WPtouch Pro's shortcodes have been processed and now also supports desktop theme shortcodes used inside alternate content added via the Mobile Content Extension.
* Added: Where categories are listed using the wptouch_fdn_ordered_cat_list() function, they can now be sorted prior to display using the 'wptouch_ordered_cat_list_categories' filter.

= Version 3.7.1 (February 24, 2015) =

* Added: Content processed for desktop shortcodes no longer cached on cart, checkout, and account pages.

= Version 3.7 (February 12, 2015) =

* Added: Overhauled support for shortcodes provided by desktop themes. WPtouch Pro can now optionally load content from WordPress with shortcodes processed.
* Fixed: Bug in URL-based filtering
* Fixed: Possible hidden redirect exploit on mobile/desktop switcher

= Version 3.6.6 (January 29, 2015) =

* Added: The ability for themes and extensions to disable Web-App Mode.
* Added: If you set a custom excerpt for your posts, WPtouch will use it when it displays related posts.
* Added: Support for category hierarchy in MobileStore
* Changed: More localization updates (German, Greek, Hindi, and Indonesian)
* Changed: More robust category listing
* Changed: We moved support to the Freshdesk platform. All links have been updated to point to our new ticket & knowledgebase and the new My Account page.
* Changed: Web-App Mode is not compatible with off-site payment gateways. MobileStore no longer allows Web-App Mode to be used when no compatible gateways are found.
* Changed: MobileStore now allows selecting a custom latest posts page.
* Fixed: Bug in MobileStore where it was not possible to completely disable product filters once any had been selected.
* Fixed: Layout bug in menu on sites with sequential nested menu items.

= Version 3.6.5 (January 9, 2015) =

* Added: Better support for Nextgen Gallery for sites using the Responsive Images extension.
* Changed: Updated localizations – Arabic, Chinese (Simplified & Traditional), Danish, Dutch, French, German, Greek, Hindi, Indonesian, Italian, Japanese, Korean, Russian, Swedish, Thai, Turkish, Vietnamese
* Changed: Change spaces in uploaded logo & startup image filenames to dashes to prevent display issues
* Changed: Removed festive icons
* Fixed: Spelling of devanagari in Advanced Type

= Version 3.6.4 (December 19, 2014) =

* Fixed: Bug in copy theme action.

= Version 3.6.3 (December 18, 2014) =

* Added: WPtouch Pro now auto-updates installed themes and extensions, ensuring your site is always completely up-to-date. Changes made to the theme will be saved (WPtouch Pro will create a new child theme with your changes) and custom themes are left unchanged.

= Version 3.6.2 (December 11, 2014) =

* Added: You can now upgrade your WPtouch Pro license from inside the plugin (if you allow 3rd-party cookies/have visited wptouch.com recently) with instant upgrade activation if your site is web-accessible.
* Added: A little festive spirit!
* Changed: Slimmed down plugin by moving screenshots out of the package.
* Changed: MobileStore - geolocating customers (to prefill location) relies on url_fopen being enabled - if it is disabled, no longer try to process the location.
* Fixed: Typo in our URL - oops!
* Fixed: Updated icon font with new icon & a new name to avoid conflicts with another plugin
* Fixed: Pagination glitch in MobileStore search

= Version 3.6.1 (November 20, 2014) =

* Added: Automatically disable WPtouch when activating WPtouch Pro (from WPtouch v3.6.1 on)
* Added: Highlight available theme & extension updates
* Added: Better support for Shortcodes Ultimate
* Added: Back-to-top links, and multilingual improvements in MobileStore
* Added: Minimum character count for mid-content ads in MultiAds
* Added: Pass along the querystring when redirecting to your custom landing page. (for ads compatibility)
* Changed: Improved Custom Latest Posts pagination handling
* Changed: Switch from WP_CONTENT_URL to content_url(), fixing menu icons for domain-mapped multisite installations
* Fixed: PHP error on WPEngine when debug mode was enabled
* Fixed: Eliminated some warnings and notices
* Fixed: Arrow directions for next/previous posts in RTL mode
* Fixed: Custom Latest Posts page now uses WPtouch posts-per-page setting

= Version 3.6 (November 6, 2014) =

* Added: New extension: Advanced Type (select custom Google fonts or use your Typekit & Fontdeck sets in WPtouch Pro)
* Added: Ability to ignore tags when identifying related content
* Added: Ability to have WPtouch work on specific URLs/pages only
* Added: Ability to have the featured slider show the latest posts from a specific post type
* Added: Prose theme now allows site owners to disable use of featured images as header background on single posts
* Added: Yelp option for footer social links
* Added: Startup Screen support for iPhone 6 and 6+
* Changed: Improved appearance of Web App Notice Message on the WordPress login page
* Changed: When viewing a WordPress media gallery item, the item's post parent is linked
* Changed: Share links adjust order and icon placement when the site is in an RTL language
* Changed: Homescreen icon now recommends 180x180 (best size for iPhone 6, 6+, backwards compatible)
* Changed: Updated add to home scripts to latest version (3.0.8)
* Fixed: WPtouch Pro can now disable certain Jetpack components that duplicate WPtouch functionality (Related Posts, Sharing Buttons, Like Box)

= Version 3.5.1 (October 4th, 2014) =

* Changed: Better home-template.php support in themes
* Fixed: Removed php warnings
* Fixed: Minor issues with our new MobileStore theme for WooCommerce
* Verified: MobileStore compatibility with WooCommerce 2.2.4

= Version 3.5 (October 1st, 2014) =

* Added: New theme MobileStore (eCommerce theme for WooCommerce!)
* Added: Option to turn off menu output on the Open homepage
* Added: Open theme now supports custom post type content
* Added: Optional comment support to Prose
* Changed: Updated FastClick module to 1.0.3 (fixes form issues)
* Changed: Theme browser now shows theme demos instead of screenshots for WebKit-based browsers (Safari, Chrome)
* Changed: Cache timing tweaked to allow filtering of cache behaviour in Infinity Cache
* Changed: If enabled in WPtouch, comment block is shown on pages only when they either accept comments and/or have comments to display
* Changed: Better SSL support w/ protocol-independent font and jQuery loading + image embeds
* Fixed: An issue where Check All/None for menu items would affect all theme menus
* Fixed: Better off-canvas menu support in Prose, Bauhaus and MobileStore
* Fixed: Prevent Web App Mode from getting stuck in a logout loop
* Fixed: Theme preview images causing SSL notices when viewing admin over HTTPS
* Fixed: Prevent mobile switch link from appearing in Web App Mode when pages are loading from cache
* Fixed: Bookmark icon not being used when bookmarking on iPad

= Version 3.4.9 (September 5th, 2014) =

* Fixed: Some customers reported ongoing issues with MIME-type detection for uploaded images.
* Updated: Compatibility with WordPress 4.0

= Version 3.4.8 (August 28th, 2014) =

* Fixed: Issue with uploading image files on servers that don’t advertise MIME type

= Version 3.4.7 (August 27th, 2014) =

* Changed: Only images (png, jpg, gif, svg) will be accepted for home screens and icons.
* Fixed: Issue with internal version upgrade notification

= Version 3.4.6 (August 26th, 2014) =

* Fixed: Sharing link text no longer output in Related Posts excerpts
* Fixed: Checkboxes & radio buttons in comment forms are visibly checked
* Fixed: Featured slider showing selected pages
* Fixed: Display of comment count at top of posts in Bauhaus after comments have closed
* Fixed: Shortcodes are now processed properly in the Multi-ads extension advertising code (Pro only)
* Fixed: RTL issues with related posts
* Changed: Now allow mid-content advertisements in Multi-ads (Pro only)

= Version 3.4.5 (August 7th, 2014) =

* Added: New and improved global back button in Web-App Mode
* Fixed: Comments remain visible after commenting has been closed for a given post/page

= Version 3.4.4 (July 17th, 2014) =

* Fixed: Custom Post Type module sometimes showed an error when searches were performed
* Fixed: Featured Slider now shows posts from custom post types enabled for mobile display
* Fixed: An issue which could cause the switch link to not store a cookie remembering the user's choice
* Fixed: Bauhaus with off-canvas and fly-in login form issue
* Changed: Improved off-canvas menu in Bauhaus and Prose themes
* Changed: Reduced Custom Post Type module memory consumption

= Version 3.4.3 (July 12th, 2014) =

* Added: Bauhaus - off-canvas menu
* Added: Back button for CMS, Bauhaus in Web-App Mode
* Fixed: Back button showing when's there's no back history left
* Fixed: An issue with homescreen icons on Android
* Fixed: Bauhaus: search and custom post types
* Fixed: Minor admin issues
* Fixed: Security issue with file uploads and authenticated users (found by Sucuri, Inc.)
* Fixed: Custom post type support for customers whose web servers strip array indices from form fields
* Fixed: Related posts excerpts too long in some languages
* Updated: Custom post type content is included in category/taxonomy/tag archive listings
* Updated: Fastclick js library to 1.0.2
* Updated: HammerJS library to 1.1.3
* Updated: Prose - Improved new off-canvas menu
* Changed: Menu Setup in admin now refers to menus by name instead of location for consistency

= Version 3.4.2 (June 27th, 2014) =

* Fixed: Issue with date/time in certain themes

= Version 3.4 (June 26th, 2014) =

* Added: New theme: Prose
* Added: New extension: Multi-Ads - multiple ad units per page view for Small Business+ customers
* Fixed: An issue with load more links in Web-App Mode with persistence enabled
* Fixed: CMS - an issue with blog listings on Firefox Mobile
* Fixed: An issue where an unwritable logfile would cause errors with debug enabled
* Changed: Updated all language files
* Changed: Improved server API for updates

= Version 3.3.4 (June 5th, 2014) =

* Fixed: An issue which caused mobile themes to be shown inside Twitter app on iPads
* Fixed: Improved Android Firefox browser and older browsers and OS versions support
* Fixed: When deactivating the license on a site license credentials became exposed
* Fixed: Wrong message in license area when a license is expired
* Fixed: Responsive Images extension - URL not active on images linked to the URL (can’t click/tap)
* Fixed: Simple theme - Child menu items in front page menu expanded by default

= Version 3.3.3 (May 30th, 2014) =

* Changed: Reduced server API calls to determine correct time offset
* Fixed: Issue with custom post types warning message
* Fixed: Removed API calls to determine server time

= Version 3.3.2 (May 29th, 2014) =

* Added: Style support for Contact Form 7 forms in posts and pages
* Fixed: Titles sometimes included HTML entities in shared Twitter content
* Fixed: Links to posts and comments in Web-App Mode not working correctly
* Changed: When enabled, related posts are now displayed before the next/previous links
* Changed: Custom post types for display are now saved differently, allowing for more reliable settings behaviour and display

= Version 3.3.1 (May 19th, 2014) =

* Fixed: Increased theme/add-on cache time

= Version 3.3 (May 15th, 2014) =

* Added: New theme! Open makes it easy to create a small Business mobile website
* Fixed: Issue where page icons configured for WPtouch no longer showed in WPtouch Pro when WPtouch was removed
* Fixed: Menu items for custom taxonomy terms now link to the correct term archive URL
* Fixed: Issue on WordPress multisite where subdomain sites wouldn’t be able to download themes/extensions
* Fixed: Issue where single-file plugins could not be disabled the Compatibility screen
* Fixed: Issue where WooRank wouldn’t properly detect WPtouch Pro
* Fixed: Responsive images on WordPress multisite would sometimes show an image URL of ‘0’
* Fixed: Issue where links extension info did not correctly target the extension in the browser
* Fixed: Issue where some JavaScript was loaded over HTTP when the page was loaded via HTTPS
* Changed: Updated URL information in Infinity Cache
* Changed: Use jQuery 2.x setting now includes jQuery 2.1.1 (latest)
* Changed: Related Posts first finds posts with at least two matching tags; if not enough are found, finds posts with at least one matching category
* Updated: WPtouch recreates custom post types and taxonomies registered in the desktop theme for display in mobile themes.

= Version 3.2.4 (April 22nd, 2014) =

* Added: Support for Windows Phone 8.1
* Updated: Product links from bravenewcode.com to wptouch.com

= Version 3.2.3 (April 20th, 2014) =

* Intentionally skipped

= Version 3.2.2 (April 9th, 2014) =

* Fixed: Only show WPML switcher when WPML is installed and active
* Changed: Featured content slider now uses slugs for category/tag filtering

= Version 3.2.1 (March 19th, 2014) =

* Added: Ability to enable/disable WPML language switcher in theme
* Updated: Translations

= Version 3.2 (March 11th, 2014) =

* Added: WPML support in all themes
* Added: Ability to add your WPtouch Pro referral code to the “Powered by WPtouch Pro” link
* Fixed: Problem with WPtouch Pro update notification not showing
* Fixed: Link to network plugins page for updating WPtouch Pro on multisite
* Changed: Spanish translation renamed to es_ES.mo/po - please verify selection in admin menu

= Version 3.1.8 (February 21st, 2014) =

* Changed: Added caching to API requests to minimize external HTTP queries

= Version 3.1.7 (January 29th, 2014) =

* Added: Firefox OS mobile support
* Added: Instagram to footer social links
* Fixed: An issue with WPTOUCH_CACHE_COOKIE that could cause issues on servers with high load
* Fixed: An issue with the WPtouch custom die handler
* Fixed: An issue with Infinity Cache (update it to v.1.0.1 or higher along with 3.1.7)

= Version 3.1.6 (January 15th, 2014) =

* Fixed: Issue with Simple Sitemap Plugin and the number of posts per page
* Fixed: Featured Slider now respects post/page ID order, better RTL behaviour
* Fixed: Rare issue with slashes appearing in the site title
* Fixed: An issue with bookmark icons showing incorrectly in Web App Mode notice bubble
* Fixed: Various RTL issues in WPtouch Pro and themes
* Fixed: Other customer reported small bugs and issues
* Note: All themes have been updated, make sure you update your themes as well folks!

= Version 3.1.5 (Dec 20th, 2013) =

* Fixed: Style issues in Bauhaus (1.0.7)
* Fixed: WordPress smileys alignments
* Fixed: Excluded entries from categories still appearing in posts navigation
* Fixed: Added meta charset html tag for better compatibility with non-english sites

= Version 3.1.4 (Dec 17th, 2013) =

* Fixed: Issues running WPtouch correctly on Windows servers
* Fixed: Memory issues on sites with huge taxonomies
* Fixed: Excessive database queries when related posts is enabled
* Fixed: An issue which could cause Sharing Links not to display
* Changed: More admin styling improvements for WordPress 3.8
* Changed: Optimizations and file cleanup

= Version 3.1.3 (Dec 13th, 2013) =

* Changed: Upgrade routines from WPtouch free to Pro (Translation: fixed broken 3.1.2 release)

= Version 3.1.2 (Dec 13th, 2013) =

* Added: Support for WordPress 3.8, styling for new 3.8 Admin Color Schemes
* Added: Support for Vine videos
* Added: Tumblr to Social Links
* Changed: Featured gallery images and thumbnails now fallback to WordPress sizes if WPtouch's versions haven't been created yet (big speed boost!)
* Fixed: An issue which could cause the featured slider to be shown even though it was disabled
* Fixed: An issue which could cause problems related to formerror.php
* Fixed: Removed call to erroneous get_error() function in Cloud migration routines
* Fixed: Small file operations issues

= Version 3.1.1 (Dec 4th, 2013) =

* Fixed: An issue which could cause the featured slider to disappear from theme settings

= Version 3.1.1 (Dec 4th, 2013) =

* Fixed: Improvements for Cloud updating mechanism
* Fixed: Admin theme preview on non-English installs
* Fixed: Issue with menu, search buttons in themes not opening menus when some jQuery versions are present
* Fixed: Checked to see if wptouch-data/cache directory is writable before concatenating javascript files
* Updated: Various translations

= Version 3.1 (Nov 27th, 2013) =

* Added: Support for BraveNewCloud Extensions (New extensions: Mobile Content, Infinity Cache, Image Optimization)
* Added: Support for BraveNewCloud Themes (New themes: Bauhaus, Hammock)
* Added: iOS 7 homescreen icon size support
* Added: iOS 7 optimizations and support
* Added: Migration routine which moves wptouch-data folder from /uploads to /wp-content
* Added: WPTP will re-install an active theme automatically if it's deleted and is a cloud theme
* Added: WPTP will rebuild wptouch-data folder if deleted & reinstall an active theme automatically if it's a cloud theme
* Added: Advanced setting for turning off post titles in featured slider
* Added: Advanced setting for choosing between 3, 5 & 10 posts for featured slider
* Added: Featured slider to Simple theme (shows on blog and home page)
* Added: You can now show sub-items in Simple's homepage menu
* Added: Back-to-top links in all theme footer areas
* Added: New tappable module to add better touch responsiveness alongside fastclick module
* Added: Login form fly-in & options for all themes
* Added: Advanced setting for sharing links to be shown on pages as well

* Fixed: An issue with local anchor links in Web App Mode
* Fixed: Issues with headers in web-app mode (all themes)
* Fixed: Issues with Smart App Banners (all themes)
* Fixed: An issue which could cause the message bubble not to show if set to 'every time'
* Fixed: Margin removed on images inside tables (all themes)
* Fixed: Admin - issue where new featured slider settings weren't hidden if slider disabled
* Fixed: Admin - issue with placeholders appearing after images were uploaded
* Fixed: Admin - issue with settings forgetting themselves
* Fixed: Admin - issue with licenses forgetting themselves
* Fixed: Admin - disabling custom posts types support in themes that support them hides the settings related to it
* Fixed: Web App Mode now checks core settings' ignored urls in addition to web app mode ignored urls when persistence is enabled
* Fixed: Warning message in concat Foundation module
* Fixed: Issue with upgrading themes in WP multisite when network activated
* Fixed: Deleting and active extension allows it to be re-downloaded
* Fixed: Issue with Featured Content Gallery where a large blank area could show with lots of images

* Changed: Renamed extensions to extensions
* Changed: Made 'Bauhaus' the default theme after settings reset
* Changed: Classic - Larger thumbs, improvements and changes for tablets
* Changed: Better web-app mode behaviour and appearance on iOS7
* Changed: Fresh new admin appearance for tabs, new/advanced flags, buttons
* Changed: Moved sharing links options to branding options from general options in theme settings
* Changed: Moved 3 core settings' order: Now Site Title & Byline, Regionalization, Display Mode
* Changed: Moved wptouch-data directory to wp-content instead of wp-content/uploads
* Changed: New design for theme & add-on browsers
* Changed: Split out admin pages to (Themes & Extensions, Theme Settings, Add-On Settings)
* Changed: Can now update cloud themes & extensions in the admin
* Changed: Removed setting for 'Hide Address Bar' caused issues and conflicts on some mobile devices
* Changed: Add2home script and bubble design to include iOS7, now incorporates web fonts for appearance
* Changed: Made switch link settings advanced

* Updated: FontAwesome CSS Icon library to version 3.2.1
* Updated: FastClick JS library to version 0.6.11
* Updated: add2home script to 2.0.11 (w/ iOS 7 support)
* Updated: Advanced jQuery option to 2.0.3

= Version 3.0.9.3 (October 17th, 2013) =

* Fixed: Link issue with related links

= Version 3.0.9.2 (October 17th, 2013) =

* Added: Related posts capability (all themes)

= Version 3.0.9.1 (August 29th, 2013) =

* Added: ability to select synchronous or asynchronous Adsense via the settings

= Version 3.0.9 (August 24th 2013) =

* Added: wptouch_force_mobile_device filter for developers to force mobile mode
* Added: Custom latest posts page for Simple theme
* Added: Ability to use custom field data for post thumbnail images in Classic Redux
* Fixed: Issue where custom advertisements would sometimes be hidden
* Changed: Switched to Google's new asynchonous advertising code

= Version 3.0.8 (August 5th, 2013) =

* Added: Ability to point to wptouch-data directory location from wp-config.php
* Added: Comment moderation notice text when comments require moderation to be displayed
* Fixed: An issue that could prevent YouTube videos without http in the url from re-sizing
* Fixed: Classic Redux - Menu drop-down issues on some Android devices
* Fixed: CMS - blog post titles overflow issue in non-WebKit browsers
* Fixed: CMS - featured slider not appearing in RTL
* Fixed: Switch link not appearing in Chrome on iOS (all themes)
* Fixed: Incompatibility with WordPressSEO by Yoast

= Version 3.0.7 (July 23rd, 2013) =

* Fixed: Possible XSS security issue

= Version 3.0.6 (July 12th, 2013) =

* Fixed: Search input in themes causing zoom on some devices
* Fixed: Issues with Google ads on Android devices causing blank pages
* Fixed: Issues with ads in Simple, CMS & Classic Redux themes
* Fixed: Viewport width issues on some Android devices
* Fixed: Web-App Mode features displaying outside of Web-App Mode
* Fixed: Better audio, video handling in Web-App Mode
* Fixed: An issue which could cause CLassic to show on tablet browsers w/o Tablet support enabled
* Changed: Social links in the footer now open in a new window

= Version 3.0.5 (July 5th, 2013) =

* Added: Support for new WordTwit free version in Classic Redux
* Added: New AJAX setting for desktop switch link
* Fixed: Issues with WordTwit Pro & Classic Redux theme
* Fixed: Issue with click-to-call links
* Fixed: Removed space from "Search" text in Classic theme
* Fixed: Custom menu items can now be opened in a new window
* Fixed: Issue with insecure content on https content pages
* Fixed: Pinterest link in social links
* Fixed: Issue with WPtouch nextpage functionality affecting desktop themes
* Fixed: RTL text display in featured slider titles
* Fixed: Issue with security nonce failure for desktop switch link
* Fixed: Issue with Manage WP update mechanism
* Fixed: Problem with object caching and WPtouch Pro due to autoload in settings
* Fixed: Issue with duplicate content in <title> tag when using WordPress SEO
* Fixed: Issues with search post and page results
* Changed: Small fix for date display in blog
* Changed: Prevented copied and child themes from being deleted if they're active
* Changed: Better tablet support & detection for Classic theme (now catches more Android tablets and Windows 10 touch tablets)

= Version 3.0.4 (May 30th, 2013) =

* Fixed: Viewport issues on Android devices
* Fixed: WPtouch Pro not showing for Lumia 920, other touch Windows Phone devices
* Added: Ability to select a page to show custom latest posts

= Version 3.0.3 (May 27th, 2013) =

* Added: Classic - Setting to turn off page titles
* Added: Setting to disable removal of featured slider posts from blog listings
* Added: Hebrew language
* Fixed: Issues with parent links in drop-down menus
* Fixed: Simple - Multi-page navigation appearing twice
* Changed: Enabled user-scaling by default in browsers, disabled in Web-App Mode
* Changed: Viewport to help Android devices that have issues with the user-scalable attribute

= Version 3.0.2 (May 22nd, 2013) =

* Added: Classic - Setting to disable post date
* Added: Classic - New post thumbnail options, post thumbnails on archive pages
* Added: Classic - Drop-down option to show 'Menu' text instead of icon
* Added: Simple - 3D menu (Advanced setting)
* Added: Vimeo, Pinterest, LinkedIn & RSS to footer social links
* Added: Hungarian translation
* Added: Slide transition speed (Advanced setting) for featured slider
* Added: Silk mode user agent for Kindles
* Added: Setting to leave administration panel untranslated even though themes are translated
* Added: Menu setting to change parent menu item to be a link or toggle children
* Added: Better display of slider images
* Fixed: Classic - An issue where the menu would not be shown if tab-bar was turned off
* Fixed: Simple - issue with ads in the header
* Fixed: Issue with WPtouch Pro showing for Windows desktop touch devices
* Fixed: Issue with Featured slider and missing entries on archive pages and feeds
* Fixed: Issue with WPtouch Pro news in overview not loading
* Fixed: Issue with junk characters in email subject line when sharing a post with unicode content
* Fixed: Issue with multipage links on single post pages
* Fixed: Issue regarding licensing in secondary multisite sites
* Fixed: Issue with Web-App Mode, persistent links
* Fixed: Issue that caused comment counts in the WP admin panel to be shown as '0'
* Fixed: Issue with ads in theme headers
* Fixed: Issue with custom post types not showing on the blog listings page
* Changed: Classic - date, author post settings now respected on single posts as well
* Changed: Classic - comments icon and number now hidden for posts with no comments, or comments disabled
* Changed: CMS - minor usability improvements from customer feedback
* Changed: Added translatable text for Post and Page text in search results
* Changed: Updated FontAwesome module to 3.1.1
* Changed: Improved menu parent/child behaviour in drop-down menus
* Changed: Improved RTL styling in themes
* Changed: Desktop switch-to-mobile links are now output using Javascript and AJAX - resolves desktop caching issues
* Changed: Network activated plugins are now listed in the compatibility section of WPtouch Pro

= Version 3.0.1 (May 8th, 2013) =

* Added: Search option in Simple theme
* Added: Failure case for when an icon-set doesn't properly install
* Added: Setting in CMS theme to enable/disable category slider
* Added: Internal image resizing for large Site Logo images
* Added: Advanced setting to adjust the target for the mobile switch link
* Fixed: Bugs with Simple theme header area
* Fixed: Removed whitespace from Google adsense settings
* Fixed: Debug warnings in notification code
* Fixed: Issue with using page IDs for Featured Slider
* Fixed: Issue with Classic Redux menu items display on tablets
* Fixed: Issue where clicking "Preview Mode" in notification center made reading the setting difficult
* Changed: Modified upgrade logic on the plugins page
* Changed: Minor text changes for consistency in admin settings
* Changed: Category slider in CMS now filters categories excluded via Foundation settings

= Version 3.0 (May 2nd, 2013) =

* Initial release

= Version 3.0GM (May 1st, 2013 ) =

* BraveNewCode internal Gold Master release
