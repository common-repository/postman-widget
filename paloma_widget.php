<?php
/*
Plugin Name: Paloma Widget
Plugin URI: http://www.paloma.se/
Description: Form to capture email subscriptions and send them to your Paloma Address List
Version: 1.14
Author: Paloma
Author URI: http://www.paloma.se/
*/

/*
	This file is part of Paloma Widget.

    Paloma Widget is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Paloma Widget is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Paloma Widget.  If not, see <http://www.gnu.org/licenses/>.
	*/


class paloma_newsletter_widget extends WP_Widget {
	
	/**
	* constructor
	*/	 
	
	
	
	function __construct() {
		parent::__construct('paloma_newsletter_widget', 'Paloma Widget', array('description' => __('Allows visitors to subscribe to a Paloma newsletter.','paloma')));	
		
		paloma_newsletter_widget::loadJS();
	}
	
	
	public function loadJS()
	{
		//wp_deregister_script( 'jquery' );
    	//wp_register_script( 'jquery', plugins_url('js/jquery-1.4.4.min.js', __FILE__));
	    wp_enqueue_script( 'jquery' );
		// wp_enqueue_script(
			 // 'contact-control-script',
			 // plugins_url( 'contact_control.js', __FILE__ )
		 // );
	}
	
  
  /**
	 * display widget
	 */	 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['paloma_title']) ? '&nbsp;' : apply_filters('widget_title', $instance['paloma_title']);
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		if(get_option('paloma_customer_has_contacts'))
		{
			$paloma_user_account_id = get_option('paloma_user_account_id');
			$paloma_user_account_api_hash = get_option('paloma_user_account_api_hash');

			$args = array(
			  'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $paloma_user_account_id . ':' . $paloma_user_account_api_hash )
			  )
			);
			
			$api_response = wp_remote_get('https://api.paloma.se/contacts/api/contactfield', $args );
			$contactFields = json_decode( wp_remote_retrieve_body( $api_response ), true );
			
			$fields = array();
			foreach($contactFields as $i => $contactField)
			{
				$fieldKey = $contactField["FieldKey"];
				if((array_key_exists("paloma_cf_" . $fieldKey, $instance)  && !empty($instance["paloma_cf_" . $fieldKey])) || $contactField["IsPrimaryField"] == 1)
				{
					$fields[] = $contactField;
				}
			}
			
			paloma_newsletter_widget::load_contact_form(	
										get_option('customer_public_guid'),
										array_key_exists('paloma_contact_list_id', $instance) ? $instance['paloma_contact_list_id'] : '',
										array_key_exists('paloma_legal_basis', $instance) ? $instance['paloma_legal_basis'] : 0, 
										array_key_exists('paloma_consent_text_guid', $instance) ? $instance['paloma_consent_text_guid'] : '', 
										array_key_exists('paloma_box_title', $instance) ? $instance['paloma_box_title'] : '',
										array_key_exists('paloma_thanks', $instance) ? $instance['paloma_thanks'] : '',
										array_key_exists('paloma_showmailings', $instance) ? $instance['paloma_showmailings'] : false,
										$fields
			);
		}
		else{
			paloma_newsletter_widget::load_form(
										array_key_exists('paloma_list_id', $instance) ? $instance['paloma_list_id'] : '',
										array_key_exists('paloma_consent_text_guid', $instance) ? $instance['paloma_consent_text_guid'] : '', 
										array_key_exists('paloma_box_title', $instance) ? $instance['paloma_box_title'] : '',
										array_key_exists('paloma_thanks', $instance) ? $instance['paloma_thanks'] : '',
										array_key_exists('paloma_showmailings', $instance) ? $instance['paloma_showmailings'] : false,
										array_key_exists('paloma_get_title', $instance) ? $instance['paloma_get_title'] : false,
										array_key_exists('paloma_get_company', $instance) ? $instance['paloma_get_company'] : false,
										array_key_exists('paloma_get_mobilenr', $instance) ? $instance['paloma_get_mobilenr'] : false,
										array_key_exists('paloma_get_phonenr', $instance) ? $instance['paloma_get_phonenr'] : false,
										array_key_exists('paloma_get_fax', $instance) ? $instance['paloma_get_fax'] : false,
										array_key_exists('paloma_get_address', $instance) ? $instance['paloma_get_address'] : false,
										array_key_exists('paloma_get_postcode', $instance) ? $instance['paloma_get_postcode'] : false,
										array_key_exists('paloma_get_city', $instance) ? $instance['paloma_get_city'] : false,
										array_key_exists('paloma_get_state', $instance) ? $instance['paloma_get_state'] : false
			);
		}
		echo $after_widget;
	}
	
	/**
	 *	update/save function
	 */	 	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['paloma_title'] = strip_tags($new_instance['paloma_title']);
		$instance['paloma_box_title'] = strip_tags($new_instance['paloma_box_title']);
		$instance['paloma_thanks'] = strip_tags($new_instance['paloma_thanks']);
		if($instance['paloma_thanks'] != null && $instance['paloma_thanks'] != "" && !strpos($instance['paloma_thanks'], "://")) {
			$instance['paloma_thanks'] = "http://" . $instance['paloma_thanks'];
		}
		$instance['paloma_consent_text_guid'] = strip_tags($new_instance['paloma_consent_text_guid']);
		$instance['paloma_showmailings'] = strip_tags($new_instance['paloma_showmailings']);
			
		if(get_option('paloma_customer_has_contacts'))
		{
			$instance['paloma_contact_list_id'] = strip_tags($new_instance['paloma_contact_list_id']);
			$instance['paloma_legal_basis'] = strip_tags($new_instance['paloma_legal_basis']);
			if($instance['paloma_legal_basis'] != 1)
			{
				$instance['paloma_consent_text_guid'] = '';
			}
			
			foreach($new_instance as $i => $item)
			{
				if(substr( $i, 0, 10 ) === "paloma_cf_")
				{
					$instance[$i] = strip_tags($item);
				}
			}

		}
		else
		{
			$instance['paloma_list_id'] = strip_tags($new_instance['paloma_list_id']);
			$instance['paloma_get_title'] = strip_tags($new_instance['paloma_get_title']);
			$instance['paloma_get_company'] = strip_tags($new_instance['paloma_get_company']);
			$instance['paloma_get_mobilenr'] = strip_tags($new_instance['paloma_get_mobilenr']);
			$instance['paloma_get_phonenr'] = strip_tags($new_instance['paloma_get_phonenr']);
			$instance['paloma_get_fax'] = strip_tags($new_instance['paloma_get_fax']);
			$instance['paloma_get_address'] = strip_tags($new_instance['paloma_get_address']);
			$instance['paloma_get_postcode'] = strip_tags($new_instance['paloma_get_postcode']);
			$instance['paloma_get_city'] = strip_tags($new_instance['paloma_get_city']);
			$instance['paloma_get_state'] = strip_tags($new_instance['paloma_get_state']);
		}
		return $instance;
	}
	
	/**
	 *	admin control form
	 */	 	
	function form($instance) {
		
		if(get_option('paloma_customer_has_contacts'))
		{
			$default = 	array( 'paloma_title' 		=> __('Paloma Newsletter', 'paloma'),
							   'paloma_box_title'	=> __('Subscribe to our newsletter', 'paloma'),
							   'paloma_contact_list_id' 		=> '0',
							   'paloma_legal_basis'	=> '0',
							   'paloma_consent_text_guid'	=> '',
							   'paloma_thanks'		=> '',
							   'paloma_showmailings' => '0',
							);
			
			$instance = wp_parse_args( (array) $instance, $default );
			
			$field_id_title = $this->get_field_id('paloma_title');
			$field_name_title = $this->get_field_name('paloma_title');
			
			$field_id_box_title = $this->get_field_id('paloma_box_title');
			$field_name_box_title = $this->get_field_name('paloma_box_title');
			
			$field_id_list = $this->get_field_id('paloma_contact_list_id');
			$field_name_list = $this->get_field_name('paloma_contact_list_id');
			
			$field_container_id_legal_basis = $this->get_field_id('paloma_legal_basis_container');
			$field_id_legal_basis = $this->get_field_id('paloma_legal_basis');
			$field_name_legal_basis = $this->get_field_name('paloma_legal_basis');
			
			$field_id_consent_text = $this->get_field_id('paloma_consent_text_guid');
			$field_name_consent_text = $this->get_field_name('paloma_consent_text_guid');
			
			$field_id_thanks = $this->get_field_id('paloma_thanks');
			$field_name_thanks = $this->get_field_name('paloma_thanks');
			
			$field_id_showmailings = $this->get_field_id('paloma_showmailings');
			$field_name_showmailings = $this->get_field_name('paloma_showmailings');
			
			$field_id_contactField = $this->get_field_id('paloma_cf_ContactFieldKey');
			$field_name_contactField = $this->get_field_name('paloma_cf_ContactFieldKey');
							   
			$file = dirname(__FILE__).'/contact_control.php';
		}
		else
		{
			$default = 	array( 'paloma_title' 		=> __('Paloma Newsletter', 'paloma'),
							   'paloma_box_title'	=> __('Subscribe to our newsletter', 'paloma'),
							   'paloma_list_id' 		=> '0',
							   'paloma_consent_text_guid' 		=> '',
							   'paloma_thanks'		=> '',
							   'paloma_showmailings' => '0',
							   'paloma_get_title' => '',
							   'paloma_get_company' => '',
							   'paloma_get_mobilenr' => '',
							   'paloma_get_phonenr' => '',
							   'paloma_get_fax' => '',
							   'paloma_get_address' => '',
							   'paloma_get_postcode' => '',
							   'paloma_get_city' => '',
							   'paloma_get_state' => '');
						   
			$instance = wp_parse_args( (array) $instance, $default );
			
			$field_id_title = $this->get_field_id('paloma_title');
			$field_name_title = $this->get_field_name('paloma_title');
			
			$field_id_box_title = $this->get_field_id('paloma_box_title');
			$field_name_box_title = $this->get_field_name('paloma_box_title');
			
			$field_id_list = $this->get_field_id('paloma_list_id');
			$field_name_list = $this->get_field_name('paloma_list_id');
			
			$field_id_consent_text = $this->get_field_id('paloma_consent_text_guid');
			$field_name_consent_text = $this->get_field_name('paloma_consent_text_guid');
			
			$field_id_thanks = $this->get_field_id('paloma_thanks');
			$field_name_thanks = $this->get_field_name('paloma_thanks');
			
			$field_id_showmailings = $this->get_field_id('paloma_showmailings');
			$field_name_showmailings = $this->get_field_name('paloma_showmailings');
			
			$field_id_get_title = $this->get_field_id('paloma_get_title');
			$field_name_get_title = $this->get_field_name('paloma_get_title');

			$field_id_get_company = $this->get_field_id('paloma_get_company');
			$field_name_get_company = $this->get_field_name('paloma_get_company');

			$field_id_get_mobilenr = $this->get_field_id('paloma_get_mobilenr');
			$field_name_get_mobilenr = $this->get_field_name('paloma_get_mobilenr');

			$field_id_get_phonenr = $this->get_field_id('paloma_get_phonenr');
			$field_name_get_phonenr = $this->get_field_name('paloma_get_phonenr');

			$field_id_get_fax = $this->get_field_id('paloma_get_fax');
			$field_name_get_fax = $this->get_field_name('paloma_get_fax');

			$field_id_get_address = $this->get_field_id('paloma_get_address');
			$field_name_get_address = $this->get_field_name('paloma_get_address');

			$field_id_get_postcode = $this->get_field_id('paloma_get_postcode');
			$field_name_get_postcode = $this->get_field_name('paloma_get_postcode');

			$field_id_get_city = $this->get_field_id('paloma_get_city');
			$field_name_get_city = $this->get_field_name('paloma_get_city');

			$field_id_get_state = $this->get_field_id('paloma_get_state');
			$field_name_get_state = $this->get_field_name('paloma_get_state');
		
			$file = dirname(__FILE__).'/control.php';
		}
		include($file);
	}
  
 
  function load_form($list_id, $consent_text_guid, $boxTitle, $thanksUrl, $mailings, $getTitle, $getCompany, $getMobileNr, $getPhoneNr, $getFax, $getAddress, $getPostcode, $getCity, $getState)
  {
	$mailarr = array();
	$cid = 0;
	$chash = '';
  	if($mailings > 0 || 1 > 0)
	{
		try
		{
			$client = new SoapClient("https://api.paloma.se/PalomaWebService.asmx?WSDL");
			$cid = get_option('paloma_customer_id');
			$chash = get_option('paloma_customer_hash');
			
			$results = $client->ListMailings(array('customerID' => $cid,
									'customerHash' => $chash,
									'addressListID' => $list_id));
			
			$i = 0;
			if(isset($results->ListMailingsResult->Mailings->Mailing))
			{
				foreach($results->ListMailingsResult->Mailings->Mailing as &$mailing)
				{
					if($i < $mailings)
					{
						$i++;
						$mailarr[$i] = $mailing;
					}
				}
			}
		}
		catch(Exception $e)
		{
		}
	}
	
	if($thanksUrl == 'http://' || $thanksUrl == 'https://')
	{
		$thanksUrl = '';
	}
	
	$file = dirname(__FILE__).'/form.php';
	include($file);
  }
  
  function load_contact_form($formKey, $list_id, $legalBasis, $consent_text_guid, $boxTitle, $thanksUrl, $mailings, $fields)
  {
	$mailarr = array();
	$cid = 0;
	$chash = '';
  	if($mailings > 0 || 1 > 0)
	{
		
	}
	
	if($thanksUrl == 'http://' || $thanksUrl == 'https://')
	{
		$thanksUrl = '';
	}
	
	$file = dirname(__FILE__).'/contact_form.php';
	include($file);
  }
  
}

/* register widget when loading the WP core */
add_action('widgets_init', 'just_register_widgets');
add_action('plugins_loaded', 'paloma_init');

add_action('wp_print_styles', 'add_paloma_stylesheet');
add_action('admin_menu', 'paloma_newsletter_actions');
add_action('enqueue_block_editor_assets', 'contact_control_scripts_enqueue');
    /*
     * Enqueue style-file, if it exists.
     */

    function add_paloma_stylesheet() {
		$myStyleFile = dirname(__FILE__).'/paloma_style.css';	
        if ( file_exists($myStyleFile) ) {
            wp_register_style('paloma_stylesheet', plugins_url('paloma_style.css', __FILE__));
            wp_enqueue_style( 'paloma_stylesheet');
        }
    }

function just_register_widgets(){
	// curl need to be installed
	register_widget('paloma_newsletter_widget');
}

	function paloma_newsletter_actions() {
		add_options_page('Paloma Widget', 'Paloma Widget', "manage_options", "paloma-widget-options", "paloma_admin");
	}
	
	function paloma_admin(){
		include('paloma_widget_admin.php');
	}

	function paloma_init(){
		load_plugin_textdomain( 'paloma', false, dirname( plugin_basename( __FILE__ ) ) );
	}
	
	function contact_control_scripts_enqueue() {
		wp_enqueue_script(
			'contact-control-script',
			plugins_url( 'contact_control.js', __FILE__ )
		);
	}
?>