=== Hide Title Remove, Hide Page And Post Title ===
Contributors: sminozzi
Donate link: https://billminozzi.com/donate
Tags: hide title, hide titles, hide page title, hide post title, disable title
Requires at least: 4.0
Tested up to: 6.6
Stable tag: 1.03
License: GPL-2.0-or-later

License URI: http://www.gnu.org/licenses/gpl-2.0.html

Remove Titles from Posts and Pages. Will attempt 3 approaches to ensure success in disabling the title. Choose the pages or posts to remove the title.

== Description ==
**Hide Title Remover**
★★★★★<br>

>The Hide Title Remover plugin allows you to easily hide titles from your WordPress posts and pages.

Comprehensive Title Removal Strategy: Leveraging 3 Approaches for Success!
This plugin will attempt 3 approaches to ensure success in disabling the title. 

1) It will try to do so using WordPress get_post_meta.
2) It will attempt to hide the title with CSS by changing the properties of the entry-title element. 
3) If these two approaches do not work, it will proceed to step 3 and allow you to locate the ID or class of the title element and inform the plugin. That's all we can do to ensure that the goal is achieved.

This plugin will attempt to automatically hide the title of your pages and posts as selected in the Settings Tab.
However, if your theme does not adhere to WordPress standards and the title continues to appear, you will need to identify the class or ID of your element (title) and add the details in the Settings Tab. There, in the Settings Tab, you will receive more information on how to do it.

If you choose to hide only selected pages and posts, the plugin will add a metabox* to all pages and posts for you to mark and decide whether the title will be displayed or removed.
(*) The plugin dashboard will provide you with more details about the Metabox on the help button.

Using Selected Pages Method, if possible, the plugin will completely removes the title instead of just hiding the title with CSS or JavaScript.

**If you encounter any issues, please request free support before leaving a negative review, as various factors such as low WordPress memory and other considerations may be at play. Check out our Troubleshooting tab.**


== Installation ==

## How do I install and activate the Hide Title plugin?

To install and activate the Hide Title plugin, you have several options:

1. **Install directly from the WordPress plugin directory**:
   - Go to Plugins > Add New in your WordPress dashboard
   - Search for "Hide Title Remover"
   - Click "Install Now" and then "Activate"

2. **Install via a downloaded ZIP file**:
   - Download the plugin ZIP file from the WordPress plugin directory
   - Go to Plugins > Add New in your WordPress dashboard
   - Click "Upload Plugin" and choose the downloaded ZIP file
   - Click "Install Now" and then "Activate"

3. **Install via FTP**:
   - Upload the `hide-site-title` folder to the `/wp-content/plugins/` directory on your server using FTP
   - Go to Plugins in your WordPress dashboard and find the "Hide Title" plugin in the list
   - Click "Activate"

Once the plugin is activated, you can configure its settings:

1. Navigate to the 'Tools' menu on your WordPress dashboard and click on 'Hide Title.'
2. Adjust the plugin settings according to your preferences.
3. Save the changes.

That's it! The Hide Title plugin is now installed, activated, and ready to use.

== Frequently Asked Questions ==

= How do I access the plugin after installation? =
After installing the plugin, navigate to the 'Tools' menu on your WordPress dashboard and click on 'Hide Title.'

= Can I revert the changes if I don't like the result? =
Certainly! You can revert the changes by deactivating the plugin. Rest assured, we do not alter any data on your WordPress site.

= Can I hide titles on custom post types? =
Yes, the plugin can be configured to hide titles on custom post types as well. 

= Is the Hide Title Remover compatible with the Gutenberg block editor? =
Yes, the plugin is fully compatible with the WordPress Gutenberg block editor. You can use the plugin to remove titles on pages and posts created with Gutenberg.

= Do I need technical knowledge to use this plugin? =
No, the plugin is designed to be easy to use, even for beginner WordPress users. The basic settings can be configured in just a few clicks. However, if your theme does not follow standards, you might need some technical knowledge to identify the title element.

= Can I use this plugin to hide product titles in WooCommerce? =
Yes, the plugin works with WooCommerce product pages. You can hide individual product titles by checking the checkbox in the plugin's metabox or hide all product titles in the plugin settings.

= Will hiding the title affect my SEO? =
Hiding the title with this plugin should not negatively impact your SEO as search engines will still be able to read the title in the HTML source code. 

= How do I find the ID or class of the title element if the title is not hiding? =
You can use your browser's developer tools (right-click on the title and select "Inspect" or "Inspect Element") to find the ID or class of the title element. Once identified, enter this information into the plugin settings under the appropriate section.

= Will this plugin work with any theme? =
The plugin is designed to work with most themes that adhere to WordPress standards. 

= What should I do if the titles are not hiding as expected? =
If the titles are not hiding as expected, you can follow these steps:

Check if the plugin is activated under the 'Plugins' menu.
Ensure that you have configured the plugin settings correctly under the 'Settings' menu.
If you're trying to hide titles selectively, make sure you've marked the corresponding metabox on each post or page.

= If I encounter an issue, where can I get support? =

After reviewing our <a href="https://siterightaway.net/troubleshooting/">troubleshooting page</a>, if the problem persists, visit the plugin's forum page and provide detailed information about the issue. 
If you do not receive a response within a business day, as sometimes emails fail to reach us, please visit   <a href="https://billminozzi.com/support/">our dedicated support page</a> 
We are in London timezone.



== Screenshots ==

1. Hide Title Plugin Dashboard
2. Hide Title Settings
3. Hide Title Troubleshooting

== Upgrade Notice ==
Now allows you to locate the ID or class of the title element and instruct the plugin to disable the titles, giving you full control over title visibility.

== Changelog ==
= 1.03 = July 8 2024 - Small Improvements
= 1.02 = May 24 2024 - Small Improvements
= 1.01 = May 20 2024 - Improved Read me
= 1.00 = Mar 10 2024
* Initial release
