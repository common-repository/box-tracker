<div  id="b0xT-second-step" style="display:none;" class="b0xT_panel-block">
    <div class="b0xT_label-field">Tell us about your Job:</div>
    <form  method="post"  action="<?php echo esc_url(get_permalink()); ?>" id="b0xT_about_job_form">
        <input type="hidden" name="b0xT_step" value="2"/>
        <div class="b0xT_row-column">
            <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <label>Date Requested:<span style="color: red">*</span></label>
                    <input type="text" id="b0xT_date_requested" class="b0xT_form-input " name="b0xT_date_requested" value=""/>
                </div>
            </div>
            <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <label>Service Type:<span style="color: red">*</span> </label>
                    <select name="b0xT_service_type"  id="b0xT_service_type" class="b0xT_form-input ">
                    </select>
                </div>
            </div>
            <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <label>Size:<span style="color: red">*</span> </label>
                    <select name="b0xT_container_size"  id="b0xT_container_size" class="b0xT_form-input b0xT_container_size"></select>
                </div>
            </div>
        </div>
        <div class="b0xT_row-column">
            <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <span id="b0xT_driving_distance" name="b0xT_driving_distance"></span>
                    <span id="b0xT_container_availability" name="b0xT_container_availability"></span>
                    <span id="b0xT_street_address" name="b0xT_street_address"></span>
                    <span id="b0xT_step_two_error" name="b0xT_step_two_error"></span>
                    <label  style="margin-top: 14px;"><b> Click "Next" for Pricing. </b></label>
                </div>
            </div>
        </div>
        <button type="submit" disabled style="display: none" aria-hidden="true"></button>
        <button name="b0xT_about_job_back" id="b0xT_about_job_back" class="b0xT_form-button b0xT_back-btn" value="Back">Back</button>
        <button name="b0xT_about_job_submit" id="b0xT_about_job_submit" class="b0xT_form-button" value="Next">Next</button>
        <label><img id="b0xT_loader" class="b0xT_loader" src="<?php echo esc_url($this->b0xT_plugin_url); ?>images/fading_squares.gif" style=" padding-top: 5px; " /></label>
        <div class="b0xT_row-column">
           <div class="column-11">
                <div class="b0xT_availability_table" id="b0xT_availability_table" style="padding-top: 50px; overflow-x: auto;">
                </div>
           </div>
        </div>
        <?php if (esc_attr(get_option('b0xT_admin_debug')) == 'On') { ?> 
            <div class="b0xT_row-column">
                <div class="column-11">
                    <h3>Pricing Query JSON Response</h3>
                    <pre>
                        <div class="b0xT_pricingquery_response_show" id="b0xT_pricingquery_response_show"></div>
                    </pre>
                </div>
            </div>
        <?php } ?>
    </form>
</div>
      