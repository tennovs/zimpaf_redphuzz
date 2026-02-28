jQuery(document).ready(function($){
    var manual_tab_fix = function (tab, id) {
        // In case the tabs aren't working
        if (!$(`[data-udrawSVG="${tab}"] > a`).hasClass('active')) {
            $('div.sidebar.has_tabs ul.nav.nav-tabs li > a').each(function(){
                if ($(this).attr('data-udrawSVG') === tab) {
                     $(`[data-udrawSVG="${tab}"] > a`).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
            $('div.sidebar.has_tabs div.tab-content div.tab-pane').each(function(){
                if ($(this).attr('id') === id) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        }
    }
    
    $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_object_added', function (e) {
        if (typeof e.object === 'object') {
            var _id = e.object.id();
            if (typeof _id === 'string' && _id.length > 0) {
                RacadSVGDesigner.settings.objectClicked = _id;
                if (e.object.type === 'text') {
                    RacadSVGDesigner.Text.display_editor();
                } else {
                    RacadSVGDesigner.Text.hide_editor();
                }

                if (e.object.type === 'image') {
                    RacadSVGDesigner.settings.objectClicked = _id;
                    RacadSVGDesigner.Images.display_actions();
                } else {
                    RacadSVGDesigner.Images.hide_actions();
                }
            }
        }
    });
    $('[data-udrawSVG="SVGDesigner"]').on('udraw_SVG_object_dragmove', function (e) {
        if (typeof e.object === 'object') {
            var _id = e.object.id();
            if (typeof _id === 'string' && _id.length > 0) {
                if (e.object.type === 'text') {
                    RacadSVGDesigner.settings.objectClicked = _id;
                    RacadSVGDesigner.Text.on_text_click(e.object);
                    RacadSVGDesigner.Text.display_editor();
                } else {
                    RacadSVGDesigner.Text.hide_editor();
                }

                if (e.object.type === 'image') {
                    RacadSVGDesigner.settings.objectClicked = _id;
                    RacadSVGDesigner.Images.on_image_click(e.object);
                    RacadSVGDesigner.Images.display_actions();
                } else {
                    RacadSVGDesigner.Images.hide_actions();
                }
            }
        }
    });
    $('div.sidebar ul.toollist > li:first-child button').on('click', function () {
        RacadSVGDesigner.deselect_other_objects();
    });
    RacadSVGDesigner.Text.display_editor = function () {
        $('[data-udrawSVG="edit_text_tab"]').removeClass('hidden');
        $('[data-udrawSVG="edit_text_tab"] a').trigger('click');
        
        setTimeout(function(){
            manual_tab_fix('edit_text_tab', 'edit_text');
        }, 50);
    };
    RacadSVGDesigner.Text.hide_editor = function () {
        $('[data-udrawSVG="edit_text_tab"]').addClass('hidden');
        $('[data-udrawSVG="pages_tab"] a').trigger('click');
        
        setTimeout(function(){
            manual_tab_fix('pages_tab', 'pages');
        }, 50)
    };

    RacadSVGDesigner.Images.display_actions = function () {
        $('[data-udrawSVG="image_action_tab"]').removeClass('hidden');
        $('[data-udrawSVG="image_action_tab"] a').trigger('click');
        
        setTimeout(function(){
            manual_tab_fix('image_action_tab', 'image_action')
        }, 50);
    };

    RacadSVGDesigner.Images.hide_actions = function () {
        $('[data-udrawSVG="image_action_tab"]').addClass('hidden');
        $('[data-udrawSVG="pages_tab"] a').trigger('click');
        
        setTimeout(function(){
            manual_tab_fix('pages_tab', 'pages');
        }, 50);
    };

    $('ul.toollist a').on('click', function(){
        RacadSVGDesigner.settings.objectClicked = undefined;
    });
    
    $('div.sidebar.has_tabs ul.nav.nav-tabs li a').on('click', function(){
        if (!$(this).hasClass('active')) {
            var id = $(this).attr('aria-controls');
            manual_tab_fix(`${id}_tab`, id);
        }
    });
});