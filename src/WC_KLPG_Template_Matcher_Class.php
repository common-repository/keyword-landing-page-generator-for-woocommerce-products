<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Template_Matcher{

	protected $shortcodeParameters;	
	protected $registeredShortcode;
	protected $contentRenderer;
	private $options;

	public function __construct($shortcodeParameters){

		$this->shortcodeParameters 	= $shortcodeParameters;
		
	}

	private function WC_KLPG_get_option($option){
		$WC_KLPG_options = $this->options = get_option('WC_KLPG_option');

		if( isset($WC_KLPG_options[$option]) && $WC_KLPG_options[$option] != '' ){
			return $WC_KLPG_options[$option];
		}else{
			return null;
		}
	}

	protected function get_shortcode_parameter($parameterToMatch){
		$parameterReturn = '';
		if( isset($this->shortcodeParameters[$parameterToMatch]) && $this->shortcodeParameters[$parameterToMatch] != ''){
			$parameterReturn = $this->shortcodeParameters[$parameterToMatch];
		}

		if(!empty($parameterReturn)){
			return $parameterReturn;
		}
	}

	public function match_content_type_to_template_or_shortcode(){
		
		$this->contentRenderer = $this->set_content_render_to_shortcode();
		return $this->contentRenderer;
		

	}

	protected function set_content_render_to_shortcode(){
			
			$this->contentRenderer['type'] 			= 'shortcode';
			$this->contentRenderer['shortcode']		= $this->get_shortcode_parameter('shortcode_name');
			$this->contentRenderer['idParameter'] 	= 'id';
			
			return $this->contentRenderer;
	}

}
?>