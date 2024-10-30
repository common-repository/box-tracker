<?php
/**
 * This class will create an instance
 * of one or more classes, and then call
 * the b0xT_init method if it exist.
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes;

use b0xT_includes\b0xT_base\B0xT_Enqueue;
use b0xT_includes\b0xT_gui\B0xT_Admin_Options;
use b0xT_includes\b0xT_base\B0xT_Plugin_Links;
use b0xT_includes\b0xT_gui\B0xT_Front_House;
use b0xT_includes\b0xT_base\B0xT_Session;
use b0xT_includes\b0xT_base\B0xT_Ajax_Control;

final class B0xT_Init {
    /**
     * Get the desired classes to get the
     * plugin working.
     * 
     * @return an array of classes 
     */
    public static function b0xT_get_classes () {
        $b0xT_array = array();

        array_push($b0xT_array, B0xT_Session::class);
        array_push($b0xT_array, B0xT_Enqueue::class);
        array_push($b0xT_array, B0xT_Ajax_Control::class);

        if(is_admin()) {
            array_push($b0xT_array, B0xT_Admin_Options::class);
            array_push($b0xT_array, B0xT_Plugin_Links::class);
        } else {
            array_push($b0xT_array, B0xT_Front_House::class);
        }

        return $b0xT_array;
    }

    /**
     * Iterate through an array of classes
     * instantiate them, and call b0xT_init.
     * 
     */
    public static function b0xT_init () {
        foreach (self::b0xT_get_classes() as $b0xT_class) {
            $b0xT_instance = self::b0xT_instantiate_class($b0xT_class);
            if(method_exists($b0xT_instance, 'b0xT_init')) {
                $b0xT_instance->b0xT_init();
            }
        }
    }

    /**
     * Instatiate the class
     * 
     * @return an instance of a class
     */
    private static function b0xT_instantiate_class($b0xT_class) {
        $b0xT_instance = new $b0xT_class(); 
        return $b0xT_instance;
    }
}