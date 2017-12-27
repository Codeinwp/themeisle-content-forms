/**
 * WordPress dependencies
 */
const {Component} = wp.element;
const {Placeholder, Spinner, withAPIData} = wp.components;
const {__} = wp.i18n;

const {
	Editable,
	BlockDescription,
	BlockControls,
	InspectorControls,
} = wp.blocks;

class ContentFormControlss extends Component {
	constructor() {
		super(...arguments);
		this.consoleValue = this.consoleValue.bind( this );
	}

	consoleValue(v) {
		console.log(v)
	}

	render() {
		const {setAttributes} = this.props;


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

		return (
			<InspectorControls key="inspector">
				<BlockDescription>
					<p>{ __( 'Shows a list of your site\'s categories.' ) }</p>
				</BlockDescription>
				<h3>{ __( 'Categories Settings' ) }</h3>
				<InspectorControls.ToggleControl
					label={ __( 'Display as dropdown' ) }
					checked={ 1 }
					onChange={ this.consoleValue }
				/>
				<InspectorControls.ToggleControl
					label={ __( 'Show post counts' ) }
					checked={ 1 }
					onChange={ this.consoleValue }
				/>
				<InspectorControls.ToggleControl
					label={ __( 'Show hierarchy' ) }
					checked={ 1 }
					onChange={ this.consoleValue }
				/>
			</InspectorControls>
		);

	}
}

export const ContentFormControls = withAPIData((props) => {
	return {
		contentForms: '/content-forms/v1/forms',
	};
})(ContentFormControlss);
