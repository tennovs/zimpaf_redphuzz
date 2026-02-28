jQuery(function($) {

	$(window).on('load', function() {
		var pre_loader = $('#preloader');
		pre_loader.fadeOut('slow', function() {
			$(this).remove();
		});
	});

	// File rename
	$(document).on('click', '.rename-edit-btn', function(e) {

		const filename = $(this).closest('td').find('input.wpfm_filename').val();
		const file_id = $(this).attr('data-fileid');
		const url = wpfm_main.rest_api_url + '/file-rename';

		const data = { fileid: file_id, filename: filename };
		$.post(url, data, function(resp) {
			
		});
	});



	// Loading files on load
	var group_id = 0;
	WPFM.renderFiles(group_id);

	$(document).on('mixLoad', function(e, state) {
		// Nothing ...
	});
	/*
	 show and hide the meta edit form or meta detail
	*/
	$(document).on('click', '.edit-meta-btn', function(e, state) {
		$(this).closest('.meta-info').hide();
		$(this).closest('.meta-info').siblings('.meta-edit-from').show();
	});
	$(document).on('click', '.go-to-meta-info-btn', function(e, state) {
		$(this).closest('.meta-edit-from').hide();
		$(this).closest('.meta-edit-from').siblings('.meta-info').show();
	});



	var dir_move_id = '';



	/*
	 **** end ---
	 */
	$(document).on('wpfm_files_rendered', function(e) {

		add_mixitup();
		// setting directory to root for BC
		WPFM.resetBC();
		WPFM.currentDir = { id: '0', title: '' };
		WPFM.dir_selected();

		add_modal_on_file();
	});

	$(document).on('wpfm_dir_changed', function(e) {

		// Nothing ...
	});

	// After directory created
	$(document).on('wpfm_after_dir_created', function(e) {

		// Loading files on load
		window.location.reload(false);

	});

	// After file saved
	$(document).on('wpfm_after_file_saved', function(e) {

		// Loading files on load
		window.location.reload(false);
		// group_id = 0;
		// WPFM.refreshFiles(group_id);
	});

	// After file/directory deleted
	$(document).on('wpfm_after_item_deleted', function(e) {

		// Loading files on load
		window.location.reload(false);
	});

	// file meta save on single file 
	$(document).on('submit', '.save-meta-frm', function(e) {

		e.preventDefault();

		var modal = $(this).closest('.frizi-modal');
		$(modal).modal('hide');

		var data = $(this).serialize();

		var wpfm_ajax_nonce = $('#wpfm_ajax_nonce').val();
		data = data + `&wpfm_ajax_nonce=${wpfm_ajax_nonce}&action=wpfm_file_meta_update`;
		// console.log(data);
		$.post(wpfm_vars.ajaxurl, data, function(resp) {
			if (resp.success) {
				WPFM.alert(resp.data, 'success');
			}
			else {
				WPFM.alert(resp.data, 'error');
			}
			location.reload();
		}).fail(function() {

			alert("error");
		});
	});

	// sending file in email
	$('.wpfm-send-file-in-email').on('submit', function(e) {

		e.preventDefault();

		$('.wpfm-sending-file').show();

		var data = $(this).serialize();
		var wpfm_ajax_nonce = $('#wpfm_ajax_nonce').val();
		data = data + `&wpfm_ajax_nonce=${wpfm_ajax_nonce}`;

		// console.log(data);
		$.post(wpfm_vars.ajaxurl, data, function(resp) {

			if (resp.success) {
				WPFM.alert(resp.data, 'success');
			}
			else {
				WPFM.alert(resp.data, 'error');
			}

			$('.wpfm-sending-file').toggle();

		}, 'json');
	});

	// On dir change
	$('#wpfm-main-wrapper').on('click', '.wpfm-dir', function(e) {

		$('#wpfm-del-dir-btn').show();

		var dir_id = $(this).data('node_id');
		var dir_title = $(this).data('title');
		var parent_id = '.parent-' + dir_id;

		// making serting and searching do some code
		// when clicking on directroy, first remove all 'mix' classes form '.wpfm_file_box'
		// then add 'mix' class only those '.wpfm_file_box' div's which is displayed
		$('.wpfm_file_box').removeClass('mix');
		$(parent_id).addClass('mix');
		// end sorting and searching section

		WPFM.currentDir = { id: dir_id, title: dir_title };
		WPFM.dir_selected();

	});

	// Breadcrumb item click
	$('#wpfm-main-wrapper').on('click', '.wpfm-bc-item', function(e) {

		var dir_id = $(this).data('node_id');
		var dir_title = $(this).data('title');

		WPFM.currentDir = { id: dir_id, title: dir_title };

		WPFM.dir_selected('bc-removed');

		// for making search and sorting 
		// first remove class of mix form all file div's
		// then add class mix to reqired or displayed file div's
		$('.wpfm_file_box').removeClass('mix');
		var files = '.parent-' + dir_id;
		$(files).addClass('mix');
		// end sections for soring ans searching

		var bcCount = WPFM.wpfm_bc.length;
		var bcIndex = 0;
		for (var i = 0; i < bcCount; i++) {

			var bcItem = WPFM.wpfm_bc[i];
			if (dir_id === bcItem.id) {
				bcIndex = i;
				// console.log('removed '+bcItem.title);
			}
		}

		var currentIndex = bcIndex + 1;
		var extraItems = bcCount - currentIndex;
		WPFM.wpfm_bc.splice(currentIndex, extraItems);
		WPFM.updateBC();
	});

	// 
	$('#wpfm-create-dir-option-btn').on('click', function(e) {
		e.preventDefault();
		$('.wpfm-new-select-wrapper').hide();
		$(this).hide();
		$('.wpfm-dir-create-wrapper').show('200');
	});

	$('.wpfm-cancle-btn').on('click', function(e) {
		e.preventDefault();
		WPFM.reset_upload_area();
	});

	$('#wpfm-dirname').on('keyup', function() {

		var value = $(this).val();
		if (value.length != 0) {
			$('#wpfm-dir-created-btn').removeAttr("disabled");
		}
		else {
			$('#wpfm-dir-created-btn').attr('disabled', 'disabled');
		};
	});


	// Creating new directory
	$('#wpfm-dir-created-btn').on('click', function() {


		jQuery.blockUI({
			message: wpfm_main.wpfm_main_msg,
			css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff'
			}

		});

		var dir_name = $('#wpfm-dirname').val();
		var dir_description = $('#wpfm-description').val();
		var shortcode_groups = $('#shortcode_groups').val();

		var data = {
			"action": "wpfm_create_directory",
			"dir_name": dir_name,
			"directory_detail": dir_description,
			"shortcode_groups": shortcode_groups,
			"parent_id": WPFM.get_selected_dir(),
			"wpfm_bp_group_id": $("#wpfm_bp_group_id").val(),
			"wpfm_ajax_nonce": $('#wpfm_ajax_nonce').val(),
		};

		WPFM.createDirectory(data);

	});

	$(document).on('click', '.file-edit-btn', function(event) {
		console.log('hi');
		$(this).closest('.ffmwp-admin-wrapper').find('.title_dec_adit_wrapper').toggle();
	});

	$(document).on('click', '.file-title-dec-cancel-adit-btn', function(event) {
		$(this).closest('.title_dec_adit_wrapper').hide();
	});

	$(document).on('click', '.del-file-btn', function(event) {
		event.preventDefault();
		var file_id = $(this).data('id');
		swal({
			title: wpfm_vars.messages.file_delete,
			icon: "warning",
			showCancelButton: true,
			buttons: true,
			buttons: [wpfm_vars.messages.text_cancel, wpfm_vars.messages.text_yes],
			dangerMode: true
		}).then(function(willDelete) {
			if (willDelete) {

				swal(wpfm_vars.messages.file_deleting, {

					className: "red-bg",
					buttons: false,

				});

				WPFM.delete_file(file_id);

			}
			else {
				$('html').css('overflow', 'visible')
				$('body').css('overflow', 'visible')
			}
		});

	});


	$(document).on('click', '.file-title-dec-save-btn', function(event) {
		event.preventDefault();
		var ajax_url = $('#ajax_url').val();
		var file_id = $(this).siblings('.file-title').data("id");
		var file_title = $(this).siblings('.file-title').val();
		var file_descrip = $(this).siblings('.file-description').val();
		var file_ChngFileDir = $(this).siblings('.ChngFileDir').val();
		var data = {
			'action': 'wpfm_edit_file_title_desc',
			'file_id': file_id,
			'file_title': file_title,
			'file_content': file_descrip,
			"wpfm_ajax_nonce": jQuery('#wpfm_ajax_nonce').val()
		}

		$.post(wpfm_vars.ajaxurl, data, function(resp) {

			if (resp.success) {

				WPFM.alert(resp.data, 'success');
				location.reload();
			}
			else {
				WPFM.alert(resp.data, 'error');
				location.reload();
			}

		}).fail(function() {
			swal('error', "File not update", "error");
		});

	});

	$('#wpfm-save-file-btn').on('click', function(event) {

		event.preventDefault();

		$('#wpfm-save-file-btn').html('please wait...').attr('disabled', 'disabled')
		var parent_dir_id = WPFM.get_selected_dir();
		var file_id = $('#exist_file_id').val();

		if (parent_dir_id == 0) {
			parent_dir_id = wpfm_main.wpfm_files_upload_dir;
		}

		var new_files = $('.wpfm-new-uploaded-files :input').serialize();
		var wpfm_nonce = $('#wpfm_save_nonce').val();
		var groups = $('#shortcode_groups').val();
		var bp_group_id = $("#wpfm_bp_group_id").val();

		var data = new_files + "&action=wpfm_save_file_data&wpfm_save_nonce=" + wpfm_nonce + "&shortcode_groups=" + groups + "&exist_file_id=" + file_id;
		data += '&parent_id=' + parent_dir_id;
		data += '&wpfm_bp_group_id=' + bp_group_id;


		WPFM.saveFiles(data);

	});

	$(document).on('change', '#wpfm_sorted_by', function(event) {
		var orederby = $(this).val();
		var oreder = $('input[type=radio][name=wpfm_sortorder]').val();
		$(".wpfm_files_grid").mixItUp('sort', orederby + ':' + oreder + ' ' + 'title:' + oreder);
	});
	
	$(document).on('change', 'input[type=radio][name=wpfm_sortorder]', function(event) {
		var oreder = $(this).val();
		var orederby = $("#wpfm_sorted_by").val();

		$(".wpfm_files_grid").mixItUp('sort', orederby + ':' + oreder);
	});

	$(document).on('change', '#group-filter', function(event) {
		var filterby = $(this).val();
		var $matching_groups = $();

		if (filterby == 'all') {
			$(".wpfm_files_grid").mixItUp('filter', 'all');
		}
		else {

			$('.mix').each(function() {
				$this = $("this");

				// add item to be filtered out if input text matches items inside the title   
				if ($(this).hasClass(filterby)) {
					$matching_groups = $matching_groups.add(this);
				}
			});
			$(".wpfm_files_grid").mixItUp('filter', $matching_groups);
		};
	});
	$(document).on('keyup', '#search_files', function(event) {
		// Delay function invoked to make sure user stopped typing

		var inputText;
		var $matching = $();
		inputText = $("#search_files").val().toLowerCase();

		// Check to see if input field is empty
		if ((inputText.length) > 0) {
			$('.mix').each(function() {
				// $this = $("this");
				// add item to be filtered out if input text matches items inside the title   
				if ($(this).children('.file_title').text().toLowerCase().match(inputText)) {
					$matching = $matching.add(this);
				}
				else {
					// removes any previously matched item
					$matching = $matching.not(this);
				}
			});
			$(".wpfm_files_grid").mixItUp('filter', $matching);
		}

		else {
			// resets the filter to show all item if input is empty
			$(".wpfm_files_grid").mixItUp('filter', 'all');
		}
	});


	if (wpfm_main.wpfm_files_move_var == 'yes') {

		// file draggable
		// $('*[data-fiel_type="file"]').draggable({
		$('.wpfm_file_box > .wpfm_user_file').draggable({

			revert: function() {

				$(this).find('.file-action').css('display', 'inline-block');
				$(this).find('.wpfm_user_file').css('border', '1px solid #ddd');
				$(this).find('.wpfm-img').css('width', '64%');

				return true;
			},
			cursor: 'move',
			// refreshPositions: true
		});

		// $('*[data-fiel_type="file"]').on("drag", function(event, ui) {
		$('.wpfm_file_box').on("drag", function(event, ui) {

			$(this).find('.file-action').css('display', 'none');
			$(this).find('.wpfm_user_file').css('border', 'none');
			$(this).find('.wpfm-img').css('width', '40%');

		});

		$('*[data-fiel_type="dir"]').droppable({

			hoverClass: 'wpfm-active-droppable-box',

			accept: '.wpfm_file_box > .wpfm_user_file',
			drop: function(event, ui) {

				var dir_id = $(this).data("node_id");
				var file_id = ui.draggable.attr('id');
				$("#wpfm-files-wrapper").find("[data-node_id='" + file_id + "']").css('display', 'none');

				var data = {
					action: 'nm_uploadfile_move_file',
					file_id: file_id,
					parent_id: dir_id,
					"wpfm_ajax_nonce": jQuery('#wpfm_ajax_nonce').val()
				}

				$.post(wpfm_vars.ajaxurl, data, function(resp) {

					if (resp.success) {
						WPFM.alert(resp.data, 'success');
						location.reload();
					}
					else {
						WPFM.alert(resp.data, 'error');
						location.reload();
					}

				}).fail(function() {

					alert("error");
				}, 'json');



			}
		});

		$('*[data-fiel_type="file"]').droppable({


			hoverClass: 'wpfm-file-droppable-box',

			accept: '*[data-fiel_type="file"]',

		});

	}


});

function add_mixitup() {

	var mix = jQuery('.wpfm_files_grid').mixItUp();
}

function add_modal_on_file(children = null) {

	const user_files = children !== null ? children : wpfm_vars.user_files;

	jQuery.each(user_files, function(index, user_file) {

		if (user_file.node_type == 'dir') {
			add_modal_on_file(user_file.children);
		}

		const modal_id = `#modal${user_file.id}`;
		const modal_target = `file_detail_box_${user_file.id}`;
		jQuery(modal_id).animatedModal({
			modalTarget: modal_target,
			animatedIn: 'lightSpeedIn',
			animatedOut: 'bounceOutDown',
			color: '#fff',
			opacityIn: '1'
		});

		const pdf_modal_id = `#pdf_modal${user_file.id}`;
		const pdf_modal_target = `pdf_file_detail_box_${user_file.id}`;
		console.log(pdf_modal_target);
		jQuery(pdf_modal_id).animatedModal({
			modalTarget: pdf_modal_target,
			animatedIn: 'lightSpeedIn',
			animatedOut: 'bounceOutDown',
			color: '#fff',
			opacityIn: '1'
		});

	});

	// jQuery('.wpfm_file_box').find('.view-icon').animatedModal({
	// 	animatedIn: 'lightSpeedIn',
	// 	animatedOut: 'bounceOutDown',
	// 	color: '#fff',
	// });
}
