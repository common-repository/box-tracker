<?php
/**
 * This class will ad links that will
 * direct us to the setting page.
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

use b0xT_includes\b0xT_base\B0xT_Global_Variables;

class B0xT_Plugin_Links extends B0xT_Global_Variables {
    function b0xT_init() {
        if($this->b0xT_plugin) {
            add_filter('plugin_action_links_'.$this->b0xT_plugin, array($this, 'b0xT_add_links'));
        }
    }

    /**
     * This function will generate a set
     * of links that we can display.
     * 
     * @param array $b0xT_links
     * @return an array of links
     */
    function b0xT_add_links($b0xT_links) {
        if($this->b0xT_admin_url && wp_http_validate_url($this->b0xT_admin_url)) {
            $b0xT_link = '<a href="'.$this->b0xT_admin_url.'admin.php?page=box-tracker-online">Settings</a>';
            array_push($b0xT_links, $b0xT_link);
        }
        return $b0xT_links;
    }
}