jQuery(document).ready(function($){
    var b0xT_first_step=$('#b0xT-first-step');
    var b0xT_second_step=$('#b0xT-second-step');
    var b0xT_third_step=$('#b0xT-third-step');
    var b0xT_fourth_step=$('#b0xT-fourth-step');
    var b0xT_job_address_vldtr;

    $('form').each(function() { this.reset() });

    $("#b0xT_address_submit").click(function(e) {
        $('.b0xT-error-msg').remove();
        if($('span[name="b0xT_google_status"]').attr('class') == "b0xT_verify_error") {
            $('#b0xT_step_one_error').after('<p class="b0xT-error-msg">Please provide a google validated address.</p>');
            return false;
        }

        e.preventDefault();       
        var formData = {
            'action': 'b0xT_get_job',
            'b0xT_job_address': $('input[name=b0xT_job_address]').val(),
            'b0xT_job_city': $('input[name=b0xT_job_city]').val(),
            'b0xT_job_state': $('select[name=b0xT_job_state]').val(),
            'b0xT_user_zipcode': $('input[name=b0xT_user_zipcode]').val(),
            'b0xT_nonce': b0xT_config.ajax_nonce, 
            'b0xT_step'    : 1
        };

        $(".b0xT_loader").show();
        
        $.ajax({
            url : b0xT_config.ajax_url,
            type : 'post',
            data : formData,
            success : function( data ) {
                $(".b0xT_loader").hide();
                var response= JSON.parse(data);
                if(response.status=='validation_error'){
                    $.each(response.message, function (field, field_message) {
                        $('#'+field).after('<p class="b0xT-error-msg">'+field_message+'</p>');
                    });
                } else if(response.status=='success'){
                    $('#b0xT_service_type').html(response.data.b0xT_service_types);
                    $('#b0xT_container_size').html(response.data.b0xT_container_sizes);
                    $('#b0xT_date_requested').val(response.data.b0xT_date_requested);
                    $('#b0xT_date_diff').val(response.data.b0xT_date_diff);
                    $('#b0xT_availability_table').html(response.data.b0xT_availability_table_show_view);
                    $('#b0xT_pricingquery_response_show').html(JSON.stringify(response.data.b0xT_pricingquery_response));
                    $("#b0xT_date_requested").datepicker({ dateFormat: 'M dd,yy', minDate: response.data.b0xT_date_diff, maxDate : response.data.b0xT_date_last});

                    b0xT_first_step.hide();
                    b0xT_second_step.show();
                    b0xT_third_step.hide();
                    b0xT_fourth_step.hide();

                    b0xT_set_terms_behaviors(response.data.b0xT_termsURL, response.data.b0xT_apiUseTerms);
                } else{
                    $('#b0xT_step_one_error').after('<p class="b0xT-error-msg">'+response.message+'</p>');
                }
            }, error: function (error) {
                $('#b0xT_step_one_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
            }
        });
    });

    function b0xT_set_terms_behaviors(b0xT_terms_url, b0xT_api_use_terms) {
        if((b0xT_api_use_terms * 1) == 0 || b0xT_terms_url == "") { 
            $('#b0xT_place_order_submit').attr("class",    "b0xT_form-button");
            $('#b0xT_place_order_submit').attr("disabled", false);
            return; 
        }

        $('#b0xT_terms_cond_check_box_link').attr("href", b0xT_terms_url);

        //we are going to use the check box to 
        //enable or disable the place order button
        $('#b0xT_terms_cond_check_box').change(function(e){
            if($('#b0xT_terms_cond_check_box').is(':checked')) {
                $('#b0xT_place_order_submit').attr("class",    "b0xT_form-button");
                $('#b0xT_place_order_submit').attr("disabled", false);
                return;
            }

            $('#b0xT_place_order_submit').attr("class",    "b0xT_form-button b0xT_form-disabled");
            $('#b0xT_place_order_submit').attr("disabled", true);
        });

        $('#b0xT_terms_cond_wrapper').css("display", "block");
    }

    $("#b0xT_about_job_submit" ).click(function(e) {
        $('.b0xT-error-msg').remove();
        if($('span[name="b0xT_google_status"]').attr('class') == "b0xT_verify_error") {
            $('#b0xT_step_one_error').after('<p class="b0xT-error-msg">Please provide a google validated address.</p>');
            return false;
        }

        e.preventDefault();
        var formData = {
            'action': 'b0xT_about_job',
            'b0xT_service_type': $('select[name=b0xT_service_type]').val(),
            'b0xT_container_size': $('select[name=b0xT_container_size]').val(),
            'b0xT_date_requested': $('input[name=b0xT_date_requested]').val(),
            'b0xT_nonce': b0xT_config.ajax_nonce,
            'b0xT_step'    :2
        };

        $(".b0xT_loader").show();

        $.ajax({
            url : b0xT_config.ajax_url,
            type : 'post',
            data : formData,
            success : function( data ) {
                $(".b0xT_loader").hide();
                var response= JSON.parse(data);
                if(response.status=='validation_error'){
                    $.each(response.message, function (field, field_message) {
                        $('#'+field).after('<p class="b0xT-error-msg">'+field_message+'</p>');
                    });
                } else if(response.status=='success'){
                    $('#b0xT_billing_state').html(response.data.b0xT_billing_state_list);
                    $('#b0xT_payment_state').html(response.data.b0xT_payment_state_list);
                    $("b0xT_total_amount").text(response.data.b0xT_total_amount);
                    $("b0xT_price_sheets_sub_total").text(response.data.b0xT_price_sheets_sub_total);
                    $("b0xT_price_sheets_taxes").text(response.data.b0xT_price_sheets_taxes);
                    $("b0xT_price_sheets_days").text(response.data.b0xT_price_sheets_days);
                    $("b0xT_price_sheets_days_price").text(response.data.b0xT_price_sheets_days_price);
                    $("b0xT_price_sheets_units_included").text(response.data.b0xT_price_sheets_units_included);
                    $("b0xT_price_sheets_unit").text(response.data.b0xT_price_sheets_unit);
                    $("b0xT_price_sheets_excess_units").text(response.data.b0xT_price_sheets_excess_units);

                    b0xT_first_step.hide();
                    b0xT_second_step.hide();
                    b0xT_third_step.show();
                    b0xT_fourth_step.hide();
                } else {
                    $('#b0xT_step_two_error').after('<p class="b0xT-error-msg">'+response.message+'</p>');
                }
            }, error: function (error) {
               $('#b0xT_step_two_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
            }
        });
    });

    $( "#b0xT_place_order_submit" ).click(function(e) {
        $('.b0xT-error-msg').remove();
        if($('span[name="b0xT_google_status"]').attr('class') == "b0xT_verify_error") {
            $('#b0xT_step_one_error').after('<p class="b0xT-error-msg">Please provide a google validated address.</p>');
            return false;
        }

        e.preventDefault();
        var formData = {
            'action': 'b0xT_place_order',
            'b0xT_user_name': $('input[name=b0xT_user_name]').val(),
            'b0xT_billing_address': $('input[name=b0xT_billing_address]').val(),
            'b0xT_billing_address_2': $('input[name=b0xT_billing_address_2]').val(),
            'b0xT_billing_city': $('input[name=b0xT_billing_city]').val(),
            'b0xT_billing_state': $('select[name=b0xT_billing_state]').val(),
            'b0xT_billing_zipcode': $('input[name=b0xT_billing_zipcode]').val(),                    
            'b0xT_billing_phone': $('input[name=b0xT_billing_phone]').val(),
            'b0xT_billing_email': $('input[name=b0xT_billing_email]').val(),
            'b0xT_payment_first_name': $('input[name=b0xT_payment_first_name]').val(),
            'b0xT_payment_last_name': $('input[name=b0xT_payment_last_name]').val(),
            'b0xT_payment_address': $('input[name=b0xT_payment_address]').val(),
            'b0xT_payment_address_2': $('input[name=b0xT_payment_address_2]').val(),
            'b0xT_payment_city': $('input[name=b0xT_payment_city]').val(),
            'b0xT_payment_state': $('select[name=b0xT_payment_state]').val(),
            'b0xT_payment_zipcode': $('input[name=b0xT_payment_zipcode]').val(),
            'b0xT_order_note': $('textarea[name=b0xT_order_note]').val(),
            'b0xT_card_number': $('input[name=b0xT_card_number]').val(),
            'b0xT_card_expiry_month': $('select[name=b0xT_card_expiry_month]').val(),
            'b0xT_card_expiry_year': $('select[name=b0xT_card_expiry_year]').val(),
            'b0xT_card_cvv': $('input[name=b0xT_card_cvv]').val(),
            'b0xT_customer_terms': ($('#b0xT_terms_cond_check_box').is(':checked') ? 1 : 0),
            'b0xT_nonce': b0xT_config.ajax_nonce,
            'b0xT_step' :3
        };

        $(".b0xT_loader").show();

        $.ajax({
            url : b0xT_config.ajax_url,
            type : 'post',
            data : formData,
            success : function( data ) {
                $(".b0xT_loader").hide();
                var response= JSON.parse(data);
                if(response.status=='validation_error'){
                    $.each(response.message, function (field, field_message) {
                        $('#'+field).after('<p class="b0xT-error-msg">'+field_message+'</p>');
                    });
                } else if(response.status=='success'){
                     b0xT_first_step.hide();
                     b0xT_second_step.hide();
                     b0xT_third_step.hide();
                     b0xT_fourth_step.show();

                     $('#b0xT_response_message').html(response.data.b0xT_response_message);
                     $('#b0xT_success_thank_you').html(response.data.b0xT_success_thank_you);

                     //display order details if valid
                     if(response.data.b0xT_order_fields.OrderID){
                        $('#b0xT_response_order_id').html("Order# "+response.data.b0xT_order_fields.OrderID);
                     }

                     if(response.data.b0xT_order_fields.OrderDate) {
                        $('#b0xT_response_order_date').html(response.data.b0xT_order_fields.OrderDate);
                     }

                     if(response.data.b0xT_order_fields.OrderAddress) {
                        var b0xT_full_address = response.data.b0xT_order_fields.OrderAddress;

                        //include other details if they are present.
                        if(response.data.b0xT_order_fields.OrderCity) {
                            b0xT_full_address += (" "+response.data.b0xT_order_fields.OrderCity);
                        }

                        if(response.data.b0xT_order_fields.OrderState) {
                            b0xT_full_address += (", "+response.data.b0xT_order_fields.OrderState);
                        }

                        if(response.data.b0xT_order_fields.OrderZip) {
                           b0xT_full_address += (" "+response.data.b0xT_order_fields.OrderZip); 
                        }

                        $('#b0xT_response_order_address').html(b0xT_full_address);
                     }

                     $('#b0xT_error_message').html(response.data.b0xT_error_message);
                     $('#b0xT_test_url').html(response.data.b0xT_test_url);
                } else{
                   $('#b0xT_step_three_error').after('<p class="b0xT-error-msg">'+response.message+'</p>');
                }
            }, error: function (error) {
               $('#b0xT_step_three_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
            }
        });
    });

    $( "#b0xT_about_job_back" ).click(function(e) {
        $('.b0xT-error-msg').remove();
        e.preventDefault();       
        var formData = {
            'action': 'b0xT_get_job',
            'b0xT_job_address': $('input[name=b0xT_job_address]').val(),
            'b0xT_job_city': $('input[name=b0xT_job_city]').val(),
            'b0xT_job_state': $('select[name=b0xT_job_state]').val(),
            'b0xT_user_zipcode': $('input[name=b0xT_user_zipcode]').val(),
            'b0xT_nonce': b0xT_config.ajax_nonce,
            'b0xT_step'    : 2
        };

        $(".b0xT_loader").show();

        $.ajax({
            url : b0xT_config.ajax_url,
            type : 'post',
            data : formData,
            success : function( data ) {
                $(".b0xT_loader").hide();
                var response= JSON.parse(data);
                if(response.status=='validation_error'){
                    $('#b0xT_step_two_error').after('<p class="b0xT-error-msg">Something went wrong, validation error.</p>');
                } else if(response.status=='success'){
                    $('#b0xT_service_type').html(response.data.b0xT_service_types);
                    $('#b0xT_container_size').html(response.data.b0xT_container_sizes);
                    $('#b0xT_date_requested').val(response.data.b0xT_date_requested);

                    b0xT_first_step.show();
                    b0xT_second_step.hide();
                    b0xT_third_step.hide();
                    b0xT_fourth_step.hide();

                    b0xT_set_terms_behaviors(response.data.b0xT_termsURL, response.data.b0xT_apiUseTerms);
                } else{
                    $('#b0xT_step_two_error').after('<p class="b0xT-error-msg">Something went wrong.</p>');
                }
            }, error: function (error) {
                $('#b0xT_step_two_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
            }
        });
    });

    $( "#b0xT_place_order_back" ).click(function(e) {
        $('.b0xT-error-msg').remove();
        e.preventDefault();       
        var formData = {
            'action': 'b0xT_get_job',
            'b0xT_job_address': $('input[name=b0xT_job_address]').val(),
            'b0xT_job_city': $('input[name=b0xT_job_city]').val(),
            'b0xT_job_state': $('select[name=b0xT_job_state]').val(),
            'b0xT_user_zipcode': $('input[name=b0xT_user_zipcode]').val(),
            'b0xT_nonce': b0xT_config.ajax_nonce,
            'b0xT_step'    : 3
        };

        $(".b0xT_loader").show();

        $.ajax({
            url : b0xT_config.ajax_url,
            type : 'post',
            data : formData,
            success : function( data ) {
                $(".b0xT_loader").hide();
                var response= JSON.parse(data);
                if(response.status=='validation_error'){
                    $('#b0xT_step_three_error').after('<p class="b0xT-error-msg">Something went wrong, validation error.</p>');
                } else if(response.status=='success'){
                    $('#b0xT_service_type').html(response.data.b0xT_service_types);
                    $('#b0xT_container_size').html(response.data.b0xT_container_sizes);
                    $('#b0xT_date_requested').val(response.data.b0xT_date_requested);

                    b0xT_first_step.hide();
                    b0xT_second_step.show();
                    b0xT_third_step.hide();
                    b0xT_fourth_step.hide();

                    b0xT_set_terms_behaviors(response.data.b0xT_termsURL, response.data.b0xT_apiUseTerms);
                } else{
                    $('#b0xT_step_three_error').after('<p class="b0xT-error-msg">Something went wrong.</p>');
                }
            }, error: function (error) {
                $('#b0xT_step_three_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
            }
        });
    });

    $( "#b0xT_date_requested" ).change(function(e) { 
        $('.b0xT-error-msg').remove();
        e.preventDefault();
        var formData = {
            'action': 'b0xT_availability_search',
            'b0xT_date_requested': $('input[name=b0xT_date_requested]').val(),
            'b0xT_nonce': b0xT_config.ajax_nonce
        };

        $.ajax({
            url : b0xT_config.ajax_url,
            type : 'post',
            data : formData,
            success : function( data ) {
                var response= JSON.parse(data);
                if(response.status=='validation_error'){
                    $('#b0xT_container_availability').after('<p class="b0xT-error-msg">'+response.message.b0xT_container_availability+'</p>');
                    $('#b0xT_container_size').html(response.data.b0xT_container_size);
                } else if(response.status=='success'){
                    $('#b0xT_container_size').html(response.data.b0xT_container_size);
                }
            }, error: function (error) {
               $('#b0xT_step_two_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
            }
        });
    });

    $("input#b0xT_sameadd").bind("click",function(o){
        if($("input#b0xT_sameadd:checked").length){
            var result = $("#b0xT_user_name").val().toString();
            var array = result.split(' ');
            b0xT_user_first_name = array[0], b0xT_user_last_name = array[1];
            $("#b0xT_payment_first_name").val(b0xT_user_first_name);
            $("#b0xT_payment_last_name").val(b0xT_user_last_name);
            $("#b0xT_payment_address").val($("#b0xT_billing_address").val());
            $("#b0xT_payment_address_2").val($("#b0xT_billing_address_2").val());
            $("#b0xT_payment_city").val($("#b0xT_billing_city").val());
            $("#b0xT_payment_state").val($("#b0xT_billing_state").val());
            $("#b0xT_payment_zipcode").val($("#b0xT_billing_zipcode").val());
        } else {
            $("#b0xT_payment_first_name").val("");
            $("#b0xT_payment_last_name").val("");
            $("#b0xT_payment_address").val("");
            $("#b0xT_payment_address_2").val("");
            $("#b0xT_payment_city").val("");
            $("#b0xT_payment_state").val("");
            $("#b0xT_payment_zipcode").val("");
        }
    });

    $("input#b0xT_billing_details_sameadd").bind("click",function(o){
        if($("input#b0xT_billing_details_sameadd:checked").length){
            $('.b0xT-error-msg').remove();
            var formData = {
                'action': 'b0xT_job_details',
                'b0xT_nonce': b0xT_config.ajax_nonce
            };

            $.ajax({
                url : b0xT_config.ajax_url,
                type : 'post',
                data : formData,
                success : function( data ) {
                    var response= JSON.parse(data);
                    if(response.status=='success'){
                        $("#b0xT_billing_address").val(response.data.b0xT_billing_address);
                        $("#b0xT_billing_city").val(response.data.b0xT_billing_city);
                        $("#b0xT_billing_state").val(response.data.b0xT_billing_state);
                        $("#b0xT_billing_zipcode").val(response.data.b0xT_billing_zipcode);
                    } else {
                        $('#b0xT_step_three_billing_details_error').after('<p class="b0xT-error-msg">'+response.message+'</p>');
                    }
                }, error: function (error) {
                   $('#b0xT_step_three_billing_details_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
                }
            });
        } else {
            $("#b0xT_billing_address").val("");
            $("#b0xT_billing_city").val("");
            $("#b0xT_billing_state").val("");
            $("#b0xT_billing_zipcode").val("");
        }
    });

    $(function () { 
        if(typeof(b0xT_cls_address_vldtr) == 'function') {
            b0xT_job_address_vldtr = new b0xT_cls_address_vldtr({
                "searchCtrl" : $('input[name=b0xT_job_address]')[0],
                "addressCtrl" : $('input[name=b0xT_job_address]')[0],
                "cityCtrl" : $('input[name=b0xT_job_city]')[0],
                "stateCtrl" : $('select[name=b0xT_job_state]')[0],
                "postalCtrl" : $('input[name=b0xT_user_zipcode]')[0],
                "validateCtrl" : $('span[name=b0xT_google_status]')[0],
                "errorShield" : $('#b0xT_google_search_error_shield')[0],
                "errorDialog" : $('#b0xT_google_search_error_dialog')[0]
            });
        }        
    });

    $(function () {
        var formData = {
            'action': 'b0xT_state_list',
            'b0xT_nonce': b0xT_config.ajax_nonce
        };

        $.ajax({
            url : b0xT_config.ajax_url,
            type : 'post',
            data : formData,
            success : function( data ) {
                var response= JSON.parse(data);
                if(response.status=='success'){
                    $('#b0xT_job_state').html(response.data.b0xT_state_list);
                } else {
                    $('#b0xT_step_one_error').after('<p class="b0xT-error-msg">'+response.message+'</p>');
                }
            }, error: function (error) {
               $('#b0xT_step_one_error').after('<p class="b0xT-error-msg">Oops. Something went wrong. Please try again later.</p>');
            }
        });
    });
});

jQuery(function($){
    $("[b0xT_data-pop]").click(function(){
        var b0xT_data_pop_value = $(this).attr("b0xT_data-pop");
        var $b0xT_popup = b0xT_data_pop_value ? $("[b0xT_data-popup='"+b0xT_data_pop_value+"']") : $(this).closest("[b0xT_data-popup]");
        $b0xT_popup.slideToggle(240);
    });
});
