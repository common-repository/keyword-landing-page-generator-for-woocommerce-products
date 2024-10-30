<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Taxonomy_Matcher{

	protected $urlParameters;
	protected $shortCodeParameters;
	protected $contentTypeTaxonomies;
	protected $matchedTaxName;
	
	private $options;

	public function __construct( $shortCodeParameters){
		global $wcklpgQueryVars;
		$this->shortCodeParameters 	= $shortCodeParameters;
		$this->urlParameters = $wcklpgQueryVars;
		
		//error_log( "***-- Tax Matcher Constructor");
	}

	////////////////////////////////////////////////////////////////
	// return shortcode param or empty string if nothing is found
	// called by prepare_taxonomy_for_content_type
	////////////////////////////////////////////////////////////////
	protected function get_shortcode_parameter($parameterToMatch){
		
		$parameterReturn = '';		// preset in case nothing found
		//error_log( "***-- get_shortcode_parameter for:".$parameterToMatch );

		if( isset($this->shortCodeParameters[$parameterToMatch]) && $this->shortCodeParameters[$parameterToMatch] != ''){
			$parameterReturn = $this->shortCodeParameters[$parameterToMatch];
		}
		return $parameterReturn;
	}


	/////////////////////////////////////////////////////////////////////////////////////
	// this is called from the main class to get the match
	// returns the array of terms and names that match the url parameter and content type
	// or null if nothing is found
	//
	// find out the content type
	// get all tax associated with content type
	// for each tax match the terms to the incoming url parameters (against thesaurus)
	// return array of arrays for each term that matches with : term and name
	//
	/////////////////////////////////////////////////////////////////////////////////////
	public function prepare_taxonomy_for_content_type(){

		//error_log( "***-- prepare_taxonomy_for_content_type");
		$ignore_url_parameters = $this->get_shortcode_parameter('ignore_incoming');
		if($ignore_url_parameters === 'true'){
			return null;
		}

		$contentType = $this->get_shortcode_parameter('content_type');		// get content type
		if( empty($contentType ) ){
			//error_log(' --- Content Type Not Found');
			return( null );
		}
		//error_log(" --- Content Type=".$contentType);	

		$success = $this->get_taxonomies_for_content_type($contentType);	// go get taxes & terms
		if( $success == false ){
			//error_log(' --- Taxonomies Not Found');
			return( null );
		}
		else if( empty($this->contentTypeTaxonomies) ){
			//error_log(' --- Taxonomies Empty');
			return( null );
		}

		// foreach( $this->contentTypeTaxonomies as $tname => $vterms){		// DEBUG print taxes and terms
		// 	//error_log( ' --- Taxonomy = '.$tname.' Terms = '.print_r( $vterms, true ) );
		// 	// Taxonomy = product_cat
		// 	// Terms = array()
		// 	//    [0] => Albums
		// 	//    [1] => Clothing
		// 	//    [2] => Hoodies
		// 	//    [3] => Music
		// 	//    [4] => Posters
		// 	//    [5] => shoe
		// 	//    [6] => Singles
		// 	//    [7] => T-shirts
		// }

		$urlParametersArray = $this->prepare_url_parameters();			// get url parameters that have matching querys
		if( empty( $urlParametersArray ) ){
			return( null );
		}
		$matchedTaxes = array();										// aggregate array of arrays
		foreach( $urlParametersArray as $urlParameter => $value){		// "keyword=shoe"
			//error_log( ' --- Matching URL to Tax - Query = '.$urlParameter.' Value = '.$value);	

			$matchedUrlTaxNames = $this->match_url_parameter_to_taxonomy($urlParameter, $value ); 	// array of arrays
			foreach( $matchedUrlTaxNames as $aTaxTerm ){		// concat returned array content with aggregate
				$matchedTaxes[] = $aTaxTerm;		// each taxterm array is: $aTaxTerm['name'] = the term 
				//															  $aTaxTerm['taxonomy'] = taxonomy

			}
		}
		if( empty( $matchedTaxes )){
			return( null );
		}

		//
		// check for duplicates, splice them out if present
		//

		return $matchedTaxes; 
	}

	


	/////////////////////////////////////////////////////////////////////////
	// get all term names for all taxonomies for the given content_type
	// stuffs array indexed by tax names with term names array, returns true
	// returns false if nothing is found
	// called by prepare_taxonomy_for_content_type
	/////////////////////////////////////////////////////////////////////////
	public function get_taxonomies_for_content_type($contentType){
		//error_log( "***-- get_taxonomies_for_content_type:".$contentType);
		$taxonomies = get_object_taxonomies($contentType); 		// wordpress function

		if($taxonomies){
			$this->contentTypeTaxonomies = array();
			foreach ($taxonomies as $tax){
				$termarray = get_terms($tax); 					// wordpress function gets term objects
				$termnames = array();
				foreach( $termarray as $termobj ){
					array_push( $termnames, $termobj->name );
				}
				if( !empty($termnames))
					$this->contentTypeTaxonomies[$tax] = $termnames;
			}
			return( true );
		}
		return( false );			// none found
	}
	
	////////////////////////////////////////////////////////////////////
	// return array of requested items or null if none are found
	// called by prepare_url_parameters
	////////////////////////////////////////////////////////////////////
	private function WC_KLPG_get_option($option){
		//error_log( "***-- WC_KLPG_get_option");

		$WC_KLPG_options = $this->options = get_option('WC_KLPG_option');

		if( isset($WC_KLPG_options[$option]) && $WC_KLPG_options[$option] != '' ){
			return $WC_KLPG_options[$option];
		}else{
			return null;
		}
	}

	////////////////////////////////////////////////////////////////////
	// get the URL parameters where query var is exists
	// returns array of url parameters to match or null if none found
	// called by prepare_taxonomy_for_content_type
	////////////////////////////////////////////////////////////////////
	public function prepare_url_parameters(){
		if( empty( $this->urlParameters ) ){
			return( null );
		}

		foreach($this->urlParameters as $key=>$value){
			$urlParameters[$key] = sanitize_text_field($value);
		}		
		//var_dump($urlParameters);
		return $urlParameters;
	}
	
	////////////////////////////////////////////////////////////////////////////////
	// returns array of arrays of all matches to supplied url parameter
	//			$aTaxTerm['name'] 	= Matching Term
	//			$aTaxTerm['taxonomy'] = Taxonomy Name where Matching Term was found
	// or empty array if no matches were found
	// called by prepare_taxonomy_for_content_type
	// uses $this->contentTypeTaxonomies
	////////////////////////////////////////////////////////////////////////////////

	public function match_url_parameter_to_taxonomy($urlParameter, $value ){

		// error_log( "***-- match_url_parameter_to_taxonomy");
		// error_log( " --- Incoming URL param = ".$value);

		$taxTermArray = array();							// the array to be returned

		// look for EXACT match term case insensitive
		$ExactMatch = false;
		foreach($this->contentTypeTaxonomies as $tname => $vterms){		// walk thru tax name and term name
			foreach($vterms as $term){									// look in this tax's vterms
				if (strcasecmp($term, $value) != 0)
					continue;
				$aTaxTerm = array();						// got a match, make array
				$aTaxTerm['name'] 	= $term;				// stuff it
				$aTaxTerm['taxonomy'] = $tname;
				$aTaxTerm['urlParameter'] = $urlParameter;
				$taxTermArray[] = $aTaxTerm;				// add to output
				$ExactMatch = true;							// set exact match flag
				//error_log( ' --- Matched Exact: '.$value.'='.$term.' Taxonomy='.$tname);
			}
		}

		// if we got exact matches, return and done
		// or we could put an option switch here to continue matching for REGEX permutations
		if( $ExactMatch ){
			return $taxTermArray;
		}

		// modify our terms to see if they matches the incoming value
		// match plurals, '..ed','..er','..ing'
		// removing spaces, hyphens
		// watch out for things like car, cart, carton, cartoon, carbon, cared, caring, cardioid, motorcar, touring car
		//			(s|ed|er|ing)/b	
		$RegexMatch = false;
		foreach($this->contentTypeTaxonomies as $tname => $vterms){		// walk thru tax name and term name
			//error_log('in the regex match loop');
			foreach($vterms as $term){									// look in this tax's vterms
				//error_log($value);
				$regex =  '/\b'.$term.'(s|ed|er|ing)?\b/i';		// use the term and permutations
				//error_log($regex);
				//error_log( preg_match( $regex, $value, $matches ) );
				if( preg_match( $regex, $value, $matches ) != 1 )
					continue;
				$aTaxTerm = array();						// got a match, make array
				$aTaxTerm['name'] 	= $term;				// stuff it
				$aTaxTerm['taxonomy'] = $tname;
				$aTaxTerm['urlParameter'] = $urlParameter;
				$taxTermArray[] = $aTaxTerm;				// add to output
				$RegexMatch = true;							// set regex match flag
				//error_log( ' --- Matched Regex: '.$value.'='.$term.' Taxonomy='.$tname);
			}
		}

		// if we got REGEX matches, return and done
		// or we could put an option switch here to continue matching from Thesarus
		if( $RegexMatch || $ExactMatch ){
			return $taxTermArray;
		}
		
		///
		return $taxTermArray;  // THIS IS TO STOP THE TABLE QUERY FROM HAPPENING
		///


		// lookup using alternate terms from our private WC_KLPG_thesaurus table
		// could have many rows for a single term, one row per synonym
		// we try to match incoming value to nouns found in thesarus
		// the table is salted in settings from "initialize" using selected categories
		// 		term varchar()			this is our primary term
		//	  	noun varchar()			this is an acceptable alternate
		// 
		$ThesaurusMatch = false;
		global $wpdb;
		foreach($this->contentTypeTaxonomies as $tname => $vterms){		// walk thru tax name and term name
			foreach($vterms as $term){									// look in this tax's vterms
				$sqlquery = 'SELECT * FROM WC_KLPG_thesaurus WHERE term='.$term.';';	// find alternates for our term
				$synonyms = $wpdb->get_results($sqlquery);
				if( $synonyms === null){
					continue;
				}
				if( empty( $synonyms )){
					continue;
				}
				foreach($synonyms as $synonym){
					if (strcasecmp($synonym->noun, $value) != 0)	// see if incoming matches an alternate term
						continue;
					$aTaxTerm = array();						// got a match, make array
					$aTaxTerm['name'] 	= $term;				// stuff it
					$aTaxTerm['taxonomy'] = $tname;
					$aTaxTerm['urlParameter'] = $urlParameter;
					$taxTermArray[] = $aTaxTerm;				// add to output
					$ThesarusMatch = true;						// set thesaurus match flag
					//error_log( ' --- Matched Thesarus: '.$value.'='.$term.' Taxonomy='.$tname);
				}
			}
		}

		// we could continue other types of matching here switched on options and
		// $RegexMatch || $ExactMatch || $ThesarusMatch
		return $taxTermArray;
	}



	// http://words.bighugelabs.com free thesaurus service requires api key restrictions might apply
	// http://thesaurus.altervista.org completely free thesaurus service requires api key
	// http://www.dictionaryapi.com/products/api-collegiate-thesaurus.htm requires api key restrictions apply

	// notes - thesaurus to be extra option, populates local table from entire website on initial load
	// also populate table from a user-supplied thesaurus (text file) as settings option

	public function salt_thesaurus($contentTypeTaxonomies){	
		//
		//
		//
	}


}
?>