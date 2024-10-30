<?php
/**
 * This class will create an admin
 * setting page on the dashboard.
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_gui;

use b0xT_includes\b0xT_base\B0xT_Global_Variables;
use b0xT_includes\b0xT_callbacks\B0xT_Admin_Options_Callbacks;

class B0xT_Admin_Options extends B0xT_Global_Variables {
    public $b0xT_admin_options_callbacks;

    function b0xT_init() {
        if(class_exists('b0xT_includes\b0xT_callbacks\B0xT_Admin_Options_Callbacks')) {
            $this->b0xT_admin_options_callbacks = new B0xT_Admin_Options_Callbacks();
            add_action('admin_menu', array($this, 'b0xT_admin_menu_page'));
            add_action('admin_init', array($this, 'b0xT_admin_options_settings'));
        }
    }

    function b0xT_admin_menu_page() {
        $b0xT_imgage_icon = '';
        if($this->b0xT_plugin_url && wp_http_validate_url($this->b0xT_plugin_url)) {
            $b0xT_imgage_icon = $this->b0xT_plugin_url.'images/icon.png';
        }

        add_menu_page('Box Tracker Online', 'Box Tracker', 'administrator', 'box-tracker-online', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_admin_options_template') ? array($this->b0xT_admin_options_callbacks, 'b0xT_admin_options_template') : '', $b0xT_imgage_icon, 101);
    }

    function b0xT_admin_options_settings() {
        register_setting('b0xT_setting_group', 'b0xT_username', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_username') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_username') : '');
        register_setting('b0xT_setting_group', 'b0xT_password', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_password') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_password') : '');
        register_setting('b0xT_setting_group', 'b0xT_admin_country', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_admin_country') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_admin_country') : '');
        register_setting('b0xT_setting_group', 'b0xT_google_api_key', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_google_api_key') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_google_api_key') : '');
        register_setting('b0xT_setting_group', 'b0xT_payment_info', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_payment_info') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_payment_info') : '');
        register_setting('b0xT_setting_group', 'b0xT_mode', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_mode') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_mode') : '');
        register_setting('b0xT_setting_group', 'b0xT_api_mode', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_api_mode') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_api_mode') : '');
        register_setting('b0xT_setting_group', 'b0xT_admin_debug', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_admin_debug') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_admin_debug') : '');
        register_setting('b0xT_setting_group', 'b0xT_zipcode_label', '');
        register_setting('b0xT_setting_group', 'b0xT_page_title', '');
        register_setting('b0xT_setting_group', 'b0xT_google_validation_bypass', method_exists($this->b0xT_admin_options_callbacks, 'b0xT_validate_bypass_google_validation') ? array($this->b0xT_admin_options_callbacks, 'b0xT_validate_bypass_google_validation') : '');
    }
}
