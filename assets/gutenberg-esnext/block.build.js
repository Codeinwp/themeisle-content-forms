/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

var __ = wp.i18n.__;
var _wp$blocks = wp.blocks,
    registerBlockType = _wp$blocks.registerBlockType,
    Editable = _wp$blocks.Editable,
    _wp$blocks$source = _wp$blocks.source,
    attr = _wp$blocks$source.attr,
    children = _wp$blocks$source.children;


var submitAction = '/wp-admin/admin-post.php';

// @TODO think about a method to magically create this list
var content_forms = ['contact', 'newsletter', 'registration'];

content_forms.forEach(function (form, index) {
	var config = window['content_forms_config_for_' + form];
	var block_attributes = {};

	_.each(config.fields, function (args, key) {
		block_attributes[key] = {
			type: 'array',
			source: 'children',
			selector: 'label'
		};
	});

	registerBlockType('content-forms/' + form, {
		title: config.title,
		icon: 'index-card',
		category: 'layout',
		attributes: block_attributes,
		edit: function edit(props) {
			var focusedEditable = props.focus ? props.focus.editable || 'title' : null;
			var attributes = props.attributes;

			var elements = [];

			_.each(config.fields, function (args, key) {

				var onChangeTitle = function onChangeTitle(value) {
					var newAtts = {};
					newAtts[key] = value;
					props.setAttributes(newAtts);
				};

				var fieldset_element = wp.element.createElement(
					'fieldset',
					{ key: key },
					wp.element.createElement(Editable, {
						tagName: 'label',
						placeholder: __('Write ' + args.label + ' title…'),
						value: attributes[key],
						onChange: onChangeTitle
					}),
					wp.element.createElement('input', { type: 'text', name: key })
				);
				elements.push(fieldset_element);
			});

			// Add a submit button; @TODO Make a setting for this;
			elements.push(wp.element.createElement(
				'button',
				{ disabled: true, key: 'submit_btn' },
				'Submit'
			));

			return wp.element.createElement(
				'form',
				{ className: props.className, method: 'post', action: submitAction },
				elements
			);
		},
		save: function save(props) {
			var className = props.className,
			    attributes = props.attributes;


			var elements = [];

			_.each(config.fields, function (args, key) {
				var fieldset_element = wp.element.createElement(
					'fieldset',
					{ key: key },
					wp.element.createElement(
						'label',
						{ htmlFor: key },
						attributes[key]
					),
					wp.element.createElement('input', { type: 'text', name: key })
				);
				elements.push(fieldset_element);
			});

			// Add a submit button; @TODO Make a setting for this;
			elements.push(wp.element.createElement(
				'button',
				{ key: 'submit_btn' },
				'Submit'
			));

			return wp.element.createElement(
				'form',
				{ className: props.className, method: 'post', action: submitAction },
				elements
			);
		}
	});
});

/***/ })
/******/ ]);