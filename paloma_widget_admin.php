<?php 
	/*
	This file is part of Postman Widget.

    Postman Widget is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Postman Widget is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Postman Widget.  If not, see <http://www.gnu.org/licenses/>.
	*/
	
    if(isset($_POST['paloma_hidden'])) {  
        //Form data sent  
		$paloma_customer_id = $_POST['paloma_customer_id'];
		$paloma_customer_hash = $_POST['paloma_customer_hash'];
		$paloma_user_account_id = $_POST['paloma_user_account_id'];
		$paloma_user_account_api_hash = $_POST['paloma_user_account_api_hash'];
		update_option('paloma_customer_id', $paloma_customer_id);
		update_option('paloma_customer_hash', $paloma_customer_hash);
		update_option('paloma_user_account_id', $paloma_user_account_id);
		update_option('paloma_user_account_api_hash', $paloma_user_account_api_hash);
?>
		<div class="updated"><p><strong><?php _e('Options saved.','paloma'); ?></strong></p></div>  
<?php  
	}
	else
	{
		if (isset($_POST['paloma_address_list_id']) && $_POST['paloma_address_list_id'] != '') {
			$paloma_address_list_id = $_POST['paloma_address_list_id'];
			update_option('paloma_address_list_id', $paloma_address_list_id);
		}
		if (isset($_POST['paloma_consent_text_id']) && $_POST['paloma_consent_text_id'] != '') {
			$paloma_consent_text_id = $_POST['paloma_consent_text_id'];
			update_option('paloma_consent_text_id', $paloma_consent_text_id);
		}
        //Normal page display  
		$paloma_customer_id = get_option('paloma_customer_id');
		$paloma_customer_hash = get_option('paloma_customer_hash');
		$paloma_user_account_id = get_option('paloma_user_account_id');
		$paloma_user_account_api_hash = get_option('paloma_user_account_api_hash');
		$paloma_address_list_id = get_option('paloma_address_list_id');
		$paloma_consent_text_id = get_option('paloma_consent_text_id');
    }   
?>

<?php
	$hasSettings = $paloma_user_account_id != null && $paloma_user_account_id != "" && $paloma_user_account_api_hash != null && $paloma_user_account_api_hash != "";
?>

<div class="wrap">  
	<h2><?php _e( 'API Settings', 'paloma' );?></h2>
  
	<form name="paloma_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
		<input type="hidden" name="paloma_hidden" value="Y">  
		
		<h4><?php _e( 'In order to collect newsletter subscriptions from your Paloma Widget, you need to provide valid API credentials. If you are an active customer, contact support to receive your credentials: support@paloma.se', 'paloma' ); ?></h4>
		
		<p style="border: 1px solid; padding: 10px; border-radius: 5px; background-color: lightblue; width: 550px; display: <?php echo($hasSettings ? "none" : "block") ?>;">
			<span style="font-weight: bold;"><?php _e('Information','paloma') ?>:</span><br/>
			<?php _e('When consent gathering is activated, you will be able to choose a consent text for the widget.','paloma') ?><br/>
			<?php _e('Consent texts are created inside Paloma under Account settings/Consent texts.','paloma') ?>
		</p>
		<table class="form-table">
			<tr>
				<th><label><?php _e('User account-ID:', 'paloma'); ?></label></th>
				<td>
					<input type="text" name="paloma_user_account_id" class="regular-text" value="<?php echo $paloma_user_account_id; ?>" size="20">
					<?php _e('ex: 12345', 'paloma'); ?>
				</td>
			</tr>  
			<tr>
				<th><label><?php _e('User account API-Hash:', 'paloma'); ?></label></th>
				<td>
					<input type="text" name="paloma_user_account_api_hash" class="regular-text" value="<?php echo $paloma_user_account_api_hash; ?>" size="20">
					<?php _e('ex: HTYU6hjGHF5Y', 'paloma'); ?>
				</td>
			</tr>
			
			
			<tr>
				<th><label><?php _e('Customer-ID:', 'paloma'); ?></label></th>
				<td>
					<input type="text" name="paloma_customer_id" class="regular-text" value="<?php echo $paloma_customer_id; ?>" size="20">
					<?php _e('ex: 1702', 'paloma'); ?>
				</td>
			</tr>  
			<tr>
				<th><label><?php _e('Customer-Hash:', 'paloma'); ?></label></th>
				<td>
					<input type="text" name="paloma_customer_hash" class="regular-text" value="<?php echo $paloma_customer_hash; ?>" size="20">
					<?php _e('ex: HTYU6hjGHF5Y', 'paloma'); ?>
				</td>
			</tr>
		</table> 
		<p class="submit">
			<input type="submit" name="Submit" class="button button-primary" value="<?php _e('Save changes', 'paloma' ) ?>" />
		</p>
		<hr />  
	</form>
	<?php
		$hasActiveContactLicense = false;
		$hasCorrectuserAccountApiSettings = false;
		
		if($paloma_user_account_id != null && $paloma_user_account_id != '' && $paloma_user_account_api_hash != null && $paloma_user_account_api_hash != '')
		{
			$api_request    = 'https://members.paloma.se/internalapis/api/customerproductlicense?product=6&active=true';
			$args = array(
			  'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $paloma_user_account_id . ':' . $paloma_user_account_api_hash )
			  )
			);
			$api_response = wp_remote_get( $api_request, $args );
			$api_response_status = wp_remote_retrieve_response_code($api_response);
			$activeLicenses = json_decode( wp_remote_retrieve_body( $api_response ), true );
			if($api_response_status == 200)
			{
				$hasCorrectuserAccountApiSettings = true;
				$hasActiveContactLicense = is_array($activeLicenses) && count($activeLicenses) > 0;
			}
			else
			{
				printf("<b>". __('Incorrect User account-ID or User account API-Hash.', 'paloma') . "</b><br/>");
			}
		}
		update_option('paloma_customer_has_contacts', $hasActiveContactLicense);
	
		if($hasActiveContactLicense)
		{
			$api_request    = 'https://api.paloma.se/contacts/api/contactlist';
			$args = array(
			  'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $paloma_user_account_id . ':' . $paloma_user_account_api_hash )
			  )
			);
			$api_response = wp_remote_get( $api_request, $args );
			$contact_lists = json_decode( wp_remote_retrieve_body( $api_response ), true );
			
			if(is_array($contact_lists))
			{
				printf("<b>". __('You have %d contactlists.','paloma') . "</b><br/>", count($contact_lists));
			}
			else if(is_object($contact_lists))
			{
				printf("<b>". __('You have 1 contactlist text.', 'paloma') . "</b>");
			}
			else
			{
				echo "<b>". __('Either the ID and hash are incorrect or you do not have any contactlists.', 'paloma') . "</b>";
			}
			
			$api_request    = 'https://members.paloma.se/internalapis/api/customer/GetCustomerPublicGuid';
			$api_response = wp_remote_get( $api_request, $args );
			$publicGuid = json_decode( wp_remote_retrieve_body( $api_response ), true );
			update_option('customer_public_guid', $publicGuid);
			
			echo "<b>Your form key is '" . $publicGuid . "'<br/>";
			print_r($publicGuid);
		}
		else if($paloma_customer_id != "" && $paloma_customer_hash != ""){
			try
			{
				$client = new SoapClient("https://api.paloma.se/PalomaWebService.asmx?WSDL");
				$results = $client->ListAddressLists(array('customerID' => $paloma_customer_id,
										'customerHash' => $paloma_customer_hash));
				$isErrorResonse = $results->ListAddressListsResult->Status == "InvalidCustomer";
				if($isErrorResonse)
				{
					printf("<b>". __('Either the Customer-ID or Customer-Hash is incorrect.','paloma') . "</b>");
				}
				else
				{
					$addressList = $results->ListAddressListsResult->AddressLists->AddressList;
					if(is_array($addressList))
					{
						printf("<b>". __('You have %d address lists.','paloma') . "</b>", count($addressList));
					}
					else if(is_object($addressList))
					{
						printf("<b>". __('You have 1 address list.', 'paloma') . "</b>");
					}
					else
					{
						printf("<b>". __('You have 0 address lists.','paloma') . "</b>");
					}
				}
			}
			catch(Exception $e)
			{
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
		echo "<br/>";
		
		if($hasCorrectuserAccountApiSettings && $paloma_user_account_id != "" && $paloma_user_account_api_hash != "")
		{
			$api_request    = 'https://api.paloma.se/contacts/api/terms?has_published_revision=1';
			$args = array(
			  'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $paloma_user_account_id . ':' . $paloma_user_account_api_hash )
			  )
			);
			$api_response = wp_remote_get( $api_request, $args );
			$consent_texts = json_decode( wp_remote_retrieve_body( $api_response ), true );
			
			if(is_array($consent_texts) && array_key_exists(0, $consent_texts) && array_key_exists("Guid", $consent_texts[0]))
			{
				printf("<b>". __('You have %d consent texts.', 'paloma') . "</b>", count($consent_texts));
			}
			else if(is_object($consent_texts) && array_key_exists("Guid", $consent_texts))
			{
				printf("<b>". __('You have 1 consent text.', 'paloma') . "</b>");
			}
			else
			{
				echo "<b>". __('Either the ID and hash are incorrect or you do not have any consent texts.', 'paloma') . "</b>";
			}
		}
		?>

</div>  