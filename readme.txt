=== ARENA SEO Plugin ===
Contributors: html5arena
Donate link: https://www.html5arena.com/
Tags: SEO, meta, keywords, page speed, redirect, sitemap
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 4.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin that improves page loading time and adds basic SEO capabilities to WordPress.

== Description ==

This plugin improves page loading time and adds basic SEO features to a WordPress site. 

SEO Features:
1. Control the meta title of each page or post.
2. Add meta description for each page or post.
3. Add meta keywords to each page/post.
4. Create Sitemap.XML file.
5. Redirect any page or post.
6. Redirect media pages to parent post.
7. Add NOINDEX to categories/author/tag pages.
8. Add NOINDEX tag to each page or post.
9. Easy embedding of analytics code and WMT verification code.

Loading Speed:
1. Move jQuery loading to the footer.
2. Remove query strings from static resources.
3. Add expired headers for better browser caching.
4. HTML Minification.
5. Remove google fonts.

ADS Injection:
1. Inject ads inside post content, after first, second, or third paragraph.
2. Inject ads code to the bottom of posts.

== Installation ==

This section describes how to install the plugin and get it working.

1. Either upload the plugin files to the `/wp-content/plugins/arenaseo` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to 'SEO Setting' and setup your preferences such as analytics code, WMT code and ads code.
4. See that new input boxes are added to the post editor and to the page editor. 
5. Use the input boxes in the editor to add meta title, meta description and meta keywords to posts and pages.
6. Use the 'Redirect' input box in the editor to redirect a post or a page to another URL.
7. Use the 'Noindex' checkbox in the editor to add a NOINDEX meta tag to a post or page.

== Frequently Asked Questions ==

= How to add analytics code ? =

Go to 'SEO Settings' and paste your analytics code inside the 'analytics' text box. 
Hit the 'Save' button. 
See that analytics code is addded to the footer of your website's html. 
Note that analytics code is not added for admin users. 

= How to add WMT code ? =

Go to 'SEO Settings' and paste your WMT verification code and hit the 'Save' button.
See that your WMT verification code is added to the header of your website.

= Why jQuery is moved to the footer ? =

jQuery is a small JS library which is used in many themes. Moving loading of jQuery to the footer 
prevents resource dependency and may help to improve the general loading time of a webpage.
By default, the plugin will move jQuery loading to the footer. To disable this feature go to 'SEO Setting'
and un-check 'Move jQuery to footer' check box.

= How to generate sitemap.xml ? =

Sitemap.xml file can help search engine to discover your website. Although it may not be a requirement, using this plugin 
you can generate sitemap.xml that contains all of the pages and post in your website.
To generate a sitemap.xml file on the root directory of your website, go to 'SEO Settings' and check the 'Add posts to sitemap' checkbox.
You can also add your pages to the sitemap by checking 'Add pages to sitemap' checkbox.

= How to add meta title and meta description to post ? =

When the plugin is active, you will see an SEO section at the bottom of the post editor. Use those input boxes and provide custom meta title,
meta description and keywords to each post. 

= What is expired headers ? =

Setting expired headers helps web browser to better cache your website and improves user experience.
The plugin adds expired headers by default. To disable expired headers go to 'SEO Settings' and uncheck 'Add expired headers' checkbox.
For expired headers to work, your web server must have mod_expired and mod_headers enabled (in Apache2 - a2enmod expires, a2enmod headers)
  
= What is HTML minification ? =

HTML minification removes redundant spaces and tabs from your final html output. This feaature is enabled by default and 
you can disable it by going to 'SEO Setting' and un-checking the 'HTML Minification' checkbox.

== Screenshots ==

1. The settings page of ARENA SEO plugin. 
2. More settings in the plugin settings page.
3. The new input boxes in the post editor.

== Changelog ==

= 1.0 =
* First release of the plugin.

== Upgrade Notice ==

= 1.0 =
NA