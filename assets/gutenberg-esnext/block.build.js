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
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_Forms_js__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_Controls_js__ = __webpack_require__(2);
var _wp$blocks = wp.blocks,
    registerBlockType = _wp$blocks.registerBlockType,
    _wp$blocks$source = _wp$blocks.source,
    attr = _wp$blocks$source.attr,
    children = _wp$blocks$source.children;





var submitAction = '/wp-admin/admin-post.php';

// @TODO think about a method to magically create this list
var content_forms = ['contact', 'newsletter', 'registration'];

/**
 * Go through each form type and register a blockType from the given config
 * @TODO maybe create a custom category for OrbitFox only?
 *
 */
content_forms.forEach(function (form, index) {
	var config = window['content_forms_config_for_' + form];
	var block_attributes = {};

	/**
  * Create an label attribute for each field
  * @TODO Maybe add a prefix for this like `label_` since we probably need a `require_` att too
  */
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

		supports: {
			html: false
		},

		attributes: block_attributes,

		edit: function edit(props) {
			var focusedEditable = props.focus ? props.focus.editable || 'title' : null;
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;


			var elements = [];

			// @TODO call the fields component

			/**
    * @TODO For sure we will need to register a set of settings from config.controls
    */

			// Add a submit button; @TODO Make a setting for this;
			// elements.push(<button disabled key="submit_btn">Submit</button>);

			return [wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__components_Controls_js__["a" /* ContentFormControls */], { key: 'inspector' }), wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__components_Forms_js__["a" /* ContentForm */], { key: 'content-form' })];
		},
		save: function save(props) {
			return JSON.stringify(props.attributes);
			//return null
		}

		// @TODO Maybe return to the old way of saving a plain html
		// save: props => {
		// 	const {
		// 		className,
		// 		attributes
		// 	} = props;
		//
		// 	let elements = [];
		//
		// 	_.each(config.fields, function (args, key) {
		// 		let fieldset_element = <fieldset key={key}>
		// 			<label htmlFor={key}>{attributes[key]}</label>
		// 			<input type="text" name={key} />
		// 		</fieldset>;
		// 		elements.push(fieldset_element)
		// 	});
		//
		// 	// Add a submit button; @TODO Make a setting for this;
		// 	elements.push(<button key="submit_btn">Submit</button>);
		//
		// 	return (
		// 		<form className={ props.className } method="post" action={submitAction}>
		// 			{elements}
		// 		</form>
		// 	);
		// }
	});
});

/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ContentForm; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Component = wp.element.Component;
var _wp$components = wp.components,
    Placeholder = _wp$components.Placeholder,
    Spinner = _wp$components.Spinner,
    withAPIData = _wp$components.withAPIData;
var __ = wp.i18n.__;

var ContentForms = function (_Component) {
	_inherits(ContentForms, _Component);

	function ContentForms() {
		_classCallCheck(this, ContentForms);

		var _this = _possibleConstructorReturn(this, (ContentForms.__proto__ || Object.getPrototypeOf(ContentForms)).apply(this, arguments));

		console.log(_this.props);
		return _this;
	}

	_createClass(ContentForms, [{
		key: "render",
		value: function render() {
			var setAttributes = this.props.setAttributes;
			//
			// <form className={ props.className } method="post" action={submitAction}>
			// 	{elements}
			// </form>

			//
			// _.each(config.fields, function (args, key) {
			// 	const onChangeLabel = value => {
			// 		let newAtts = {};
			// 		newAtts[key] = value;
			// 		setAttributes( newAtts );
			// 	};
			//
			// 	console.log(args)
			//
			// 	let fieldset_element = <fieldset key={key}>
			// 		<Editable
			// 			tagName="label"
			// 			placeholder={ __( 'Write ' + args.label + ' title…' ) }
			// 			value={ attributes[key] }
			// 			onChange={ onChangeLabel }
			// 		/>
			// 		<input type="text" name={key} disabled="disabled" placeholder={args.placeholder} />
			// 	</fieldset>;
			// 	elements.push(fieldset_element)
			// });
			//

			return wp.element.createElement(
				Placeholder,
				{
					key: "form",
					icon: "admin-post",
					label: __('Form')
				},
				wp.element.createElement(Spinner, null)
			);
		}
	}]);

	return ContentForms;
}(Component);

var ContentForm = withAPIData(function () {
	return {
		ContentForm: '/content-forms/v1/forms'
	};
})(ContentForms);

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ContentFormControls; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Component = wp.element.Component;
var _wp$components = wp.components,
    Placeholder = _wp$components.Placeholder,
    Spinner = _wp$components.Spinner,
    withAPIData = _wp$components.withAPIData;
var __ = wp.i18n.__;
var _wp$blocks = wp.blocks,
    Editable = _wp$blocks.Editable,
    BlockDescription = _wp$blocks.BlockDescription,
    BlockControls = _wp$blocks.BlockControls,
    InspectorControls = _wp$blocks.InspectorControls;

var ContentFormControlss = function (_Component) {
	_inherits(ContentFormControlss, _Component);

	function ContentFormControlss() {
		_classCallCheck(this, ContentFormControlss);

		var _this = _possibleConstructorReturn(this, (ContentFormControlss.__proto__ || Object.getPrototypeOf(ContentFormControlss)).apply(this, arguments));

		_this.consoleValue = _this.consoleValue.bind(_this);
		return _this;
	}

	_createClass(ContentFormControlss, [{
		key: 'consoleValue',
		value: function consoleValue(v) {
			console.log(v);
		}
	}, {
		key: 'render',
		value: function render() {
			var setAttributes = this.props.setAttributes;

			// let inspectorControls = null;
			//
			// if ( typeof config.controls !== "undefined" ) {
			//
			// 	let blockControls = []
			//
			// 	_.each(config.controls, function (args, key) {
			//
			// 		blockControls.push(<fieldset>
			// 			<BlockEdit key={ 'block-edit-custom-' + key } />
			// 			<InspectorControls.TextControl
			// 				key={key}
			// 				label={  args.label }
			// 				value={ props.attributes[key] || '' }
			// 				onChange={ ( nextValue ) => {
			//
			// 					let newValues = {}
			//
			// 					newValues[key] = nextValue
			//
			// 					setAttributes( newValues );
			// 				} }
			// 			/></fieldset>
			// 		)
			// 	})
			//
			// 	inspectorControls = props.focus && (
			// 		<InspectorControls key="inspector-content-forms">
			// 			<BlockDescription>
			// 				<p>{ __( 'Form Settings' ) }</p>
			// 			</BlockDescription>
			// 			{blockControls}
			// 		</InspectorControls>
			// 	);
			// }

			return wp.element.createElement(
				InspectorControls,
				{ key: 'inspector' },
				wp.element.createElement(
					BlockDescription,
					null,
					wp.element.createElement(
						'p',
						null,
						__('Shows a list of your site\'s categories.')
					)
				),
				wp.element.createElement(
					'h3',
					null,
					__('Categories Settings')
				),
				wp.element.createElement(InspectorControls.ToggleControl, {
					label: __('Display as dropdown'),
					checked: 1,
					onChange: this.consoleValue
				}),
				wp.element.createElement(InspectorControls.ToggleControl, {
					label: __('Show post counts'),
					checked: 1,
					onChange: this.consoleValue
				}),
				wp.element.createElement(InspectorControls.ToggleControl, {
					label: __('Show hierarchy'),
					checked: 1,
					onChange: this.consoleValue
				})
			);
		}
	}]);

	return ContentFormControlss;
}(Component);

var ContentFormControls = withAPIData(function (props) {
	return {
		contentForms: '/content-forms/v1/forms'
	};
})(ContentFormControlss);

/***/ })
/******/ ]);