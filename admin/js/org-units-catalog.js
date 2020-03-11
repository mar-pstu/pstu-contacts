( function( blocks, i18n, element, components, _ ) {
	
	var el              = element.createElement,
		SelectControl   = wp.components.SelectControl,
		plugin_name     = 'pstu_contacts';



	blocks.registerBlockType( 'pstu-contacts/org-units-catalog', {
		title: i18n.__( 'Список подрзделений', plugin_name ),
		keywords: [
			i18n.__( 'ПГТУ', plugin_name ),
			i18n.__( 'контакты', plugin_name ),
			i18n.__( 'подразделение', plugin_name ),
			i18n.__( 'список', plugin_name ),
		],
		icon: 'admin-multisite',
		category: 'widgets',
		
		attributes: {},

		supports: {
			customClassName: true,
			html: false,
		},


		edit: function( props ) {
			return el( 'div', {}, '[org_units_catalog]' );
		},

		save: function( props ) {
			return el( 'div', {}, '[org_units_catalog]' );
		},

	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
);