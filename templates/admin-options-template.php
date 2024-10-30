<div class="wrap">
	<div id="b0xT_setting_logo_div">
		<?php echo '<img src="'.esc_url($this->b0xT_plugin_url).'images/logo.png'.'">'; ?>
	</div>

	<?php settings_errors(); ?>

	<form id="b0xT_setting_form" method="post" action="options.php">
		<?php
			settings_fields('b0xT_setting_group');
			do_settings_sections('box-tracker-online');
		?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Page Title</th>
					<td><input type="text" name="b0xT_page_title" value="<?php echo esc_attr(get_option('b0xT_page_title')); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row">Username</th>
					<td><input type="text" name="b0xT_username" value="<?php echo  esc_attr(get_option('b0xT_username')); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row">Password</th>
					<td><input type="password" name="b0xT_password" value="<?php echo esc_attr(get_option('b0xT_password')); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row">Postalcode/Zipcode Label </th>
					<td><input type="text" name="b0xT_zipcode_label" value="<?php echo esc_attr(get_option('b0xT_zipcode_label')); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row">Country</th>
					<td>
						<select name="b0xT_admin_country">
							<option value="United States" <?php selected(esc_attr(get_option('b0xT_admin_country')), 'United States'); ?>>United States</option>
							<option value="Canada" <?php selected(esc_attr(get_option('b0xT_admin_country')), 'Canada'); ?>>Canada</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Google API Key</th>
					<td>
						<input type="text" name="b0xT_google_api_key" value="<?php echo esc_attr(get_option('b0xT_google_api_key')); ?>">
						<a href="https://developers.google.com/places/web-service/get-api-key" target="_blank">How to get api key?</a>
					</td>
				</tr>
                    <tr valign="top">
                         <th scope="row">Bypass Google Validation</th>
                         <td>
                              <select name="b0xT_google_validation_bypass">
                                   <option value="On"  <?php selected(esc_attr(get_option('b0xT_google_validation_bypass')), 'On');  ?>>On</option>
                                   <option value="Off" <?php if(esc_attr(get_option('b0xT_google_validation_bypass'))) { 
                                        selected(esc_attr(get_option('b0xT_google_validation_bypass')), 'Off'); 
                                   } else { 
                                        echo 'selected="selected"'; 
                                   } ?>>Off</option>
                              </select>
                         </td>
                    </tr>
				<tr valign="top">
					<th scope="row">Payment Info</th>
					<td>
						<select name="b0xT_payment_info">
							<option value="Show" <?php selected(esc_attr(get_option('b0xT_payment_info')), 'Show'); ?>>Show</option>
							<option value="Hide" <?php selected(esc_attr(get_option('b0xT_payment_info')) ,'Hide'); ?>>Hide</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Mode</th>
					<td>
						<select name="b0xT_mode">
							<option value="TEST" <?php selected(esc_attr(get_option('b0xT_mode')), 'TEST'); ?>>Test</option>
							<option value="LIVE" <?php selected(esc_attr(get_option('b0xT_mode')), 'LIVE'); ?>>Live</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">API Mode</th>
					<td>
						<select name="b0xT_api_mode">
							<option value="cmdBoxTWebAPIRequestService" <?php selected(esc_attr(get_option('b0xT_api_mode')), 'cmdBoxTWebAPIRequestService'); ?>>Requesting Service</option>
							<option value="cmdBoxTWebAPIDirectBooking" <?php selected(esc_attr(get_option('b0xT_api_mode')), 'cmdBoxTWebAPIDirectBooking'); ?>>Direct Booking</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Debug</th>
					<td>
						<select name="b0xT_admin_debug">
							<option value="On" <?php selected(esc_attr(get_option('b0xT_admin_debug')), 'On'); ?>>On</option>
							<option value="Off" <?php selected(esc_attr(get_option('b0xT_admin_debug')), 'Off'); ?>>Off</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">IP Address</th>
					<td>
						<input type="text" name="b0xT_ip_address" id="b0xT_ip_address" value="" disabled>
						<input type="button" id="b0xT_check_details_ip" class="button-primary" value="Check Details" disabled/>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
</div>
