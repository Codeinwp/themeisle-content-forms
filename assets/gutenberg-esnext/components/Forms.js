/**
 * WordPress dependencies
 */
const {Component} = wp.element;
const {Placeholder, Spinner, withAPIData} = wp.components;
const { __ } = wp.i18n;

class ContentForms extends Component {
	constructor() {
		super(...arguments);
		console.log(this.props)
	}

	render() {
		const {setAttributes} = this.props;
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
		return (
			<Placeholder
				key="form"
				icon="admin-post"
				label={__('Form')}
			>
				<Spinner/>
			</Placeholder>
		);
	}
}

export const ContentForm = withAPIData( () => {
	return {
		ContentForm: '/content-forms/v1/forms',
	};
} )( ContentForms );
