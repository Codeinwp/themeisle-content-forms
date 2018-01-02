/**
 * WordPress dependencies
 */
// import { map } from 'lodash';
const {Component} = wp.element;
const {Placeholder, Spinner, withAPIData} = wp.components;
const {__} = wp.i18n;
const {Editable, BlockEdit, InspectorControls} = wp.blocks;


class FormEditor extends Component {
	constructor() {
		super(...arguments);
		let form = 'contact';

		this.config = window['content_forms_config_for_' + form];
	}

	render() {
		const component = this
		const {attributes, setAttributes, focus, setFocus, className} = this.props
		const {fields} = attributes
		const placeholderEl = <Placeholder
			key="form-loader"
			icon="admin-post"
			label={__('Form')}>
			<Spinner/>
		</Placeholder>

		let controlsEl = []
		let fieldsEl = []
		_.each(component.config.controls, function (args, key) {
			controlsEl.push(<fieldset key={key}>
				<BlockEdit key={'block-edit-custom-' + key}/>
				<InspectorControls.TextControl
					key={key}
					label={args.label}
					value={attributes[key] || ''}
					onChange={(nextValue) => {
						let newValues = {}
						newValues[key] = nextValue
						component.props.setAttributes(newValues);
					}}
				/></fieldset>
			)
		})

		if (fields.length === 0) {
			fieldsEl.push(
				<Placeholder
					key="placeholder"
					label={__('Content Forms')}/>
			)
		} else {
			_.each(fields, function (args, key) {
				let val = ''

				if ( typeof args.label === "object" ) {
					val = args.label[0]
				} else if ( typeof args.label === "string" ) {
					val = args.label
				}

				fieldsEl.push(<div key={key}>
					<Editable
						value={ val }
						tagName="div"
						placeholder="Label"
						className="content-form-field-label"
						onChange={(nextValue) => {
							let newValues = attributes.fields
							newValues[key]['label'] = nextValue
							setAttributes({ fields: newValues })
						}}
					/>

					<input type="text" disabled="disabled" />
				</div>)
			})
		}

		return [
			(<InspectorControls key="inspector">
				<h3>{__('Form Settings')}</h3>
				{controlsEl}
			</InspectorControls>),
			(<div key="fields">
				{(fieldsEl === [])
				? placeholderEl
				: fieldsEl}
			</div>)
		]
	}
}

export const ContentFormEditor = withAPIData(() => {
	return {
		ContentForm: '/content-forms/v1/forms',
	};
})(FormEditor);
