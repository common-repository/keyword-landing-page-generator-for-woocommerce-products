<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Custom_Styles_Scripts{

	public function init(){
		add_action('admin_enqueue_scripts', array($this, 'wcklpg_settings_script') );
		add_action('admin_enqueue_scripts', array($this, 'enqueue_button_script') );
	}

	
	public function wcklpg_settings_script(){
		wp_register_script( 'wcklpg_settings_script', plugins_url( '../js/wcklpgSettings.js',__FILE__), array('jquery') );
       	wp_register_script( 'jquery-cookie', plugins_url( '../js/libs/js.cookie.js',__FILE__), array('jquery') );
       	wp_enqueue_script( 'wcklpg_settings_script');
       	wp_enqueue_script( 'jquery-cookie');
	}

	
	public function enqueue_button_script(){
        wp_enqueue_style('wcklpg_dashboard_styles', plugins_url( '../css/wcklpgDashboard.css',__FILE__) );
        wp_register_script( 'WC_KLPG_shortcode_button', plugins_url( '../js/shortcodeButton.js',__FILE__), array('jquery') );
        wp_enqueue_script( 'WC_KLPG_shortcode_button');
        wp_localize_script('WC_KLPG_shortcode_button', 'WC_KLPG_shortcode_button_vars', array(
                'WC_KLPG_options' => __( get_option('WC_KLPG_option') )
            )
        );
    }

    
}
?>