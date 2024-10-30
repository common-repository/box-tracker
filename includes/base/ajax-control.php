<?php
/**
 * This class will mange the ajax calls
 * 
 * @package BoxTrackerOnline
 */

namespace b0xT_includes\b0xT_base;

use b0xT_includes\b0xT_base\B0xT_Server_Calls;
use \Datetime;

class B0xT_Ajax_Control {
    public $b0xT_server_calls;

    function b0xT_init() {    
        $this->b0xT_server_calls = new B0xT_Server_Calls();
        add_action('wp_ajax_b0xT_state_list', array($this, 'b0xT_state_list'));
        add_action('wp_ajax_nopriv_b0xT_state_list', array($this, 'b0xT_state_list'));

        add_action('wp_ajax_b0xT_get_job', array($this, 'b0xT_get_job'));
        add_action('wp_ajax_nopriv_b0xT_get_job', array($this, 'b0xT_get_job'));

        add_action('wp_ajax_b0xT_availability_search', array($this, 'b0xT_availability_search'));
        add_action('wp_ajax_nopriv_b0xT_availability_search', array($this, 'b0xT_availability_search'));

        add_action('wp_ajax_b0xT_about_job', array($this, 'b0xT_about_job'));
        add_action('wp_ajax_nopriv_b0xT_about_job', array($this, 'b0xT_about_job'));

        add_action('wp_ajax_b0xT_job_details', array($this, 'b0xT_job_details'));
        add_action('wp_ajax_nopriv_b0xT_job_details', array($this, 'b0xT_job_details'));

        add_action('wp_ajax_b0xT_place_order', array($this, 'b0xT_place_order'));
        add_action('wp_ajax_nopriv_b0xT_place_order', array($this, 'b0xT_place_order'));
    }

    /**
     * Get the state list from the wordpress data base
     * 
     */ 
    function b0xT_state_list() {
        //security checks
        if(!$this->b0xT_security_checks()) {
            wp_send_json($this->b0xT_response('error', 'Something went wrong', ''));
        }

        //list of states that match the country and then create state list
        $b0xT_ss_billing_country = sanitize_text_field(get_option('b0xT_admin_country'));
        $b0xT_states             = $this->b0xT_server_calls->b0xT_get_states($b0xT_ss_billing_country); //b0xT_get_states() sanitizes values
        $b0xT_state_list         = '<option value="">--Select State--</option>';

        if(is_array($b0xT_states)) {
            foreach($b0xT_states as $b0xT_state){
                $b0xT_state_list .= '<option value="'.$b0xT_state->state_short.'">'.$b0xT_state->state_name.'</option>';
            }
        }

        //return array
        $job_state_list_data = array(
            'b0xT_state_list' => $b0xT_state_list
        );

        wp_send_json($this->b0xT_response('success', 'State date found.', $job_state_list_data));
    }

    /**
     * Get job details
     * 
     */
    function b0xT_job_details() {
        //security checks
        if(!$this->b0xT_security_checks()) {
            wp_send_json($this->b0xT_response('error', 'Something went wrong', ''));
        }

        $b0xT_job_address       = sanitize_text_field($_SESSION['b0xT_ssession']['job_address']);
        $b0xT_job_city          = sanitize_text_field($_SESSION['b0xT_ssession']['job_city']);
        $b0xT_job_state         = sanitize_text_field($_SESSION['b0xT_ssession']['job_state']);
        $b0xT_post_user_zipcode = sanitize_text_field($_SESSION['b0xT_ssession']['jobZipPostal']);

        $b0xT_job_details_data = array(
            "b0xT_billing_address" => $b0xT_job_address,
            "b0xT_billing_city"    => $b0xT_job_city,
            "b0xT_billing_state"   => $b0xT_job_state,
            "b0xT_billing_zipcode" => $b0xT_post_user_zipcode
        );

        wp_send_json($this->b0xT_response('success', 'Found job details', $b0xT_job_details_data));
    }

    /**
     * Get the pricing data needed to start gathering information
     * about the location where the user wants service.
     * 
     */
    function b0xT_get_job() {
        //security checks
        if(!$this->b0xT_security_checks()) {
            wp_send_json($this->b0xT_response('error', 'Something went wrong', ''));
        }

        //any errors
        $b0xT_field_errors      = array();
        $b0xT_post_user_zipcode = sanitize_text_field($_POST['b0xT_user_zipcode']);
        $b0xT_job_address       = sanitize_text_field($_POST['b0xT_job_address']);
        $b0xT_job_city          = sanitize_text_field($_POST['b0xT_job_city']);
        $b0xT_job_state         = sanitize_text_field($_POST['b0xT_job_state']);

        if($b0xT_post_user_zipcode == "") {
            $b0xT_zipcode_label                     = sanitize_text_field(get_option('b0xT_zipcode_label'));
            $b0xT_zipcode_label_message             = empty($b0xT_zipcode_label) ? 'Zipcode' : $b0xT_zipcode_label;
            $b0xT_field_errors['b0xT_user_zipcode'] = 'Please enter '.$b0xT_zipcode_label_message.'.';
        }

        if($b0xT_job_address == "") {
            $b0xT_field_errors['b0xT_job_address'] = 'Please enter job address';
        }
        
        if($b0xT_job_city == "") {
            $b0xT_field_errors['b0xT_job_city'] = 'Please enter job city';
        }

        if($b0xT_job_state == "") {
            $b0xT_field_errors['b0xT_job_state'] = 'Please select job state';
        } else if(strlen($b0xT_job_state) != 2) {
            $b0xT_field_errors['b0xT_job_state'] = 'Please select valid job state';
        }

        if(!empty($b0xT_field_errors)) {
            wp_send_json($this->b0xT_response('validation_error', $b0xT_field_errors, ''));
        }

        //gather some data, starting with the handshake.
        $b0xT_handshake = $this->b0xT_server_calls->b0xT_handshake(); //b0xT_handshake() sanitizes values

        if($b0xT_handshake && $b0xT_handshake->status == '200') {
            $b0xT_ss_billing_country = sanitize_text_field(get_option('b0xT_admin_country'));

            //gather price sheet data
            $b0xT_pricing_query = $this->b0xT_server_calls->b0xT_pricing_query($b0xT_handshake->key, $b0xT_post_user_zipcode); //b0xT_pricing_query() sanitizes values

            if($b0xT_pricing_query && $b0xT_pricing_query->status == '200'){
                $b0xT_price_sheets = $b0xT_pricing_query->priceSheets;

                $b0xT_assets_array = array();
                $b0xT_assets_class_array = array();  

                if($b0xT_price_sheets && is_array($b0xT_price_sheets)) {
                    //extracting and sanitizing the data i need
                    $b0xT_size_price_sheets = sizeof($b0xT_price_sheets);

                    for ($x = 0; $x < $b0xT_size_price_sheets; $x++) {
                        $b0xT_assets_class = strtolower($b0xT_price_sheets[$x]->Assets['0']->AssetClass);
                        array_push($b0xT_assets_class_array, $b0xT_assets_class);

                        $b0xT_min_distance = $b0xT_price_sheets[$x]->Distance1;
                        $b0xT_max_distance = $b0xT_price_sheets[$x]->Distance2;

                        //validate some values
                        if(!is_numeric($b0xT_min_distance) && !is_numeric($b0xT_max_distance)) { continue; }
                        if($b0xT_min_distance > $b0xT_max_distance) { continue; }

                        //grouping price sheets by asset, min distance and max distance
                        if(!isset($b0xT_assets_array[$b0xT_assets_class][$b0xT_min_distance][$b0xT_max_distance])) {
                            $b0xT_price_sheets_new                        = array();
                            $b0xT_price_sheets_new['b0xT_price_sheet_id'] = $b0xT_price_sheets[$x]->ID;
                            $b0xT_price_sheets_new['b0xT_total_amount']   = $b0xT_price_sheets[$x]->Total;
                            $b0xT_price_sheets_new['b0xT_sub_total']      = $b0xT_price_sheets[$x]->SubTotal;
                            $b0xT_price_sheets_new['b0xT_taxes']          = $b0xT_price_sheets[$x]->Taxes;
                            $b0xT_price_sheets_new['b0xT_days']           = $b0xT_price_sheets[$x]->Days;
                            $b0xT_price_sheets_new['b0xT_days_price']     = $b0xT_price_sheets[$x]->DaysPrice;
                            $b0xT_price_sheets_new['b0xT_units_included'] = $b0xT_price_sheets[$x]->UnitsIncluded;
                            $b0xT_price_sheets_new['b0xT_unit']           = $b0xT_price_sheets[$x]->Unit;
                            $b0xT_price_sheets_new['b0xT_excess_units']   = $b0xT_price_sheets[$x]->ExcessUnits;
                            $b0xT_price_sheets_new['b0xT_distance1']      = $b0xT_price_sheets[$x]->Distance1;
                            $b0xT_price_sheets_new['b0xT_distance2']      = $b0xT_price_sheets[$x]->Distance2;
                            $b0xT_assets_array[$b0xT_assets_class][$b0xT_min_distance][$b0xT_max_distance] = $b0xT_price_sheets_new;  
                        }
                    }
                }

                //sort the assets arrays
                sort($b0xT_assets_class_array);

                $b0xT_days_of_the_week = array();
                if(is_array($b0xT_pricing_query->daysOfTheWeek)) {
                    $b0xT_days_of_the_week = $b0xT_pricing_query->daysOfTheWeek;  
                }

                //sanitize the availability data
                $b0xT_availability_table                     = array();
                $b0xT_availability_table['b0xT_col_headers'] = array();
                $b0xT_availability_table['b0xT_rows']        = array();

                if(is_array($b0xT_pricing_query->availabilityTable->colHeaders)) {
                    $b0xT_availability_table['b0xT_col_headers'] = $b0xT_pricing_query->availabilityTable->colHeaders;
                }

                if(is_array($b0xT_pricing_query->availabilityTable->rows)) {
                    //$b0xT_availability_table['b0xT_rows'] = $b0xT_pricing_query->availabilityTable->rows;
                    foreach($b0xT_pricing_query->availabilityTable->rows as $b0xT_row) {
                        $b0xT_row_day_int = date('w', strtotime($b0xT_row[0]));
                        if($b0xT_days_of_the_week[$b0xT_row_day_int]) {
                            array_push($b0xT_availability_table['b0xT_rows'], $b0xT_row);
                        }
                    }
                }

                $b0xT_earliest_booking_date = $b0xT_pricing_query->earliestBookingDate;
                $b0xT_earliest_booking_date = DateTime::createFromFormat('Y-m-d', $b0xT_earliest_booking_date) !== false ? $b0xT_earliest_booking_date : "";

                //get state information from google based on center lng and center lat
                $b0xT_mapit_center_lat = $b0xT_pricing_query->mapItCenterLat;
                $b0xT_mapit_center_lng = $b0xT_pricing_query->mapItCenterLng;

                //make sure its a lat/lng
                $b0xT_mapit_center_lat = is_numeric($b0xT_mapit_center_lat) && $b0xT_mapit_center_lat >= -90 && $b0xT_mapit_center_lat <= 90 ? $b0xT_mapit_center_lat : "0";
                $b0xT_mapit_center_lng = is_numeric($b0xT_mapit_center_lng) && $b0xT_mapit_center_lng >= -180 && $b0xT_mapit_center_lng <= 180 ? $b0xT_mapit_center_lng : "0";

                //create the container size list
                $b0xT_assets_class_header_array = array_unique($b0xT_assets_class_array);
                $b0xT_container_sizes           = '<option value="">--Container Size--</option>';
                foreach($b0xT_assets_class_header_array as $b0xT_container_size){
                    $b0xT_container_sizes .='<option value="'.$b0xT_container_size.'" '.'>'.$b0xT_container_size.'</option>';
                } 

                //create the service type list
                $b0xT_service_types       = "";
                $b0xT_service_types_array = array();

                if(is_array($b0xT_pricing_query->serviceTypes)) {
                    $b0xT_service_types_array = $b0xT_pricing_query->serviceTypes;
                }

                foreach($b0xT_service_types_array as $b0xT_service_type) {
                    $b0xT_service_types .= '<option value="'.$b0xT_service_type.'" '.'>'.$b0xT_service_type.'</option>';
                } 

                $b0xT_lad_size_of_rows = sizeof($b0xT_availability_table['b0xT_rows']) - 1;
                $b0xT_last_availabile_date = $b0xT_availability_table['b0xT_rows'][$b0xT_lad_size_of_rows][0];

                $b0xT_today     = date("Y-m-d");  
                $b0xT_date_diff = strtotime($b0xT_today) - strtotime($b0xT_earliest_booking_date);
                $b0xT_date_diff = round($b0xT_date_diff / (60 * 60 * 24));
                $b0xT_date_diff = trim($b0xT_date_diff,"-");

                $b0xT_date_last = strtotime($b0xT_last_availabile_date) - strtotime($b0xT_today);
                $b0xT_date_last = round($b0xT_date_last / (60 * 60 * 24));
                $b0xT_date_last = trim($b0xT_date_last, "-");

                //build availability html table
                $b0xT_availability_table_col_headers = $b0xT_availability_table['b0xT_col_headers'];
                $b0xT_availability_table_col_header_indexes = array();

                $b0xT_availability_table_show_view = "<h3>Container Availability Table</h3>";                
                $b0xT_availability_table_show_view .= "<div class='b0xT_availability_inner'><table id='b0xT_availability_table_str'><thead><tr>";

                //headers
                foreach($b0xT_availability_table_col_headers as $b0xT_availability_table_col_header){
                    if ($b0xT_availability_table_col_header == 'Date') {
                        $b0xT_availability_table_show_view .="<th><b>".$b0xT_availability_table_col_header."</b></th>";
                    } else {   
                        if (in_array($b0xT_availability_table_col_header, $b0xT_assets_class_header_array)) { 
                            //keep track of the index to reference it when display rows
                            $b0xT_availability_table_col_header_index = array_search($b0xT_availability_table_col_header, $b0xT_availability_table_col_headers);
                            array_push($b0xT_availability_table_col_header_indexes, $b0xT_availability_table_col_header_index);
                            $b0xT_availability_table_show_view .="<th style='text-align:center;'><b>".$b0xT_availability_table_col_header."</b></th>";
                        }
                    }
                } 

                $b0xT_availability_table_show_view .= "</tr></thead>";
                $b0xT_availability_table_show_view .= "<tbody>";

                //keep track of the next 7 days
                $b0xT_timestamp = strtotime('next Sunday');
                $b0xT_days = array();
                for ($i = 0; $i < 7; $i++) { 
                    $b0xT_days[] = strftime('%A', $b0xT_timestamp);
                    $b0xT_timestamp = strtotime('+1 day', $b0xT_timestamp);
                } 

                $b0xT_availability_table_rows      = $b0xT_availability_table['b0xT_rows'];
                $b0xT_availability_table_rows_size = sizeof($b0xT_availability_table_rows);
                $b0xT_earliest_booking_date_conv   = strtotime($b0xT_earliest_booking_date);

                $b0xT_asset_buffer = $b0xT_pricing_query->assetBuffer;
                $b0xT_asset_buffer = is_numeric($b0xT_asset_buffer) ? $b0xT_asset_buffer : "0";

                //rows
                for ($i = 0; $i < $b0xT_availability_table_rows_size; $i++) {
                    $b0xT_availability_table_rows_size_inner = sizeof($b0xT_availability_table_rows[$i]);
                    $b0xT_availability_table_show_view .= "<tr>";

                    //the dates
                    for ($j = 0; $j < $b0xT_availability_table_rows_size_inner; $j++) {
                        if (DateTime::createFromFormat('Y-m-d', $b0xT_availability_table_rows[$i][$j]) !== false) {
                            $b0xT_availability_table_date        = strtotime($b0xT_availability_table_rows[$i][$j]);
                            $b0xT_day_of_availability_table_date = date("l", $b0xT_availability_table_date);
                            $b0xT_day_of_availability_table_day  = array_search($b0xT_day_of_availability_table_date, $b0xT_days);

                            if(!is_numeric($b0xT_earliest_booking_date_conv)) { continue; }
                            if(!is_numeric($b0xT_availability_table_date)) { continue; }
                            if(!is_numeric($b0xT_days_of_the_week[$b0xT_day_of_availability_table_day])) { continue; }

                            if ($b0xT_earliest_booking_date_conv <= $b0xT_availability_table_date) {
                                if ($b0xT_days_of_the_week[$b0xT_day_of_availability_table_day] > 0 ) {
                                    $b0xT_availability_table_show_view .= "<td><b>".date("M d, Y", strtotime($b0xT_availability_table_rows[$i][$j]))."</b></td>";
                                }               
                            }               
                        }               
                    }       
                   
                    //the assets     
                    foreach($b0xT_availability_table_col_header_indexes as $b0xT_availability_table_col_header_index){
                        $b0xT_availability_table_date        = strtotime($b0xT_availability_table_rows[$i][0]);
                        $b0xT_day_of_availability_table_date = date("l", $b0xT_availability_table_date);
                        $b0xT_day_of_availability_table_day  = array_search($b0xT_day_of_availability_table_date, $b0xT_days);

                        if(!is_numeric($b0xT_earliest_booking_date_conv)) { continue; }
                        if(!is_numeric($b0xT_availability_table_date)) { continue; }
                        if(!is_numeric($b0xT_days_of_the_week[$b0xT_day_of_availability_table_day])) { continue; }

                        if ($b0xT_earliest_booking_date_conv <= $b0xT_availability_table_date) {
                            if ($b0xT_days_of_the_week[$b0xT_day_of_availability_table_day] > 0 ){
                                $b0xT_available_asset = $b0xT_availability_table_rows[$i][$b0xT_availability_table_col_header_index];
                                if ( is_numeric($b0xT_available_asset) && ($b0xT_available_asset > $b0xT_asset_buffer)) {
                                    $b0xT_availability_table_show_view .= "<td style='color:green;text-align:center;'>&#10003;</td>";
                                } else {           
                                    $b0xT_availability_table_show_view .= "<td style='color:red;text-align:center;'>X</td>";
                                }               
                            }               
                        }               
                    } 

                    $b0xT_availability_table_show_view .= "</tr>";
                }  

                $b0xT_availability_table_show_view .= "</tbody>";
                $b0xT_availability_table_show_view .= "</table></div>";

                //terms and conditions
                $termsUrl    = wp_http_validate_url($b0xT_pricing_query->termsUrl) ? $b0xT_pricing_query->termsUrl : "";
                $apiUseTerms = is_numeric($b0xT_pricing_query->apiUseTerms) ? $b0xT_pricing_query->apiUseTerms : 0;

                //add some variables to sessions for other forms, all sanitized
                $_SESSION['b0xT_ssession']['job_address']            = $b0xT_job_address;
                $_SESSION['b0xT_ssession']['job_city']               = $b0xT_job_city;
                $_SESSION['b0xT_ssession']['job_state']              = $b0xT_job_state;
                $_SESSION['b0xT_ssession']['jobZipPostal']           = $b0xT_post_user_zipcode;
                $_SESSION['b0xT_ssession']['billing_country']        = $b0xT_ss_billing_country;
                $_SESSION['b0xT_ssession']['map_center_lat']         = $b0xT_mapit_center_lat;
                $_SESSION['b0xT_ssession']['map_center_lng']         = $b0xT_mapit_center_lng;
                $_SESSION['b0xT_ssession']['assetsClassArray']       = $b0xT_assets_array;
                $_SESSION['b0xT_ssession']['availability_table']     = $b0xT_availability_table;
                $_SESSION['b0xT_ssession']['assetsClassHeaderArray'] = $b0xT_assets_class_header_array;
                $_SESSION['b0xT_ssession']['assetBuffer']            = $b0xT_asset_buffer;
                $_SESSION['b0xT_ssession']['daysOfTheWeek']          = $b0xT_days_of_the_week;
                $_SESSION['b0xT_ssession']['days']                   = $b0xT_days;

                //return array
                $job_field_data = array(
                    'b0xT_service_types'                => $b0xT_service_types,
                    'b0xT_container_sizes'              => $b0xT_container_sizes,
                    'b0xT_date_requested'               => $b0xT_earliest_booking_date,
                    'b0xT_date_diff'                    => $b0xT_date_diff,
                    'b0xT_date_last'                    => $b0xT_date_last,
                    'b0xT_availability_table_show_view' => $b0xT_availability_table_show_view,
                    'b0xT_pricingquery_response'        => $b0xT_pricing_query,
                    'b0xT_termsURL'                     => $termsUrl,
                    'b0xT_apiUseTerms'                  => $apiUseTerms,
                    'debug'                             => ''
                );

                wp_send_json($this->b0xT_response('success', 'Pricing Query data found.', $job_field_data));
            } else {
                $b0xT_message = 'Status: '.$b0xT_pricing_query->status.'<br>Error: '.$b0xT_pricing_query->errorString;
                wp_send_json($this->b0xT_response('error', $b0xT_message, ''));
            }
        } else {
            $b0xT_message = 'Invalid configuration.<br>Status: '.$b0xT_handshake->status.'<br>Error: '.$b0xT_handshake->errorString;
            wp_send_json($this->b0xT_response('error', $b0xT_message, ''));
        }
    }

    /**
     * Verify that the new date request is available for booking
     * 
     */
    function b0xT_availability_search() {
        //security checks
        if(!$this->b0xT_security_checks()) {
            wp_send_json($this->b0xT_response('error', 'Something went wrong', ''));
        }

        //clean up the post date requested
        $b0xT_date_requested = sanitize_text_field($_POST['b0xT_date_requested']);
        $b0xT_date_requested_formated = date_format(date_create($b0xT_date_requested), "Y-m-d");

        //gather data
        $b0xT_availability_table = array();
        if(is_array($_SESSION['b0xT_ssession']['availability_table'])) {
            $b0xT_availability_table = $this->b0xT_sanitize_array_values($_SESSION['b0xT_ssession']['availability_table']);
        }

        $b0xT_availability_table_result = $this->b0xT_server_calls->b0xT_search_availability_table($b0xT_availability_table['b0xT_rows'], $b0xT_date_requested_formated); //b0xT_search_availability_table() sanitizes values

        $b0xT_container_field_data  = array();
        $b0xT_container_sizes = '<option value="">--Container Size--</option>';

        //is the container available on the date requested
        if (is_array($b0xT_availability_table_result) && empty($b0xT_availability_table_result)) {
            $b0xT_container_field_data['b0xT_container_size'] = $b0xT_container_sizes;
            $b0xT_field_errors['b0xT_container_availability'] = 'No booking available on the date selected. Please refer to the availability table.';
            wp_send_json($this->b0xT_response('validation_error', $b0xT_field_errors, $b0xT_container_field_data));
        }

        //repopulate the container size field.
        $b0xT_assets_class_header_array = array();
        if(is_array($_SESSION['b0xT_ssession']['assetsClassHeaderArray'])) {
            $b0xT_assets_class_header_array = $this->b0xT_sanitize_array_values($_SESSION['b0xT_ssession']['assetsClassHeaderArray']);
        }

        foreach($b0xT_assets_class_header_array as $b0xT_container_size){
            $b0xT_container_sizes .='<option value="'.$b0xT_container_size.'" '.'>'.$b0xT_container_size.'</option>';
        } 

        $b0xT_container_field_data['b0xT_container_size'] = $b0xT_container_sizes;
        wp_send_json($this->b0xT_response('success', 'Container data found.', $b0xT_container_field_data));
    }

    /**
     * Gather information about the job and select the most
     * optimum price sheet if any.
     * 
     */
    function b0xT_about_job() {
        //security checks
        if(!$this->b0xT_security_checks()) {
            $b0xT_message = 'Something went wrong';
            wp_send_json($this->b0xT_response('error', esc_html($b0xT_message), ''));
        }

        //any errors
        $b0xT_field_errors            = array();
        $b0xT_service_type            = sanitize_text_field($_POST['b0xT_service_type']);
        $b0xT_container_size          = sanitize_text_field($_POST['b0xT_container_size']);
        $b0xT_job_address             = sanitize_text_field($_SESSION['b0xT_ssession']['job_address']);
        $b0xT_job_city                = sanitize_text_field($_SESSION['b0xT_ssession']['job_city']);
        $b0xT_job_state               = sanitize_text_field($_SESSION['b0xT_ssession']['job_state']);
        $b0xT_date_requested          = sanitize_text_field($_POST['b0xT_date_requested']);
        $b0xT_date_requested_formated = date_format(date_create($b0xT_date_requested), "Y-m-d");

        if($b0xT_service_type == "") {
            $b0xT_field_errors['b0xT_service_type'] = 'Please select serivce type';
        }
        
        if($b0xT_container_size == "") {
            $b0xT_field_errors['b0xT_container_size'] = 'Please select container size';
        }
        
        if($b0xT_date_requested == "") {
            $b0xT_field_errors['b0xT_date_requested'] = 'Please enter job request date';
        }

        if(!empty($b0xT_field_errors)) {
            wp_send_json($this->b0xT_response('validation_error', $b0xT_field_errors, ''));
        }

        //gather data
        $b0xT_availability_table = array();
        if(is_array($_SESSION['b0xT_ssession']['availability_table'])) {
            $b0xT_availability_table = $this->b0xT_sanitize_array_values($_SESSION['b0xT_ssession']['availability_table']);
        }

        $b0xT_availability_table_result = $this->b0xT_server_calls->b0xT_search_availability_table($b0xT_availability_table['b0xT_rows'], $b0xT_date_requested_formated); //b0xT_search_availability_table() sanitizes values

        $b0xT_container_size_index = array_search($b0xT_container_size, $b0xT_availability_table['b0xT_col_headers']);
        $b0xT_containers_available = $b0xT_availability_table_result[$b0xT_container_size_index];
        $b0xT_containers_available = is_numeric($b0xT_containers_available) ? $b0xT_containers_available : "0";

        $b0xT_asset_buffer = sanitize_text_field($_SESSION['b0xT_ssession']['assetBuffer']);
        $b0xT_asset_buffer = is_numeric($b0xT_asset_buffer) ? $b0xT_asset_buffer : "0";

        //is the container available on the date requested
        if (is_numeric($b0xT_containers_available) && ( $b0xT_containers_available <= $b0xT_asset_buffer)) {
            $b0xT_field_errors['b0xT_container_availability'] = 'This dumpster size is not available on the date selected. Please refer to the availability table.';
            wp_send_json($this->b0xT_response('validation_error', $b0xT_field_errors, ''));
        }

        $b0xT_ss_billing_country = sanitize_text_field($_SESSION['b0xT_ssession']['billing_country']);
        $b0xT_billing_state_list = '<option value="">--Select Billing State--</option>';
        $b0xT_payment_state_list = '<option value="">--Select Payment State--</option>';

        $b0xT_states = $this->b0xT_server_calls->b0xT_get_states($b0xT_ss_billing_country); //b0xT_get_states() sanitizes values

        if(is_array($b0xT_states)) {
            foreach($b0xT_states as $b0xT_state){
                $b0xT_billing_state_list .= '<option value="'.$b0xT_state->state_short.'" '.'>'.$b0xT_state->state_name.'</option>';
                $b0xT_payment_state_list .= '<option value="'.$b0xT_state->state_short.'" '.'>'.$b0xT_state->state_name.'</option>';
            }
        }

        $b0xT_state_full     = $this->b0xT_server_calls->b0xT_get_state_name($b0xT_job_state); //b0xT_get_state_name() sanitizes values
        $b0xT_address        = $b0xT_state_full ? $b0xT_job_address.",".$b0xT_job_city.",".sanitize_text_field($b0xT_state_full->state_name).",".sanitize_text_field($b0xT_ss_billing_country) : "";
        $b0xT_lat_long_query = $this->b0xT_server_calls->b0xT_get_latitude_longitude($b0xT_address); //b0xT_get_latitude_longitude() sanitizes values

        //any errors
        if($b0xT_lat_long_query['b0xT_error_message']){
            $b0xT_field_errors['b0xT_street_address'] = $b0xT_lat_long_query['b0xT_error_message'];
            wp_send_json($this->b0xT_response('validation_error', $b0xT_field_errors, ''));
        }

        $b0xT_lat  = $b0xT_lat_long_query['b0xT_lat'];
        $b0xT_long = $b0xT_lat_long_query['b0xT_long'];
        $b0xT_lat  = is_numeric($b0xT_lat) && $b0xT_lat >= -90 && $b0xT_lat <= 90 ? $b0xT_lat : "0";
        $b0xT_lng  = is_numeric($b0xT_lng) && $b0xT_lng >= -180 && $b0xT_lng <= 180 ? $b0xT_lng : "0";

        //driving distance
        $b0xT_ss_map_center_lat = sanitize_text_field($_SESSION['b0xT_ssession']['map_center_lat']);
        $b0xT_ss_map_center_lng = sanitize_text_field($_SESSION['b0xT_ssession']['map_center_lng']);
        $b0xT_ss_map_center_lat = is_numeric($b0xT_ss_map_center_lat) && $b0xT_ss_map_center_lat >= -90 && $b0xT_ss_map_center_lat <= 90 ? $b0xT_ss_map_center_lat : "0";
        $b0xT_ss_map_center_lng = is_numeric($b0xT_ss_map_center_lng) && $b0xT_ss_map_center_lng >= -180 && $b0xT_ss_map_center_lng <= 180 ? $b0xT_ss_map_center_lng : "0";

        $b0x_driving_distance_query = $this->b0xT_server_calls->b0xT_get_driving_distance($b0xT_lat, $b0xT_long, $b0xT_ss_map_center_lat, $b0xT_ss_map_center_lng); //b0xT_get_driving_distance() sanitizes values

        //any errors
        if($b0xT_driving_distance_query['b0xT_error_message']){
            $b0xT_field_errors['b0xT_driving_distance'] = $b0xT_driving_distance_query['b0xT_error_message'];
            wp_send_json($this->b0xT_response('validation_error', $b0xT_field_errors, ''));
        } 

        $b0xT_drv_distance_clean = sanitize_text_field($b0x_driving_distance_query['b0xT_distance']);
        $b0xT_drv_distance       = str_replace(array("km", "mi", ","), "", $b0x_driving_distance_query['b0xT_distance']);

        $b0xT_assets_array = array();
        if(is_array($_SESSION['b0xT_ssession']['assetsClassArray'])) {
            $b0xT_assets_array = $this->b0xT_sanitize_array_values($_SESSION['b0xT_ssession']['assetsClassArray']);
        }

        $b0xT_selected_price_sheet;
        foreach($b0xT_assets_array as $b0xT_assets => $b0xT_assets_value) {
            if(!is_array($b0xT_assets_value)) { continue; }
            if($b0xT_container_size != $b0xT_assets) { continue; };

            foreach($b0xT_assets_value as $b0xT_min_distance => $b0xT_min_distance_value) {
                if(!is_array($b0xT_min_distance_value)) { continue; }

                foreach($b0xT_min_distance_value as $b0xT_max_distance => $b0xT_max_distance_value) {
                    //no distance price sheet ? use it and call it a day.
                    if($b0xT_min_distance == 0 && $b0xT_max_distance == 0) {
                        $b0xT_selected_price_sheet = $b0xT_max_distance_value;
                        break 3;
                    }

                    //is the distance within the price sheet specs
                    if($b0xT_drv_distance < $b0xT_min_distance) { continue 2; }
                    if($b0xT_drv_distance > $b0xT_max_distance) { continue; }

                    if(!$b0xT_selected_price_sheet) {
                        $b0xT_selected_price_sheet = $b0xT_max_distance_value;
                        continue;     
                    }

                    //at this point we have multiple price sheets that fit all,
                    //requirements. lets refine our current price sheet selection.
                    if($b0xT_selected_price_sheet['b0xT_distance1'] > $b0xT_min_distance) {
                        $b0xT_selected_price_sheet = $b0xT_max_distance_value;
                        continue;
                    }

                    //now that we have selected the highest min distance that we could
                    //find, lets select the smallest maximum distance that we could find.
                    if($b0xT_selected_price_sheet['b0xT_distance1'] == $b0xT_min_distance && $b0xT_selected_price_sheet['b0xT_distance2'] > $b0xT_max_distance) {
                        $b0xT_selected_price_sheet = $b0xT_max_distance_value;
                        continue;
                    }
                }
            }
        }

        //any price sheet.
        if(!$b0xT_selected_price_sheet) {
            wp_send_json($this->b0xT_response('error', 'No pricing information available please contact our office.', ''));
        }

        //gather price sheet data, all the values are sanitized at the top.
        $b0xT_total_amount                = $b0xT_selected_price_sheet['b0xT_total_amount'];
        $b0xT_price_sheets_sub_total      = $b0xT_selected_price_sheet['b0xT_sub_total'];
        $b0xT_price_sheets_taxes          = $b0xT_selected_price_sheet['b0xT_taxes'];
        $b0xT_price_sheets_days           = $b0xT_selected_price_sheet['b0xT_days'];
        $b0xT_price_sheets_days_price     = $b0xT_selected_price_sheet['b0xT_days_price'];
        $b0xT_price_sheets_units_included = $b0xT_selected_price_sheet['b0xT_units_included'];
        $b0xT_price_sheets_unit           = $b0xT_selected_price_sheet['b0xT_unit'];
        $b0xT_price_sheets_excess_units   = $b0xT_selected_price_sheet['b0xT_excess_units'];
        $b0xT_price_sheets_id             = $b0xT_selected_price_sheet['b0xT_price_sheet_id'];

        //add some variables to sessions for other forms, all sanitized
        $_SESSION['b0xT_ssession']['lat']            = $b0xT_lat;
        $_SESSION['b0xT_ssession']['long']           = $b0xT_long;
        $_SESSION['b0xT_ssession']['job_distance']   = $b0xT_drv_distance_clean;
        $_SESSION['b0xT_ssession']['date_requested'] = $b0xT_date_requested_formated;
        $_SESSION['b0xT_ssession']['service_type']   = $b0xT_service_type;
        $_SESSION['b0xT_ssession']['container_size'] = $b0xT_container_size;
        $_SESSION['b0xT_ssession']['priceSheetID']   = $b0xT_price_sheets_id;

        $b0xT_billing_field_data = array(
            'b0xT_billing_state_list'          => $b0xT_billing_state_list,
            'b0xT_payment_state_list'          => $b0xT_payment_state_list,
            'b0xT_total_amount'                => $b0xT_total_amount,
            'b0xT_price_sheets_sub_total'      => $b0xT_price_sheets_sub_total,
            'b0xT_price_sheets_taxes'          => $b0xT_price_sheets_taxes,
            'b0xT_price_sheets_days'           => $b0xT_price_sheets_days,
            'b0xT_price_sheets_days_price'     => $b0xT_price_sheets_days_price,
            'b0xT_price_sheets_units_included' => $b0xT_price_sheets_units_included,
            'b0xT_price_sheets_unit'           => $b0xT_price_sheets_unit,
            'b0xT_price_sheets_excess_units'   => $b0xT_price_sheets_excess_units
        );

        wp_send_json($this->b0xT_response('success', 'Price sheet data found.', $b0xT_billing_field_data));
    }

    /**
     * Gather billing data, submit
     * the request for service.
     * 
     */
    function b0xT_place_order() {
        //security checks
        if(!$this->b0xT_security_checks()) {
            wp_send_json($this->b0xT_response('error', 'Something went wrong', ''));
        }

        $b0xT_field_errors           = array();
        $b0xT_zipcode_label          = sanitize_text_field(get_option('b0xT_zipcode_label'));
        $b0xT_zipcode_label_message  = empty($b0xT_zipcode_label) ? 'Zipcode' : $b0xT_zipcode_label;

        $b0xT_mode                   = sanitize_text_field(get_option('b0xT_mode'));
        $b0xT_user_name              = sanitize_text_field($_POST['b0xT_user_name']);
        $b0xT_billing_address        = sanitize_text_field($_POST['b0xT_billing_address']);
        $b0xT_billing_address_2      = sanitize_text_field($_POST['b0xT_billing_address_2']);
        $b0xT_billing_city           = sanitize_text_field($_POST['b0xT_billing_city']);
        $b0xT_billing_state          = sanitize_text_field($_POST['b0xT_billing_state']);
        $b0xT_billing_zipcode        = sanitize_text_field($_POST['b0xT_billing_zipcode']);
        $b0xT_billing_phone          = sanitize_text_field($_POST['b0xT_billing_phone']);
        $b0xT_filtered_billing_phone = preg_replace("/[^\d]/", "", $b0xT_billing_phone);
        $b0xT_billing_email          = sanitize_text_field($_POST['b0xT_billing_email']);
 
        $b0xT_payment_info           = esc_attr(get_option('b0xT_payment_info'));
        $b0xT_payment_first_name     = sanitize_text_field($_POST['b0xT_payment_first_name']);
        $b0xT_payment_last_name      = sanitize_text_field($_POST['b0xT_payment_last_name']);
        $b0xT_payment_address        = sanitize_text_field($_POST['b0xT_payment_address']);
        $b0xT_payment_address_2      = sanitize_text_field($_POST['b0xT_payment_address_2']);
        $b0xT_payment_city           = sanitize_text_field($_POST['b0xT_payment_city']);
        $b0xT_payment_state          = sanitize_text_field($_POST['b0xT_payment_state']);
        $b0xT_payment_zipcode        = sanitize_text_field($_POST['b0xT_payment_zipcode']);
        $b0xT_card_number            = sanitize_text_field($_POST['b0xT_card_number']);
        $b0xT_card_expiry_month      = sanitize_text_field($_POST['b0xT_card_expiry_month']);
        $b0xT_card_expiry_year       = sanitize_text_field($_POST['b0xT_card_expiry_year']);
        $b0xT_card_cvv               = sanitize_text_field($_POST['b0xT_card_cvv']);
        $b0xT_order_note             = sanitize_text_field($_POST['b0xT_order_note']);
        $b0xT_customer_terms         = sanitize_text_field($_POST['b0xT_customer_terms']);

        //billing
        if($b0xT_user_name == "") {
            $b0xT_field_errors['b0xT_user_name'] = 'Please enter name';
        }

        if($b0xT_billing_address == "") {
            $b0xT_field_errors['b0xT_billing_address'] = 'Please enter address';
        }

        if($b0xT_billing_city == "") {
            $b0xT_field_errors['b0xT_billing_city'] = 'Please enter city';
        }

        if($b0xT_billing_state == "") {
            $b0xT_field_errors['b0xT_billing_state'] = 'Please enter state';
        } else if(strlen($b0xT_billing_state) != 2) {
            $b0xT_field_errors['b0xT_billing_state'] = 'Please enter valid state';
        }

        if($b0xT_billing_zipcode == "") {
            $b0xT_field_errors['b0xT_billing_zipcode'] = 'Please enter '.$b0xT_zipcode_label_message;
        }

        if($b0xT_billing_phone == "") {
            $b0xT_field_errors['b0xT_billing_phone'] = 'Please enter phone number';
        } else if(strlen($b0xT_filtered_billing_phone) <= 9) {
            $b0xT_field_errors['b0xT_billing_phone'] = 'Please enter valid phone number';
        }

        if($b0xT_billing_email == "") {
            $b0xT_field_errors['b0xT_billing_email'] = 'Please enter email address';
        } else if(!filter_var($b0xT_billing_email, FILTER_VALIDATE_EMAIL)) {
            $b0xT_field_errors['b0xT_billing_email'] = 'Please enter valid email address';
        }

        //payment
        if($b0xT_payment_info === "Show") {
            if($b0xT_payment_first_name == "") {
                $b0xT_field_errors['b0xT_payment_first_name'] = 'Please enter first name';
            }            

            if($b0xT_payment_last_name == "") {
                $b0xT_field_errors['b0xT_payment_last_name'] = 'Please enter last name';
            } 

            if($b0xT_payment_address == "") {
                $b0xT_field_errors['b0xT_payment_address'] = 'Please enter address';
            } 

            if($b0xT_payment_city == "") {
                $b0xT_field_errors['b0xT_payment_city'] = 'Please enter city';
            }

            if($b0xT_payment_state == "") {
                $b0xT_field_errors['b0xT_payment_state'] = 'Please enter state';
            } else if(strlen($b0xT_payment_state) != 2) {
                $b0xT_field_errors['b0xT_payment_state'] = 'Please enter valid state';
            }

            if($b0xT_payment_zipcode == "") {
                $b0xT_field_errors['b0xT_payment_zipcode'] = 'Please enter '.$b0xT_zipcode_label_message;
            }

            if($b0xT_card_number == "") {
                $b0xT_field_errors['b0xT_card_number'] = 'Please enter card number';
            } else if($this->b0xT_server_calls->b0xT_credit_card_number_valid($b0xT_card_number) == false) {
                $b0xT_field_errors['b0xT_card_number'] = 'Please enter valid card number';    
            }

            if($b0xT_card_expiry_month == "") {
                $b0xT_field_errors['b0xT_card_expiry_month'] = 'Please select month';
            }  

            if($b0xT_card_expiry_year == "") {
                $b0xT_field_errors['b0xT_card_expiry_year'] = 'Please select year';
            } 

            if($b0xT_card_cvv == "") {
                $b0xT_field_errors['b0xT_card_cvv'] = 'Please enter cvv';
            } else if(preg_match('/^[0-9]{3,4}$/', $b0xT_card_cvv) == 0) {
                $b0xT_field_errors['b0xT_card_cvv'] = 'Please enter valid cvv';
            }
        }

        if(!empty($b0xT_field_errors)) {
            wp_send_json($this->b0xT_response('validation_error', $b0xT_field_errors, ''));
        }

        //make a service request
        $b0xT_handshake = $this->b0xT_server_calls->b0xT_handshake(); //b0xT_handshake() sanitizes values
 
        if($b0xT_handshake && $b0xT_handshake->status == '200') {
            $b0xT_job_address        = sanitize_text_field($_SESSION['b0xT_ssession']['job_address']);
            $b0xT_job_city           = sanitize_text_field($_SESSION['b0xT_ssession']['job_city']);
            $b0xT_job_state          = sanitize_text_field($_SESSION['b0xT_ssession']['job_state']);
            $b0xT_job_zipcode        = sanitize_text_field($_SESSION['b0xT_ssession']['jobZipPostal']);
            $b0xT_ss_billing_country = sanitize_text_field($_SESSION['b0xT_ssession']['billing_country']);
            $b0xT_lat                = sanitize_text_field($_SESSION['b0xT_ssession']['lat']);
            $b0xT_long               = sanitize_text_field($_SESSION['b0xT_ssession']['long']);
            $b0xT_drv_distance       = sanitize_text_field($_SESSION['b0xT_ssession']['job_distance']);
            $b0xT_date_requested     = sanitize_text_field($_SESSION['b0xT_ssession']['date_requested']);
            $b0xT_service_type       = sanitize_text_field($_SESSION['b0xT_ssession']['service_type']);
            $b0xT_container_size     = sanitize_text_field($_SESSION['b0xT_ssession']['container_size']);
            $b0xT_price_sheet_id     = sanitize_text_field($_SESSION['b0xT_ssession']['priceSheetID']);

            $b0xT_user_order_data = array(
                'key'              => $b0xT_handshake->key,
                'name'             => $b0xT_user_name,
                'billingAddress1'  => $b0xT_billing_address,
                'billingAddress2'  => $b0xT_billing_address_2,
                'billingCity'      => $b0xT_billing_city,
                'billingStateProv' => $b0xT_billing_state,
                'billingZipPostal' => $b0xT_billing_zipcode,
                'billingContry'    => $b0xT_ss_billing_country,
                'billingPhone'     => $b0xT_billing_phone,
                'billingEmail'     => $b0xT_billing_email,
                'jobAddress'       => $b0xT_job_address,
                'jobCity'          => $b0xT_job_city,
                'jobStateProv'     => $b0xT_job_state,
                'jobZipPostal'     => $b0xT_job_zipcode,
                'jobLatitude'      => $b0xT_lat,
                'jobLongitude'     => $b0xT_long,
                'distanceByRoad'   => $b0xT_drv_distance,
                'dateRequested'    => $b0xT_date_requested,
                'serviceType'      => $b0xT_service_type,
                'assetClass'       => $b0xT_container_size,
                'priceSheetID'     => $b0xT_price_sheet_id,
                'note'             => $b0xT_order_note,
                'mode'             => $b0xT_mode,
                'customer_terms'   => $b0xT_customer_terms
            );

            $b0xT_user_card_data = array();

            if($b0xT_payment_info === "Show") {
                $b0xT_user_card_data['ccardFName']            = $b0xT_payment_first_name;
                $b0xT_user_card_data['ccardLName']            = $b0xT_payment_last_name;
                $b0xT_user_card_data['ccardBillingAddress1']  = $b0xT_payment_address;
                $b0xT_user_card_data['ccardBillingAddress2']  = $b0xT_payment_address_2;
                $b0xT_user_card_data['ccardBillingCity']      = $b0xT_payment_city;
                $b0xT_user_card_data['ccardBillingStateProv'] = $b0xT_payment_state;
                $b0xT_user_card_data['ccardBillingZipPostal'] = $b0xT_payment_zipcode;
                $b0xT_user_card_data['ccardNumber']           = $b0xT_card_number;
                $b0xT_user_card_data['ccardExp']              = $b0xT_card_expiry_month.$b0xT_card_expiry_year;
                $b0xT_user_card_data['ccardCVV']              = $b0xT_card_cvv;
            }

            if(!empty($b0xT_user_card_data)) {
                $b0xT_user_order_data = array_merge($b0xT_user_order_data, $b0xT_user_card_data); 
            } 

            //final step
            $b0xT_place_order_query = $this->b0xT_server_calls->b0xT_place_order($b0xT_user_order_data); //b0xT_place_order() sanitizes values

            $b0xT_message           = "";
            $b0xT_response_message  = "";
            $b0xT_error_message     = "";
            $b0xT_success_thank_you = "";
            $b0xT_test_url          = "";

            $b0xT_order_fields = array();

            if($b0xT_place_order_query->status == '200') {
                $b0xT_response_message  = "Order Placed Successfully, we will contact you soon.";
                $b0xT_success_thank_you = "Thank You";
                $b0xT_message           = 'Place order success.';

                $b0xT_order_fields["OrderID"]      =  $b0xT_place_order_query->OrderID;
                $b0xT_order_fields["OrderDate"]    =  $b0xT_place_order_query->OrderDate;
                $b0xT_order_fields["OrderAddress"] =  $b0xT_place_order_query->OrderAddress;
                $b0xT_order_fields["OrderCity"]    =  $b0xT_place_order_query->OrderCity;
                $b0xT_order_fields["OrderState"]   =  $b0xT_place_order_query->OrderState;
                $b0xT_order_fields["OrderZip"]     =  $b0xT_place_order_query->OrderZip;
            } else {
                $b0xT_error_message = "Sorry..! Something went wrong please try again later.<br>Status: ".$b0xT_place_order_query->status.'<br>Error: '.$b0xT_place_order_query->errorString;
                $b0xT_message       = 'Place order failed.';
            }

            if($b0xT_mode === "TEST") {
                $b0xT_test_url = $b0xT_place_order_query->TestURL;
            }

            $b0xT_thank_you_data = array(
                'b0xT_response_message'  => $b0xT_response_message,
                'b0xT_error_message'     => $b0xT_error_message,
                'b0xT_success_thank_you' => $b0xT_success_thank_you,
                'b0xT_order_fields'      => $b0xT_order_fields,
                'b0xT_test_url'          => $b0xT_test_url
            );           

            $_SESSION = array();
            session_destroy();
            
            wp_send_json($this->b0xT_response('success', $b0xT_message, $b0xT_thank_you_data));
        } else {
            $b0xT_message = 'Invalid configuration.<br>Status: '.$b0xT_handshake->status.'<br>Error: '.$b0xT_handshake->errorString;
            wp_send_json($this->b0xT_response('error', $b0xT_message, ''));
        }
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
     * Validate the ajax call 
     * 
     * @return 0 or 1
     */
    function b0xT_security_checks() {
        if(!DOING_AJAX) { return 0; } 
        if(!check_ajax_referer('_check__ajax_100', 'b0xT_nonce')) { return 0; }
        return 1;
    }

    /**
     * customize error
     * 
     * @return json
     */
    function b0xT_response($b0xt_status, $b0xt_message, $b0xT_data) {
        $b0xt_json = json_encode(array(
            'status'  => $b0xt_status,
            'message' => $b0xt_message,
            'data'    => $b0xT_data
        ));
        return $b0xt_json;
    }
}