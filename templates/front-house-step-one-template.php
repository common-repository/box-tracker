<h1 class="entry-title"><?php echo  esc_attr(get_option('b0xT_page_title')); ?></h1>
<div id="b0xT-first-step" class="b0xT_panel-block">
    <input type="hidden" id="b0xT_google_status_bypass" value="<?php if(esc_attr(get_option('b0xT_google_validation_bypass'))) {
        echo esc_attr(get_option('b0xT_google_validation_bypass')); 
    } else {
        echo "Off"; 
    } ?>"/>

	<div class="b0xT_label-field">Enter address to begin:</div>
	<form  method="post"  action="<?php echo esc_url(get_permalink()); ?>" id="b0xT_address_form">
	    <input type="hidden" class="b0xT_form-input" name="b0xT_step" value="1"/>
		<div class="b0xT_row-column">
            <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <label>Job Address:<span style="color: red">*</span></label>
                    <input type="text" id="b0xT_job_address" class="b0xT_form-input " name="b0xT_job_address" value=""/>
                    <div style="padding-top: 5px; font-size: 90%;">
                        <strong>
                            <em>GOOGLE STATUS: <span name="b0xT_google_status" class="b0xT_verify_error">NOT VERIFIED</span></em>
                        </strong>
                    </div>
                    <div id="b0xT_google_search_error_shield" class="b0xT_dialog_box_shield"></div>
                    <div id="b0xT_google_search_error_dialog" class="b0xT_dialog_box_div"></div>
                </div>
            </div>
            <div class="b0xT_column-22">
                <div class="b0xT_field-row">
                    <label>Job City:<span style="color: red">*</span></label>
                    <input type="text" id="b0xT_job_city" class="b0xT_form-input " name="b0xT_job_city" value=""/>
                </div>
            </div>
            <div class="b0xT_column-22">
                <div class="b0xT_field-row">
                    <label>Job State:<span style="color: red">*</span></label>
                    <select name="b0xT_job_state"  id="b0xT_job_state" class="b0xT_form-input "></select>
                </div>
            </div>
	        <div class="b0xT_column-22">
	            <div class="b0xT_field-row">
	                <label><?php echo(esc_attr(get_option('b0xT_zipcode_label')) == "") ? 'Zipcode' : esc_attr(get_option('b0xT_zipcode_label')); ?>:<span style="color: red">*</span></label>
	                <input type="text" id="b0xT_user_zipcode" class="b0xT_form-input" name="b0xT_user_zipcode" value=""/>
	            </div>  
	        </div>
	 	</div>
		<div class="b0xT_row-column">
            <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <span id="b0xT_step_one_error" name="b0xT_step_one_error"></span>
                </div>
            </div>
        </div>
        <button type="submit" disabled style="display: none" aria-hidden="true"></button>
        <button name="b0xT_address_submit" id="b0xT_address_submit" class="b0xT_form-button" value="Next">Next</button>
       	<label><img id="b0xT_loader" class="b0xT_loader" src="<?php echo esc_url($this->b0xT_plugin_url); ?>images/fading_squares.gif" style="z-index: 9999; padding-top: 25px; " /></label> 
	</form>
</div>
