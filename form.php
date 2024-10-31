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
	<form name="fpren" onsubmit="return ValidateForm()" action="https://newsletter.paloma.se/register/" method="get">
		<input type="hidden" name="distlistkey" value="<?php echo $list_id;?>" />
		<input type="hidden" name="gora" value="pren" />
		<input type="hidden" name="tacksida" value="<?php echo $thanksUrl;?>" />
		<div class="paloma-text-field"><?php _e('Name', 'paloma') ?></div>
		<input type="text" name="namn" class="paloma-name-text" />
		<div class="paloma-text-field"><?php _e('Email', 'paloma') ?></div>
		<input type="text" name="email" class="paloma-email-text" />
		<?php
		if(!empty($consent_text_guid))
		{
			echo ('<input type="hidden" name="termsguid" value="' . $consent_text_guid . '" />');
			echo ('<input required="" name="haschecked" type="checkbox">');
			echo ('<span>Jag samtycker till <a href="https://public.paloma.se/Consent/ReadConsent?termsguid=' . $consent_text_guid . '" target="_blank">behandling av mina personuppgifter</a></span>');
		}
		if(!empty($getTitle))
		{
			echo '<div class="paloma-text-field">' . __('Title', 'paloma') . '</div>';
			echo '<input type="text" name="title" class="paloma-get-title-text" />';
		}
		if(!empty($getCompany))
		{
			echo '<div class="paloma-text-field">' . __('Company', 'paloma') . '</div>';
			echo '<input type="text" name="company" class="paloma-get-company-text" />';
		}
		if(!empty($getMobileNr))
		{
			echo '<div class="paloma-text-field">' . __('Mobile Number', 'paloma') . '</div>';
			echo '<input type="text" name="mobile_phone" class="paloma-get-mobilenr-text" />';
		}
		if(!empty($getPhoneNr))
		{
			echo '<div class="paloma-text-field">' . __('Phone Number', 'paloma') . '</div>';
			echo '<input type="text" name="phone" class="paloma-get-phonenr-text" />';
		}
		if(!empty($getFax))
		{
			echo '<div class="paloma-text-field">' . __('Fax', 'paloma') . '</div>';
			echo '<input type="text" name="fax" class="paloma-get-fax-text" />';
		}
		if(!empty($getAddress))
		{
			echo '<div class="paloma-text-field">' . __('Address', 'paloma') . '</div>';
			echo '<input type="text" name="address" class="paloma-get-address-text" />';
		}
		if(!empty($getPostcode))
		{
			echo '<div class="paloma-text-field">' . __('Postal Code', 'paloma') . '</div>';
			echo '<input type="text" name="postcode" class="paloma-get-postcode-text" />';
		}
		if(!empty($getCity))
		{
			echo '<div class="paloma-text-field">' . __('City', 'paloma') . '</div>';
			echo '<input type="text" name="city" class="paloma-get-city-text" />';
		}
		if(!empty($getState))
		{
			echo '<div class="paloma-text-field">' . __('Country', 'paloma') . '</div>';
			echo '<input type="text" name="state" class="paloma-get-state-text" />';
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
    function EmailCheck(str) {
        var ret = true;
        var at = "@"
        var dot = "."
        var lat = str.indexOf(at)
        var lstr = str.length
        var ldot = str.indexOf(dot)
        if (str.indexOf(at) == -1) {
            ret = false;
        }
        if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr) {
            ret = false;
        }
        if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr) {
            ret = false;
        }
        if (str.indexOf(at, (lat + 1)) != -1) {
            ret = false;
        }
        if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot) {
            ret = false;
        }
        if (str.indexOf(dot, (lat + 2)) == -1) {
            ret = false;
        }
        if (str.indexOf(" ") != -1) {
            ret = false;
        }
        return ret;
    }
    function ValidateForm() {
        var emailID = document.fpren.email
        if ((emailID.value == null) || (emailID.value == '')) {
            alert('<?php esc_attr_e('Invalid e-mail.','paloma')?>')
            emailID.focus()
            return false;
        }
        if (EmailCheck(emailID.value) == false) {
            emailID.value = ""
            emailID.focus()
            return false;
        }
		if(document.fpren.haschecked != null && !document.fpren.haschecked.checked)
		{
			return false;
		}
        return true;
    }
</script>


	