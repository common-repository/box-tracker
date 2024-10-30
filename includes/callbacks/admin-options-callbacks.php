<?php
/**
 * Dynamiclly handle callbacks for 
 * the amdmin settings api class.
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_callbacks;

use b0xT_includes\b0xT_base\B0xT_Global_Variables;

class B0xT_Admin_Options_Callbacks extends B0xT_Global_Variables {
    function b0xT_admin_options_template() { 
        if($this->b0xT_plugin_path && file_exists($this->b0xT_plugin_path.'templates/admin-options-template.php')) {      
            return require_once $this->b0xT_plugin_path.'templates/admin-options-template.php';
        }
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_username($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        //validate
        if($b0xT_input == ""){
            add_settings_error('b0xT_username', 'b0xT_username', __('Please enter a username', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_username'));
        }  

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_password($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        //validate
        if($b0xT_input == ""){
            add_settings_error('b0xT_password', 'b0xT_password', __('Please enter a password', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_password'));
        }

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_admin_country($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        //validate
        if(!($b0xT_input == "United States" || $b0xT_input == "Canada")) {
            add_settings_error('b0xT_admin_country', 'b0xT_admin_country', __('Please select a country', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_admin_country'));
        }   

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_google_api_key($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        //validate
        if($b0xT_input == ""){
            add_settings_error('b0xT_google_api_key', 'b0xT_google_api_key', __('Please enter a Google API Key', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_google_api_key'));
        }   

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_payment_info($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        //validate
        if(!($b0xT_input == "Show" || $b0xT_input == "Hide")){
            add_settings_error('b0xT_payment_info', 'b0xT_payment_info', __('Please select to show/hide payment info.', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_payment_info'));
        } 

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_mode($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        //validate
        if(!($b0xT_input == "TEST" || $b0xT_input == "LIVE")){
            add_settings_error('b0xT_mode', 'b0xT_mode', __('Please select a Test/Live mode', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_mode'));
        }  

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_api_mode($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        if(!($b0xT_input == "cmdBoxTWebAPIRequestService" || $b0xT_input == "cmdBoxTWebAPIDirectBooking")) {
            add_settings_error('b0xT_api_mode', 'b0xT_api_mode', __('Please select an api mode', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_api_mode'));
        }  

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_admin_debug($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        if(!($b0xT_input == "On" || $b0xT_input == "Off")){
            add_settings_error('b0xT_admin_debug', 'b0xT_admin_debug', __('Please select a Debug', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_admin_debug'));
        }  

        return $b0xT_input;
    }

    /**
     * Validates the the input and
     * throws an error if its blank
     * 
     * @param string $b0xT_input
     * @return string $b0xT_input
     */
    function b0xT_validate_bypass_google_validation($b0xT_input) {
        //sanitize
        $b0xT_input = sanitize_text_field($b0xT_input);

        if(!($b0xT_input == "On" || $b0xT_input == "Off")){
            add_settings_error('b0xT_google_validation_bypass', 'b0xT_google_validation_bypass', __('Bypass Google Validation must be equal to On/Off', 'b0xT'), 'error');
            $b0xT_input = sanitize_text_field(get_option('b0xT_google_validation_bypass'));
        }  

        return $b0xT_input;
    }
}
