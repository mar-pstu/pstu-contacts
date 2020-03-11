( function( blocks, i18n, element, components, _ ) {
	
	var el              = element.createElement,
		SelectControl   = wp.components.SelectControl,
		plugin_name     = 'pstu_contacts';


	function set_shortcode( props, level ) {
		var shortcode = '[contacts_catalog headings_level="' + level + '" ]';
		props.setAttributes( { shortcode: shortcode } );
	}


	blocks.registerBlockType( 'pstu-contacts/contacts-catalog', {
		title: i18n.__( 'Список контактов', plugin_name ),
		keywords: [
			i18n.__( 'ПГТУ', plugin_name ),
			i18n.__( 'контакты', plugin_name ),
			i18n.__( 'список', plugin_name ),
		],
		icon: 'format-aside',
		category: 'widgets',
		
		attributes: {
			headings_level: {
				type: 'string',
				default: 'h3'
			},
			shortcode: {
				type: 'array',
				source: 'children',
				selector: 'div',
				default: '[contacts_catalog]'
			},
		},

		supports: {
			customClassName: true,
			html: false,
		},


		edit: function( props ) {
			return el( 'div', {},
				el( wp.editor.InspectorControls, null,
					el( wp.components.PanelBody,
						{
							title: i18n.__( 'Параметры шорткода', plugin_name ),
							initialOpen: true,
						},
						el( SelectControl, {
							label: i18n.__( 'Уровень заголовка', plugin_name ),
							value: props.attributes.headings_level,
							options: [
								{
									value: 'h2',
									isActive: props.attributes.headings_level === 'h2',
									label: i18n.__( 'H2', plugin_name ),
								},
								{
									value: 'h3',
									isActive: props.attributes.headings_level === 'h3',
									label: i18n.__( 'H3', plugin_name ),
								},
								{
									value: 'h4',
									isActive: props.attributes.headings_level === 'h4',
									label: i18n.__( 'H4', plugin_name ),
								},
								{
									value: 'h5',
									isActive: props.attributes.headings_level === 'h5',
									label: i18n.__( 'H5', plugin_name ),
								},
							],
							onChange: function( value ) {
								props.setAttributes( { headings_level: value } );
								set_shortcode( props, value );
							},
						} ),
					),
				),
				el( 'code', {}, props.attributes.shortcode )
			);
		},

		save: function( props ) {
			return el( 'div', {}, props.attributes.shortcode );
		},

	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
);