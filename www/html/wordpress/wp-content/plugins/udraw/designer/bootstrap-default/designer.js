jQuery(document).ready(function ($) {
    function updateEditBtn(object) {
        if (object) {
            let coords = RacadDesigner.setElementUnderObject(object, $('[data-udraw="editObject"]'));
            let left = coords.left;
            let top = coords.top;
            if (object.type === 'image' && !RacadDesigner.settings.disableImageReplace) {
                left -= $('[data-udraw="replaceImage"]').outerWidth();
            }
            $('[data-udraw="editObject"]').css({
                left: left,
                top: top
            }).show();
        }
    }

    $('[data-udraw="clear_background"]').click(function () {
        RacadDesigner.canvas.setBackgroundColor(null, RacadDesigner.canvas.renderAll.bind(RacadDesigner.canvas));
        RacadDesigner.canvas.backgroundImage = 0;
        RacadDesigner.canvas.renderAll();
    });

    $('[data-udraw="SaveCloseButton"]').click(function () {
        RacadDesigner.Pages.save(true);
        RacadDesigner.SaveDesignXML('close');
    });

    $('.trigger_upload_image').on('click', function () {
        $('[data-udraw="uploadImage"]').trigger('click');
    });

    RacadDesigner.canvas.on('selection:created', function (e) {
        let object = e.target;
        updateEditBtn(object);
        if (object) {
            $('div.tools').addClass('active');
        }
    });

    RacadDesigner.canvas.on('selection:updated', function (e) {
        let object = e.target;
        updateEditBtn(object);
        if (object) {
            $('div.tools').addClass('active');
            if (object._objects) {
                for (var o = 0; o < object._objects.length; o++) {
                    if (object._objects[o].type === 'i-text' || object._objects[o].type === 'text' || object._objects[o].type === 'textbox' || object._objects[o].type === 'anchor') {
                        $('[data-udraw="textModal"]').show();
                        $('[data-udraw="textModal"]').addClass('active');
                        break;
                    }
                }
            }
        }
    });

    RacadDesigner.canvas.on('object:modified', function (e) {
        let object = e.target;
        updateEditBtn(object);
        $('div.tools').addClass('active');
    });

    RacadDesigner.canvas.on('selection:cleared', function (e) {
        $('[data-udraw="editObject"]').hide();
        $('div.tools').removeClass('active');
    });

    $('[data-udraw="increaseZoomButton"]').click(function () {
        RacadDesigner.changeZoom(0.1);
        jQuery('[data-udraw="zoomDisplay"]').text(Math.round(RacadDesigner.zoom.currentZoom * 100) + "%");
    });

    $('[data-udraw="decreaseZoomButton"]').click(function () {
        RacadDesigner.changeZoom(-0.1);
        jQuery('[data-udraw="zoomDisplay"]').text(Math.round(RacadDesigner.zoom.currentZoom * 100) + "%");
    });

    RacadDesigner.changeZoom = function (quantity) {
		var newZoom = RacadDesigner.zoom.currentZoom + quantity;
		var passCheck = false;
		if (quantity > 0) {
			if (newZoom <= 5.01) {
				passCheck = true;
			}
		} else if (quantity < 0) {
			if (newZoom >= 0.09) {
				passCheck = true;
			}
		}
		if (passCheck) {
			RacadDesigner.zoom.zoomChange = Math.round((newZoom - RacadDesigner.zoom.currentZoom) * 100) / 100;
			RacadDesigner.zoom.currentZoom = newZoom;
            RacadDesigner.zoom.change_zoom_display(RacadDesigner.zoom.currentZoom);
			RacadDesigner.ScaleCanvas(newZoom);
			var zoomPercentage = Math.round((newZoom * 100));
			$('[data-udraw="zoomPercentage"]').html(zoomPercentage + '%');
            $('[data-udraw="zoomLevel"]').val(RacadDesigner.zoom.currentZoom);
		}
    }

    $('[data-udraw="editObject"]').on('click', function () {
        $('div.tools_container').addClass('active');
    });

    $('a.close_tools_btn').on('click', function () {
        $('div.tools_container').removeClass('active');
    });

    $('.toggle_pages_layers').on('click', function () {
        RacadDesigner.canvas.discardActiveObject();
        $('div.tools_container').addClass('active');
    });
    
    $('div.modal').on('shown.bs.modal', function (){
        //Because for some reason, some modals will have some padding-right on show
        $(this).css('padding-right', 0); 
    });
    
    $('.trigger_image_upload').on('click', function(){
        $('[data-udraw="uploadImage"]').trigger('click');
    });

    $('[data-udraw="uDrawBootstrap"]').on('udraw-object-added', function (e) {
        var object = e.object;
        object.center();
        object.centerV();
        object.centerH();
        RacadDesigner.canvas.renderAll(); 
    });

    //Set up the colour pickers; Destroy the old colour picker because pitpik can't run multiple instances at the same time
    $('[data-udraw="designerColourPicker"]').colorPicker('destroy');
    $('[data-udraw="background_colour"]').val(RacadDesigner.canvas.backgroundColor);
    $('[data-udraw="background_colour"], [data-udraw="designerColourPicker"], [data-udraw="text_colour_picker"], [data-udraw="border_colour_picker"]').colorPicker({
        size: 3,
        margin: {top: 0, left: 0},
        renderCallback: function (colors, mode) {
            var data_attr = $($(this)[0].input).attr('data-udraw');
            var _str = 'rgba(' + colors.RND.rgb.r + ',' + colors.RND.rgb.g + ',' + colors.RND.rgb.b + ',' + colors.alpha + ')';
            var activeObject = RacadDesigner.canvas.getActiveObject();
            if (data_attr === 'background_colour') {
                $('[data-udraw="background_colour"]').val(_str);
                RacadDesigner.canvas.setBackgroundColor(_str, RacadDesigner.canvas.renderAll.bind(RacadDesigner.canvas));
            } else if (data_attr === 'designerColourPicker') {
                $('[data-udraw="designerColourPicker"]').val(_str);
                RacadDesigner.SetColor(_str);
            }
                RacadDesigner.ReloadObjects();
        },
        actionCallback: function (e, action) {
            var data_attr = $($(this)[0].input).attr('data-udraw');
            var _colour, _li;
            if (data_attr === 'designerColourPicker') {
                if (action === 'init') {
                    RacadDesigner.temporaryHistoryData = JSON.stringify(RacadDesigner.ToJSON());
                } else if (action ===  'changeXYValue') {
                    var activeObject = RacadDesigner.canvas.getActiveObject();
                    //var activeGroup = RacadDesigner.canvas.getActiveGroup();
                    //if ((activeObject && activeObject.type !== 'image' && activeObject.type !== 'group') || activeGroup) {
                    if ((activeObject && activeObject.type !== 'image' && activeObject.type !== 'group')) {
                        RacadDesigner.pushTempDataIntoArray();
                    }
                    if (activeObject && activeObject.type === 'group' && activeObject.racad_properties.isAdvancedText) {
                        activeObject.opacity = 1;
                    }
                    //Add swatch to list
                    _colour = $('[data-udraw="designerColourPicker"]').val();
                    _li = $('<li></li>').attr('data-colour', _colour).css('background-color', _colour);
                    $('ul.colour_list.custom_colours').each(function(){
                        var list = $(this);
                        var colour_exists = false;
                        $('li', list).each(function(){
                            var this_colour = $(this).attr('data-colour');
                            if (this_colour === _colour) {
                                colour_exists = true;
                                return;
                            }
                        });
                            if (!colour_exists) {
                                list.append(_li);
                                $(_li).css('height', $(_li).width());
                            }
                    });
                }
            } else if (data_attr === 'background_colour') {
                if (action === 'changeXYValue') {
                    //Add swatch to list
                    _colour = $('[data-udraw="background_colour"]').val();
                    _li = $('<li></li>').attr('data-colour', _colour).css('background-color', _colour);
                    $('ul.colour_list.custom_colours').each(function(){
                        var list = $(this);
                        var colour_exists = false;
                        $('li', list).each(function(){
                            var this_colour = $(this).attr('data-colour');
                            if (this_colour === _colour) {
                                colour_exists = true;
                                return;
                            }
                        });
                        if (!colour_exists) {
                            list.append(_li);
                            $(_li).css('height', $(_li).width());
                        }
                    });
                }
            }
        }
    });
});