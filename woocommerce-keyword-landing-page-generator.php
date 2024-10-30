<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Key Word Landing Page Generator
 * Plugin URI:        https://cosmosplugin.com
 * Description:       Use Key Words, UTM Codes, or URL Parameters to generate landing pages for your WooCommerce Shop
 * Version:           1.0.3
 * Author:            Cosmos Web Interactive
 * Author URI:        https://cosmosplugin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die("Don' be messin' with us, man!");
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if( is_plugin_active( 'keyword-landing-page-generator-for-woocommerce-products/woocommerce-keyword-landing-page-generator.php' ) ){
	
	define( 'WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_DIR', plugin_dir_path( __FILE__ ) );
	define( 'WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR', WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_DIR. '/src/' );
	
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Settings_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_URL_Rewrite_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Custom_Styles_Scripts_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Main_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Query_Arguments_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Post_Object_Array_Getter_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Taxonomy_Matcher_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Template_Matcher_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_HTML_Render_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Shortcode_UI_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Go_Pro_Class.php');
	require_once(WOOCOMMERCE_KEYWORD_LANDING_PAGE_GENERATOR_INC_DIR.'WC_KLPG_Settings_Footer.php');
	
}


register_activation_hook( __FILE__, 'wcklpg_activate_plugin' ) ;
register_deactivation_hook( __FILE__, 'wcklpg_deactivate_plugin' );

add_action('wp', 'start_wcklpg_session');
add_action('plugins_loaded', 'WC_KLPG_plugin_loader');
add_action('admin_init', 'WC_KLPG_admin_loader');

add_action('template_redirect', 'WC_KLPG_delete_post_ids_array_transient');
add_shortcode( 'wcklpg', 'WC_KLPG_generator' );

/*
*	Begin User Session - Used for prevention of duplicate content
*/
function start_wcklpg_session() {
    session_start();
}


/*
*	Initializing Shortcode Builder UI Class
*/
function WC_KLPG_admin_loader(){
	$shortcodeUI = new WC_KLPG_Shortcode_UI();
}


/*
*	Initializing Main Classes
*/
function WC_KLPG_plugin_loader(){
	
	$WC_KLPG_url_rewrites = new WC_KLPG_URL_Rewrite();
	$WC_KLPG_url_rewrites->init();

	$WC_KLPG_custom_styles_scripts = new WC_KLPG_Custom_Styles_Scripts();
	$WC_KLPG_custom_styles_scripts->init();

}


/*
*	Deletion of post id array transient - done to keep the DB clean of transient options
*/
function WC_KLPG_delete_post_ids_array_transient(){
	if( session_id() ){
		delete_transient('wcklpg_postids_'.session_id());
	}
}


/*
*	Shortcode function - returns rendered dynamic content
*	$parameters = shortcode parameters
*/
function WC_KLPG_generator($parameters){	
	
	$shortcodeParameters = shortcode_atts( array(
			'content_type'	=> '',
			'quantity'		=> '',
			'renderby'		=> '',
			'shortcode_name'=> ''
		), $parameters );
	
	$dynamicContentGen 	 = new WC_KLPG_Main( $shortcodeParameters);
	$dynamicContent 	 = $dynamicContentGen->get_dynamic_content();
	
	return $dynamicContent; 

}

function wcklpg_activate_plugin(){
	flush_rewrite_rules();
}
function wcklpg_deactivate_plugin(){
	// empty for now
}
?>