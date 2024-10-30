<?php
/**
 * @package BoxTrackerOnline
 */

//Some security protocols
if(!defined('WP_UNINSTALL_PLUGIN')) { die; }

//now delete the options.
 delete_option('b0xT_page_title');
 delete_option('b0xT_username');
 delete_option('b0xT_password');
 delete_option('b0xT_zipcode_label');
 delete_option('b0xT_admin_country');
 delete_option('b0xT_google_api_key');
 delete_option('b0xT_payment_info');
 delete_option('b0xT_mode');
 delete_option('b0xT_api_mode');
 delete_option('b0xT_admin_debug');
 delete_option('b0xT_google_validation_bypass');
