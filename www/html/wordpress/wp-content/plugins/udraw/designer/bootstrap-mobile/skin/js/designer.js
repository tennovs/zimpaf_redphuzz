jQuery(document).ready(function($){

    RacadDesigner.canvas.on({
        'selection:created': objectSelection,
        'selection:updated': objectSelection
    });

    function objectSelection(object) {
        var activeObject = object.target;
        if (activeObject && activeObject.hasOwnProperty('racad_properties') && activeObject.racad_properties.isLabelled){
            $('[data-udraw="textArea"]').hide();
        } else {
            $('[data-udraw="textArea"]').show();
        }
        jQuery('[data-udraw="designerColourContainer"]').show();
        if (activeObject.type === 'image') {
            jQuery('[data-udraw="replaceImage"]').show();
            jQuery('[data-udraw="designerColourContainer"]').hide();
        } else {
            jQuery('[data-udraw="replaceImage"]').hide();
        }
        if (activeObject.type === 'path-group') {
            jQuery('[data-udraw="designerColourContainer"]').hide();
        }
        if (activeObject.is_text()) {
            jQuery('li.element-btn a.text').trigger('click');
            jQuery('.float-toolbar .text-items').addClass('active');
        } else {
            jQuery('li.element-btn a.layers').trigger('click');
            jQuery('.float-toolbar .text-items').removeClass('active');
        }
        
        jQuery('.float-toolbar').addClass('active');
    }

    RacadDesigner.canvas.on("object:added", function (object) {
        jQuery('.float-toolbar').addClass('active');
        var activeObject = object.target;
        if (activeObject.is_text()) {
            jQuery('li.element-btn a.text').trigger('click');
            jQuery('.float-toolbar .text-items').addClass('active');
        } else {
            jQuery('li.element-btn a.layers').trigger('click');
            jQuery('.float-toolbar .text-items').removeClass('active');
        }
    });
    
    RacadDesigner.canvas.on("selection:cleared", function (object) {
        jQuery('.float-toolbar').removeClass('active');
    });

    RacadDesigner.triggerImageUpload = function () {
		jQuery('[data-udraw="uploadImage"]').trigger('click');
	}

    var oldupdateuielements = RacadDesigner.UpdateUIElements;
    RacadDesigner.UpdateUIElements = function () {
        oldupdateuielements();
        var activeObject = RacadDesigner.canvas.getActiveObject();
        if (activeObject && activeObject.is_text()) {
            RacadDesigner.Text.UpdateFontStylesUI();
        }
    }

    jQuery('.image-container a.local').click(function() {
        jQuery('.inner-image-container[data-udraw="localImageList"]').show();
    });
    
    jQuery('[data-udraw="duplicateBtn"]').click(function () {
        RacadDesigner.copyObject();
        RacadDesigner.pasteObject();
    });
    jQuery('[data-udraw="toolboxClose"]').click(function () {
        jQuery(this).parent().modal('hide');
    });
    jQuery('#udraw-bootstrap').on('udraw-image-collection-loaded', function (event) {
        var subDirectory = event.subDirectory;
        var categoryContainer = event.categoryContainer;
        if (categoryContainer === '[data-udraw="uDrawClipartFolderContainer"]' && subDirectory.length > 0) {
            jQuery('[data-udraw="uDrawClipartFolderContainer"]').hide();
        }
    });
    jQuery('[data-udraw="layerLabelsModal"]').on("focus", "textarea.labelLayersInput", function () {
        jQuery('[data-udraw="textArea"]').hide();
    });

    $('.trigger_image_upload').on('click', function(){
        $('[data-udraw="uploadImage"]').trigger('click');
    });

    $('.replaceBtn').on('click', function(){
        $('.replace-image-upload-btn').trigger('click');
    });

});

