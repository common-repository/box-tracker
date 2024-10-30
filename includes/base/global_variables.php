<?php
/**
 * This class will set global variables
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

class B0xT_Global_Variables {
    public $b0xT_plugin;
    public $b0xT_plugin_path;
    public $b0xT_plugin_url;
    public $b0xT_admin_url;
    public $b0xT_boxT_url;

    /**
     * Set variables
     * 
     */
    function __construct() {
        $this->b0xT_plugin      = plugin_basename(dirname(__FILE__, 3)).'/box-tracker-online.php';
        $this->b0xT_plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->b0xT_plugin_url  = plugin_dir_url(dirname(__FILE__, 2));
        $this->b0xT_admin_url   = admin_url();
        $this->b0xT_boxT_url    = 'https://www.dumpster.software/controller.html';

        //test enviorment
        //$this->b0xT_boxT_url    = 'https://boxtracker.dev2.rocks/controller.html';
    }
}