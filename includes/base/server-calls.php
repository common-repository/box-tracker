<?php
/**
 * This class will handle server calls
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

use b0xT_includes\b0xT_base\B0xT_Global_Variables;

class B0xT_Server_Calls extends B0xT_Global_Variables {

    /**
     * Validate user information
     * 
     * @return array
     */
    function b0xT_handshake(){
        $b0xT_username = sanitize_text_field(get_option('b0xT_username'));
        $b0xT_password = sanitize_text_field(get_option('b0xT_password'));

        $b0xT_user_credentials = array( 
            'command'   => 'cmdBoxTWebAPIHandShake',
            'username'  => $b0xT_username,
            'password'  => $b0xT_password 
        );

        $b0xT_user_credentials_query_string = http_build_query($b0xT_user_credentials);
        $b0xT_response                      = wp_remote_post($this->b0xT_boxT_url, array('body' => $b0xT_user_credentials_query_string));
        return $this->b0xT_sanitize_reponse(json_decode($b0xT_response['body']));
    }

    /**
     * Get price sheet data
     * 
     * @param string $b0xT_key
     * @param string $user_zipcode
     * @return array
     */
    function b0xT_pricing_query($b0xT_key, $b0xT_user_zipcode) {
        $b0xT_username     = sanitize_text_field(get_option('b0xT_username'));
        $b0xT_key          = sanitize_text_field($b0xT_key);
        $b0xT_user_zipcode = sanitize_text_field($b0xT_user_zipcode);

        $b0xT_user_pricingquery  = array(
            'command'       => 'cmdBoxTWebAPIPricing',
            'username'      => $b0xT_username,
            'key'           => $b0xT_key,
            'zipPostalCode' => $b0xT_user_zipcode
        ); 

        $b0xT_user_pricingquery_query_string = http_build_query($b0xT_user_pricingquery);
        $b0xT_response                       = wp_remote_post($this->b0xT_boxT_url, array('body' => $b0xT_user_pricingquery_query_string));
        return $this->b0xT_sanitize_reponse(json_decode($b0xT_response['body']));
    }

    /**
     * Request service
     * 
     * @param string $b0xT_data
     * @return array
     */
    function b0xT_place_order($b0xT_data){
        if(!is_array($b0xT_data)) { return null; }
        $b0xT_data     = $this->b0xT_sanitize_array_values($b0xT_data);
        $b0xT_username = sanitize_text_field(get_option('b0xT_username'));
        $b0xT_api_mode = sanitize_text_field(get_option('b0xT_api_mode'));

        $b0xT_user_placeoder = array( 
            'command'   => $b0xT_api_mode,
            'username'  => $b0xT_username
        );

        $b0xT_user_placeoder              = array_merge($b0xT_user_placeoder, $b0xT_data);
        $b0xT_user_placeoder_query_string = http_build_query($b0xT_user_placeoder);
        $b0xT_response                    = wp_remote_post($this->b0xT_boxT_url, array('body' => $b0xT_user_placeoder_query_string));
        return $this->b0xT_sanitize_reponse(json_decode($b0xT_response['body']));
    }

    /**
     * Validate the credit card number
     * 
     * @param string $b0xT_number
     * @return true or false
     */
    function b0xT_credit_card_number_valid($b0xT_number) {
        //Remove non-digits from the number
        $b0xT_number = preg_replace('/[^0-9]/', '', $b0xT_number);
 
        //Get the string length and parity
        $b0xT_number_length = strlen($b0xT_number);
        if($b0xT_number_length == 0){
            return false;
        }

        $b0xT_parity = $b0xT_number_length % 2;
        
        //Split up the number into sin-
        //gle digits and get the total
        $b0xT_total = 0;
        for ($i = 0; $i < $b0xT_number_length; $i++) { 
            $b0xT_digit = $b0xT_number[$i];

            //Multiply alterna-
            //te digits by two
            if ($i % 2 == $b0xT_parity) {
                $b0xT_digit *= 2;

                //If the sum is two dig- 
                //its,  add them together
                if ($b0xT_digit > 9) {
                    $b0xT_digit -= 9;
                }       
            }       
            //Sum up the digits
            $b0xT_total += $b0xT_digit;
        }

        //If the total mod 10 equ-
        //als 0, the number is valid
        return ($b0xT_total % 10 == 0) ? TRUE : FALSE;
    }

    /**
     * Request driving distance from google
     * 
     * @param string $b0xT_lat1
     * @param string $b0xT_long1
     * @param string $b0xT_lat2
     * @param string $b0xT_long2
     * @return array
     */
    function b0xT_get_driving_distance($b0xT_lat1, $b0xT_long1, $b0xT_lat2, $b0xT_long2) {
        $b0xT_lat1           = sanitize_text_field($b0xT_lat1);
        $b0xT_long1          = sanitize_text_field($b0xT_long1);
        $b0xT_lat2           = sanitize_text_field($b0xT_lat2);
        $b0xT_long2          = sanitize_text_field($b0xT_long2);
        $b0xT_country_id     = sanitize_text_field(get_option('b0xT_admin_country'));
        $b0xT_google_api_key = sanitize_text_field(get_option('b0xT_google_api_key'));
        $b0xT_units = ($b0xT_country_id == "Canada" ? "metric" : "imperial");

        $b0xT_url = "https://maps.googleapis.com/maps/api/distancematrix/json";

        $b0xT_url_arg = array( 
            'units'        => $b0xT_units,
            'origins'      => $b0xT_lat1.','.$b0xT_long1,
            'destinations' => $b0xT_lat2.','.$b0xT_long2,
            'mode'         => 'driving',
            'key'          => $b0xT_google_api_key
        );

        $b0xT_url_arg_query_string = http_build_query($b0xT_url_arg);
        $b0xT_url_new              = $b0xT_url."?".$b0xT_url_arg_query_string;     
        $b0xT_response             = wp_remote_post($b0xT_url_new, array('body' => $b0xT_url_arg_query_string));
        $b0xT_response             = $this->b0xT_sanitize_reponse(json_decode($b0xT_response['body']));

        $b0xT_error_message = isset($b0xT_response->error_message) ? $b0xT_response->error_message : "";
                    
        if($b0xT_error_message != ""){
            return array('b0xT_error_message' => $b0xT_error_message);
        }
        
        $b0xT_dist = $b0xT_response->rows[0]->elements[0]->distance->text;
        $b0xT_time = $b0xT_response->rows[0]->elements[0]->duration->text;
                  
        if($b0xT_dist == "" || $b0xT_time == ""){
            return array('b0xT_error_message' => 'Latitude and longitude invalid.');
        }

        return array('b0xT_distance' => $b0xT_dist, 'b0xT_time' => $b0xT_time, 'b0xT_response' => $b0xT_response); 
    }

    /**
     * Request lat lng from google
     * 
     * @param string $b0xT_address
     * @return array
     */
    function b0xT_get_latitude_longitude($b0xT_address) {
        $b0xT_address        = sanitize_text_field($b0xT_address);
        $b0xT_google_api_key = sanitize_text_field(get_option('b0xT_google_api_key'));

        $b0xT_url = "https://maps.google.com/maps/api/geocode/json";

        $b0xT_url_arg = array( 
            'address' => $b0xT_address,
            'sensor'  => 'false',
            'key'     => $b0xT_google_api_key
        );

        $b0xT_url_arg_query_string = http_build_query($b0xT_url_arg);
        $b0xT_url_arg_query_string = str_replace("%2","+",$b0xT_url_arg_query_string);
        $b0xT_url_new              = $b0xT_url."?".$b0xT_url_arg_query_string;
        $b0xT_response             = wp_remote_post($b0xT_url_new, array('body' => $b0xT_url_arg_query_string));
        $b0xT_response             = $this->b0xT_sanitize_reponse(json_decode($b0xT_response['body']));

        $b0xT_error_message = isset($b0xT_response->error_message) ? $b0xT_response->error_message : "";
                    
        if($b0xT_error_message != ""){
            return array('b0xT_error_message' => $b0xT_error_message);
        }
                    
        if(empty($b0xT_response->results)) {
            return array('b0xT_error_message' =>'Please enter valid address.');
        }
        
        $b0xT_formatted_address = $b0xT_response->results[0]->formatted_address;
        $b0xT_lat               = $b0xT_response->results[0]->geometry->location->lat;
        $b0xT_long              = $b0xT_response->results[0]->geometry->location->lng; 
        return array('b0xT_lat' => $b0xT_lat, 'b0xT_long' => $b0xT_long, 'b0xT_formatted_address' => $b0xT_formatted_address);
    }

    /**
     * Request lat, lng from google based state
     * 
     * @param string $b0xT_lat
     * @param string $b0xT_long
     * @return array
     */
    function b0xT_get_state_by_latitude_longitude($b0xT_lat, $b0xT_long) {
        $b0xT_lat            = sanitize_text_field($b0xT_lat);
        $b0xT_long           = sanitize_text_field($b0xT_long);
        $b0xT_google_api_key = sanitize_text_field(get_option('b0xT_google_api_key'));

        $b0xT_url = "https://maps.googleapis.com/maps/api/geocode/json";

        $b0xT_url_arg = array(
            'latlng'  => $b0xT_lat.','.$b0xT_long,
            'sensor'  => 'false',  
            'key'     => $b0xT_google_api_key
        );

        $b0xT_url_arg_query_string = http_build_query($b0xT_url_arg);
        $b0xT_url_new              = $b0xT_url."?".$b0xT_url_arg_query_string;
        $b0xT_response             = wp_remote_post($b0xT_url_new, array('body' => $b0xT_url_arg_query_string));
        $b0xT_response             = $this->b0xT_sanitize_reponse(json_decode($b0xT_response['body']));

        $b0xT_error_message = isset($b0xT_response->error_message) ? $b0xT_response->error_message : "";
            
        if($b0xT_error_message != ""){
            return array('b0xT_error_message' => $b0xT_error_message);
        }

        $b0xT_long_name  = $b0xT_response->results[0]->address_components[4]->long_name;
        $b0xT_short_name = $b0xT_response->results[0]->address_components[4]->short_name;
        return array('b0xT_long_name' => $b0xT_long_name, 'b0xT_short_name' => $b0xT_short_name);
    }

    /**
     * Find date requested in availability table
     * 
     * @param array $b0xT_availability_rows
     * @param string $b0xT_date_requested
     * @var string $b0xT_v
     * @return array
     */
    function b0xT_search_availability_table($b0xT_availability_rows, $b0xT_date_requested) {
        if(!is_array($b0xT_availability_rows)) { return null; }
        $b0xT_availability_rows = $this->b0xT_sanitize_array_values($b0xT_availability_rows);
        $b0xT_date_requested    = sanitize_text_field($b0xT_date_requested);

        $b0xT_result = array();
        foreach ($b0xT_availability_rows as $b0xT_row_value) {
            if($b0xT_row_value[0] == $b0xT_date_requested) {
                $b0xT_result = $b0xT_row_value;
                break;  
            }
        }

        return $b0xT_result;
    }

    /**
     * Get a list of states
     * 
     * @param string $b0xT_country_id
     * @return string
     */
    function b0xT_get_states($b0xT_country_id) {
        $b0xT_country_id = sanitize_text_field($b0xT_country_id);

        global $wpdb;
        if($b0xT_country_id == "Canada"){
             $b0xT_country_id = 'CAN';
        } else {
             $b0xT_country_id = 'USA';
        }

        $b0xT_output = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."b0xT_states WHERE country_id = '".$b0xT_country_id."'");

        if(!is_array($b0xT_output)) { return null; }
        return $this->b0xT_sanitize_array_values($b0xT_output);
    }

    /**
     * Get state name
     * 
     * @param string $b0xT_state_code
     * @return string
     */
    function b0xT_get_state_name($b0xT_state_code) {
        $b0xT_state_code = sanitize_text_field($b0xT_state_code);

        global $wpdb;
        $b0xT_output = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."b0xT_states WHERE state_short = '".$b0xT_state_code."'");

        if(!is_object($b0xT_output)) { return null; }
        return $this->b0xT_sanitize_array_values($b0xT_output);
    }

   /**
    * Sanitize array
    * 
    * @param  array $b0xT_array
    * @return sanitized array
    */
    function b0xT_sanitize_array_values($b0xT_array) {
        //if not an array dont proceed.
        if(!is_array($b0xT_array)) { return $b0xT_array; }

        foreach ($b0xT_array as $b0xT_key => &$b0xT_value) {
            if(is_array($b0xT_value)) {
                $b0xT_value = $this->b0xT_sanitize_array_values($b0xT_value);
            } else {
                if(is_object($b0xT_value)) {
                    $b0xT_value = $this->b0xT_sanitize_object_values($b0xT_value);
                } else {
                    $b0xT_value = $this->b0xT_sanitize_string_values($b0xT_value);
                }
            }
        }

        return $b0xT_array;
    }

   /**
    * Sanitize objects
    * 
    * @param  object $b0xT_object
    * @return sanitized object
    */
    function b0xT_sanitize_object_values($b0xT_object) {
        //if not an object dont proceed.
        if(!is_object($b0xT_object)) { return $b0xT_object; }

        foreach ($b0xT_object as $b0xT_key => &$b0xT_value) {
            if(is_object($b0xT_value)) {
                $b0xT_value = $this->b0xT_sanitize_object_values($b0xT_value);
            } else {
                if(is_array($b0xT_value)) {
                    $b0xT_value = $this->b0xT_sanitize_array_values($b0xT_value);
                } else {
                    $b0xT_value = $this->b0xT_sanitize_string_values($b0xT_value);
                }
            }
        }

        return $b0xT_object;
    }

   /**
    * Sanitize json
    * 
    * @param  object $b0xT_data
    * @return sanitized $b0xT_data
    */
    function b0xT_sanitize_string_values($b0xT_data) {
        $b0xT_data_temp = json_decode($b0xT_data);
        if($b0xT_data_temp && is_array($b0xT_data_temp)) {
            return $this->b0xT_sanitize_array_values($b0xT_data_temp);
        } 

        if($b0xT_data_temp && is_object($b0xT_data_temp)) {
            return $this->b0xT_sanitize_object_values($b0xT_data_temp);
        }

        if($b0xT_data == null) {
            return "";
        }

        return sanitize_text_field($b0xT_data);
    }

    /**
     * Server calls sanitize
     * 
     * @param array/object $b0xT_data
     * @return array/object $b0xT_data
     */
    function b0xT_sanitize_reponse($b0xT_data) {
        if(is_array($b0xT_data)) {
            return $this->b0xT_sanitize_array_values($b0xT_data); 
        }

        if(is_object($b0xT_data)) {
            return $this->b0xT_sanitize_object_values($b0xT_data);
        }

        return (object) array(
            'status'      => '511',
            'errorString' => 'Failed to sanitize data'
        );
    }
}