=== Plugin Name ===
Contributors: cosmoswebinteractive
Donate link: https://cosmosplugin.com/
Tags: woocommerce, utm codes, url parameters, dynamic landing page, keywords, landing pages, dynamic content, landing page generator, landing page,ads,google ads,landing page customization,keyword,shortcodes,adwords,bing,targeted landing page,targeted landing pages,conversion,conversions,conversion optimization,optimization
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Marketing campaigns are only as good as the pages they lead to. It's difficult to make sure that relevant content and products are displayed to people who click on your ads. 

Luckily, third party ad services send information to your website about each click.

The WooCommerce Keyword Landing Page Generator looks at the information coming in and serves up WooCommerce products that match what the user was searching for! 

Generate dynamic WooCommerce products in a landing page based on incoming url parameters, keywords or utm codes -- the information sent to your website from third party ad services such as Google Adwords.

Every version of the landing gets a unique, SEO friendly URL -- /bags/leather/ and /bags/canvas/ and /bags/denim/ -- One page, unlimited combinations of WooCommerce products from your shop, all relevant to what the user was searching for.

See https://cosmosplugin.com for more information.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/keyword-landing-page-generator-for-woocommerce-products` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->WCKLPG screen to configure the plugin
4. Enter URL Parameters ( these can be keywords, utm codes or any other parameter provided by a third party ad service )
5. Use the shortcode builder on pages, posts or any other content type to add dynamic WooCommerce products

== Frequently Asked Questions ==

Q. What types of content will WooCommerce Keyword Landing Page Generator work with?

A. This plugin works exclusively with WooCommerce products.

Q. How many URL parameters can be set?

A.There is no limit to the number of URL parameters that can be set. The settings page provides an easy to use way of adding an unlimited number of URL parameters, along with operators for each – IN or NOT IN

Q. Does WooCommerce Keyword Landing Page Generator support pretty permalinks?

A. Yes.
What this means is that instead of relying on ugly URL parameters, such as:
http://yoursite.com/landing-page/?clothing=t-shirts
You will be able to use a standard URL! The above becomes:
http://yoursite.com/landing-page/clothing/t-shirts/
If you don’t want to use pretty permalinks, standard URL parameters will still work. UTM codes can still be used for both tracking and generating dynamic content with WooCommerce Keyword Landing Page Generator.

Q. What format do UTM Parameters need to be entered in?

A.Enter UTM parameters in the following format:
utm_source
utm_medium
utm_campaign
utm_content
utm_term
No special characters and no values.

Q. How does WooCommerce Keyword Landing Page Generator work?

A. The plugin works by adding a custom shortcode to a page or post. The shortcode has parameters to determine if you want a full product or a grid product displayed.
Once the page or post with the shortcode loads, it will first check to see if any URL parameters are present. If there are URL parameters, it will look for content that matches it in categories, tags or any other taxonomy. WooCommerce Keyword Landing Page Generator looks at all taxonomies associated with a given content type. Once a match is found, then that content will be displayed. 

== Changelog ==

= 1.0 =
* First Release

= 1.0.2 =
* Change settings page menu item location

== Upgrade Notice ==

= 1.0.2 =
Version 1.0.2 Makes the settings page more accessible