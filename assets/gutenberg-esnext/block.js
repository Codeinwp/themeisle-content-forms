const {
	registerBlockType,
} = wp.blocks;
const { __ } = wp.i18n;
import { ContentFormEditor } from './components/FormEditor.js'

// @TODO think about a method to magically create this list
const content_forms = [
	'contact',
	'newsletter',
	'registration'
];

/**
 * Go through each form type and register a blockType from the given config
 * @TODO maybe create a custom category for OrbitFox only?
 *
 */
content_forms.forEach(function (form, index) {
	let config = window['content_forms_config_for_' + form];

	registerBlockType('content-forms/' + form, {
		title: config.title,
		icon: 'index-card',
		category: 'common',
		keywords: [ __( 'forms' ), __( 'fields' ) ],
		edit: ContentFormEditor,
		save: props => {
			const component = this
			const {attributes} = props
			const {fields} = attributes
			let fieldsEl = []

			_.each(fields, function (args, key) {
				let label = args.label

				fieldsEl.push(<span key={key} className="content-form-field-label" label={label}></span>)
			})

			return (<div key="fields" className="fields">
				{fieldsEl}
			</div>)
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

