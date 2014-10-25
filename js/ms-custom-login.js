/* ------------------------------------
	ms-custom-login.js
	Mignon Style
	http://mignonstyle.com/
------------------------------------ */

jQuery(function($){
	options_slidebox();
	options_checkbox();

	// h3 option box
	function options_slidebox(){
		$('.option-box h3').click(function(){
			var parent_id = $(this).parents('.option-box').attr('id');
			parent_id = '#'+parent_id;

			$(parent_id+' .inside').stop().slideToggle('fast');
			$(parent_id).stop().toggleClass('close-box');
		});
	}

	// checkbox
	$('.option-check .target input:checkbox').change(function(){
		var parent_id = $(this).parents('.option-check').attr('id');
		checkbox_show_hide(parent_id);
	});

	function options_checkbox(){
		$('.option-check').each(function(){
			var parent_id = $(this).attr('id');
			checkbox_show_hide(parent_id);
		});
	}

	function checkbox_show_hide(parent_id){
		parent_id = '#'+parent_id;

		if($(parent_id+' .target input:checkbox').prop('checked')){
			$(parent_id+' .hidebox').show();
		}else{
			$(parent_id+' .hidebox').hide();
		}
	}
});