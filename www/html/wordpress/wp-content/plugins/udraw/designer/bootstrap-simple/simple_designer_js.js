jQuery(document).ready(function ($) {
    $('li.trigger_upload_image').on('click', function () {
        $('[data-udraw="uploadImage"]').trigger('click');
    });
    $('div.footer div.menu_item a').on('click', function () {
        var _type = $(this).attr('data-object_type');
        switch (_type) {
            case 'text':
                RacadDesigner.Text.build_text_list();
                $('div.enter_text.modal').modal('show');
                break;
            case 'image':
                $('div.add_image.modal').modal('show');
                break;
            case 'misc':
                $('div.add_misc.modal').modal('show');
                break;
        }
    });

    RacadDesigner.canvas.on('object:selected', function (e) {
        var object = e.target;
        $('div.footer, div.objects_menu_container').addClass('hidden')
        $('div.objects_menu_container').removeClass('active');
        $('div.objects_menu_container').each(function () {
            $(this).removeClass('active');
        });
        $('div.object_tools').addClass('hidden');
        if (object.is_text()) {
            $('div.object_tools[data-object_type="text"]').removeClass('hidden');
            $('div.object_tools[data-object_type="text"] div.curved_text').addClass('hidden');
            if (object.curvedText) {
                $('div.object_tools[data-object_type="text"] div.curved_text').removeClass('hidden');
            }
        } else if (object.type === 'image') {
            $('div.object_tools[data-object_type="image"]').removeClass('hidden');
            if (object.racad_properties.isPlaceHolder) {
                $('div.filter_row, [data-udraw="cropButton"]').addClass('hidden');
            } else {
                $('div.filter_row, [data-udraw="cropButton"]').removeClass('hidden');
            }
        } else if (object.is_shape()) {
            $('div.object_tools[data-object_type="shape"]').removeClass('hidden');
        }
    });
    RacadDesigner.canvas.on('selection:cleared', function (e) {
        $('div.footer, div.objects_menu_container').removeClass('hidden')
        $('div.object_tools').addClass('hidden');
        $('ul.objects_menu').each(function () {
            $(this).removeClass('active');
        });
    });

    $('[data-udraw="image_library"]').on('click', function () {
        $('[data-udraw="stock_image_modal"]').modal('show');
    });
    $('[data-udraw="image_library"]').on('click', function () {
        $('[data-udraw="stock_image_modal"]').modal('show');
    });
    $('[data-udraw="social_media"]').on('click', function () {
        $('[data-udraw="social_media_modal"]').modal('show');
    });
    $('#linkedTemplates').on('click', function () {
        $('#linked-templates-modal').modal('show');
        $('#linked-templates-modal').addClass('active');
    });

    $('[data-udraw="social_media_type"]').on('change', function () {
        var _type = $(this).val();
        $('div.social_media_container').each(function () {
            $(this).removeClass('active');
        });
        $(`div.social_media_container[data-social_media="${_type}"]`).addClass('active');
    });
    $('[data-udraw="uDrawBootstrap"]').on('udraw-loaded', function () {
        RacadDesigner.init_colour_picker(3, -125, -350);
    });
    $('a.decrease_curve_btn').on('click', function () {
        var active_object = RacadDesigner.canvas.getActiveObject();
        if (active_object && active_object.is_text() && active_object.curvedText) {
            active_object.radius += 10
            RacadDesigner.canvas.renderAll();
        }
    });
    $('a.increase_curve_btn').on('click', function () {
        var active_object = RacadDesigner.canvas.getActiveObject();
        if (active_object && active_object.is_text() && active_object.curvedText) {
            active_object.radius -= 10
            RacadDesigner.canvas.renderAll();
        }
    });
    $('a.reverse_curve_btn').on('click', function () {
        var active_object = RacadDesigner.canvas.getActiveObject();
        if (active_object && active_object.is_text() && active_object.curvedText) {
            RacadDesigner.canvas.getActiveObject().reverse = !RacadDesigner.canvas.getActiveObject().reverse
            RacadDesigner.canvas.renderAll();
        }
    });
    $('a.duplicate_btn').on('click', function () {
        RacadDesigner.copyObject();
        RacadDesigner.pasteObject();
    });
    $('a#reset_layers').on('click', function () {
        RacadDesigner.canvas.discardActiveObject().renderAll();
    });

    $('[data-udraw="uDrawBootstrap"]').on('udraw-object-added', function (e) {
        var object = e.object;
        if (object.is_text()) {
            RacadDesigner.Text.UpdateCurrentText('Enter Your Text', object);
            $('[data-udraw="textArea"]').val('Enter Your Text');
            var _li = RacadDesigner.Text.build_text_list_item(object);
            if (_li) {
                $('div.enter_text ul.text_objects_list').append(_li);
            }
            $('.modal.enter_text').hide();
        }
        
        if (object.type === 'image') { 
            $('div.add_image.modal').modal('hide');
        }
    });
    
    //After retrieving linked templates
    $('[data-udraw="uDrawBootstrap"]').on('udraw-retrieved-linked-templates', function(event){
        if (event.templates.length === 0) {
            $('div.footer div.menu_item a#linkedTemplates').parent().hide();
        } else {
            $('div.footer div.menu_item a#linkedTemplates').parent().show();
        }
    });
    
    $('[data-udraw="uDrawBootstrap"]').on('udraw-loaded-design', function() {
        $('#linked-templates-modal').modal('hide');
        $('#linked-templates-modal').removeClass('active');
    });


    RacadDesigner.Text.build_text_list = function () {
        var _ul = $('div.enter_text ul.text_objects_list');
        _ul.empty();
        RacadDesigner.canvas.getObjects().forEach(function (object) {
            var _li = RacadDesigner.Text.build_text_list_item(object);
            if (_li) {
                _ul.append(_li);
            }
        });
    }
    RacadDesigner.Text.build_text_list_item = function (object) {
        if (object.is_text()) {
            var placeholder = "Enter your text";
            var val = object.text;
            if (object.racad_properties.isLabelled) {
                placeholder = object.racad_properties.isLabelled;
            }
            var _input = $('<input />').attr({
                type: 'text',
                'data-object_id': object.racad_properties._id,
                placeholder: placeholder
            }).val(val).on('input propertychange', function () {
                var id = $(this).attr('data-object_id');
                var object = RacadDesigner.GetObjectById(id);
                RacadDesigner.Text.UpdateCurrentText($(this).val(), object);
            });
            var _li = $('<li></li>').append(_input);
            return _li;
        }
    }
    
    var resize_canvas = function () {
        if ($('[data-udraw="canvasContainer"]').is(':visible') && !RacadDesigner.settings.canvasScaling
                && !RacadDesigner.settings.switching_pages && !RacadDesigner.settings.designerBusy) {
            var _width = RacadDesigner.documentSize.width;
            var _height = RacadDesigner.documentSize.height;
            var _innerWidth = $('[data-udraw="canvasContainer"]').innerWidth();
            var _innerHeight = $('[data-udraw="canvasContainer"]').innerHeight();

            var ratio = Math.min((_innerWidth / _width), (_innerHeight / _height)); //Get the smaller ratio so the canvas will fit inside the container
            RacadDesigner.ForceZoom(ratio * 0.9);
        } else {
            setTimeout(function(){
                resize_canvas();
            }, 500);
        }
    };

    $('div.add_image ul.objects_menu li').on('click', function () {
        $('div.add_image').modal('hide');
    });
    $('div.add_misc ul.objects_menu li').on('click', function () {
        $('div.add_misc').modal('hide');
    });
    $('[data-udraw="uDrawBootstrap"]').on('udraw-loaded-design udraw-switched-page', function () {
        resize_canvas();
    });
    $(window).on('resize', function () {
        resize_canvas();
    });
    $('a.layer_btn').on('click', function(){
        var _type = $(this).attr('data-layer_type');
        var active_object = RacadDesigner.canvas.getActiveObject();
        if (active_object) {
            if (_type === 'forward') {
                active_object.bringForward();
            } else if (_type === 'backwards') {
                active_object.sendBackwards();
            }
       }
    });
    
    $('.replace_image').on('click', function(){
        if (RacadDesigner.canvas.getActiveObject()) {
            var activeObject = RacadDesigner.canvas.getActiveObject();
            if (activeObject.type === 'image' || activeObject.racad_properties.clipLocked) {
                RacadDesigner.replaceImageObjectId = activeObject.racad_properties._id;
                $('div.add_image.modal').modal('show');
            }
        }
    });
    
    $('[data-udraw="uDrawBootstrap"]').on('udraw-retrieved-linked-templates', function(e){
        if (e.templates.length === 0) {
            $('a#linkedTemplates').addClass("hidden");
        }
    });
});