function b0xT_cls_address_vldtr(b0xT_arg_config) { 
     var b0xT_address_data = this;
     b0xT_address_data.config = b0xT_arg_config;
     var b0xT_place_auto_complete;

     this._b0xT_init = function() {
          //setup google places address autocomplete
          var b0xT_search = b0xT_address_data.config.searchCtrl;
          b0xT_place_auto_complete = new google.maps.places.Autocomplete(
               b0xT_search, { 
                    fields: ['geometry', 'address_component', 'type']
               }
          );

          var b0xT_call_back = function () {
               b0xT_address_data._b0xT_fill_in_address();
          }

          b0xT_place_auto_complete.addListener( 'place_changed',  b0xT_call_back);
          b0xT_address_data.placeautocomplete = b0xT_place_auto_complete;

          //limit the counties to usa and canada
          b0xT_place_auto_complete.setComponentRestrictions({
               country: ['us', 'ca']
          });

          //setup event listensers on fields
          b0xT_address_data.config.addressCtrl.onchange = b0xT_address_data._b0xT_address_changed;
          b0xT_address_data.config.cityCtrl.onchange = b0xT_address_data._b0xT_address_changed;
          b0xT_address_data.config.stateCtrl.onchange = b0xT_address_data._b0xT_address_changed;
          b0xT_address_data.config.postalCtrl.onchange = b0xT_address_data._b0xT_address_changed;
     };

     this._b0xT_fill_in_address = function () {
          var b0xT_place = b0xT_address_data.placeautocomplete.getPlace();

          if (!b0xT_place.geometry) {

               b0xT_address_data.b0xT_refresh_google_verification();

               if(b0xT_address_data.dBox) {
                    b0xT_address_data.dBox.b0xT_open_dialog("WARNING!!", 'No details available for input: '+b0xT_place.name);
               }
               return;
          }
        
          //get autocompleted address
          let b0xT_street_number = b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'street_number' );
          b0xT_street_number.short_name = b0xT_street_number.short_name ? b0xT_street_number.short_name : b0xT_address_data.config.searchCtrl.value.split(' ')[0].replaceAll(/\D/g, '');
  
          let b0xT_street_name = b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'street_address' );
          b0xT_street_name = b0xT_street_name.long_name ? b0xT_street_name : b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'route' );
          b0xT_street_name = b0xT_street_name.long_name ? b0xT_street_name : b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'intersection' );
  
          let b0xT_city = b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'locality' );
          b0xT_city = b0xT_city.long_name ? b0xT_city : b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'administrative_area_level_3' );
          b0xT_city = b0xT_city.long_name ? b0xT_city : b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'sublocality_level_1' );
  
          let b0xT_state = b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'administrative_area_level_1' );
          let b0xT_postal = b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'postal_code' );         
          let b0xT_postal_suffix = b0xT_address_data._b0xT_find_address_component( b0xT_place.address_components, 'postal_code_suffix' );

          //we are done, clear address field
          b0xT_address_data.config.addressCtrl.value = "";

          //however, is state part of the selected country ?
          //if not we dont want to go any further than this
          var found_state = 0;
          for(var option of b0xT_address_data.config.stateCtrl.options) {
               if(option.value == b0xT_state.short_name) {
                    found_state++;
               }
          }

          if(!found_state) {

               b0xT_address_data.b0xT_refresh_google_verification();

               if(b0xT_address_data.dBox) {
                    b0xT_address_data.dBox.b0xT_open_dialog("WARNING!!", "This address appears to reside in a country that is not accepted by this company");
               }
               return;
          }

          b0xT_address_data.config.addressCtrl.value = `${b0xT_street_number.short_name} ${b0xT_street_name.short_name}`;
          b0xT_address_data.config.cityCtrl.value = b0xT_city.long_name;
          b0xT_address_data.config.stateCtrl.value = b0xT_state.short_name;
          b0xT_address_data.config.postalCtrl.value = b0xT_postal.short_name;

          //check if the address is rooftop
          let b0xT_verified = 0;
          if(b0xT_place.types.includes('premise') || b0xT_place.geometry.location_type == 'ROOFTOP' || b0xT_postal_suffix.short_name.length) {
               //TODO::
               b0xT_verified = 1;
          } else if(document.getElementById("b0xT_google_status_bypass").value == "On") {
               //we are going to check for valid cordinates
               let b0xT_coordinate_validate = function() {
                    let b0xT_pattern = new RegExp("^-?[1-9]\\d{1,2}($|\.\\d+$)");

                    if(!b0xT_place.geometry) { return 0; }
                    if(!b0xT_place.geometry.location) { return 0; }

                    let b0xT_lat = b0xT_place.geometry.location.lat() * 1;
                    let b0xT_lng = b0xT_place.geometry.location.lng() * 1;

                    if(!b0xT_pattern.exec(b0xT_lat)) { return 0; }
                    if(!b0xT_pattern.exec(b0xT_lng)) { return 0; }

                    if(!(b0xT_lat <= 90 && b0xT_lat >= -90)) { return 0; }
                    if(!(b0xT_lng <= 180 && b0xT_lng >= -180)) { return 0; }
                    return 2;
               }

               b0xT_verified = b0xT_coordinate_validate();
          }

          b0xT_address_data.b0xT_refresh_google_verification(b0xT_verified);
     };

     this._b0xT_find_address_component = function( b0xT_address_array, b0xT_search ) {
          for( let i = 0; i < b0xT_address_array.length; i++ ) {
               if ( b0xT_address_array[i].types[0] == b0xT_search ) {
                    return b0xT_address_array[i];
               }
          }
          return { long_name: '', short_name: '', types: [ b0xT_search ] };
     };

     this.b0xT_refresh_google_verification = function(b0xT_arg_code) {
          switch( b0xT_arg_code ) {
               case 1:
                    b0xT_address_data.config.validateCtrl.innerHTML = 'VERIFIED';
                    b0xT_address_data.config.validateCtrl.className = 'b0xT_verify_success';
                    break;
               case 2:
                    b0xT_address_data.config.validateCtrl.innerHTML = 'BYPASSED';
                    b0xT_address_data.config.validateCtrl.className = 'b0xT_verify_success';
                    break;
               default:
                    b0xT_address_data.config.validateCtrl.innerHTML = 'NOT VERIFIED';
                    b0xT_address_data.config.validateCtrl.className = 'b0xT_verify_error';
                    break;
          }
     };

     this._b0xT_address_changed = function() {
          if(b0xT_address_data.config.validateCtrl.className != 'b0xT_verify_error') {
               b0xT_address_data.b0xT_refresh_google_verification();
          }

          //reset error div if its present
          if(document.getElementsByClassName("b0xT-error-msg") && document.getElementsByClassName("b0xT-error-msg")[0]) {
               document.getElementsByClassName("b0xT-error-msg")[0].remove();
          }
     };

     this._b0xT_set_defaults = function() {
          if ( !b0xT_address_data.config.addressCtrl   ) b0xT_address_data.config.addressCtrl   = b0xT_address_data._b0xT_create_input_text_obj();
          if ( !b0xT_address_data.config.cityCtrl      ) b0xT_address_data.config.cityCtrl      = b0xT_address_data._b0xT_create_input_text_obj();
          if ( !b0xT_address_data.config.stateCtrl     ) b0xT_address_data.config.stateCtrl     = b0xT_address_data._b0xT_create_input_text_obj();
          if ( !b0xT_address_data.config.postalCtrl    ) b0xT_address_data.config.postalCtrl    = b0xT_address_data._b0xT_create_input_text_obj();

          if(b0xT_address_data.config.errorShield && b0xT_address_data.config.errorDialog) {
               var config = new Object();
               config.shield_div = b0xT_address_data.config.errorShield;
               config.dialog_box_div = b0xT_address_data.config.errorDialog;
               if((typeof(b0xT_pop_up_dialog) == "function")) {
                    b0xT_address_data.dBox = new b0xT_pop_up_dialog(config);
               }
          }
     };

     this._b0xT_create_input_text_obj = function() {
        let b0xT_input = document.createElement('INPUT');
        b0xT_input.setAttribute('type', 'text');
        return b0xT_input;
     };

     this._b0xT_set_defaults();
     this._b0xT_init();
     return this;
}



