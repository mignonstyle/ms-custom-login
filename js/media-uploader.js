/* ------------------------------------
	media-uploader.js
	Mignon Style
	http://mignonstyle.com/
------------------------------------ */

jQuery(function($){
	var frame;

	options_media_uploader();

	function options_media_uploader(){
		$('.option-upload-button').on('click', function(e){
			if($('.media-upload.upload-select').size()){
				$('.media-upload.upload-select').removeClass('upload-select');
			}

			if($(e.target).closest('.media-upload')){
				var parent = $(e.target).closest('.media-upload');
				parent = parent.addClass('upload-select');
				options_add_file(e, parent);
			}
		});

		$('.option-remove-button').on('click', function(e){
			if($(e.target).closest('.media-upload')){
				var parent = $(e.target).closest('.media-upload');
				options_remove_file(parent);
			}
		});
	}

	function options_add_file(e, parent){
		e.preventDefault();

		// If the media frame already exists, reopen it.
		if(frame){
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media({
			title: option_media_text.title,
			library: {
				type: 'image'
			},
			button: {
				text: option_media_text.button,
				close: false,
			},
			multiple: false,
		});

		// When an image is selected, run a callback.
		frame.on('select', function(){
			var image = frame.state().get('selection').first();
			frame.close();

			// parent id
			var parent_ID = $('.upload-select').attr('ID');
			parent = $('#'+parent_ID);

			// grandparents id
			var this_grandparents = parent.closest('.option-box');
			var grandparents = $('#'+this_grandparents.attr('id'));

			if(image.attributes.type == 'image'){
				$('input[name*="_url"]', parent).val(image.attributes.url);
				$('.upload-remove table tr', parent).prepend('<td class="upload-preview"><img src="'+image.attributes.url+'" alt="" /></td>');
				$('.upload-remove', parent).addClass('remove-open').removeClass('upload-open');
				$('.media-children', grandparents).addClass('children-show').removeClass('children-hide');
			}
		});

		// Finally, open the modal.
		frame.open();
	}

	function options_remove_file(parent){
		var parent_id = $('#'+parent.attr('ID'));
		var this_grandparents = parent_id.closest('.option-box');
		var grandparents = $('#'+this_grandparents.attr('id'));

		$('input[name*="_url"]', parent).val('');
		$('.upload-remove', parent).addClass('upload-open').removeClass('remove-open');
		$('.media-children', grandparents).addClass('children-hide').removeClass('children-show');
		$('td.upload-preview', parent).empty();
	}
});