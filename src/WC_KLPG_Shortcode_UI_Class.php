<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Shortcode_UI{

    private $options;

    public function __construct(){
       
        add_action('media_buttons', array($this, 'WC_KLPG_shortcode_button'), 20);
        add_action('admin_footer-post.php', array($this, 'add_modal_html') );
        add_action('admin_footer-post-new.php', array($this, 'add_modal_html') ); 
       
    }

    private function WC_KLPG_get_option($option){
        $WC_KLPG_options = $this->options = get_option('WC_KLPG_option');

        if( isset($WC_KLPG_options[$option]) && $WC_KLPG_options[$option] != '' ){
            return $WC_KLPG_options[$option];
        }else{
            return null;
        }
    }

    public function WC_KLPG_shortcode_button() {
 
        echo '<button id="wcklpg-button" class="button wcklpg-add-dynamic-content" data-editor="content" title="' . __("WooCommerce Keyword Landing Page Generator - Add Dynamic WooCommerce Product", "wcklpg") . '">' . __("WooCommerce KLPG", "wcklpg") . '</button>';
        
    }

    public function add_modal_html(){
       
        $html ='';
        $html .= '<div class="wcklpg-shortcode-modal hidden">';
            $html .= '<div class="wcklpg-shortcode-modal-content">';
                $html .= '<div class="wcklpg-shortcode-modal-content-header">';

                        $html .= '<img src="'.plugins_url( '/images/shortcode-builder-topbar.jpg', dirname(__FILE__) ) . '" > ';
                        $html .= '<button type="button" class="wcklpg-modal-close">×</button>';
                        
                    $html .= '</div>';
                    $html .= '<div class="error-msg hidden"><span>'.esc_html__('One or more required fields needs to be set.','wcklpg').'</span></div>';
                $html .='<div class="wcklpg-fakescroll-container">';
                    $html .='<div class="wcklpg-shortcode-parameters-container">';
                        $html .= '<div class="wcklpg-shortcode-modal-parameters-table-container">';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
                    $html .= '<div class="wcklpg-shortcode-display-container hidden"><textarea disabled class="wcklpg-shortcode-display"></textarea></div>';
                    $html .= '<div class="wcklpg-modal-button-container">';
                    $html .= $this->build_basic_shortcode_buttons();  
                    $html .= '</div>';
                $html .= '</div>';
        $html .= '</div>';
        echo $html;
        
    }

    public function build_basic_shortcode_buttons(){
        $html  = '';
        $html .= '<table class="wcklpg-modal-button-table basic"><tr>';
        $html .= '<td width="50%"><button class="wcklpg-button wcklpg-modal-full-product">'.esc_html__('Full Page Product','wcklpg').'</button></td>';
        $html .= '<td width="50%"><button class="wcklpg-button wcklpg-modal-grid-product">'.esc_html__('Grid Product','wcklpg').'</button></td>';
        $html .= '</tr></table>';
        return $html;
    }
    
}
?>