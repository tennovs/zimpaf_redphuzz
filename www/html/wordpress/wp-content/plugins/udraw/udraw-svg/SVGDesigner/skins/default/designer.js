(function($) {
    $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_design_loaded', function(event){
        var design_file = RacadSVGDesigner.settings.design_file;
        if (design_file.substring(design_file.length - 4) === 'json') {
            $('div.editing_tips_div .next_page_tips').removeClass('hidden');
            RacadSVGDesigner.Zoom.calculate_canvas_container_height();
        }
        
        $(window).on('resize', function (){
            clearTimeout($.data(this, 'canvas_resize_timer'));
            $.data(this, 'canvas_resize_timer', setTimeout(function(){
                if ($('div.canvas_container').is(':visible')) {
                    if (typeof RacadSVGDesigner.overwrite_center_canvas === 'function') {
                        RacadSVGDesigner.overwrite_center_canvas();
                    } else {
                        //Resize replace image container
                        var _height = $('[data-udrawsvg="image_replace_modal"] div.modal-body').height() - ($('[data-udrawsvg="image_replace_list"]').height() + $('div.image_count_container').height());
                        $('div.replace_image_browse_container').height(_height * 0.9);

                        $('[data-udrawSVG="SVGDesigner"]').trigger({
                            type: 'udraw_svg_design_centered'
                        });
                    }
                }
            }, 250));
        }).trigger('resize');
        $('div.button_group').each(function(){
            var _count = $(this).children().length;
            $('button', this).each(function(){
                $(this).css('width', 'calc(90% / '+ _count +')');
            });
        });
        if (RacadSVGDesigner.Pages.list.length <= 1 && RacadSVGDesigner.settings.mode === '' && !RacadSVGDesigner.settings.display_layers) {
            if (use_edit_text_modal) {
                $('div.sidebar.has_tabs').hide();
            }
        }
        
        if (!$('div.sidebar.has_tabs').is(':visible') && $('div.sidebar:not(.has_tabs)').html().indexOf('<') === -1) {
            $('div.sidebar:not(.has_tabs)').hide();
            $('[data-udrawSVG="SVGDesigner"] div.main_body').css('width', '100%');
        } else if ($('div.sidebar:not(.has_tabs)').html().indexOf('<') === -1 && $('div.sidebar.has_tabs').is(':visible')) {
            $('[data-udrawSVG="SVGDesigner"] div.main_body').css('width', '70%');
        } else if ($('div.sidebar:not(.has_tabs)').html().indexOf('<') >= 0 && !$('div.sidebar.has_tabs').is(':visible')) {
            $('[data-udrawSVG="SVGDesigner"] div.main_body').css('width', '85%');
        }
        
        RacadSVGDesigner.Zoom.zoom_canvas(RacadSVGDesigner.Zoom.current_zoom);
    });
    
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
    
    $('[data-udrawSVG="edit_text_modal"]').on('shown.bs.modal', function(){
        if (!use_edit_text_modal) {
            $('[data-udrawSVG="edit_text_tab"]').removeClass('hidden');
            $('[data-udrawSVG="edit_text_tab"] > a').trigger('click');
        }
    });
    $('[data-udrawSVG="edit_text_modal"]').on('hidden.bs.modal', function(){
        if (!use_edit_text_modal) {
            $('[data-udrawSVG="edit_text_tab"]').addClass('hidden');
            $('[data-udrawSVG="pages_tab"] > a').trigger('click');
        }
    });
    $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_object_added', function(e){
        if (!use_edit_text_modal) {
            if (typeof e.object === 'object') {
                var _id = e.object.id();
                if (typeof _id === 'string' && _id.length > 0) {
                    RacadSVGDesigner.settings.objectClicked = _id;
                    if (e.object.type === 'text') {
                        $('[data-udrawSVG="edit_text_modal"]').modal('show');
                    } else {
                        $('[data-udrawSVG="edit_text_modal"]').modal('hide');
                    }
                }
            }
        }
    });
    $('[data-udrawSVG="SVGDesigner"]').on('udraw_SVG_object_dragmove', function(e){
        if (!use_edit_text_modal) {
            if (typeof e.object === 'object') {
                var _id = e.object.id();
                if (typeof _id === 'string' && _id.length > 0) {
                    if (e.object.type === 'text') {
                        RacadSVGDesigner.settings.objectClicked = _id;
                        $('[data-udrawSVG="edit_text_modal"]').modal('show');
                    } else {
                        $('[data-udrawSVG="edit_text_modal"]').modal('hide');
                    }
                }
            }
        }
    });
    
    $('div.sidebar ul.toollist > li:first-child button').on('click', function () {
        RacadSVGDesigner.deselect_other_objects();
    });
})(window.jQuery)