/**
 * Posts Carousel block for Gutenberg 
 *
 */
( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;
	var Editable = blocks.Editable;
	var AlignmentToolbar = wp.blocks.AlignmentToolbar;
	var BlockControls = wp.blocks.BlockControls;
	var InspectorControls = wp.blocks.InspectorControls;
	var TextControl = wp.components.TextControl;
	var ServerSideRender = wp.components.ServerSideRender;
	var RangeControl = wp.components.RangeControl;
	var PanelColorSettings = wp.editor.PanelColorSettings;
	var ColorPalette = wp.components.ColorPalette;
	var SelectControl = wp.components.SelectControl;
	var TextareaControl = wp.components.TextareaControl;
	var RichText = wp.editor.RichText;

	/**
	 * Agent Login Block
	 * @param  heading, redirect
	 * @return {null}       Rendered through PHP
	 */
	blocks.registerBlockType( 'block/file-manager', {
		title: __( 'File Manager' ),
		icon: 'editor-insertmore',
		category: 'common',
	    keywords: [
            __('files'),
            // __('posts'),
            // __('gallery')
	    ],
		attributes: {
// 			id: {
// 				type: 'string',
// 				default: '1609'
// 			},
		},	    
	    edit: function(props) {
	        return el(ServerSideRender, {
		        	key: "editable",
		        	block: "block/file-manager",
		        	attributes:  props.attributes
		        })
	    },
		save: function(props) {
	        return null;
		},
	});

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);