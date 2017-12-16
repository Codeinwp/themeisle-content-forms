const { __ } = wp.i18n;
const {
	registerBlockType,
	Editable,
	source: {
		attr,
		children
	}
} = wp.blocks;

const submitAction = '/wp-admin/admin-post.php';

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
	let block_attributes = {};

	/**
	 * Create an label attribute for each field
	 * @TODO Maybe add a prefix for this like `label_` since we probably need a `require_` att too
	 */
	_.each(config.fields, function (args, key) {
		block_attributes[key] = {
			type: 'array',
			source: 'children',
			selector: 'label'
		}
	});

	registerBlockType( 'content-forms/' + form, {
		title: config.title,
		icon: 'index-card',
		category: 'layout',
		attributes: block_attributes,
		edit: props => {
			const focusedEditable = props.focus ? props.focus.editable || 'title' : null;
			const attributes = props.attributes;

			let elements = [];

			_.each(config.fields, function (args, key) {

				const onChangeLabel = value => {
					let newAtts = {};
					newAtts[key] = value;
					props.setAttributes( newAtts );
				};

				let fieldset_element = <fieldset key={key}>
					<Editable
						tagName="label"
						placeholder={ __( 'Write ' + args.label + ' title…' ) }
						value={ attributes[key] }
						onChange={ onChangeLabel }
					/>
					<input type="text" name={key} />
				</fieldset>;
				elements.push(fieldset_element)
			});

			/**
			 * @TODO For sure we will need to register a set of settings from config.controls
			 */

			// Add a submit button; @TODO Make a setting for this;
			elements.push(<button disabled key="submit_btn">Submit</button>);

			return (
				<form className={ props.className } method="post" action={submitAction}>
					{elements}
				</form>
			);
		},
		save: props => {
			const {
				className,
				attributes
			} = props;

			let elements = [];

			_.each(config.fields, function (args, key) {
				let fieldset_element = <fieldset key={key}>
					<label htmlFor={key}>{attributes[key]}</label>
					<input type="text" name={key} />
				</fieldset>;
				elements.push(fieldset_element)
			});

			// Add a submit button; @TODO Make a setting for this;
			elements.push(<button key="submit_btn">Submit</button>);

			return (
				<form className={ props.className } method="post" action={submitAction}>
					{elements}
				</form>
			);
		}
	} );
});

