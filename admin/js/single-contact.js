( function( blocks, editor, i18n, element, components, _ ) {
	var el              = element.createElement,
		InnerBlocks     = editor.InnerBlocks,
		SelectControl   = wp.components.SelectControl,
		CheckboxControl = wp.components.CheckboxControl,
		RichText        = editor.RichText,
		plugin_name     = 'pstu_contacts',
		contacts;


	function httpGetSync( theUrl, callback ) {
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function() { 
			if ( xmlHttp.readyState == 4 && xmlHttp.status == 200 )
				callback( xmlHttp.responseText );
		}
		xmlHttp.open( "GET", theUrl, false );
		xmlHttp.send( null );
	}

	function get_contacts() {
		var contacts = [
			{ value: '-1', label: i18n.__( 'Не выбрано', plugin_name ) },
		];
		httpGetSync( rest_url + 'wp/v2/contact', function( answer ) {
			JSON.parse( answer ).forEach( function( contact, index ) {
				contacts[ contacts.length ] = {
					value: contact.id,
					label: contact.title.rendered
				}
			} );
		} );
		return contacts;
	}


	function set_shortcode( props, id, contact_template ) {
		var shortcode = '[pstu_contact id="' + id + '" contact_template="' + contact_template + '" ]';
		props.setAttributes( { shortcode: shortcode } );
	}

	blocks.registerBlockType( 'pstu-contacts/single-contact', {
		title: i18n.__( 'Контакт', plugin_name ),
		description: i18n.__( 'Одиночный контакт.', plugin_name ),
		keywords: [
			i18n.__( 'ПГТУ', plugin_name ),
			i18n.__( 'контакт', plugin_name ),
			i18n.__( 'сотрудник', plugin_name ),
		],
		icon: 'admin-users',
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
			contact_template: {
				type: 'string',
				default: 'card',
			}
		},

		supports: {
			customClassName: true,
			html: false,
		},


		edit: function( props ) {
			var id = props.attributes.id;
			contacts = get_contacts();
			return el( 'div', { className: props.className },
				el( wp.editor.InspectorControls, null,
					el( wp.components.PanelBody,
						{
							title: i18n.__( 'Параметры шорткода', plugin_name ),
							initialOpen: true,
						},
						el( SelectControl, {
							label: i18n.__( 'Имя сотрудника', plugin_name ),
							value: props.attributes.id,
							options: contacts,
							onChange: function( value ) {
								props.setAttributes( { id: value } );
								set_shortcode( props, value, props.attributes.contact_template );
							},
						} ),
						el( SelectControl, {
							label: i18n.__( 'Название категории', plugin_name ),
							value: props.attributes.contact_template,
							options: [
								{
									label: i18n.__( 'карточка', plugin_name ),
									value: 'card',
								},
								{
									label: i18n.__( 'линия', plugin_name ),
									value: 'bar',
								},
								{
									label: i18n.__( 'минимум информации', plugin_name ),
									value: 'item',
								},
							],
							onChange: function( value ) {
								props.setAttributes( { contact_template: value } );
								set_shortcode( props, props.attributes.id, value );
							},
						} ),
					),
				),
				( props.attributes.id.length > 0 ) ? [
					el( 'div', {}, contacts.find( x=>x.value==props.attributes.id ).label ),
					el( 'code', {}, props.attributes.shortcode )
				] : el( 'div', {}, i18n.__( 'Выберите сорудника', plugin_name ) ),
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