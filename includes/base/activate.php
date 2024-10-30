<?php
/**
 * This class will trigger everytime
 * The plugin gets activated. 
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

class B0xT_Activate {
    public static function b0xT_activate() {
        flush_rewrite_rules();
        self::b0xT_create_states_table();
    }

    /**
     * This function will create the states table
     * which will be used to populate some of the
     * fields on the form.
     * 
     */
    private static function b0xT_create_states_table() {
        global $wpdb;
        $b0xT_table_name = $wpdb->prefix.'b0xT_states';
        $b0xT_charset_collate = $wpdb->get_charset_collate();

        $b0xT_table_exist = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($b0xT_table_name));        

        if($wpdb->get_var($b0xT_table_exist) === $b0xT_table_name) {
            $b0xT_query_drop_table = "DROP TABLE $b0xT_table_name";
            $wpdb->query($b0xT_query_drop_table);
        }

        //create the table
        $b0xT_query_create_table = "CREATE TABLE $b0xT_table_name (
            `state_id` int(5) NOT NULL AUTO_INCREMENT,
            `state_name` varchar(40) NOT NULL,
            `state_short` varchar(10) NOT NULL,
            `country_id` varchar(10) NOT NULL,
            PRIMARY  KEY (`state_id`)
        ) $b0xT_charset_collate;";   

        //insert some values.
        $b0xT_query_insert_values = "INSERT INTO $b0xT_table_name (`state_id`, `state_name`, `state_short`, `country_id`) VALUES (1, 'Alabama', 'AL', 'USA'), (2, 'Alaska', 'AK', 'USA'), (3, 'Arizona', 'AZ', 'USA'), (4, 'Arkansas', 'AR', 'USA'), (5, 'California', 'CA', 'USA'), (6, 'Colorado', 'CO', 'USA'), (7, 'Connecticut', 'CT', 'USA'), (8, 'Delaware', 'DE', 'USA'), (9, 'Florida', 'FL', 'USA'), (10, 'Georgia', 'GA', 'USA'), (11, 'Hawaii', 'HI', 'USA'), (12, 'Idaho', 'ID', 'USA'), (13, 'Illinois', 'IL', 'USA'), (14, 'Indiana', 'IN', 'USA'), (15, 'Iowa', 'IA', 'USA'), (16, 'Kansas', 'KS', 'USA'), (17, 'Kentucky', 'KY', 'USA'), (18, 'Louisiana', 'LA', 'USA'), (19, 'Maine', 'ME', 'USA'), (20, 'Maryland', 'MD', 'USA'), (21, 'Massachusetts', 'MA', 'USA'), (22, 'Michigan', 'MI', 'USA'), (23, 'Minnesota', 'MN', 'USA'), (24, 'Mississippi', 'MS', 'USA'), (25, 'Missouri', 'MO', 'USA'), (26, 'Montana', 'MT', 'USA'), (27, 'Nebraska', 'NE', 'USA'), (28, 'Nevada', 'NV', 'USA'), (29, 'New Hampshire', 'NH', 'USA'), (30, 'New Jersey', 'NJ', 'USA'), (31, 'New Mexico', 'NM', 'USA'), (32, 'New York', 'NY', 'USA'), (33, 'North Carolina', 'NC', 'USA'), (34, 'North Dakota', 'ND', 'USA'), (35, 'Ohio', 'OH', 'USA'), (36, 'Oklahoma', 'OK', 'USA'), (37, 'Oregon', 'OR', 'USA'), (38, 'Pennsylvania', 'PA', 'USA'), (39, 'Rhode Island', 'RI', 'USA'), (40, 'South Carolina', 'SC', 'USA'), (41, 'South Dakota', 'SD', 'USA'), (42, 'Tennessee', 'TN', 'USA'), (43, 'Texas', 'TX', 'USA'), (44, 'Utah', 'UT', 'USA'), (45, 'Vermont', 'VT', 'USA'), (46, 'Virginia', 'VA', 'USA'), (47, 'Washington', 'WA', 'USA'), (48, 'West Virginia', 'WV', 'USA'), (49, 'Wisconsin', 'WI', 'USA'), (50, 'Wyoming', 'WY', 'USA'), (51, 'Alberta', 'AB', 'CAN'), (52, 'British Columbia', 'BC', 'CAN'), (53, 'Manitoba', 'MB', 'CAN'), (54, 'New Brunswick', 'NB', 'CAN'), (55, 'Newfoundland', 'NL', 'CAN'), (56, 'Northwest Territories', 'NT', 'CAN'), (57, 'Nova Scotia', 'NS', 'CAN'), (58, 'Nunavut', 'NU', 'CAN'), (59, 'Ontario', 'ON', 'CAN'), (60, 'Prince Edward Island', 'PE', 'CAN'), (61, 'Quebec', 'QC', 'CAN'), (62, 'Saskatchewan', 'SK', 'CAN'), (63, 'Yukon', 'YT', 'CAN')";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($b0xT_query_create_table);
        dbDelta($b0xT_query_insert_values);
    }
}