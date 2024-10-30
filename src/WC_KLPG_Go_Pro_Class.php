<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_KLPG_Go_Pro {

	protected $proUpgradeUrl;

	public function __construct () {
		$this->proUpgradeUrl = '//cosmosplugin.com/';
	}

	public function render_go_pro_html () {
		echo '<h3>'.esc_html__('Upgrade To Cosmos Infinite Content Engine', 'wcklpg').'</h3>';
		?>

<div class="wcklpg-go-pro-container">
	<p>WooCommerce Keyword Landing Page Generator has a big brother in <strong>Cosmos Infinite Content Engine for WordPress.</strong></p>

	<p>Cosmos Plugin adds complete control over the content that is generated in addition to adding support for other content types. WooCommerce Products, Posts, Pages, Images and any other content type present in your WP installation will be able to be added as content blocks.</p>

	<p>The name is different for a reason - <strong>Cosmos Plugin</strong> can provide the savvy user with virtually infinite content combinations that are always relevant to your customers' interests with a small amount of setup and know how.</p>
	<div class="upgrade-button-container">
		<a target="_blank" href="//cosmosplugin.com/cosmos-plugin-pricing/" class="wcklpg-button upgrade-button">Upgrade to Cosmos Plugin Today</a>
	</div>
	<h3>Cosmos Plugin Feature List :</h3>
	<ul class="cosmos-feature-list">
	 	<li>Everything that WooCommerce Keyword Landing Page Generator offers</li>
	 	<li>Premium online support for one year</li>
	 	<li>Automatic updates for one year</li>
	 	<li>Support for all content types : Posts, pages, images, etc.</li>
	 	<li>Quantity Control - set the number of content blocks to be rendered with a single instance of the shortcode</li>
	 	<li>Render Control - render using standard markup, content type shortcodes or template files</li>
	 	<li>Include or Exclude Featured Image - for posts, pages and other content types that have featured images, you can control whether or not to include the featured image in the rendered content block</li>
	 	<li>Ordering - set ordering to be random, by name, author, date published, or date modified</li>
	 	<li>Content filtering - gain complete control over the generated content by filtering out certain results or limiting the returned content to specified categories or tags</li>
	 	<li>Ignore URL parameters - set some content blocks to ignore incoming url parameters, keywords or utm codes and display completely random content or content from a specific category or tag, regardless of the incoming parameters</li>
	 	<li>Complex query support - advanced users can set multiple url parameters with different operators</li>
	</ul>
	<div class="cosmos-screenshots">
	<h3>Cosmos Infinite Content Engine for WordPress Screenshots</h3>
 	<div>
 		<a title="Click to open full sized screenshot" href="https://cosmosplugin.com/wp-content/uploads/2015/12/shorcode-builder-standard.jpg" target="_blank"><img src="https://cosmosplugin.com/wp-content/uploads/2015/12/shorcode-builder-standard.jpg" ></a>
 		<p>Cosmos Shortcode builder overlaid on the WordPress default editor.</p>
<p>Cosmos Plugin provides an easy to use, intuitive interface for generating dynamic content. It works great in both Visual as well as HTML editor modes.</p>
 	</div>
 	<div>
 		<a title="Click to open full sized screenshot" href="https://cosmosplugin.com/wp-content/uploads/2015/12/shorcode-builder-visual-composer.jpg" target="_blank"><img src="https://cosmosplugin.com/wp-content/uploads/2015/12/shorcode-builder-visual-composer.jpg" ></a>
 		<p>Shortcode Builder used with Visual Composer page builder.</p>
<p>Cosmos works well with page builder plugins such as Visual Composer. Here you can see the shortcode builder becomes available in the text box module.
This plugin has also been tested and works well with Elegant Themes’ popular Divi theme.</p>
 	</div>
 	<div>
 		<a title="Click to open full sized screenshot" href="https://cosmosplugin.com/wp-content/uploads/2015/12/settings-page-full.jpg" target="_blank"><img src="https://cosmosplugin.com/wp-content/uploads/2015/12/settings-page-full.jpg" ></a>
 		<p>Main settings page – this is where we add URL parameters to listen for as well as register shortcodes and templates for content types.</p>
<p>The settings page for Cosmos Plugin is where UTM codes or URL parameters are set that will be used to generate dynamic content.
This is also where content types are mapped to templates or shortcodes which Cosmos uses to render the dynamic content.</p>
 	</div>
 	<div>
 		<a title="Click to open full sized screenshot" href="https://cosmosplugin.com/wp-content/uploads/2015/12/advanced-settings-page.jpg" target="_blank"><img src="https://cosmosplugin.com/wp-content/uploads/2015/12/advanced-settings-page.jpg" ></a>
 		<p>Enable or Disable Advanced Term Settings</p>
<p>The advanced settings section is where you can enable or disable the advanced WordPress Term parameters in the shortcode builder for more granular control over the content that is generated.</p>
 	</div>
 	<div>
 		<a title="Click to open full sized screenshot" href="https://cosmosplugin.com/wp-content/uploads/2015/12/advanced-settings-page.jpg" target="_blank"><img src="https://cosmosplugin.com/wp-content/uploads/2015/12/advanced-settings-page.jpg" ></a>
 		<p>Use this page within the settings to access support and up to date documentation.</p>
<p>Links to the latest documentation, the support ticketing system and an up to date list of known shortcodes for content types is all located here.
A brief quick start tutorial video is also embedded directly into this page.</p>
 	</div>
 	</div>

</div>

		<?php

	}	

} // end class

?>