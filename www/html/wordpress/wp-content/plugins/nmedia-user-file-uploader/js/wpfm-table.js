(function(window) {
	'use strict';
	// This function will contain all our code
	function wpfm() {
		var _thisWPFM = {};

		_thisWPFM.dirSelected = wpfm_vars.directory_init;
		_thisWPFM.user_files = wpfm_vars.user_files;
		_thisWPFM.currentDir = { id: _thisWPFM.dirSelected, title: '' };

		_thisWPFM.fiels_per_row = wpfm_vars.files_per_row;
		_thisWPFM.columns = 'col-sm-' + _thisWPFM.fiels_per_row;
		// console.log
		_thisWPFM.wpfm_bc = [];
		_thisWPFM.file_wrapper = '';
		// Saving all files
		_thisWPFM.saveFiles = function(data) {


			jQuery.post(wpfm_vars.ajaxurl, data, function(resp) {
				// console.log(resp.message); return;

				var status = resp.status;
				if (status == 'success') {


					if (wpfm_vars.file_meta != '' && wpfm_vars.file_meta != null) {

						_thisWPFM.show_file_meta_form(resp.file_objects);
					}
					else {


						swal(wpfm_vars.messages.file_uploaded, {

							className: "red-bg",
							buttons: false,

						});

						jQuery.event.trigger({
							type: "wpfm_after_file_saved",
							dir_info: data,
							response: resp,
							time: new Date()
						});

					}
				}
				else if (status == 'error') {

					_thisWPFM.alert(resp.message, status);
				};
				// hide upload btn or save btn
				jQuery('.wpfm_upload_button').hide();



			}).fail(function() {
				_thisWPFM.alert(wpfm_vars.messages.http_server_error, 'error');
			});
		}


		// Showing file meta forms
		_thisWPFM.show_file_meta_form = function(file_objects) {

			jQuery.each(file_objects, function(index, file_post) {

				var $container = jQuery("#wpfm-file-" + file_post.file_id);

				var file_meta = file_post.file_obj.file_meta_html;

				var meta_form = jQuery('<form/>')
					.addClass('save-meta-on-file-upload-frm ')
					.appendTo($container);

				jQuery(file_meta).appendTo(meta_form);

				// submit button
				var btn_submit = jQuery('<button/>')
					.addClass('btn btn-success save-file-meta-btn')
					.html('Save')
					.appendTo(meta_form);


				$container.html(meta_form);

				jQuery('<hr/>')
					.appendTo($container);

				_thisWPFM.form_get_in_js();

			});
		}


		_thisWPFM.form_get_in_js = function() {

			// file meta save on file upload
			jQuery(document).on('submit', '.save-meta-on-file-upload-frm', function(e) {
				e.preventDefault();

				swal('File Uploading..', {

					className: "red-bg",
					buttons: false,

				});
				var form = jQuery(this);
				var data = form.serialize();

				var wpfm_ajax_nonce = jQuery('#wpfm_ajax_nonce').val();
				data = data + `&wpfm_ajax_nonce=${wpfm_ajax_nonce}&action=wpfm_file_meta_update`;

				jQuery.post(wpfm_vars.ajaxurl, data, function(resp) {

					if (resp.success) {
						form.removeClass('save-meta-on-file-upload-frm');
						form.html('<p>File Meta Saved.</p>');
						// check if file_meta_frm axist in dom
						var total_form = jQuery('#wpfm_file_loading_wrapper .save-meta-on-file-upload-frm').length;

						// event triger when last file meta form submite`
						if (total_form == 0) {
							jQuery('.wpfm_upload_button').show();
							jQuery('#wpfm-files-wrapper').show();
							jQuery.event.trigger({
								type: "wpfm_after_file_saved",
								time: new Date()
							});
							location.reload();
							// window.location.replace(wpfm_vars.url);
						};
					}
					else {
						_thisWPFM.alert(resp.data, 'error');
						location.reload();
					}
				}).fail(function() {

					alert("error");
				}, 'json');
			});

		}
		// Creating directory
		_thisWPFM.createDirectory = function(data) {

			jQuery.post(wpfm_vars.ajaxurl, data, function(resp) {

				if (resp.success) {
					_thisWPFM.alert(resp.data, 'success');
					jQuery.event.trigger({
						type: "wpfm_after_dir_created",
						dir_info: data,
						time: new Date()
					});
				}
				else {
					_thisWPFM.alert(resp.data, 'error');
					window.location.reload(false);
				}

			}).fail(function() {
				_thisWPFM.alert(wpfm_vars.messages.http_server_error, 'error');
			}, 'json');
		}

		_thisWPFM.renderFiles = function() {

			jQuery('#upload_files_btn_area').show();
			jQuery('#wpfm-files-wrapper').show().html('');


			_thisWPFM.file_wrapper = jQuery('<table/>')
				.addClass('wpfm_files_list_table')
				.addClass('table table-hover')
				.appendTo('#wpfm-files-wrapper');


			var html = "<thead><tr><th>Thumbnail</th><th>Title</th><th>Owner</th><th>Size</th><th>File Group</th><th>Action</th></tr></thead>";

			jQuery(html).appendTo(_thisWPFM.file_wrapper);



			var gird_html = _thisWPFM.create_list_view_2(_thisWPFM.user_files);

			_thisWPFM.reset_upload_area();
			setTimeout(function() {

				jQuery.event.trigger({
					type: "wpfm_files_rendered",
					time: new Date()
				});
			}, 500);
		}

		// _thisWPFM.refreshFiles = function(group_id) {

		// 	//geting all user files
		// 	var data = {
		// 		action: 'wpfm_get_user_files',
		// 		'send_json': 'yes'
		// 	};

		// 	jQuery.post(wpfm_vars.ajaxurl, data, function(user_files) {

		// 		_thisWPFM.user_files = user_files;
		// 		_thisWPFM.renderFiles();
		// 	}).fail(function() {

		// 		//@Fayaz: Need to show some localized error message
		// 		_thisWPFM.alert("Please try again", "error");
		// 	});
		// }

		_thisWPFM.get_file_by_id = function(file_id, children) {

			var file_found = Array();

			if (file_id == 0) return file_found;

			var searchable_files = children === undefined ? _thisWPFM.user_files : children;

			jQuery.each(searchable_files, function(i, file) {

				if (file.id === file_id) {
					file_found = file;
					return false;
				}
				else {

					if (file.node_type === 'dir' && file.children.length > 0 && file_found.length == 0) {
						file_found = _thisWPFM.get_file_by_id(file_id, file.children);
					}
				}
			});
			return file_found;
		}

		_thisWPFM.create_list_view_2 = function(user_files, is_child) {

				jQuery.each(user_files, function(index, user_file) {

					var parent_has_class = user_file.file_parent != 0 ? 'has-parent' : '';
					var node_id = 'node-' + user_file.id;

					var parent_id_class = 'parent-' + user_file.file_parent;

					if (!is_child) {
						/**
						 * playing trick for fil/dir sharing
						 * in shared view we have to set shared files parent to 0
						 * so when/if parent directory is shared it will not render files twice
						 **/
						parent_id_class = 'parent-0';
					}

					// console.log(user_file);


					const file_wrapper = jQuery('<tr/>')
						.addClass('wpfm_file_box margin-bottom')
						.addClass(`wpfm-file-${user_file.id}`)
						.addClass(parent_id_class)
						.addClass(parent_has_class)
						.addClass('wpfm-table-tr')
						.attr('data-pid', user_file.file_parent)
						.attr('data-node_id', user_file.id)
						.attr('data-title', user_file.title)
						.attr('data-price', user_file.price)
						.attr('data-price_html', user_file.price_html)
						.attr('id', node_id)
						.appendTo(_thisWPFM.file_wrapper);

					// this is add file group filter classes add for mixitup

					// this is add file group filter classes add for mixitup
					if (!user_file.file_groups.length == 0) {

						jQuery.each(user_file.file_groups, function(index, value) {

							file_wrapper.addClass(value);
						});
					};
					// end group filter

					if (parent_id_class == "parent-0") {
						file_wrapper.addClass('mix');
					};

					if (user_file.node_type == 'dir') {

						// 
						file_wrapper.attr('data-fiel_type', 'dir');
					}
					else {
						file_wrapper.attr('data-fiel_type', 'file');
					}

					if (user_file.node_type == 'dir') {


						var icon_box = jQuery('<div/>')
							.addClass('icon-box-table')
							.html(user_file.thumb_image)

						const td1 = jQuery("<td/>")
							.append(icon_box)
							.appendTo(file_wrapper);

						const td2 = jQuery("<td/>")
							.append(user_file.title_date)
							.appendTo(file_wrapper);

						//td3 owner
						const td3 = jQuery("<td/>")
							.append(user_file.owner_image)
							.appendTo(file_wrapper);


						//td4 size
						const td4 = jQuery("<td/>")
							.append(user_file.size)
							.appendTo(file_wrapper);

						//td5 file froup
						const td5 = jQuery("<td/>")
							.append(user_file.file_groups)
							.appendTo(file_wrapper);

						var delete_icon = jQuery('<span/>')
							.html(user_file.delete_button)

						var share_icon = '';
						if (user_file.is_share_enable) {

							var share_icon = jQuery('<button/>')
								.addClass('share-icon btn share-file-btn wpfm-us-modal')
								.attr({ "title": "Share", "data-post_id": user_file.id, "data-toggle": "modal" })
								.html("<span class='dashicons dashicons-share-alt2'></span>")
						};

						const td6 = jQuery("<td/>")
							.append(share_icon)
							.append(delete_icon)
							.append(user_file.download_button)
							.append(user_file.view_button)
							.appendTo(file_wrapper);

					}
					else {

						var icon_box = jQuery('<div/>')
							.addClass('icon-box-table file-box')
							.html(user_file.thumb_image)

						const td1 = jQuery("<td/>")
							.append(icon_box)
							.appendTo(file_wrapper);

						const td2 = jQuery("<td/>")
							.append(user_file.title_date)
							.appendTo(file_wrapper);

						//td3 owner
						const td3 = jQuery("<td/>")
							.append(user_file.owner_image)
							.appendTo(file_wrapper);


						//td4 size
						const td4 = jQuery("<td/>")
							.append(user_file.size)
							.appendTo(file_wrapper);

						//td5 file froup
						const td5 = jQuery("<td/>")
							.append(user_file.file_groups)
							.appendTo(file_wrapper);


						var delete_icon = jQuery('<span/>')
							.html(user_file.delete_button)
						// .appendTo(file_actions);

						var share_icon = '';
						if (user_file.is_share_enable) {

							var share_icon = jQuery('<button/>')
								.addClass('share-icon btn share-file-btn wpfm-us-modal')
								.attr({ "title": "Share", "data-post_id": user_file.id, "data-toggle": "modal" })
								.html("<span class='dashicons dashicons-share-alt2'></span>")
						};

						const td6 = jQuery("<td/>")
							.append(share_icon)
							.append(delete_icon)
							.append(user_file.download_button)
							.append(user_file.view_button)
							.appendTo(file_wrapper);




						// download button
						// file_actions.append(user_file.download_button);
						// var download_icon = jQuery('<span/>')
						//        .html(user_file.download_button)
						//                    .appendTo(file_actions);

						// var file_title = jQuery('<h5/>')
						// 	.addClass('file_title text-center')
						// 	.html(user_file.title)
						// 	.attr({ 'data-modal': 'frizi-modal', 'data-target': '#file_detail_box_' + user_file.id })
						// 	.appendTo(file_wrapper);
					}
					// if (user_file.children != null && user_file.children.length > 0) {
					// 	_thisWPFM.create_list_view_2(user_file.children, true);
					// }

					// if (user_file.is_share_enable) {

					// 	var share_icon = jQuery('<button/>')
					// 		.addClass('share-icon btn share-file-btn wpfm-us-modal')
					// 		.attr({ "title": "Share", "data-post_id": user_file.id, "data-toggle": "modal" })
					// 		.html("<span class='dashicons dashicons-share-alt2'></span>")
					// };

					// view button form file.class
					// jQuery(user_file.view_button).appendTo(file_actions);

					// if (user_file.is_deletable) {

					// 	// download button
					// 	var delete_icon = jQuery('<span/>')
					// 		.html(user_file.delete_button)

					// }

					// var icon_box = jQuery('<div/>')
					// 	.addClass('icon-box-table')
					// 	.html(user_file.thumb_image)

					// icon_box

					//td1 image
					// const td1 = jQuery("<td/>")
					// 	.append(icon_box)
					// 	.appendTo(file_wrapper);

					//td2 title
					// const td2 = jQuery("<td/>")
					// 	.append(user_file.title)
					// 	.appendTo(file_wrapper);

					// //td3 owner

					// const td3 = jQuery("<td/>")
					// 	.append(user_file.owner_image)
					// 	.appendTo(file_wrapper);


					// //td4 size
					// const td4 = jQuery("<td/>")
					// 	.append(user_file.size)
					// 	.appendTo(file_wrapper);

					// //td5 file froup
					// const td5 = jQuery("<td/>")
					// 	.append(user_file.file_groups)
					// 	.appendTo(file_wrapper);

					//td5 action
					// const td6 = jQuery("<td/>")
					// 	.append(share_icon)
					// 	.append(delete_icon)
					// 	.append(user_file.download_button)
					// 	.append(user_file.view_button)
					// 	.appendTo(file_wrapper);


					// if (user_file.children != null && user_file.children.length > 0) {
					// 	_thisWPFM.create_list_view_2(user_file.children, true);
					// }


					// console.log(wpfm_vars.is_visible_file_details);

					if (user_file.children != null && user_file.children.length > 0) {
						_thisWPFM.create_list_view_2(user_file.children, true);
					}
				});



				if (wpfm_vars.is_visible_file_details) {

					_thisWPFM.file_details_modals(user_files);
				};



				return true;
			},

			_thisWPFM.user_file = function(user_file) {


			},



			_thisWPFM.file_details_modals = function(user_files) {

				jQuery.each(user_files, function(index, user_file) {
					if (user_file.node_type == 'file') {
						jQuery("body").append(user_file.file_detail_html);
					}
				});
			}


		_thisWPFM.reset_upload_area = function() {

			jQuery('.wpfm-new-select-wrapper').show('200');
			jQuery('#wpfm-create-dir-option-btn').show('200');

			jQuery('.wpfm-dir-create-wrapper').hide();

			// Remove uploaded files
			jQuery('#wpfm_file_loading_wrapper').html('');
			// Hide save file button
			jQuery('#wpfm-save-file-btn').hide();
		}

		// Directory changed
		_thisWPFM.dir_selected = function(bc) {

			if (bc !== 'bc-removed') {

				var file = _thisWPFM.get_file_by_id(_thisWPFM.currentDir.id);
				_thisWPFM.addToBC(file);
			}

			_thisWPFM.dirSelected = _thisWPFM.currentDir.id;


			// Show directory nodes
			jQuery('.wpfm_file_box').hide();
			var child_class = '.parent-' + _thisWPFM.dirSelected;
			jQuery(child_class).show();


			jQuery.event.trigger({
				type: "wpfm_dir_changed",
				time: new Date()
			});
		}

		_thisWPFM.resetBC = function() {

			_thisWPFM.wpfm_bc = [];
		}

		_thisWPFM.addToBC = function(node) {

			if (_thisWPFM.wpfm_bc.length == 0) {
				_thisWPFM.wpfm_bc.push(wpfm_vars.breadcrumb);
			}

			if (node.id !== undefined) {
				var new_node = { id: _thisWPFM.currentDir.id, title: _thisWPFM.currentDir.title };
				_thisWPFM.wpfm_bc.push(new_node);
			}

			_thisWPFM.updateBC();
		}

		_thisWPFM.updateBC = function() {

			if (_thisWPFM.wpfm_bc == []) return;

			var wpfm_bc_dom = jQuery('#wpfm-bc');
			wpfm_bc_dom.html('');

			jQuery.each(_thisWPFM.wpfm_bc, function(i, bc) {

				var BCItem = jQuery('<li/>')
					.html(bc.title)
					.attr('data-node_id', bc.id)
					.attr('data-title', bc.title)
					.addClass('wpfm-bc-item')
					.addClass('breadcrumb-item active')
					.appendTo(wpfm_bc_dom);
			});
		}

		// Return selected dir id or 0
		_thisWPFM.get_selected_dir = function() {

			return _thisWPFM.dirSelected;
		}

		_thisWPFM.alert = function(message, type) {

			type = undefined ? 'success' : type
			return swal(message, "", type);
		}

		_thisWPFM.block = function() {

			jQuery.blockUI({
				message: wpfm_vars.wpfm_lib_msg,
				css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				},
			});
		}

		_thisWPFM.unblock = function() {

			jQuery.unblockUI();
		}

		// meta_detail 
		_thisWPFM.get_meta_detail_modal = function(file_id, file_meta) {

			var files_meta_model_wrapper = jQuery('<div/>')
				.addClass('modal fade')
				.attr('id', 'file_meta_' + file_id)
				.appendTo('#wpfm-files-wrapper');

			var files_detail_model = jQuery('<div/>')
				.addClass('modal-dialog')
				.appendTo(files_meta_model_wrapper);

			var files_meta_model_content = jQuery('<div/>')
				.addClass('modal-content')
				.appendTo(files_detail_model);

			var files_detail_model_header = jQuery('<div/>')
				.addClass('modal-header')
				.appendTo(files_meta_model_content);

			var files_meta_model_close_btn = jQuery('<button/>')
				.addClass('close')
				.attr({ 'data-dismiss': 'modal' })
				.html("&times;")
				.appendTo(files_detail_model_header);

			var files_detail_model_heading = jQuery('<h4/>')
				.addClass('modal-title')
				.html('Set File Meta')
				.appendTo(files_detail_model_header);

			var files_meta_model_body = jQuery('<div/>')
				.addClass('modal-body')
				.appendTo(files_meta_model_content);

			if (file_meta) {

				var meta_fields = jQuery("<form/>")
					.addClass("form save-meta-frm")
					.html(file_meta)
					.appendTo(files_meta_model_body);
			}
			else {
				var meta_fields = jQuery("<div/>")
					.html("No Meta Found")
					.appendTo(files_meta_model_body);
			};


		}

		// Deleting a file
		_thisWPFM.delete_file = function(file_id) {

			//first hide modal
			var modal_id = '#file_detail_box_' + file_id;

			jQuery(modal_id).hide();
			jQuery('.back-shadow').hide();

			var data = {
				'action': 'wpfm_delete_file',
				'file_id': file_id,
				"wpfm_ajax_nonce": jQuery('#wpfm_ajax_nonce').val()
			}

			jQuery.post(wpfm_vars.ajaxurl, data, function(resp) {

				if (resp.success) {
					_thisWPFM.alert(resp.data, 'success');
					jQuery.event.trigger({
						type: "wpfm_after_item_deleted",
						file_id: file_id,
						time: new Date()
					});
				}
				else {
					_thisWPFM.alert(resp.data, 'error');
				}
			}, 'json');
		}
		// We will add functions to our library here !

		return _thisWPFM;
	}

	// We need that our library is globally accesible, then we save in the window
	if (typeof(window.WPFM) === 'undefined') {
		window.WPFM = wpfm();
	}
})(window); // We send the window variable withing our function


// Then we can call it using
//
