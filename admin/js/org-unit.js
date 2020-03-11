( function( blocks, editor, i18n, element, components, _ ) {

	var el              = element.createElement,
		InnerBlocks     = editor.InnerBlocks,
		SelectControl   = wp.components.SelectControl,
		CheckboxControl = wp.components.CheckboxControl,
		RichText        = editor.RichText,
		plugin_name     = 'pstu_contacts';

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
					label: term.name
				}
			} );
		} );
		return terms;
	}

	function set_shortcode( props, id, leader, contact_template ) {
		var leader_str = ( leader ) ? '1' : '0';
		var shortcode = '[pstu_org_unit id="' + id + '" leader="' + leader_str +'" contact_template="' + contact_template + '" ]';
		props.setAttributes( { shortcode: shortcode } );
	}

	blocks.registerBlockType( 'pstu-contacts/org-unit', {
		title: i18n.__( 'Подразделение', plugin_name ),
		description: i18n.__( 'Список контактов подразделения.', plugin_name ),
		keywords: [
			i18n.__( 'ПГТУ', plugin_name ),
			i18n.__( 'контакты', plugin_name ),
			i18n.__( 'подразделение', plugin_name ),
			i18n.__( 'список', plugin_name ),
			i18n.__( 'сотрудники', plugin_name ),
		],
		icon: 'groups',
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
			leader: {
				type: 'bool',
				default: true,
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
			terms = get_terms();
			return el( 'div', { className: props.className },
				el( wp.editor.InspectorControls, null,
					el( wp.components.PanelBody,
						{
							title: i18n.__( 'Параметры шорткода', plugin_name ),
							initialOpen: true,
						},
						el( SelectControl, {
							label: i18n.__( 'Название категории', plugin_name ),
							value: props.attributes.id,
							options: terms,
							onChange: function( value ) {
								props.setAttributes( { id: value } );
								set_shortcode( props, value, props.attributes.leader, props.attributes.contact_template );
							},
						} ),
						el( CheckboxControl, {
							label: i18n.__( 'Показывать руководителя', plugin_name ),
							help: i18n.__( 'руководитель выводится перед списком сотрудников', plugin_name ),
							checked: props.attributes.leader,
							onChange: function ( value ) {
								props.setAttributes( { leader: value } );
								set_shortcode( props, props.attributes.id, value, props.attributes.contact_template );
							}
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
								set_shortcode( props, props.attributes.id, props.attributes.leader, value );
							},
						} ),
					),
				),
				( props.attributes.id.length > 0 ) ? [
					el( 'div', {}, terms.find( x=>x.value==props.attributes.id ).label ),
					el( 'code', {}, props.attributes.shortcode )
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