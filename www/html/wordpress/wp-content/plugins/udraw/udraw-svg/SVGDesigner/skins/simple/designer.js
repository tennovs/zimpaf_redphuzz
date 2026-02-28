jQuery(document).ready(function ($) {
    $('div.menu_item a').on('click', function () {
        var _type = $(this).attr('data-object_type');
        switch (_type) {
            case 'text':
                $('div.enter_text.modal').modal('show');
                break;
            case 'image':
                $('[data-udrawSVG="add_image_modal"]').modal('show');
                break;
        }
    });

    $('[data-udraw="image_library"]').on('click', function () {
        $('[data-udrawSVG="stock_image_modal"]').modal('show');
    });
    $('[data-udraw="social_media"]').on('click', function () {
        $('[data-udraw="social_media_modal"]').modal('show');
    });

    $('[data-udraw="social_media_type"]').on('change', function () {
        var _type = $(this).val();
        $('div.social_media_container').each(function () {
            $(this).removeClass('active');
        });
        $(`div.social_media_container[data-social_media="${_type}"]`).addClass('active');
    });

    $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_object_added udraw_svg_object_clicked', function (e) {
        $('div.object_tools').each(function () {
            $(this).addClass('hidden');
        });

        $(`div.object_tools[data-object_type="${e.object.type}"]`).removeClass('hidden');

        if (e.object.type === 'text') {
            if (e.type === 'udraw_svg_object_added') {
                var _ul = $('div.enter_text ul.text_objects_list');
                var _li = RacadSVGDesigner.Text.build_text_item(e.object);
                _ul.append(_li);
            }

            $('[data-udrawSVG="text_area"]').val(e.object.text());
        }
    });

    $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_object_removed', function () {
        $('div.object_tools').each(function () {
            $(this).addClass('hidden');
        });
    });

    $('div.enter_text').on('show.bs.modal', function () {
        var _ul = $('div.enter_text ul.text_objects_list');
        _ul.empty();
        $('#svg_canvas text').each(function () {
            var object = RacadSVGDesigner.get_object($(this).attr('id'));
            var _li = RacadSVGDesigner.Text.build_text_item(object);
            _ul.append(_li);
        });
    });

    RacadSVGDesigner.Text.build_text_item = function (object) {
        if (object.type === 'text') {
            var placeholder = "Enter your text";
            var val = object.text();
            var _input = $('<input />').attr({
                type: 'text',
                'data-object_id': object.attr('id'),
                placeholder: placeholder
            }).val(val).on('input propertychange', function () {
                var id = $(this).attr('data-object_id');
                var object = RacadSVGDesigner.get_object(id);
                if (object.type === 'text') {
                    object.text($(this).val());
                }

                if (RacadSVGDesigner.settings.objectClicked === object.attr('id')) {
                    $('[data-udrawSVG="text_area"]').val(object.text());
                }
            });
            var _li = $('<li></li>').append(_input);
            return _li;
        }
    }
    
    $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_scaled_to_fit', function(){
        $('[data-udrawsvg="SVGDesigner"] div.canvas_container').css('height', '');
    })
});