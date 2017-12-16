(function (blocks, i18n, element, _) {
	let el = element.createElement;
	let children = blocks.source.children;
	let attr = blocks.source.attr;

	// @TODO think about a method to magically create this list
	const content_forms = [
		'contact',
		'newsletter',
		'registration'
	];

	content_forms.forEach(function (form, index) {
		let config = window['content_forms_config_for_' + form];
		let attributes = {};

		_.each(config.fields, function (args, key) {
			attributes[key] = {
				type: 'array',
				source: 'children',
				selector: 'label'
			}
		});

		blocks.registerBlockType('content-forms/' + form, {
			title: config.title,
			icon: 'list-view',
			category: 'layout',
			attributes: attributes,
			edit: function (props) {
				let attributes = props.attributes;

				console.log( attributes );

				let elements = [];

				_.each(config.fields, function (args, key) {
					let field_type = 'text';
					let curr_val = attributes[key];

					if ( typeof curr_val === 'object' ) {
						curr_val = curr_val[0]
					}

					let fieldset_element = el('fieldset', {key: key},
						el(blocks.Editable, {
							tagName: 'label',
							inline: true,
							placeholder: 'Write ' + args.label + ' label…',
							value: curr_val,
							onChange: function (value) {
								let newAtts = {};

								if ( typeof value === 'object' ) {
									value = value[0]
								}

								newAtts[key] = value;

								props.setAttributes( newAtts );
							}
						}),
						el('input', {type: field_type, disabled: 'disabled', name: key}, args.id)
					);

					elements.push(fieldset_element)
				});

				return el('div', {className: props.className}, (elements));
			},
			save: function (props) {
				let elements = [];

				_.each(config.fields, function (args, key) {

					let label = props.attributes[key]

					let fieldset_element = el('fieldset', {key: key},
						el('label', {}, label),
						el('input', {type: 'text', name: key}, args.id)
					);

					elements.push(fieldset_element)
				});

				return el('div', {className: props.className}, (elements));
			}
		});

	})
})(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	window._,
);
