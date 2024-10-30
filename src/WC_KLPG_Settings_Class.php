<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WC_KLPG_Settings{
	
	private $options;
	protected $numURLParameters;
	protected $activeTab;
	protected $shortcodesPageURL = '//cosmosplugin.com/content-type-shortcodes/';
	

	/*
	*
	*	magic __construct method - set class members and call init function
	*
	*/
	public function __construct(){			
		
        $this->options = get_option('WC_KLPG_option');   
        $this->numURLParameters 	= $this->get_num_url_parameters();
        if(isset($_COOKIE['activeTab'])){
        	$this->activeTab = $_COOKIE['activeTab'];    
    	}
    	$this->init();
	}


	/*
	*
	*	init() - adding WP Actions
	*
	*/
	public function init(){
		add_action( 'admin_menu', array( $this, 'add_wcklpg_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_WC_KLPG_settings' ) );
        add_action( 'admin_init', array( $this, 'register_WC_KLPG_settings' ) );
        add_action( 'wcklpg_settings_menu', array( $this, 'wcklpg_menu_setup' ) );
        add_action( 'admin_init', array($this, 'wcklpg_flush_rewrites' ) );
	}

	/*
	*
	*	wcklpg_flush_rewrites() - this has to be in settings class due to timing requirements of execution
	*
	*/
	public function wcklpg_flush_rewrites() {
	    $allowPrettyPermalinksOption = isset( $this->options['wcklpg_allow_pretty_permalinks'] ) ? 
								$this->options['wcklpg_allow_pretty_permalinks']  : 'false' ;
	    if ( $this->options['url_parameters_updated'] == 'true' && $allowPrettyPermalinksOption == 'enabled' ) {
	        for ( $i = 0; $i<$this->numURLParameters; $i++ ){
				if( $this->options['url_parameter_'.$i] != '' ){
					add_rewrite_endpoint( $this->options['url_parameter_'.$i], EP_ALL );
				}
			}
	        flush_rewrite_rules();
	        update_option($this->options['url_parameters_updated'], 'false');
	    }else if( $allowPrettyPermalinksOption == 'disabled' ){
	    	flush_rewrite_rules();
	    }
	}

	/*
	*	@params : $option - name of serialized option attached to WC_KLPG_option array
	*	returns value of option 
	*
	*/

	private function WC_KLPG_get_option($option){
		$WC_KLPG_options = $this->options = get_option('WC_KLPG_option');

		if( isset($WC_KLPG_options[$option]) && $WC_KLPG_options[$option] != '' ){
			return $WC_KLPG_options[$option];
		}else{
			return null;
		}
	}
	
	/*
	*	adds settings page to dashboard for plugin
	*
	*
	*/
	public function add_wcklpg_settings_page(){
		// this function is going to add a page in the admin to hold settings 
		add_menu_page(
			"WCKLPG" , 
			"WCKLPG" , 
			"manage_options"   , 
			"wcklpg-settings" , // this is the page
			array($this, 'WC_KLPG_options' )
		);
		
	}

	/*
	*
	*	@params : $tab - name of menu item to check if active
	*	returns 'active' string if the active menu item is stored in cookie, otherwise set active menu item to main settings
	*
	*/
	public function is_active_menu_item($tab){
		 if(!$this->activeTab)
		 	$this->activeTab = 'main-settings';

		if($tab === $this->activeTab)
			return 'active';
	}

	/*
	*
	*	@params : $tab - name of tab to check if active
	*	returns 'hidden' string added as class name if the $tab parameter is not active tab
	*
	*/
	public function is_active_tab($tab){
		if(!$this->activeTab)
		 	$this->activeTab = 'main-settings';

		if($tab != $this->activeTab)
			return 'hidden';
	}

	/*
	*
	*	setting up the tab menu for settings pages
	*
	*/
	public function wcklpg_menu_setup(){
		$mainSettingsText = esc_html__('Main Settings', 'wcklpg');
		//$AdvancedSettingsText = esc_html__('Advanced Settings', 'wcklpg');
		
		$docText = esc_html__('Documentation', 'wcklpg');
		$goProText = esc_html__('Upgrade To Pro Version', 'wcklpg');

		$html  = '';

		$html .= '<div class="wcklpg-settings-menu-container">';
			$html .= '<a class="wcklpg-settings-menu-item '.$this->is_active_menu_item('main-settings').'" href="#" data-tab-id="main-settings">'.$mainSettingsText.'</a>';
			
			$html .= do_action('wcklpg-additional-settings-menu-items');
			$html .= '<a class="wcklpg-settings-menu-item '.$this->is_active_menu_item('documentation').'" href="#" data-tab-id="documentation">'.$docText.'</a>';
			$html .= '<a class="wcklpg-settings-menu-item '.$this->is_active_menu_item('go-pro').'" href="#" data-tab-id="go-pro">'.$goProText.'</a>';
			$html .= '<div class="throbber-loader hidden">'.esc_html__('Loading', 'wcklpg').'...</div>';
			$html .= '<button class="wcklpg-settings-submit">'.esc_html__('Save Settings', 'wcklpg').'</button>';

		$html .= '</div>';

		echo $html;
	}

	/*
	*
	*	main function for adding option UI to settings page
	*
	*/
	public function WC_KLPG_options(){
			
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}	
		 // Set class property
	    $this->options = get_option( 'WC_KLPG_option' );	
		
		echo '<div class="wrap wcklpg wcklpg-settings-wrap">';
			echo '<div class="wcklpg-settings-content">';
				echo '<div class="wcklpg-logo-container">';
				echo '<img src="'.plugins_url( '/images/settings-topbar.jpg', dirname(__FILE__) ) . '" > ';
				echo '</div>';
		do_action('wcklpg_settings_menu');
		?>
	    	<form id="wcklpg-settings-form" method="post" action="options.php" >
		    <?php echo '<div class="settings-tab-content '.$this->is_active_tab('main-settings').'" data-tab-id="main-settings">'; ?>
			        <?php
				        do_action('before_wcklpg_settings_section');
				        $this->print_main_section_info();
				        wp_nonce_field( 'update-options' );
				        $this->url_parameters_updated(); 
						settings_fields( 'WC_KLPG_options_group' );   
						do_settings_sections( 'wcklpg-settings' );
				        do_action('after_wcklpg_settings_section');
				        print_settings_footer();
			        ?>
			      
		        </div>

		    <?php echo '<div class="settings-tab-content '.$this->is_active_tab('documentation').'" data-tab-id="documentation" class="hidden">'; ?>
		        <?php 
		        	echo $this->get_remote_documentation();
		        	print_settings_footer();
		        ?>
		       
		        </div>

		    <?php echo '<div class="settings-tab-content '.$this->is_active_tab('go-pro').'" data-tab-id="go-pro" class="hidden">'; ?>
		        <?php 
		        	echo $this->get_upgrade_information();
		        	print_settings_footer();
		        ?>
		        </div>
	        
	        </form>

	        <div class="hidden wcklpg-settings-alert success">
		    	<?php _e('Settings Saved Successfully', 'wcklpg'); ?>
		    </div>
		    <div class="hidden wcklpg-settings-alert warning">
		    	<?php _e('Settings Changed, be Sure to Save', 'wcklpg'); ?>
		    </div>
	    </div>
	</div> <!-- end Wrap -->
	<?php } // end options function


	/*
	*
	*	required function for registering settings called by WP action hook
	*
	*/
	public function register_WC_KLPG_settings(){
		
	 	register_setting(
            'WC_KLPG_options_group', // Option group
            'WC_KLPG_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );
	
		add_settings_section(
            'main_settings', // ID
            null, // Title
            null, // Callback
            'wcklpg-settings' // Page
        );

		add_settings_field(
			'url_parameters_table',
			null,
			array($this, 'url_parameters_table'),
			'wcklpg-settings',
			'main_settings'
		); 
	
	} // end register function
	
	/*
	*
	*	@params : $input - value of new setting to be sanitized
	*	returns sanitized version of the $input
	*
	*/
	public function sanitize( $input ){
       
        
        $new_input = array(); 
        
    	foreach ($input as $setting => $value){
    		if($value != ''){
    			$new_input[$setting] = $this->WC_KLPG_wpkses($value) ;
    		}
        }
		return $new_input;
	}

   	private function WC_KLPG_wpkses($input){
		return wp_kses($input, array());
	}     
 
    public function print_section_info(){
        echo _('Enter your settings below:', 'wcklpg');
    }
	
	public function print_main_section_info(){
        echo '<h3>'.esc_html__('Keywords, UTM Codes or URL Parameters to match', 'wcklpg').'</h3>';
        echo '<p>'.esc_html__('Enter each Keyword, UTM Code or URL parameter that will be used to build dynamic content:','wcklpg').'</p>';
        echo '<img src="'.plugins_url( '/images/url-parameters-image.jpg', dirname(__FILE__) ) . '" > ';
    }

    public function get_num_url_parameters(){
    	$urlParameters = array();
		if(!empty($this->options)){
			foreach($this->options as $option=>$value){
				if(stripos($option, 'url_parameter_') !== false && stripos($option, 'operator') === false ){
					array_push($urlParameters,
						$option
					);
				}
			}
		}else{
			return null;
		}
		$numURLParameters = count($urlParameters);
		return $numURLParameters;
    }
   
	//  url parameters
	public function url_parameters_table(){
		
		$numURLParameters = $this->get_num_url_parameters();

		$html  = '<div class="url-parameters-container">';
		$html .= '<table class="url-parameter-table">';
		$html .= '<tr><th colspan="2" style="width:50%;">'.esc_html__('URL Parameter','wcklpg').'</th></tr>';
		if($numURLParameters < 1){
			$html .= $this->print_url_parameter_row(0);
		}		
		for( $i = 0; $i < $numURLParameters; $i++){
			$html .= $this->print_url_parameter_row($i);			
		}
		$html .= '</table>';
		$html .= '<button class="url-parameter-add-button url-parameters-button url-parameter-add">'.esc_html__('Add New URL Parameter','wcklpg').'</button>';
		$html .= '</div>';
		$html .= $this->get_advanced_options();
		echo $html;
	}

	public function url_parameters_updated(){
		$html = '<input class="url_parameters_updated" type="hidden" name="WC_KLPG_option[url_parameters_updated]" id="WC_KLPG_option[url_parameters_updated]" value="true">';
		echo $html;
	}

	private function print_url_parameter_row($i){
		$url_parameter_name = 'url_parameter_'.$i;
		$url_parameter_value = isset( $this->options[$url_parameter_name] ) ? 
								$this->WC_KLPG_wpkses( $this->options[$url_parameter_name] ) : '' ;

		$url_parameter_operation_name = 'url_parameter_'.$i.'_operator';
		$url_parameter_operation_value = isset( $this->options[$url_parameter_operation_name] ) ? 
								$this->WC_KLPG_wpkses( $this->options[$url_parameter_operation_name] ) : '' ;
		$closeButton =  '<button class="url-parameter-remove-button url-parameters-button url-parameter-delete">'.esc_html__('Delete','wcklpg').'</button>';
		if($i !== 0 && $url_parameter_value === ''){
			$classes = 'hidden';
		}
		if($i === 0){
			$classes = 'prototype';
		}
		if($i !== 0){
			$classes = '';
		}

		$html = 
		'<tr class="url-parameter-table-row '.$classes.'">
			<td>
				<input class="url-parameter-input" value="'.$url_parameter_value.'" id="WC_KLPG_option['.$url_parameter_name.']" name="WC_KLPG_option['.$url_parameter_name.']" type="text">
			</td>
			
			<td colspan="2">';
		$html .= $closeButton;		
		$html.=	'</td>
		</tr>';
		return $html;
	}

	public function print_allow_pretty_permalinks_toggle(){
		$allowPrettyPermalinksOption = isset( $this->options['wcklpg_allow_pretty_permalinks'] ) ? 
								$this->options['wcklpg_allow_pretty_permalinks']  : '' ;

		$html  = '';
		$html .= '<h4>'.esc_html__('Allow Pretty Permalinks','wcklpg').'</h4>';
		$html .= '<div class="wcklpg-radio-group-radio-container">';
		$html .= '<label>'.esc_html__('Enabled','wcklpg').'</label>'; 
		$html .= '<input type="radio" name="WC_KLPG_option[wcklpg_allow_pretty_permalinks]" value="enabled"';		
			if($allowPrettyPermalinksOption === 'enabled'){
				$html .= 'checked />';
			}else{
				$html .= '/>';
			}
		$html .= '</div>';
		
		$html .= '<div class="wcklpg-radio-group-radio-container">';
		$html .= '<label>'.esc_html__('Disabled','wcklpg').'</label>';
		$html .= '<input type="radio" name="WC_KLPG_option[wcklpg_allow_pretty_permalinks]" value="disabled"';
			if($allowPrettyPermalinksOption === 'disabled' || $allowPrettyPermalinksOption == '' ){
				$html .= 'checked />';
			}else{
				$html .= '/>';
			}
		$html .= '</div>';

		return $html;
	}

	public function get_advanced_options(){

		$html  = '';

		$html .= '<div class="wcklpg-advanced-options-container">';
		$html .= '<h3>'.esc_html__('Enable Pretty Permalinks','wcklpg').'</h3>';
		$html .= '<p>'.esc_html__('Enabling this option will allow pretty permalinks for your URL parameters.','wcklpg').'</p>';
		$html .= $this->print_allow_pretty_permalinks_toggle();
		$html .= '</div>';

		return $html;

	}

	public function get_remote_documentation(){
		
		$documentationURL 	= '//cosmosplugin.com/documentation/';
		
		$html = '<h3>'.esc_html__('Documentation','wcklpg').'</h3>';
		
		$html .='<div class="wcklpg-support-documentation-container">';
		
			$html .= '<p>'.esc_html__('Read up to date plugin documentation:','wcklpg').'</br><a title="'.esc_html__('Click Here to Read Plugin Documentation on the Plugin Home Page','wcklpg').'" class="button-secondary" href="'.$documentationURL.'" target="_blank">'.esc_html__('Online Documentation','wcklpg').'</a></p>';
		
		$html .= '</div>';
		
		return $html;
	}

	public function get_upgrade_information(){
		
		$updgadeClass = new WC_KLPG_Go_Pro();

		$html  = '';
		
		$html .= '<div class="wcklpg-support-documentation-container">';
		
			$html .= '<div>';
				$html .= $updgadeClass->render_go_pro_html();
			$html .= '</div>';
		
		$html .= '</div>';
		
		return $html;
	}
		
} // end class
?>