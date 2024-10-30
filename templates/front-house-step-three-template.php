<div id="b0xT-third-step"  style="display:none;" class="b0xT_panel-block">
    <div class="b0xT_label-field" style="padding-bottom: 25px;"><b>Place your Order:</b></div>
    <div class="b0xT_row-column">
        <div class="b0xT_column-33">
            <div class="b0xT_field-row">
                <label><b>Order Details : </b></label>
                <table style="border: none !important; width: 70%;font-size: 18px;">
                    <tbody>
                        <tr>
                            <td style="text-align: left; border: none !important;">Sub Total : </td>
                            <td style="text-align: right; border: none !important; "> $<b0xT_price_sheets_sub_total></b0xT_price_sheets_sub_total></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border: none !important; border-bottom: 3px solid #767676 !important;">Taxes : </td>
                            <td style="text-align: right; border: none !important; border-bottom: 3px solid #767676 !important;"> $<b0xT_price_sheets_taxes></b0xT_price_sheets_taxes></td>
                        </tr>
                        <tr>
                            <th style="text-align: left; border: none !important;"><b>Total : </b></th>
                            <th style="text-align: right; border: none !important;"><b> $<b0xT_total_amount></b0xT_total_amount></b></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="b0xT_column-33">
            <div class="b0xT_field-row">
                <label><b>Included : </b></label>
                <p>
                    <b>Days Included: <b0xT_price_sheets_days></b0xT_price_sheets_days> </b>, <b>Extra Days: $<b0xT_price_sheets_days_price></b0xT_price_sheets_days_price>/day</b><br>
                    <b>Included: <b0xT_price_sheets_units_included></b0xT_price_sheets_units_included> <b0xT_price_sheets_unit></b0xT_price_sheets_unit></b>, <b>Extra Unit: $<b0xT_price_sheets_excess_units></b0xT_price_sheets_excess_units> per <b0xT_price_sheets_unit></b0xT_price_sheets_unit>.</b>
                </p>
            </div>
        </div>
    </div>
    <div class="b0xT_row-column">
        <div class="b0xT_column-50">
            <div class="b0xT_field-row">
                <label><input id="b0xT_billing_details_sameadd" name="b0xT_billing_details_sameadd" type="checkbox" value="b0xT_billing_details_sameadd" /> Check if Billing Details are same as Job Address</label>
                <div><span id="b0xT_step_three_billing_details_error" name="b0xT_step_three_billing_details_error"></span></div>
            </div>
        </div>
    </div>
    <div class="b0xT_label-field">Billing Details:</div>
    <form  method="post"  action="<?php echo esc_url(get_permalink()); ?>" id="b0xT_place_order_form">
        <input type="hidden" class="b0xT_form-input " name="b0xT_step" value="3"/>
        <div class="b0xT_form-block" style="margin-bottom: 5px;">
            <div class="b0xT_row-column">
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Name:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_user_name" class="b0xT_form-input " name="b0xT_user_name" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Billing Address:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_billing_address" class="b0xT_form-input " name="b0xT_billing_address" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Billing Address 2:</label>
                        <input type="text" id="b0xT_billing_address_2" class="b0xT_form-input " name="b0xT_billing_address_2" value=""/>
                    </div>
                </div>
            </div>
            <div class="b0xT_row-column">
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Billing City:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_billing_city" class="b0xT_form-input " name="b0xT_billing_city" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Billing State:<span style="color: red">*</span></label>
                        <select name="b0xT_billing_state"  id="b0xT_billing_state" class="b0xT_form-input "></select>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Billing <?php echo(esc_attr(get_option('b0xT_zipcode_label')) == "") ? 'Zipcode' : esc_attr(get_option('b0xT_zipcode_label')); ?>:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_billing_zipcode" class="b0xT_form-input " name="b0xT_billing_zipcode" value=""/>
                    </div>
                </div>
            </div>
            <div class="b0xT_row-column">
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Billing Phone:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_billing_phone" class="b0xT_form-input " name="b0xT_billing_phone" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Billing Email:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_billing_email" class="b0xT_form-input " name="b0xT_billing_email" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <div class="b0xT_form-block">
            <?php if(esc_attr(get_option('b0xT_payment_info')) === "Show") { ?>
            <div class="b0xT_row-column">
                <div class="b0xT_column-50">
                    <div class="b0xT_field-row">
                        <label><input id="b0xT_sameadd" name="b0xT_sameadd" type="checkbox" value="b0xT_sameadd" /> Check if Payment Details are same as Billing Details</label>
                    </div>
                </div>
            </div>
            <div class="b0xT_label-field" style="margin-top: 8px;">Payment Details:</div>
            <div class="b0xT_row-column">
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>First Name:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_payment_first_name" class="b0xT_form-input " name="b0xT_payment_first_name" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Last Name:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_payment_last_name" class="b0xT_form-input " name="b0xT_payment_last_name" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Payment Address:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_payment_address" class="b0xT_form-input " name="b0xT_payment_address" value=""/>
                    </div>
                </div>
            </div>
            <div class="b0xT_row-column">
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Payment Address 2:</label>
                        <input type="text" id="b0xT_payment_address_2" class="b0xT_form-input " name="b0xT_payment_address_2" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Payment City:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_payment_city" class="b0xT_form-input " name="b0xT_payment_city" value=""/>
                    </div>
                </div>
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row">
                        <label>Payment State:<span style="color: red">*</span></label>
                        <select name="b0xT_payment_state"  id="b0xT_payment_state" class="b0xT_form-input "></select>
                    </div>
                </div>
            </div>
            <div class="b0xT_row-column">
                <div class="b0xT_column-33">
                    <div class="b0xT_field-row"> 
                        <label>Payment <?php echo(esc_attr(get_option('b0xT_zipcode_label')) == "") ? 'Zipcode' : esc_attr(get_option('b0xT_zipcode_label')); ?>:<span style="color: red">*</span></label>
                        <input type="text" id="b0xT_payment_zipcode" class="b0xT_form-input " name="b0xT_payment_zipcode" value="" />
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="b0xT_row-column">
                <div class="b0xT_column-50">
                    <div class="b0xT_field-row">
                        <label>Placement:</label>
                        <textarea  id="b0xT_order_note" class="b0xT_form-input " name="b0xT_order_note" ></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="b0xT_form-block" style="margin-bottom: 6px;">
            <?php if(esc_attr(get_option('b0xT_payment_info')) === "Show") { ?>
            <div class="b0xT_label-field">Payment Method:</div>

            <div class="column-11">
                <div class="b0xT_field-row">
                    <strong><label>Amount to be charged: <strong>$<b0xT_total_amount></b0xT_total_amount></strong></label> </strong>
                </div>
                </div>

                <div class="b0xT_row-column">
                <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <label>Card Number:<span style="color: red">*</span></label>
                    <input type="text" id="b0xT_card_number" class="b0xT_form-input " name="b0xT_card_number" maxlength="16" />
                </div>
                </div>

                <div class="b0xT_column-22">
                <div class="b0xT_field-row">
                    <label>Expiry Month:<span style="color: red">*</span></label>
                    <select name="b0xT_card_expiry_month"  id="b0xT_card_expiry_month" class="b0xT_form-input ">
                        <option value="">--Select Month--</option>
                        <?php
                        for ($month = 1; $month <= 12; $month++) {
                            echo '<option value="'.esc_attr(sprintf("%02d",$month)).'">'.esc_html(sprintf("%02d",$month)).'</option>';
                        } ?>
                    </select>
                </div>
                </div>

                <div class="b0xT_column-22">
                <div class="b0xT_field-row">
                    <label>Expiry Year:<span style="color: red">*</span></label>
                    <select name="b0xT_card_expiry_year"  id="b0xT_card_expiry_year" class="b0xT_form-input ">
                        <option value="">--Select Year--</option>
                        <?php
                        for ($i = date('y'); $i <= date('y')+20; $i++) {
                            echo '<option value="'.esc_attr($i).'">'.esc_html($i).'</option>';
                        } ?>
                    </select>
                </div>
                </div>
                <div class="b0xT_column-22">
                    <div class="b0xT_field-row">
                        <label>CVV:<span style="color: red">*</span> <a b0xT_data-pop="1" style="font-size: 12px">What is my CVV code?</a></label>
                        <input type="text" id="b0xT_card_cvv" class="b0xT_form-input " name="b0xT_card_cvv" />
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="b0xT_row-column">
            <div class="b0xT_column-33">
                <div class="b0xT_field-row">
                    <span id="b0xT_step_three_error" name="b0xT_step_three_error"></span>
                </div>
            </div>
        </div>
        <div id="b0xT_terms_cond_wrapper" style="display: none; margin-bottom: 30px;">
            <div class="b0xT_verify_error" style="margin-bottom: 10px;">
               * Please read and acknowledge our terms and conditions. 
            </div>
            <div>
                <input type="checkbox" id="b0xT_terms_cond_check_box" />
                <label><a id="b0xT_terms_cond_check_box_link" target="_blank">Click to view terms and conditions.</a></label>
            </div>
        </div>
        <button type="submit" disabled style="display: none" aria-hidden="true"></button>
        <button name="b0xT_place_order_back" id="b0xT_place_order_back"    class="b0xT_form-button b0xT_back-btn" value="Back">Back</button>
        <button name="b0xT_place_order_submit" id="b0xT_place_order_submit" class="b0xT_form-button b0xT_form-disabled" value="Next" disabled>Next</button>
        <label><img id="b0xT_loader" class="b0xT_loader" src="<?php echo esc_url($this->b0xT_plugin_url); ?>images/fading_squares.gif" style=" padding-top: 5px;" /></label>
    </form>
</div>
<div b0xT_data-popup="1">
    <div>
        <div>
            <button b0xT_data-pop style="height: 10px !important; width: 10px !important; font-weight: 100 !important; line-height: 0px !important; position: sticky; left: 1370px !important; bottom: 325px !important; background: none !important;color: black !important;">X</button>
        </div>
        <div>
            <img src="<?php echo esc_url($this->b0xT_plugin_url); ?>images/cvv.png">
        </div>
    </div>
</div>
