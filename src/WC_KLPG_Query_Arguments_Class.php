<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Query_Arguments{

	protected $shortcodeParameters;
	protected $contentTax;
	private $options;

	public function __construct($shortcodeParameters, $contentTax){

		$this->shortcodeParameters 	= $shortcodeParameters;
		$this->contentTax 			= isset($contentTax) ? $contentTax : '';
		$this->options 				= get_option('WC_KLPG_option');

	}

	private function WC_KLPG_get_option($option){
		//error_log( "***-- WC_KLPG_get_option");

		$WC_KLPG_options = $this->options;

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

	
	public function build_tax_query_terms(){
		if( empty($this->contentTax) )			
			return null;

		$contentQueryArguments['tax_query'] = array();
		$contentQueryArguments['tax_query']['relation'] = 'OR';
		foreach ($this->contentTax as $tax){			
			array_push($contentQueryArguments['tax_query'],
				array(
					'taxonomy' 	=> $tax['taxonomy'],
					'field' 	=> 'name',
					'terms'		=> $tax['name'],
					'operator'	=> 'IN'
				)
			);
		}
		return $contentQueryArguments['tax_query'];	
	}

	public function build_wp_query_arguments(){
		$theKeywords = '';
		
		$contentType 		  = $this->get_shortcode_parameter('content_type');

		$contentQueryArguments['post_type'] 		= $contentType ;
		$contentQueryArguments['post_status']		= $contentType === 'attachment' ? 'any' : 'publish';
		$contentQueryArguments['posts_per_page']	= isset($postsPerPage) 	? $postsPerPage : '1';
		$contentQueryArguments['orderby'] 			= isset($orderBy) 		? $orderBy 		: 'rand';
		$contentQueryArguments['tax_query'] 		= $this->build_tax_query_terms();

		return $contentQueryArguments;
	}

}
?>