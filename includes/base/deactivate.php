<?php
/**
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

class B0xT_Deactivate {
    public static function b0xT_deactivate() {
        flush_rewrite_rules();
        self::b0xT_clean_database();
    }

    private static function b0xT_clean_database() {
        global $wpdb;
        $b0xT_table_name = $wpdb->prefix.'b0xT_states';

        $b0xT_table_exist = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($b0xT_table_name)); 
               
        if($wpdb->get_var($b0xT_table_exist) === $b0xT_table_name) {
            $b0xT_query_drop_table = "DROP TABLE $b0xT_table_name";
            $wpdb->query($b0xT_query_drop_table); 
        }
    }
}