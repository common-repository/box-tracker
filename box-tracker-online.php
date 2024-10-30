<?php
/**
 * @package BoxTrackerOnline
 */

/*
Plugin Name: Box Tracker Online
Plugin URI: https://www.dumpster.software/api/word-press-plugin.html
Description: The Box Tracker plugin facilitates online ordering for waste haulers.  Depending on configuration, orders will result either in service requests on the customer screen or fully booked work orders on dispatch.  Using the Web API tab on Box Tracker's Preferences app, you can prevent over booking, control which days of the week online orders will be accepted, and prevent same day ordering.  For more information about Box Tracker or this plugin please contact support at 603 546 6751 option 2 or support@cairnapps.com
Version: 2.0.7
Author: Cairn Applications Inc
Author URI: https://www.cloud-computing.rocks/
License: GPLv2 or later
Text Domain: box-tracker-online
*/

//security protocols
if(!defined('ABSPATH')) { die; }
if(!function_exists('add_action')) { die; }

//include some classes, these classes will be used to implement namespaces
if(file_exists(plugin_dir_path(__FILE__).'includes/base/required-paths.php')) {
    require_once plugin_dir_path(__FILE__).'includes/base/required-paths.php';
}

if(class_exists('b0xT_includes\b0xT_base\B0xT_Required_Paths')) {
    $b0xT_required_paths = new b0xT_includes\b0xT_base\B0xT_Required_Paths(plugin_dir_path(__FILE__));
    foreach($b0xT_required_paths->b0xT_get_paths() as $b0xT_path) {
        require_once $b0xT_path;
    }
}

//flush and create tables
function b0xT_activate_flush() {
    if(class_exists('b0xT_includes\b0xT_base\B0xT_Activate')) {
        b0xT_includes\b0xT_base\B0xT_Activate::b0xT_activate();
    }
}

//flushes and drop tables
function b0xT_deactivate_flush() {
    if(class_exists('b0xT_includes\b0xT_base\B0xT_Deactivate')) {
        b0xT_includes\b0xT_base\B0xT_Deactivate::b0xT_deactivate();
    }
}

register_activation_hook(__FILE__, 'b0xT_activate_flush');
register_deactivation_hook(__FILE__, 'b0xT_deactivate_flush');

//initialize some classes 
if(class_exists('b0xT_includes\B0xT_Init')) {
    b0xT_includes\B0xT_Init::b0xT_init();
}