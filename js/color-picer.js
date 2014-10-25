/* ------------------------------------
	color-picer.js
	Mignon Style
	http://mignonstyle.com/
------------------------------------ */

jQuery(function($){
	options_colorpicker();

	function options_colorpicker(){
		$('.color-picker .color-picker-field').wpColorPicker({
			change: function(event, ui){
				$(this).val(ui.color.toString());
			},
		});
	}
});