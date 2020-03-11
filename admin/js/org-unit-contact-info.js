( function( blocks, editor, i18n, element, components, _ ) {
	
	var el              = element.createElement,
		InnerBlocks     = editor.InnerBlocks,
		SelectControl   = wp.components.SelectControl,
		CheckboxControl = wp.components.CheckboxControl,
		RichText        = editor.RichText,
		plugin_name     = 'pstu_contacts',
		org_unit;

	function httpGetSync( theUrl, callback ) {
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function() { 
			if ( xmlHttp.readyState == 4 && xmlHttp.status == 200 )
				callback( xmlHttp.responseText );
		}
		xmlHttp.open( "GET", theUrl, false );
		xmlHttp.send( null );
	}

	function get_terms() {
		var terms = [
			{ value: '-1', label: i18n.__( 'Не выбрано', plugin_name ) },
		];
		httpGetSync( rest_url + 'wp/v2/org_units', function( answer ) {
			JSON.parse( answer ).forEach( function( term, index ) {
				terms[ terms.length ] = {
					value: term.id,
					label: term.name,
					description: term.description,
				}
			} );
		} );
		return terms;
	}


	function set_shortcode( props, id ) {
		var shortcode = '[pstu_org_unit_contacts_info id="' + id + '" ]';
		props.setAttributes( { shortcode: shortcode } );
	}


	blocks.registerBlockType( 'pstu-contacts/org-unit-contacts-info', {
		title: i18n.__( 'Контактная информация подразделения', plugin_name ),
		description: '',
		keywords: [
			i18n.__( 'ПГТУ', plugin_name ),
			i18n.__( 'контакты', plugin_name ),
			i18n.__( 'подразделение', plugin_name ),
			i18n.__( 'описание', plugin_name ),
			i18n.__( 'контакты', plugin_name ),
		],
		icon: 'id-alt',
		category: 'widgets',
		
		attributes: {
			id: {
				type: 'string',
				default: ''
			},
			shortcode: {
				type: 'array',
				source: 'children',
				selector: 'div',
				default: ''
			},
		},

		supports: {
			customClassName: true,
			html: false,
		},


		edit: function( props ) {
			var id = props.attributes.id;
			terms = get_terms();
			return el( 'div', { className: props.className },
				el( wp.editor.InspectorControls, null,
					el( wp.components.PanelBody,
						{
							title: i18n.__( 'Параметры шорткода', plugin_name ),
							initialOpen: true,
						},
						el( SelectControl, {
							label: i18n.__( 'Название подразделения', plugin_name ),
							value: props.attributes.id,
							options: terms,
							onChange: function( value ) {
								props.setAttributes( { id: value } );
								set_shortcode( props, value, props.attributes.contact_template );
							},
						} ),
					),
				),
				( props.attributes.id.length > 0 ) ? [
					el( 'div', { className: 'org-unit-name' }, terms.find( x=>x.value==props.attributes.id ).label ),
					el( 'code', {}, props.attributes.shortcode ),
				] : el( 'div', {}, i18n.__( 'Выберите подразделение', plugin_name ) ),
			);
		},

		save: function( props ) {
			return el( 'div', {}, props.attributes.shortcode );
		},

	} );

} )(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
);