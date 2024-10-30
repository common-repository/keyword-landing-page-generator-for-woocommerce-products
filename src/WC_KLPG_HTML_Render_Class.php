<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_HTML_Render{

	protected 	$arrayOfPosts;
	protected   $wpQueryResults;
	protected	$shortcodeParameters;
	protected	$template;
	private $options;
	
	
	public function __construct($template, $arrayOfPosts, $wpQueryResults, $shortcodeParameters){
		
		$this->shortcodeParameters	= $shortcodeParameters;
		$this->template 	 		= $template;
		$this->arrayOfPosts			= $arrayOfPosts;
		$this->wpQueryResults		= $wpQueryResults;
		//$this->set_html_render_default_actions_and_filters();
	}

	private function WC_KLPG_get_option($option){
		$WC_KLPG_options = $this->options = get_option('WC_KLPG_option');

		if( isset($WC_KLPG_options[$option]) && $WC_KLPG_options[$option] != '' ){
			return $WC_KLPG_options[$option];
		}else{
			return null;
		}
	}

	private function set_html_render_default_actions_and_filters(){

		// keeping this inactive function around so I can revisit actions and filters later 

		add_filter('content_before_dpg_content_block', 	array($this, 'content_before_dpg_content_block'));
		//add_action('dpg_content_block_featured_image', 	array($this, 'dpg_content_block_featured_image'), 1, 1  );
		//add_action('dpg_content_internal_content', 		array($this, 'dpg_content_internal_content' ), 	 1, 1  );
		add_filter('content_after_dpg_content_block', 	array($this, 'content_after_dpg_content_block' 	));

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

	protected function get_post_column_data($contentBlock, $postParameter){
		$postParameterToReturn = '';
		$contentBlockArray = get_object_vars($contentBlock);

		if( isset($contentBlockArray[$postParameter]) && $contentBlockArray[$postParameter] != '' ){
			$postParameterToReturn = $contentBlockArray[$postParameter];
		}

		if(!empty($postParameterToReturn)){
			return $postParameterToReturn;
		}
	}

	public function render_dpg_content_block(){
		$html ='';
		
		foreach($this->arrayOfPosts as $contentBlock){
			$html .= $this->content_before_dpg_content_block($contentBlock);
			$html .= $this->do_registered_shortcode_for_content($contentBlock);
			$html .= $this->content_after_dpg_content_block();
		}

		return $html;	
	}

	public function content_before_dpg_content_block($contentBlock){
		$html  = '';
		$contentType = $this->get_post_column_data($contentBlock, 'post_type');

		$html .= '<div class="dpg_content--'.$contentType.'">';

		return $html;
	}

	public function content_after_dpg_content_block(){
		$html  = '';
		$html .= '</div>';

		return $html;
	}

	

	public function do_registered_shortcode_for_content($contentBlock){
		
		$postId = $this->get_post_column_data($contentBlock, 'ID');
		
		$content['shortcode'] 		= $this->template['shortcode'];
		$content['idParameter'] 	= $this->template['idParameter'];
		
		return do_shortcode('['.$content['shortcode'].' '.$content['idParameter'].'='.$postId . ']' );
	}
	
}
?>