/* ------------------------------------
	ms-custom-login.js
	Mignon Style
	http://mignonstyle.com/
------------------------------------ */

jQuery(function($){
	options_slidebox();
	options_checkbox();
	codemirror();
	preview_popup();
	cookie_tabs();

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
		var eml = $(this);
		var parent_id = eml.parents('.option-check').attr('id');
		var elm_id = eml.parents('.target').attr('id');
		checkbox_show_hide(eml, parent_id, elm_id);
	});

	function options_checkbox(){
		$('.option-check .target input:checkbox').each(function(){
			var eml = $(this);
			var parent_id = eml.parents('.option-check').attr('id');
			var elm_id = eml.parents('.target').attr('id');
			checkbox_show_hide(eml, parent_id, elm_id);
		});
	}

	function checkbox_show_hide(eml, parent_id, elm_id){
		parent_id = '#' + parent_id;
		var elm_class = '.' + elm_id;

		if(eml.prop('checked')){
			$('.hidebox' + elm_class).show();
		}else{
			$('.hidebox' + elm_class).hide();
		}
	}

	// form position select x
	form_pos_select_x();
	function form_pos_select_x(){
		$('select[id*="mcl_form_x_select"]').change(function(){
			form_pos_show_hide_x();
		});
	}

	form_pos_show_hide_x();
	function form_pos_show_hide_x(){
		var select_x = $('select[id*="mcl_form_x_select"]').val();

		if(select_x.indexOf('center') != -1){
			$('.form-x-pos').hide();
		}else{
			$('.form-x-pos').show();
		}
	}

	// form position select y
	form_pos_select_y();
	function form_pos_select_y(){
		$('select[id*="mcl_form_y_select"]').change(function(){
			form_pos_show_hide_y();
		});
	}

	form_pos_show_hide_y();
	function form_pos_show_hide_y(){
		var select_x = $('select[id*="mcl_form_y_select"]').val();

		if(select_x.indexOf('center') != -1){
			$('.form-y-pos').hide();
		}else{
			$('.form-y-pos').show();
		}
	}

	// notice option
	$('#notice-option .notice-close').hide();
	$('#notice-option .notice-desc').hide();
	notice_option();

	function notice_option(){
		$('#notice-option .notice-open').click(function(){
			$('#notice-option .notice-desc').show();
			$('#notice-option .notice-close').show();
			$('#notice-option .notice-open').hide();
		});

		$('#notice-option .notice-close').click(function(){
			$('#notice-option .notice-desc').hide();
			$('#notice-option .notice-close').hide();
			$('#notice-option .notice-open').show();
		});
	}

	$('#notice-option input').change(function(){
		notice_option();
	});

	/*
	* CodeMirror
	*/
	function codemirror(){
		var editor = CodeMirror.fromTextArea(document.getElementById("ms_custom_login_options[mcl_custom_css]"), {
			lineNumbers: true,
			lineWrapping: true,
		});
	}

	/*
	* login page preview
	*/
	function preview_popup(){
		var $href = $('#preview a').attr('href');
		$('#preview a').attr('href', $href+'?TB_iframe=true&width=800&height=600&sandbox=""');
	}

	/*
	* jquery.cookie.js
	* jquery.ui.tabs.min.js
	* Save to cookie open tabs
	* Cookies will be deleted if you close the browser
	*/
	function cookie_tabs(){
		$('#tabset').tabs({
			active: ($.cookie('mcl_tabs')) ? $.cookie('mcl_tabs') : 0,
			activate: function(event, ui){
				// Expiration date of the cookie (30 minutes)
				var date = new Date();
				date.setTime(date.getTime()+(30*60*1000));

				// Register cookies
				$.cookie('mcl_tabs', $(this).tabs('option', 'active'), {expires:date});
			}
		});
	}
});