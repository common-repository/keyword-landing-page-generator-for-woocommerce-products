<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_URL_Rewrite{

	private $numURLParameters;
	private $wcklpgOptions;
	private $rewriteTags;
	private $WC_KLPG_settings;

	public function __construct(){
		$this->wcklpgOptions 		= get_option('WC_KLPG_option');
		$this->WC_KLPG_settings 		= new WC_KLPG_Settings();
		$this->numURLParameters 	= $this->WC_KLPG_settings->get_num_url_parameters();
	}

	public function init(){
		add_filter( 'query_vars', array( $this,'WC_KLPG_add_query_vars' ) );
		add_action( 'pre_get_posts', array( $this, 'unset_query_arg' ) );
	}
	
	/*
	*	Adding Query Vars 
	*	@param: $qvars is the array of query variables 
	*	automatically passed to the function through the WP filter
	*/
	public function WC_KLPG_add_query_vars($qvars){
		//error_log('in the add query vars function');
		for ( $i = 0; $i<$this->numURLParameters; $i++ ){
			if( $this->wcklpgOptions['url_parameter_'.$i] != '' ){
			 	$qvars[] = $this->wcklpgOptions['url_parameter_'.$i];	
			}
		}
		return $qvars;
	}

	/***
	*	Since 1.2.8 grab the query vars and then pass them directly to the main class
	*	unset the vars, to get around the bug in wordpress
	*	@param $query : this is brought in automatically from WP - is the entire query object
	***/
	public function unset_query_arg($query){
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		
		global $wcklpgQueryVars;
		
		for ( $i = 0; $i<$this->numURLParameters; $i++ ){
			if( $this->wcklpgOptions['url_parameter_'.$i] != '' ){
			 	
			 	$key = $this->wcklpgOptions['url_parameter_'.$i];

			 	$ref = $query->get( $key );
		
				if ( ! empty( $ref ) ) {
					
					// need to pass the args to the query object
					$wcklpgQueryVars[$key] = $ref;
					
					// unset ref var from $wp_query
					$query->set( $key, null );
					
					global $wp;
					// unset ref var from $wp
					unset( $wp->query_vars[ $key ] );
					// if in home (because $wp->query_vars is empty) and 'show_on_front' is page
					if ( empty( $wp->query_vars ) && get_option( 'show_on_front' ) === 'page' ) {
					 	// reset and re-parse query vars
						$wp->query_vars['page_id'] = get_option( 'page_on_front' );
						$query->parse_query( $wp->query_vars );
					}
				}	
			}
		}
	}
	
}
?>