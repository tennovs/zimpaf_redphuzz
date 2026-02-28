(function($){
    var upload_file_changed = false;
    function save_template () {
        var design_path, preview_path;
        if (upload_file_changed) {
            RacadSVGDesigner.Save.file(function(response){
                design_path = response.output_path + response.document_name;
                preview_path = response.output_path + response.preview_image;
                __save(design_path, preview_path);
            }, true);
        } else {
            RacadSVGDesigner.Pages.process_data(RacadSVGDesigner.settings.current_page, function(svg, index, preview_data){
                RacadSVGDesigner.Pages.save(svg, index, preview_data, function(){
                    design_path = RacadSVGDesigner.settings.design_file;
                    preview_path = RacadSVGDesigner.Pages.list[0].preview_url;
                    __save(design_path, preview_path);
                });
            });
        }
    }
    function __save (design_path, preview_path) {
        var summary = RacadSVGDesigner.Load.loaded_summary;
        var template_name = $('[data-udrawsvg_template="svg_template_name"]').val();
        if (template_name.length === 0) {
            var _save_btn = $('[data-udrawsvg_template="save"]');
            _save_btn.removeClass('disabled');
            $('i', _save_btn).addClass('hidden');
            $('span', _save_btn).removeClass('hidden');
            window.alert(i18n.t('template_name_required'));
            return;
        }
        $.ajax({
            url: RacadSVGDesigner.settings.handler_file,
            type: 'POST',
            contentType: "application/x-www-form-urlencoded",
            dataType: "json",
            data: {
                action: 'udraw_svg_save_template',
                name: template_name,
                design_path: design_path,
                preview: preview_path,
                access_key: RacadSVGDesigner.settings.access_key,
                design_summary: summary
            },
            success: function (result) {
                if (result.success) {
                    $('[data-udrawsvg_template="add_template_modal"]').modal('hide');
                    window.location.href = window.location.origin + window.location.pathname + '?page=udraw_svg&action=' + result.type;
                } else {
                    window.alert(result.message);
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
    function load_template_data(data) {
        var design_file = RacadSVGDesigner.settings.design_file = data.design_path;
        var doc_name = RacadSVGDesigner.settings.document_name = RacadSVGDesigner.settings.design_file.replace(RacadSVGDesigner.settings.output_path, '');
        RacadSVGDesigner.settings.access_key = data.access_key;
        
        //Check for older JSON files. Throw alert if detected.
        if (doc_name.substring([doc_name.length - 4]) === 'json' && design_file.indexOf('_templates_') < 0) {
            window.alert('Please re-upload the SVG file. Design files created with the older version of the SVGDesigner are now incompatible.');
        }
        
        var summary = RacadSVGDesigner.Load.loaded_summary = data.design_summary;
        var rounded_ratio = Math.round(summary.aspect_ratio * 100) / 100;
        $('[data-udrawsvg_template="svg_template_name"]').val(data.name);
        $('[data-udrawsvg_template="template_preview"]').attr('src', data.preview);
        if (typeof summary.image_placeholders !== 'undefined') {
            $('[data-udrawsvg_template="image_placeholder_count"]').text(summary.image_placeholders);
        }
        if (typeof summary.text_count !== 'undefined') {
            $('[data-udrawsvg_template="text_count"]').text(summary.text_count);
        }
        if (!isNaN(rounded_ratio)) {
            $('[data-udrawsvg_template="aspect_ratio"]').text(rounded_ratio);
        }
        $('[data-udrawsvg_template="template_width"]').text(summary.width);
        $('[data-udrawsvg_template="template_height"]').text(summary.height);
        if (typeof summary.pages === 'undefined') {
            $('[data-udrawsvg_template="pages_count"]').text(summary.pages);
        }
        
    }
    function clear_inputs() {
        //Clear all previous inputs
        $('[data-udrawsvg_template="svg_template_name"]').val('');
        $('[data-udrawsvg_template="upload_svg"]').val('');
        $('[data-udrawsvg_template="template_preview"]').attr('src', '');
        $('[data-udrawsvg_template="summary_table"] span').each(function(){
            $(this).html('');
        });
        $('[data-udrawsvg_template="pdf_file_upload_trigger"]').removeClass('hidden');
        $('div.edit_template_div').addClass('hidden');
        $('div.upload_file_div').removeClass('hidden');
        $('div.template_summary').addClass('hidden');
        $('[data-udrawsvg_template="edit_template"]').addClass('hidden');
        
        var _save_btn = $('[data-udrawsvg_template="save"]');
        _save_btn.removeClass('disabled');
        $('i', _save_btn).addClass('hidden');
        $('span', _save_btn).removeClass('hidden');
    }
    $(document).ready(function(){
        $('[data-udrawsvg_template="add_template"]').on('click', function(){
            RacadSVGDesigner.settings.design_file = '';
            RacadSVGDesigner.settings.document_name = '';
            RacadSVGDesigner.settings.access_key = '';
            $('[data-udrawsvg_template="add_template_modal_header"]').text(i18n.t('add_template'));
            $('[data-udrawsvg_template="file_upload_trigger"]').text(i18n.t('upload_svg'));
            clear_inputs();
            $('[data-udrawsvg_template="add_template_modal"]').modal('show');
        });
        $('[data-udrawsvg_template="save"]').on('click', function(){
            //Spinner
            $('i', $(this)).removeClass('hidden');
            $('span', $(this)).addClass('hidden');
            $(this).addClass('disabled');
            if (!RacadSVGDesigner.settings.access_key) {
                if (!$('[data-udrawsvg_template="upload_svg"]').val() && RacadSVGDesigner.settings.design_file.length === 0) {
                    //Not an existing template and did not upload a file.
                    //Throw an error
                    window.alert(i18n.t('no_file_uploaded'));
                    $(this).removeClass('disabled');
                    $('i', this).addClass('hidden');
                    $('span', this).removeClass('hidden');
                    return;
                }
            }
            //Otherwise continue 
            save_template();
        });
        $('[data-udrawsvg_template="blank"]').on('click', function(){
            RacadSVGDesigner.Pages.list = new Array();
            $('[data-udrawsvg="page_list"]').empty();
            if (typeof RacadSVGDesigner.canvas === 'object') {
                RacadSVGDesigner.canvas.remove();
            }
            
            var session_id = RacadSVGDesigner.settings.session_id = window.new_session_id;
            var _path = RacadSVGDesigner.settings.output_path;
            RacadSVGDesigner.settings.design_file = `${_path}/${session_id}/${session_id}.json`;
            
            RacadSVGDesigner.Pages.create(function () {
                //Build Font Size select
                RacadSVGDesigner.Text.build_font_size_select();
                //Build zoom dropdown menu
                RacadSVGDesigner.Zoom.update_max_zoom_step();
                //Build the layers list
                if (RacadSVGDesigner.settings.display_layers) {
                    RacadSVGDesigner.Layers.reset();
                }
            
                $('[data-udrawsvg_template="edit_template_modal"]').modal('show');
                $('[data-udrawsvg_template="edit_template_modal"]').css({
                    'z-index': '',
                    top: '',
                    left: ''
                });
            });
        });
        $('[data-udrawsvg_template="file_upload_trigger"]').on('click',function(){
            $('[data-udrawsvg_template="upload_svg"]').trigger('click');
        });
        $('[data-udrawsvg_template="pdf_file_upload_trigger"]').on('click',function(){
            $('[data-udrawsvg_template="upload_pdf"]').trigger('click');
        });
        $('[data-udrawsvg_template="upload_svg"]').on('change',function(e){
            upload_file_changed = true;
            var files = e.target.files;
            var reader = new FileReader();
            reader.onload = function(event) {
                var contents = event.target.result;
                RacadSVGDesigner.Load.contents = contents;
                //Temporarily set it to regular mode so we can get the proper preview and placeholder count.
                RacadSVGDesigner.settings.mode = '';
                RacadSVGDesigner.Load.from_upload(contents, function(){
                    var svg = RacadSVGDesigner.canvas.svg();
                    RacadSVGDesigner.svg_to_png(svg, function (imageData) {
                    //RacadSVGDesigner.get_canvas_preview(function(imageData){
                        $('[data-udrawsvg_template="template_preview"]').attr('src', imageData);
                        if (typeof RacadSVGDesigner.Load.loaded_summary === 'object') {
                            var summary = RacadSVGDesigner.Load.loaded_summary;
                            var rounded_ratio = Math.round(summary.aspect_ratio * 100) / 100;
                            if (typeof summary.image_placeholders !== 'undefined') {
                                $('[data-udrawsvg_template="image_placeholder_count"]').text(summary.image_placeholders);
                            }
                            if (typeof summary.text_count !== 'undefined') {
                                $('[data-udrawsvg_template="text_count"]').text(summary.text_count);
                            }
                            if (!isNaN(rounded_ratio)) {
                                $('[data-udrawsvg_template="aspect_ratio"]').text(rounded_ratio);
                            }
                            $('[data-udrawsvg_template="template_width"]').text(summary.width);
                            $('[data-udrawsvg_template="template_height"]').text(summary.height);
                            var pages_count = summary.pages;
                            if (typeof summary.pages === 'undefined') {
                                pages_count = RacadSVGDesigner.Pages.list.length;
                            }
                            $('[data-udrawsvg_template="pages_count"]').text(pages_count);
                            
                            $('div.upload_file_div').addClass('hidden');
                            $('div.template_summary').removeClass('hidden');
                        }
                    });
                });
            }
            reader.readAsText(files[0]);
        });
        $('[data-udrawsvg_template="upload_pdf"]').fileupload({
            url: RacadSVGDesigner.settings.handler_file,
            autoUpload: true,
            sequentialUploads: true,
            chooseText: 'Apply',
            formData: {
                action: RacadSVGDesigner.handler_actions.upload_pdf_template
            },
            submit: function (e, data) {
                $('div.spinner_div').removeClass('hidden');
                $('div.spinner_div span').html('Uploading file...')
                $('div.template_preview').addClass('hidden');
                $('div.upload_file_div').addClass('hidden');
                $('[data-udrawsvg_template="save"]').addClass('disabled');
            },
            done: function (e, data) {
                //File is done converting to SVG
                //Now convert SVG to PNG
                var design_file = JSON.parse(data.result).design_file;
                $('div.spinner_div span').html('Fetching thumbnail images...');
                $.ajax({
                    url: RacadSVGDesigner.settings.handler_file,
                    type: 'POST',
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    data: {
                        action: 'udraw_svg_get_pngs',
                        design_file: design_file
                    },
                    success: function (result) {
                        //Load in the first 
                        var design_file = JSON.parse(data.result).design_file;
                        RacadSVGDesigner.settings.design_file = design_file;
                        $('div.edit_template_div').removeClass('hidden');
                        $('[data-udrawsvg_template="edit_template"]').removeClass('hidden');
                        $('div.spinner_div').addClass('hidden');
                        $('[data-udrawsvg_template="save"]').removeClass('disabled');
                        RacadSVGDesigner.Load.json_file(RacadSVGDesigner.settings.design_file, function(){
                            if (typeof RacadSVGDesigner.Pages.list === 'object' && RacadSVGDesigner.Pages.list.length > 0) {
                                var preview_url = '';
                                for (var prop in RacadSVGDesigner.Pages.list[0]) {
                                    if (prop.indexOf('preview') !== -1) {
                                        preview_url = RacadSVGDesigner.Pages.list[0][prop];
                                    }
                                }
                                $('[data-udrawsvg_template="template_preview"]').attr('src', preview_url);
                                $('div.template_preview').removeClass('hidden');
                            }
                        });
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            },
            progress: function (e, data) {
                let progress = Math.round((data.loaded / data.total) * 100);
                if (progress === 100) {
                    $('div.spinner_div span').html('Converting file to SVG...')
                }
            }
        });
        $('[data-action="edit"]').on('click', function(){
            var id = $(this).attr('data-template_id');
            //Find the template with this id
            $.ajax({
                url: RacadSVGDesigner.settings.handler_file,
                type: 'POST',
                contentType: "application/x-www-form-urlencoded",
                dataType: "json",
                data: {
                    action: 'udraw_svg_get_template',
                    template_id: id
                },
                success: function (result) {
                    clear_inputs();
                    if (result.design_path.substring(result.design_path.length - 4) === 'json') {
                        $('div.upload_file_div').addClass('hidden');
                        $('div.edit_template_div').removeClass('hidden');
                        $('[data-udrawsvg_template="edit_template"]').removeClass('hidden');
                    } else {
                        $('[data-udrawsvg_template="add_template_modal_header"]').text(i18n.t('edit_template'));
                        $('[data-udrawsvg_template="file_upload_trigger"]').text(i18n.t('change_svg_file'));
                        $('[data-udrawsvg_template="pdf_file_upload_trigger"]').addClass('hidden');
                    }
                    load_template_data(result);
                    $('div.edit_template_div').removeClass('hidden');
                    $('div.template_summary').removeClass('hidden');
                    $('[data-udrawsvg_template="add_template_modal"]').modal('show');
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
        $('[data-action="delete"]').on('click', function(){
            var access_key = $(this).attr('data-access_key');
            var template_id = $(this).attr('data-template_id');
            if (window.confirm('Delete this template #' + template_id + '?')) {
                $.ajax({
                    url: RacadSVGDesigner.settings.handler_file,
                    type: 'POST',
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    data: {
                        action: 'udraw_svg_delete_template',
                        access_key: access_key
                    },
                    success: function (result) {
                        if (result) {
                            window.location.href = window.location.origin + window.location.pathname + '?page=udraw_svg&action=deleted';
                        } else {
                            window.alert('Error deleting template. Please try again or contact support.');
                        }
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }
        });
        $('button.toggle_instructions').on('click', function(){
            if ($('div.instructions').hasClass('active')) {
                $('div.instructions').removeClass('active');
            } else {
                $('div.instructions').addClass('active');
            }
        });
        $('[data-udrawsvg="admin_save"]').on('click', function(){
            RacadSVGDesigner.Save.file(function(data){
                if (typeof RacadSVGDesigner.Pages.list === 'object' && RacadSVGDesigner.Pages.list.length > 0) {
                    var preview_url = '';
                    for (var prop in RacadSVGDesigner.Pages.list[0]) {
                        if (prop.indexOf('preview') !== -1) {
                            preview_url = RacadSVGDesigner.Pages.list[0][prop];
                        }
                    }
                    var date = new Date();
                    $('[data-udrawsvg_template="template_preview"]').attr('src', `${preview_url}?t=${date.getTime()}`);
                }
                $('[data-udrawsvg_template="edit_template_modal"]').css({
                    'z-index': -1,
                    top: '-9999px',
                    left: '-9999px'
                });
                $('[data-udrawsvg_template="edit_template_modal"] [data-dismiss="modal"]').trigger('click');
                
                
            });
        });
        $('[data-udrawsvg_template="edit_template"]').on('click', function(){
            $('[data-udrawsvg_template="edit_template_modal"]').modal('show');
            RacadSVGDesigner.Load.design_file(function(){
                $('[data-udrawsvg_template="edit_template_modal"]').css({
                    'z-index': '',
                    top: '',
                    left: ''
                });
            }, function(){
                if (RacadSVGDesigner.settings.design_file.length === 0 && RacadSVGDesigner.Load.contents.length > 0) {
                    RacadSVGDesigner.settings.mode = 'admin';
                    RacadSVGDesigner.Load.from_upload(RacadSVGDesigner.Load.contents, function(){

                    });
                }
            });
        });
        $('[data-udrawsvg_template="edit_template_modal"]').modal('show');
        $('div.admin_container').removeClass('hidden');
    });
})(window.jQuery);