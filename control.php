<?php
/*
	This file is part of  Widget.

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

$selected = 'selected = "selected"';
?>
<p>
	<label><?php _e('Title','paloma') ?><br/>
    <input type="text" class="widefat" id="<?php echo $field_id_title?>" name="<?php echo $field_name_title?>" value="<?php echo esc_attr( $instance['paloma_title'] )?>" />
    </label>
   
</p>
<p>
	<label><?php _e('Description','paloma') ?><br/>
    <input type="text" class="widefat" id="<?php echo $field_id_box_title?>" name="<?php echo $field_name_box_title?>" value="<?php echo esc_attr( $instance['paloma_box_title'] )?>" />
    </label>
   
</p>
<p>
	<?php _e('Extra Fields', 'paloma') ?><br/>
	<label><input type="checkbox" name="<?php echo $field_name_get_title?>" id="<?php echo $field_id_get_title?>" value="Title" <?php 
		if(!empty($instance['paloma_get_title']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Title', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_company?>" id="<?php echo $field_id_get_company?>" value="Company" <?php 
		if(!empty($instance['paloma_get_company']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Company', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_mobilenr?>" id="<?php echo $field_id_get_mobilenr?>" value="MobilePhone" <?php 
		if(!empty($instance['paloma_get_mobilenr']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Mobile Nr', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_phonenr?>" id="<?php echo $field_id_get_phonenr?>" value="Phone" <?php 
		if(!empty($instance['paloma_get_phonenr']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Phone Nr', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_fax?>" id="<?php echo $field_id_get_fax?>" value="Fax" <?php 
		if(!empty($instance['paloma_get_fax']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Fax', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_address?>" id="<?php echo $field_id_get_address?>" value="Address" <?php 
		if(!empty($instance['paloma_get_address']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Address', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_postcode?>" id="<?php echo $field_id_get_postcode?>" value="Postcode" <?php 
		if(!empty($instance['paloma_get_postcode']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Postcode', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_city?>" id="<?php echo $field_id_get_city?>" value="City" <?php 
		if(!empty($instance['paloma_get_city']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('City', 'paloma')?><br/></label>
	<label><input type="checkbox" name="<?php echo $field_name_get_state?>" id="<?php echo $field_id_get_state?>" value="State" <?php 
		if(!empty($instance['paloma_get_state']))
		{
			echo 'checked="checked"';
		}
	?>><?php _e('Country', 'paloma')?><br/></label>
</p>
<p>
	<label><?php _e('Address List','paloma') ?><br/>
    <select name="<?php echo $field_name_list?>" id="<?php echo $field_id_list?>">
    <option value=""><?php _e('Select list','paloma') ?></option>
    <?php 
		$paloma_customer_id = get_option('paloma_customer_id');
		$paloma_customer_hash = get_option('paloma_customer_hash');
		try
		{
			$client = new SoapClient("https://api.paloma.se/PalomaWebService.asmx?WSDL");
			$results = $client->ListAddressLists(array('customerID' => $paloma_customer_id,
									'customerHash' => $paloma_customer_hash));
			$addressList = $results->ListAddressListsResult->AddressLists->AddressList;
			if(is_array($addressList)){
				foreach($addressList as &$list) {
				$selected = ($list->ListID == $instance['paloma_list_id']) ? 'selected = "selected"' : '';
				?>
				<option value="<?php echo $list->ListID?>" <?php echo $selected?> ><?php echo $list->ListTitle?></option>
				<?php }
			} else if(is_object($addressList)){
				$selected = ($addressList->ListID == $instance['paloma_list_id']) ? 'selected = "selected"' : '';
				printf("<option value=\"%s\" %s>%s</option>", $addressList->ListID, $selected, $addressList->ListTitle);
			}
		}
		catch(Exception $e)
		{
			echo 'Could not load address lists';
		}
		?>
    </select>
    </label>
	
	<?php
		$paloma_user_account_id = get_option('paloma_user_account_id');
		$paloma_user_account_api_hash = get_option('paloma_user_account_api_hash');
		
		$consentVisible = $paloma_user_account_id != null && $paloma_user_account_id != "" && $paloma_user_account_api_hash != null && $paloma_user_account_api_hash != "";
	?>
	
	<div style="display: <?php echo($consentVisible ? "block" : "none"); ?>;">
		<label><?php _e('Consent Text','paloma') ?><br/>
		<select name="<?php echo $field_name_consent_text?>" id="<?php echo $field_id_consent_text?>">
		<option value=""><?php _e('Select consent text','paloma') ?></option>
		<?php 
			$paloma_user_account_id = get_option('paloma_user_account_id');
			$paloma_user_account_api_hash = get_option('paloma_user_account_api_hash');

			$api_request    = 'https://api.paloma.se/contacts/api/terms?has_published_revision=1';
			$args = array(
			  'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $paloma_user_account_id . ':' . $paloma_user_account_api_hash )
			  )
			);
			$api_response = wp_remote_get( $api_request, $args );
			$consent_texts = json_decode( wp_remote_retrieve_body( $api_response ), true );
			
			if(is_array($consent_texts)){
				foreach($consent_texts as &$consent_text) {
				$selected = ($consent_text["Guid"] == $instance['paloma_consent_text_guid']) ? 'selected = "selected"' : '';
				?>
				<option value="<?php echo $consent_text["Guid"]?>" <?php echo $selected?> ><?php echo $consent_text["Name"]?></option>
				<?php }
			} else if(is_object($consent_texts)){
				$selected = ($consent_texts["Guid"] == $instance['paloma_consent_text_guid']) ? 'selected = "selected"' : '';
				printf("<option value=\"%s\" %s>%s</option>", $consent_texts["Guid"], $selected, $consent_texts["Name"]);
			}
			
			?>
		</select>
		</label>
	</div>
	<div style="display: <?php echo($consentVisible ? "none" : "block"); ?>;">
		<p style="border: 1px solid; padding: 10px; border-radius: 5px; background-color: lightblue;">
			<span style="font-weight: bold;"><?php _e('Tip','paloma') ?>:</span><br/>
			<?php _e('You can enter additional api settings to enable gathering of consent for GDPR','paloma') ?>
		</p>
	</div>
</p>
<p>
  <label><?php _e('URL of Thanks Page','paloma') ?><br/>
    <input type="text" class="widefat" name="<?php echo $field_name_thanks?>" id="<?php echo $field_id_thanks?>" value="<?php echo esc_attr( $instance['paloma_thanks'] )?>">
    </label> 
</p>
<p>
	<label><?php _e('Show Recent Mailings','paloma') ?><br/>
		<select name="<?php echo $field_name_showmailings?>" id="<?php echo $field_id_showmailings?>">
			<option value="0"<?php if($instance['paloma_showmailings'] == '0') echo ' selected="selected"'?>><?php _e('None','paloma') ?></option>
			<option value="1"<?php if($instance['paloma_showmailings'] == '1') echo ' selected="selected"'?>>1</option>
			<option value="2"<?php if($instance['paloma_showmailings'] == '2') echo ' selected="selected"'?>>2</option>
			<option value="3"<?php if($instance['paloma_showmailings'] == '3') echo ' selected="selected"'?>>3</option>
			<option value="4"<?php if($instance['paloma_showmailings'] == '4') echo ' selected="selected"'?>>4</option>
			<option value="5"<?php if($instance['paloma_showmailings'] == '5') echo ' selected="selected"'?>>5</option>
		</select>
	</label>
</p>