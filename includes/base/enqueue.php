<?php
/**
 * This class will load my
 * scripts and my css files.
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

use b0xT_includes\b0xT_base\B0xT_Global_Variables;

class B0xT_Enqueue extends B0xT_Global_Variables {
    function b0xT_init() {
        if($this->b0xT_plugin_url && wp_http_validate_url($this->b0xT_plugin_url)) {
            add_action('admin_enqueue_scripts', array($this, 'b0xT_admin_enqueue'));
            add_action('wp_enqueue_scripts',    array($this, 'b0xT_wp_enqueue'));
        }
    }

    //Admin page
    function b0xT_admin_enqueue() {
        wp_enqueue_style('b0xT-admin-options-style', $this->b0xT_plugin_url.'styles/admin-options-styles.css');
    }

    //Front end page
    function b0xT_wp_enqueue() {
        global $post; //we need to know if the short code exist before we load scripts/css
        if(is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'box-tracker-online')) {
            wp_enqueue_style('b0xT-dialog-box-style',  $this->b0xT_plugin_url.'styles/dialog-box-styles.css', '', '', 'all');
            wp_enqueue_style('b0xT-front-house-style', $this->b0xT_plugin_url.'styles/front-house-styles.css', '', '', 'all');
            wp_enqueue_style('b0xT-jquery-ui-style',   "https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css", '', '', 'all');

            $b0xT_google_api_key = esc_attr(get_option('b0xT_google_api_key'));
            wp_enqueue_script('b0xT-front-house-script',        $this->b0xT_plugin_url.'javascript/front-house-script.js', array('jquery'), '', 'all');
            wp_enqueue_script('b0xT-address-validation-script', $this->b0xT_plugin_url.'javascript/address-validation-script.js', '', '', 'all');
            wp_enqueue_script('b0xT-dialog-box-script',         $this->b0xT_plugin_url.'javascript/dialog-box-script.js', '', '', 'all');
     
            wp_enqueue_script('b0xT-google-maps', "https://maps.googleapis.com/maps/api/js?key=$b0xT_google_api_key&libraries=places", '', '', 'all');
            wp_enqueue_script('jquery-ui-datepicker');

            //localize
            $this->b0xT_front_house_script_localize();
        }
    }

    /**
     * Create the nonce and the url needed for the server calls, and
     * localize the information to make it accessible on the script
     * 
     */
    private function b0xT_front_house_script_localize() {
        if($this->b0xT_admin_url && wp_http_validate_url($this->b0xT_admin_url)) {
            $b0xT_config = array(
                 'ajax_url'   => $this->b0xT_admin_url."admin-ajax.php",
                 'ajax_nonce' => wp_create_nonce('_check__ajax_100'));

            wp_localize_script('b0xT-front-house-script', 'b0xT_config', $b0xT_config);
        }
    }
}