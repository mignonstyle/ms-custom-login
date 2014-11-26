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
	$('.option-check .target input:checkbox, .option-check .target2 input:checkbox').change(function(){
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

		if ( parent_id == '#logo-setting'){
			if($(parent_id+' .target2 input:checkbox').prop('checked')){
				$(parent_id+' .hidebox2').show();
			}else{
				$(parent_id+' .hidebox2').hide();
			}
		}else{
			if($(parent_id+' .target input:checkbox').prop('checked')){
				$(parent_id+' .hidebox').show();
			}else{
				$(parent_id+' .hidebox').hide();
			}
		}
	}

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