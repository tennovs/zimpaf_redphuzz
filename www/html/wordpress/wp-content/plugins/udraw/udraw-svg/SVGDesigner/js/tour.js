jQuery(document).ready(function($){
    if (typeof cart_item_key === 'string' && cart_item_key.length === 0) {
        var start_tour = function () {
            //End a previous tour
            hopscotch.endTour();
            // Define the tour!
            var tour = {
                id: "tour",
                steps: new Array()
            };
            if ($('ul.toollist > li:first-child').length) {
                tour.steps.push({
                    title: "Add Images",
                    content: "Click this button to add images to the document",
                    target: $('ul.toollist > li:first-child')[0],
                    placement: "right"
                });
            }
            if ($('[data-udrawsvg="add_text"]').length) {
                tour.steps.push({
                    title: "Add Text",
                    content: "Click this button to add text to the document",
                    target: $('[data-udrawsvg="add_text"]')[0],
                    placement: "right"
                });
            }
            if ($('[data-udrawsvg="document_background_colour"]').length) {
                tour.steps.push({
                    title: "Background Colour",
                    content: "Background colour of the document can be changed here",
                    target: $('[data-udrawsvg="document_background_colour"]')[0],
                    placement: "right"
                });
            }
            if ($('[data-udrawsvg="rotate_counter_clockwise"]').length) {
                tour.steps.push({
                    title: "Rotate Template",
                    content: "Click these buttons to rotate the template",
                    target: $('[data-udrawsvg="rotate_counter_clockwise"]')[0],
                    placement: "right"
                });
            }
            if ($('ul.zoom_ul').length) {
                tour.steps.push({
                    title: "Zoom",
                    content: "The template can be zoomed in and out with these controls",
                    target: $('ul.zoom_ul')[0],
                    placement: "top"
                });
            }
            if ($('[aria-controls="pages"]').length) {
                tour.steps.push({
                    title: "Sides",
                    content: "Different sides can be selected to be designed here",
                    target: $('[aria-controls="pages"]')[0],
                    placement: "left"
                });
            }
            if ($('div.designer_menu > div.btn-group > button').length) {
                tour.steps.push({
                    title: "Editing tools",
                    content: "Different editing tools can be found here here",
                    target: $('div.designer_menu > div.btn-group > button')[0],
                    placement: "bottom"
                });
            }
            if ($('[data-udrawsvg="back_to_options"]').length) {
                tour.steps.push({
                    title: "Back to Options",
                    content: "If needed, you can go back to the pricing options here",
                    target: $('[data-udrawsvg="back_to_options"]')[0],
                    placement: "bottom"
                });
            }
            if ($('[data-udrawsvg="add_to_cart"]').length) {
                tour.steps.push({
                    title: "Add to Cart",
                    content: "After designing, you may add the product to cart",
                    target: $('[data-udrawsvg="add_to_cart"]')[0],
                    placement: "bottom"
                });
            }

            // Start the tour only if it is a new template.
            hopscotch.startTour(tour);
        }
        
        var load_tour = true;
        $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_design_loaded', function (event) {
            $('[data-udrawsvg="design_now"]').on('click', function(){
                if (load_tour) {
                    if ($('[data-udrawsvg="image_replace_modal"]').is(':visible')){
                        $('[data-udrawsvg="apply_bulk_image_replace"]').on('click', function(){
                            start_tour();
                        });
                    } else {
                        start_tour();
                    }
                    load_tour = false;
                }
            });
        });
        
        $('[data-udrawsvg="design_now"]').on('click', function(){
            $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_design_loaded', function (event) {
                if (load_tour) {
                    if ($('[data-udrawsvg="image_replace_modal"]').is(':visible')){
                        $('[data-udrawsvg="apply_bulk_image_replace"]').on('click', function(){
                            start_tour();
                        });
                    } else {
                        start_tour();
                    }
                    load_tour = false;
                }
            });
        });
    };
});