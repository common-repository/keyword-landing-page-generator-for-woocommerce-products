<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Main{

	/*
		WC_KLPG_Main class has the responsibility of directing traffic - the generation of dynamic content is a 
		multi step process that has to be done in a certain sequence - this class takes care of that
	*/
	protected $shortcodeParameters;
	protected $contentTax;
	protected $wpQueryArguments;
	protected $wpQueryObject;
	protected $arrayOfPosts;
	protected $matchedTemplate;
	protected $html;

	public function __construct($shortcodeParameters){
		$this->shortcodeParameters	= $shortcodeParameters;	
	}

	
	/**
	*	get_dynamic_content() - this function is the traffic director, running a sequence which results in a dynamic
	*	content block returning
	**/
	public function get_dynamic_content(){
		
		$taxonomyMatcher 		= new WC_KLPG_Taxonomy_Matcher($this->shortcodeParameters);
		$this->contentTax 		= $taxonomyMatcher->prepare_taxonomy_for_content_type();

		// need conditions for null returns from the tax matcher - do we display default data, or a user specified item?
		// this is now handled by the ignore_incoming shortcode parameter

		$queryArgumentsBuilder 	= new WC_KLPG_Query_Arguments($this->shortcodeParameters, $this->contentTax );
		$this->wpQueryArguments = $queryArgumentsBuilder->build_wp_query_arguments();
		
		$wcklpgTemplateMatcher 	= new WC_KLPG_Template_Matcher($this->shortcodeParameters);
		$this->matchedTemplate  = $wcklpgTemplateMatcher->match_content_type_to_template_or_shortcode();

		$wcklpgPostObjectArrayGetter = new WC_KLPG_Post_Object_Array_Getter($this->wpQueryArguments, $this->shortcodeParameters);
		$this->arrayOfPosts 	= $wcklpgPostObjectArrayGetter->build_array_of_post_objects();
		$this->wpQueryResults	= $wcklpgPostObjectArrayGetter->return_query_results();

		$htmlRenderer 			= new WC_KLPG_HTML_Render($this->matchedTemplate, $this->arrayOfPosts, $this->wpQueryResults, $this->shortcodeParameters);
		$this->html 			= $htmlRenderer->render_dpg_content_block();
		return $this->html;
	}
}
?>