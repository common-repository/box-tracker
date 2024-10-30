function b0xT_pop_up_dialog(b0xT_arg_config) {
	var b0xT_dialog_box = this;
	b0xT_dialog_box.config = b0xT_arg_config;

	this._b0xT_init = function() {
		if(b0xT_dialog_box.config.shield_div && b0xT_dialog_box.config.dialog_box_div) {
			b0xT_dialog_box.config.shield_div.onclick = function() { b0xT_dialog_box.b0xT_close_dialog(); }

			//create content of dialog box
			var b0xT_header_div = document.createElement("div");
			var b0xT_first_child = document.createElement("div");
			var b0xT_second_child = document.createElement("div");
			var b0xT_span = document.createElement("span");
			b0xT_span.innerHTML = "X";

			b0xT_second_child.appendChild(b0xT_span);

			b0xT_second_child.onclick = function() {
				b0xT_dialog_box.b0xT_close_dialog();
			}

			b0xT_header_div.appendChild(b0xT_first_child);
			b0xT_header_div.appendChild(b0xT_second_child);

			var b0xT_content_div = document.createElement("div");

			b0xT_dialog_box.config.dialog_box_div.appendChild(b0xT_header_div);
			b0xT_dialog_box.config.dialog_box_div.appendChild(b0xT_content_div);

			//keep track of some divs
			b0xT_dialog_box.header_div = b0xT_first_child;
			b0xT_dialog_box.content_div = b0xT_content_div;
		}
	}

	this.b0xT_open_dialog = function(b0xT_header, b0xT_content) {
		if(b0xT_dialog_box.header_div && b0xT_dialog_box.content_div) {
			//clear
			b0xT_dialog_box.header_div.innerHTML = "";
			b0xT_dialog_box.content_div.innerHTML = "";

			//fill
			b0xT_dialog_box.header_div.innerHTML = b0xT_header;
			b0xT_dialog_box.content_div.innerHTML = b0xT_content;

			//display
			b0xT_dialog_box.config.shield_div.style.display = "block";
			b0xT_dialog_box.config.dialog_box_div.style.display = "block";
		}
	}

	this.b0xT_close_dialog = function() {
		if(b0xT_dialog_box.header_div && b0xT_dialog_box.content_div) {
			//hide
			b0xT_dialog_box.config.dialog_box_div.style.display = "none";
			b0xT_dialog_box.config.shield_div.style.display = "none";

			//clear
			b0xT_dialog_box.header_div.innerHTML = "";
			b0xT_dialog_box.content_div.innerHTML = "";
		}
	}

	this._b0xT_init();
	return this;
}