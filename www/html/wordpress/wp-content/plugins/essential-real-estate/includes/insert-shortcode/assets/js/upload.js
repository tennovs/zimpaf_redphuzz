(function ($) {
	$(document).ready(function() {
		init_upload();
		function init_upload() {
			$(".ere-image-upload").on('click', function (event) {
				event.preventDefault();

				var activeFileUploadContext = $(this).parent(),
					relid = $(this).attr('rel-id');

				// if its not null, its broking custom_file_frame's onselect "activeFileUploadContext"
				custom_file_frame = null;

				// Create the media frame.
				custom_file_frame = wp.media.frames.customHeader = wp.media({
					// Set the title of the modal.
					title: $(this).data("choose"),

					// Tell the modal to show only images. Ignore if want ALL
					library: {
						type: 'image'
					},
					// Customize the submit button.
					button: {
						text: $(this).data("update")
					}
				});

				custom_file_frame.on("select", function () {
					// Grab the selected attachment.
					var attachment = custom_file_frame.state().get("selection").first();

					// Update value of the targetfield input with the attachment url.
					$('.ere-image-screenshot', activeFileUploadContext).attr('src', attachment.attributes.url);
					$('#options-item-id', activeFileUploadContext).attr('value', attachment.attributes.id);
					$('#' + relid).val(attachment.attributes.url).trigger('change');

					$('.ere-image-upload', activeFileUploadContext).hide();
					$('.ere-image-screenshot', activeFileUploadContext).show();
					$('.ere-image-upload-remove', activeFileUploadContext).show();
				});
				custom_file_frame.open();
			});
			$(".ere-image-upload-remove").on('click', function (event) {
				var activeFileUploadContext = $(this).parent();
				var relid = $(this).attr('rel-id');

				event.preventDefault();

				$('#' + relid).val('');
				$(this).prev().fadeIn('slow');
				$('.ere-image-screenshot', activeFileUploadContext).fadeOut('slow');
				$('#options-item-id', activeFileUploadContext).attr('value', '');
				$(this).fadeOut('slow');
			});
		}
	});
})(jQuery);
