<?php
/**
 * This class will load up the
 * form for the front house page.
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_gui;
use b0xT_includes\b0xT_base\B0xT_Global_Variables;

class B0xT_Front_House extends B0xT_Global_Variables {
    function b0xT_init() {
        add_shortcode('box-tracker-online', array($this, 'b0xT_load_plugin_form'));
    }

     /**
     * @var string $b0xT_password
     * @var string $b0xT_username
     * @return template or an error
     */
    function b0xT_load_plugin_form() {
        //lets make sure we have some credentials on file.
        $b0xT_username = sanitize_text_field(get_option('b0xT_username'));
        $b0xT_password = sanitize_text_field(get_option('b0xT_password'));
        
        if($b0xT_username == "" || $b0xT_password == "") {
            return __('Please contact to administrator, Invalid configuration.', 'box-tracker-online');
        }

        if($this->b0xT_plugin_path) {
            if(file_exists($this->b0xT_plugin_path.'templates/front-house-step-one-template.php')) {
                require_once $this->b0xT_plugin_path.'templates/front-house-step-one-template.php'; 
            }

            if(file_exists($this->b0xT_plugin_path.'templates/front-house-step-two-template.php')) {
                require_once $this->b0xT_plugin_path.'templates/front-house-step-two-template.php';
            }

            if(file_exists($this->b0xT_plugin_path.'templates/front-house-step-three-template.php')) {
                require_once $this->b0xT_plugin_path.'templates/front-house-step-three-template.php';
            }
            
            if(file_exists($this->b0xT_plugin_path.'templates/front-house-step-four-template.php')) {
                require_once $this->b0xT_plugin_path.'templates/front-house-step-four-template.php';
            }
        }
    }
}