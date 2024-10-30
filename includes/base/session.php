<?php
/**
 * This class will create a session
 * when the plug in launches.
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

class B0xT_Session {
    function b0xT_init() {
        add_action('init', array($this, 'b0xT_session_start'));
    }

    //start session
    function b0xT_session_start() {
        if(!session_id()) {
            session_start();
        }
    }
}