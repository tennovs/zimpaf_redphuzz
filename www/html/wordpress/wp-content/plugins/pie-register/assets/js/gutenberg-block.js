/**
 * Pie Register Form Block
 *
 * A block for embedding a Pie Register Form into a post/page.
 */
'use strict';
/* global pie_register_block_editor, wp */

// var { serverSideRender: ServerSideRenderer = wp.components.ServerSideRender } = wp;
// var { createElement, Fragment } = wp.element;
// var { registerBlockType } = wp.blocks;
// var { InspectorControls } = wp.blockEditor || wp.editor;
// var { SelectControl, ToggleControl, PanelBody, Placeholder } = wp.components;

var PieRegisterIcon = wp.element.createElement('svg', {
  width: 24,
  height: 24,
  viewBox: '0 0 26 24',
}, wp.element.createElement('path', {
  fill: 'currentColor',
  d: 'M23.39,13.65V5.43H2V4.16q11.33,0,22.67,0c0,2.82,0,5.64,0,8.47C24.63,13.31,23.39,13.65,23.39,13.65Z M26.91,12.26c.35-.22.72-.42,1.09-.61L11.66,23.77,6.79,18.51c1.61,1.11,3.08,2.41,4.64,3.59C16.6,18.84,21.75,15.54,26.91,12.26Z M24.63,23.85H23.39V16.23l1.23-.78C24.65,18.25,24.63,21.05,24.63,23.85Z M22.69,6.88c0,.35,0,.7,0,1H1.29V23.85H0c0-5.66,0-11.32,0-17Z M20.48,9.52c0,.35,0,.7,0,1.06H3V9.5C8.8,9.5,14.64,9.46,20.48,9.52Z'
}));
wp.blocks.registerBlockType('pie-register/form-selector', {
  title: pie_register_block_editor.i18n.title,
  icon: PieRegisterIcon,
  category: 'widgets',
  keywords: pie_register_block_editor.i18n.form_keywords,
  description: pie_register_block_editor.i18n.description,
  attributes: {
    formId: {
      type: 'string'
    },
    displayTitle: {
      type: 'boolean'
    },
    displayDescription: {
      type: 'boolean'
    }
  },

  edit(props) {
    const {
      attributes: {
        formId = '',
        displayTitle = false,
        displayDescription = false
      },
      setAttributes
    } = props;
    const formOptions = pie_register_block_editor.forms.map(value => ({
      value: value.Id,
      label: value.Title
	}));
    let jsx;
    formOptions.unshift({
      value: 'login_form',
      label: 'Login Form'
    });
    formOptions.unshift({
      value: 'forgot_password',
      label: 'Forgot Password'
    });
    formOptions.unshift({
      value: '',
      label: pie_register_block_editor.i18n.form_select
    });

    function selectForm(value) {
      setAttributes({
        formId: value
      });
    }

    function toggleDisplayTitle(value) {
      setAttributes({
        displayTitle: value
      });
    }

    function toggleDisplayDescription(value) {
      setAttributes({
        displayDescription: value
      });
    }

    jsx = [/*#__PURE__*/React.createElement(wp.blockEditor.InspectorControls, {
      key: "piereg-gutenberg-form-selector-inspector-controls"
    }, /*#__PURE__*/React.createElement(wp.components.PanelBody, {
      title: pie_register_block_editor.i18n.form_settings
    }, /*#__PURE__*/React.createElement(wp.components.SelectControl, {
      label: pie_register_block_editor.i18n.form_selected,
      value: formId,
      options: formOptions,
      onChange: selectForm
    }), /*#__PURE__*/React.createElement(wp.components.ToggleControl, {
      label: pie_register_block_editor.i18n.show_title,
      checked: displayTitle,
      onChange: toggleDisplayTitle
    }), /*#__PURE__*/React.createElement(wp.components.ToggleControl, {
      label: pie_register_block_editor.i18n.show_description,
      checked: displayDescription,
      onChange: toggleDisplayDescription
    })))];
    if (formId) {
      jsx.push( /*#__PURE__*/React.createElement(wp.components.ServerSideRender, {
        key: "piereg-gutenberg-form-selector-server-side-renderer",
        block: "pie-register/form-selector",
        attributes: props.attributes
      }));
    } else {
      jsx.push( /*#__PURE__*/React.createElement(wp.components.Placeholder, {
        key: "piereg-gutenberg-form-selector-wrap",
        icon: PieRegisterIcon,
        instructions: pie_register_block_editor.i18n.title,
        className: "piereg-gutenberg-form-selector-wrap"
      }, /*#__PURE__*/React.createElement(wp.components.SelectControl, {
        key: "piereg-gutenberg-form-selector-select-control",
        value: formId,
        options: formOptions,
        onChange: selectForm
      })));
    }
    return jsx;
  },

  save() {
    return null;
  }

});