<?php
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

	$selected = 'selected = "selected"';

	$paloma_user_account_id = get_option('paloma_user_account_id');
	$paloma_user_account_api_hash = get_option('paloma_user_account_api_hash');

	$args = array(
	  'headers' => array(
		'Authorization' => 'Basic ' . base64_encode( $paloma_user_account_id . ':' . $paloma_user_account_api_hash )
	  )
	);
	
	$api_response = wp_remote_get('https://api.paloma.se/contacts/api/contactfield', $args );
	$contactFields = json_decode( wp_remote_retrieve_body( $api_response ), true );
	
	$api_response = wp_remote_get('https://api.paloma.se/contacts/api/contactsegment', $args );
	$contactLists = json_decode( wp_remote_retrieve_body( $api_response ), true );
	
	$api_response = wp_remote_get('https://api.paloma.se/contacts/api/terms?has_published_revision=1', $args );
	$consentTexts = json_decode( wp_remote_retrieve_body( $api_response ), true );

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
<label><?php _e('Fields to include in form','paloma') ?></label>
<br/>
	<?php 
		foreach($contactFields as &$contactField)
		{
			$fieldKey = $contactField["FieldKey"];
			$checked = "";
			$disabled = "";
			if(!empty($instance["paloma_cf_" . $fieldKey]))
			{
				$checked = 'checked="checked"';
			}
			if($contactField["IsPrimaryField"] == true)
			{
				$checked = 'checked="checked"';
				$disabled = 'disabled="disabled"';
			}
			$inputId = str_replace("ContactFieldKey", $fieldKey, $field_id_contactField);
			$inputName = str_replace("ContactFieldKey", $fieldKey, $field_name_contactField);
			printf("<label><input type=\"checkbox\" name=\"%s\" id=\"%s\" value=\"%s\" %s %s />%s<br/></label>", $inputName, $inputId,$fieldKey,$checked,$disabled,$contactField["TranslatedName"]);
		}
	?>
</p>
<p>
	<label><?php _e('Add to list','paloma') ?> (<?php _e('optional','paloma') ?>)<br/>
    <select name="<?php echo $field_name_list?>" id="<?php echo $field_id_list?>">
    <option value=""><?php _e('Not selected','paloma') ?></option>
    <?php 

        if(is_array($contactLists)){
            foreach($contactLists as &$list) {
				$selected = ($list["Guid"] == $instance['paloma_contact_list_id']) ? 'selected = "selected"' : '';
				?>
					<option value="<?php echo $list["Guid"]?>" <?php echo $selected?> ><?php echo $list["Name"]?></option>
				<?php 
			}
        } else if(is_object($contactLists)){
            $selected = ($contactLists->Id == $instance['paloma_contact_list_id']) ? 'selected = "selected"' : '';
            printf("<option value=\"%s\" %s>%s</option>", $contactLists->Id, $selected, $contactLists->Name);
        }
		?>
    </select>
    </label>
</p>
<p>
	<label><?php _e('Legal basis','paloma') ?> <br/>
		<select name="<?php echo $field_name_legal_basis?>" id="<?php echo $field_id_legal_basis?>" onchange="legalBasisChanged('<?php echo $field_id_legal_basis?>', '<?php echo $field_container_id_legal_basis; ?>')">
			<option value="0" <?php if($instance['paloma_legal_basis'] == '0') echo ' selected="selected"'?>><?php _e('Missing','paloma') ?></option>
			<option value="1" <?php if($instance['paloma_legal_basis'] == '1') echo ' selected="selected"'?>><?php _e('Consent','paloma') ?></option>
			<option value="2" <?php if($instance['paloma_legal_basis'] == '2') echo ' selected="selected"'?>><?php _e('Other legal basis','paloma') ?></option>
			<option value="3" <?php if($instance['paloma_legal_basis'] == '3') echo ' selected="selected"'?>><?php _e('Not needed','paloma') ?></option>
			<option value="4" <?php if($instance['paloma_legal_basis'] == '4') echo ' selected="selected"'?>><?php _e('Legitimate interest','paloma') ?></option>
			<option value="5" <?php if($instance['paloma_legal_basis'] == '5') echo ' selected="selected"'?>><?php _e('Consent stored outside of paloma','paloma') ?></option>
			<option value="6" <?php if($instance['paloma_legal_basis'] == '6') echo ' selected="selected"'?>><?php _e('Legal obligation','paloma') ?></option>
			<option value="7" <?php if($instance['paloma_legal_basis'] == '7') echo ' selected="selected"'?>><?php _e('Public task','paloma') ?></option>
			<option value="8" <?php if($instance['paloma_legal_basis'] == '8') echo ' selected="selected"'?>><?php _e('Vital interest','paloma') ?></option>
			<option value="9" <?php if($instance['paloma_legal_basis'] == '9') echo ' selected="selected"'?>><?php _e('Contract','paloma') ?></option>
		</select>
	</label>
</p>
<p id="<?php echo $field_container_id_legal_basis; ?>" style="display: <?php if($instance['paloma_legal_basis'] == 1){echo "block";} else { echo "none";} ?>;">
	<label><?php _e('Consent text','paloma') ?>  (<?php _e('optional','paloma') ?>)<br/>
		<select name="<?php echo $field_name_consent_text?>" id="<?php echo $field_id_consent_text?>">
			<option value=""><?php _e('Not selected','paloma') ?></option>
			<?php 
				if(is_array($consentTexts)){
					foreach($consentTexts as &$consent_text) {
					$selected = ($consent_text["Guid"] == $instance['paloma_consent_text_guid']) ? 'selected = "selected"' : '';
					?>
					<option value="<?php echo $consent_text["Guid"]?>" <?php echo $selected?> ><?php echo $consent_text["Name"]?></option>
					<?php }
				} else if(is_object($consentTexts)){
					$selected = ($consentTexts["Guid"] == $instance['paloma_consent_text_guid']) ? 'selected = "selected"' : '';
					printf("<option value=\"%s\" %s>%s</option>", $consentTexts["Guid"], $selected, $consentTexts["Name"]);
				}
			?>
		</select>
	</label>
</p>
<p>
  <label><?php _e('URL of Thanks Page','paloma') ?><br/>
    <input type="text" class="widefat" name="<?php echo $field_name_thanks?>" id="<?php echo $field_id_thanks?>" value="<?php echo esc_attr( $instance['paloma_thanks'] )?>">
    </label> 
</p>
