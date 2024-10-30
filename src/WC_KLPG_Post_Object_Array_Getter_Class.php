<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Post_Object_Array_Getter{
	
	protected $wpQueryArguments;
	protected $shortcodeParameters;
	protected $wpQueryResults;
	
	public function __construct($wpQueryArguments, $shortcodeParameters){
		$this->wpQueryArguments 	= $wpQueryArguments;
		$this->shortcodeParameters 	= $shortcodeParameters;
	}

	public function build_array_of_post_objects(){
		$postIdsOnPage 			= $this->get_WC_KLPG_post_ids_transient();	
		$dynamicContentQuery 	= new WP_Query($this->wpQueryArguments);
		$this->wpQueryResults 	= $dynamicContentQuery;
		$dynamicContentBlocks 	= array();
		
		$i = 0;	
		foreach($dynamicContentQuery->posts as $post){			
			if( !$postIdsOnPage || $this->post_is_unique($post->ID) ){
				$dynamicContentBlocks[$i++] = $post;
				$this->set_WC_KLPG_post_ids_transient($postIdsOnPage, $post->ID);
			}
		}
		return $dynamicContentBlocks;
	}

	public function return_query_results(){
		
		return $this->wpQueryResults;
	}

	private function get_WC_KLPG_post_ids_transient(){
		$postIdsOnPage = get_transient('wcklpg_postids_'.session_id());
		if(!$postIdsOnPage){
			$postIdsOnPage = array();
		}
		$this->wpQueryArguments['post__not_in'] = $postIdsOnPage;
		return $postIdsOnPage;
	}

	private function set_WC_KLPG_post_ids_transient($postIdsOnPage, $postID){
		array_push($postIdsOnPage, $postID);
		if(session_id()){
			set_transient('wcklpg_postids_'.session_id(), $postIdsOnPage, 30 );
		}		
	}

	private function post_is_unique($postID){
		$postIdsOnPage = $this->get_WC_KLPG_post_ids_transient();
		
		if(!$postIdsOnPage){
			return true;
		}else if( !in_array($postID, $postIdsOnPage) ){
			return true;
		}
	}
}
?>