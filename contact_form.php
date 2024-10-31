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
	wp_enqueue_script( 'jquery' );	
?>
<div class="paloma-email-subscription-box">
	<span class="paloma-email-subscription-title"><?php echo $boxTitle?></span>
	<form name="SubscribeForm" onsubmit="return ValidateForm()" action="https://public.paloma.se/subscription/register" method="POST">
		<input type="hidden" name="FormKey" value="<?php echo $formKey;?>">
		<input type="hidden" name="Lists" value="<?php echo $list_id;?>" />
		<input type="hidden" name="ThanksPage" value="<?php echo $thanksUrl;?>" />
		<input type="hidden" name="LegalBasis" value="<?php echo $legalBasis;?>">
		<?php
		if(!empty($consent_text_guid))
		{
			echo ('<input type="hidden" name="TermsGuid" value="' . $consent_text_guid . '" />');
			echo ('<input required="" name="haschecked" type="checkbox">');
			echo ('<span>&nbsp;'. __('I consent to', 'paloma') . '&nbsp;<a href="https://public.paloma.se/Consent/ReadConsent?termsguid=' . $consent_text_guid . '" target="_blank">' . __('the processing of personal data', 'paloma') . '</a></span>');
		}
		if(!empty($getTitle))
		{
			echo '<div class="paloma-text-field">' . __('Title', 'paloma') . '</div>';
			echo '<input type="text" name="title" class="paloma-get-title-text" />';
		}
		
		foreach($fields as $i => $field)
		{
			$required = $field["IsPrimaryField"] /* || field.IsRequired*/;
			$requiredAttribute = '';
			if($required) {
				$requiredAttribute = 'required';
			}
			echo '<div class="paloma-text-field">' . $field["TranslatedName"] . '</div>';
			echo '<input type="text" id="SubscriberForm_' . $field["FieldKey"] . '" name="' . $field["FieldKey"] . '" class="paloma-get-title-text" ' . $requiredAttribute . ' />';
			
			
			//Required validation message
			if ($required) {
				echo '<span id="SubscriberForm_' . $field["FieldKey"] . '_error_required" style="display:none;">' . __('Required', 'paloma') . '</span>';
			}
			
			//Data type validation message
			$dataTypeValidationMessage = '';
			switch ($field["ContactFieldType"]["DataType"]) {
				case 1: //string
					$dataTypeValidationMessage = __('ValueMustBeAString', 'paloma');
					break;
				case 2: //int
					$dataTypeValidationMessage = __('ValueMustBeAnInteger', 'paloma');
					break;
				case 3: // decimal
					$dataTypeValidationMessage = __('ValueMustBeANumber', 'paloma');
					break;
				case 4: //boolean
					$dataTypeValidationMessage = __('ValueMustBeTrueOrFalse', 'paloma');
					break;
			}
			echo '<span id="SubscriberForm_' . $field["FieldKey"] . '_error_datatype" style="display:none;">' . $dataTypeValidationMessage . '</span>';
			
			//Validation rules validation messages
			$rules = array_merge($field["ContactFieldValidationRules"], $field["ContactFieldType"]["ContactFieldValidationRules"]);
			foreach($rules as $r => $rule) {
				$message = $rule["Messages"]["0"]["Message"];
				echo '<span id="SubscriberForm_' . $field["FieldKey"] . '_error_' . $r . '" style="display:none;">' . $message . '</span>';
			}
		}
		
		?>
		<p><input type="submit" value="<?php esc_attr_e('Subscribe', 'paloma') ?>" class="paloma-submit" /></p>
		<?php if(!empty($mailarr)) { ?>
		<h3 class="widget-title"><?php _e('Latest mailings', 'paloma') ?></h3>
		<div class="paloma-mailings_list"><ul>
		<?php 
		foreach($mailarr as $mail)
		{
			$maildate = DateTime::createFromFormat('Y-n-j G:i:s', $mail->SendDate);
			if(is_object($maildate))
			{
			 echo '<li><a href="http://newsletter.paloma.se/webversion/default.aspx?cid='. $cid .'&mid=' . $mail->MailingID . '">'. $mail->Subject . ' <span class="paloma-maildate">'. $maildate->format('Y-m-d') . '</span></a></li>';
			}
		} ?>
		</ul></div>
		<?php } ?>
	</form>
</div>	


<script type="text/javascript">
    <?php
        $validationScripts = '';


		$validationScripts = $validationScripts . "var value = '';\n";

		foreach($fields as $i => $field)
		{
			$validationScript = '';

			$rules = array_merge($field["ContactFieldValidationRules"], $field["ContactFieldType"]["ContactFieldValidationRules"]);

			if ($field["IsPrimaryField"] /* || field.IsRequired*/ || count($rules) > 0 || $field["ContactFieldType"]["DataType"] == 2 || $field["ContactFieldType"]["DataType"] == 3) {
				$validationScript = $validationScript . 'var ' . $field["FieldKey"] . ' = document.SubscribeForm.SubscriberForm_' . $field["FieldKey"] . ";\n";
				$validationScript = $validationScript . 'value = ' . $field["FieldKey"] . ".value;\n";

				$required = $field["IsPrimaryField"] /* || field.IsRequired*/;

				if ($required) {
					$validationScript = $validationScript . "if(value == null || value == '') {\n"
						. "\t" . 'var errorElement = document.getElementById("SubscriberForm_' . $field["FieldKey"] . "_error_required\");\n"
						. "\tif(errorElement){\n"
						. "\t\t" . "errorElement.style.display = 'block';\n"
						. "\t}\n"
						. "\t" . $field["FieldKey"] . ".focus();\n"
						. "\t" . "return false;\n"
						. "}\n"
						. "else {\n"
						. "\t" . 'var errorElement = document.getElementById("SubscriberForm_' . $field["FieldKey"] . "_error_required\");\n"
						. "\tif(errorElement){\n"
						. "\t\t" . "errorElement.style.display = 'hidden';\n"
						. "\t}\n"
						. "}\n";
				}

				if (!$required) {
					$validationScript = $validationScript . "if(value != null && value != '') {\n";
				}

				if ($field["ContactFieldType"]["DataType"] == 2 || $field["ContactFieldType"]["DataType"] == 3) {
					$condition = '';
					switch ($field["ContactFieldType"]["DataType"]) {
						case 2: //int
						{
							$condition = '!isNaN(val) && val == parseInt(val).toString()';
							break;
						}
						case 3: // decimal
						{
							$condition = 'isNumeric(val)';
							break;
						}
					}
					$validationScript = $validationScript . 'if(' . $condition . ") {\n"
						. "\t" . 'var errorElement = document.getElementById("SubscriberForm_' . $field["FieldKey"] . "_error_datatype\");\n"
						. "\tif(errorElement){\n"
						. "\t\t" . "errorElement.style.display = 'block';\n"
						. "\t}\n"
						. "\t" . $field["FieldKey"] . ".focus();\n"
						. "\t" . "return false;\n"
						. "}\n"
						. "else {\n"
						. "\t" . 'var errorElement = document.getElementById("SubscriberForm_' . $field["FieldKey"] . "_error_datatype\");\n"
						. "\tif(errorElement){\n"
						. "\t\t" . "errorElement.style.display = 'hidden';\n"
						. "\t}\n"
						. "}\n";
				}

				foreach($rules as $r => $rule) {
					$condition = '';
					switch ($rule["Operator"]) {
						case 0: //Equals
							$condition = 'value != \'' . $rule["Value"] . '\'';
							break;
						case 1: //GreaterThan
							if ($field["ContactFieldType"]["DataType"] == 2) {
								$condition = 'parseInt(value) <= ' . $rule["Value"];
							}
							else if ($field["ContactFieldType"]["DataType"] == 3) {
								$condition = 'parseFloat(value) <= ' . $rule["Value"];
							}
							break;
						case 2: //LessThan
							if ($field["ContactFieldType"]["DataType"] == 2) {
								$condition = 'parseInt(value) >= ' . $rule["Value"];
							}
							else if ($field["ContactFieldType"]["DataType"] == 3) {
								$condition = 'parseFloat(value) >= ' . $rule["Value"];
							}
							break;
						case 3: //DoesNotEqual
							$condition = 'value != \'' . $rule["Value"] . '\'';
							break;
						case 4: //GreaterOrEqual
							if ($field["ContactFieldType"]["DataType"] == 2) {
								$condition = 'parseInt(value) < ' . $rule["Value"];
							}
							else if ($field["ContactFieldType"]["DataType"] == 3) {
								$condition = 'parseFloat(value) < ' . $rule["Value"];
							}
							break;
						case 5: //LessOrEqual
							if ($field["ContactFieldType"]["DataType"] == 2) {
								$condition = 'parseInt(value) > ' . $rule["Value"];
							}
							else if ($field["ContactFieldType"]["DataType"] == 3) {
								$condition = 'parseFloat(value) > ' . $rule["Value"];
							}
							break;
						case 6: //Like
							$condition = '!RegExp(/' . str_replace(array("'", '/'), array('\\\'', '\\/'), $rule["Value"]) . '/).test(value)';
							break;
						case 7: //NotLike
							$condition = 'RegExp(/' . str_replace(array("'", '/'), array('\\\'', '\\/'), $rule["Value"]) . '/).test(value)';
							break;
						case 8: //BeginsWith
							$condition = 'value.length < ' . $rule["Value"]
								. ' || value.substring(0, ' . strlen($rule["Value"]) . ') != \'' . $rule["Value"] . '\'';
							break;
						case 9: //DoesNotBeginWith
							$condition = 'value.length < ' . strlen($rule["Value"])
								. ' || value.substring(0, ' . strlen($rule["Value"]) . ') == \'' . $rule["Value"] . '\'';
							break;
						case 10: //EndsWith
							$condition = 'value.length < ' . strlen($rule["Value"]) . ' || value.substring(value - ' . strlen($rule["Value"]) . ') != \'' . $rule["Value"] . '\'';
							break;
						case 11: //DoesNotEndWith
							$condition = 'value.length >= ' . strlen($rule["Value"]) . ' && value.substring(value - ' . strlen($rule["Value"]) . ') == \'' . $rule["Value"] . '\'';
							break;
						case 12: //Contains
							$condition = 'value.lastIndexOf(\'' . $rule["Value"] . '\') == -1';
							break;
						case 13: //DoesNotContain
							$condition = 'value.lastIndexOf(\'' . $rule["Value"] . '\') != -1';
							break;
					}

					if ($condition != null && strlen($condition) > 0) {
						$validationScript = $validationScript . 'if(' . $condition . ") {\n"
							. "\t" . 'var errorElement = document.getElementById("SubscriberForm_' . $field["FieldKey"] . '_error_' . $r . '");' . "\n"
							. "\tif(errorElement){\n"
							. "\t\terrorElement.style.display = 'block';\n"
							. "\t}\n"
							. "\t" . $field["FieldKey"] . ".focus();\n"
							. "\t" . "return false;\n"
							. "}\n"
							. "else {\n"
							. "\t" . 'var errorElement = document.getElementById("SubscriberForm_' . $field["FieldKey"] . '_error_' . $r . '");' . "\n"
							. "\tif(errorElement){\n"
							. "\t\terrorElement.style.display = 'hidden';\n"
							. "\t}\n"
							. "}\n";
					}
				}

				if (!$required) {
					$validationScript = $validationScript . "}\n";
				}

				$validationScripts = $validationScripts . $validationScript;
			}
		}

		print( "function ValidateForm() {\n" . $validationScripts . "\n" . "}\n");
    ?>
</script>


	