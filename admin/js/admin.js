( function( $ ) {

	'use strict';


	jQuery( document ).ready( function () {
		var default_image = '',
			$button_add = jQuery( '#add-foto' ),
			$button_remove = jQuery( '#remove-foto' ),
			$foto = jQuery( '#foto-image' ),
			$control = jQuery( '#foto' );
		function check_default() {
			var value = $control.val();
			if ( value.trim().length > 0 ) {
				$foto.attr( 'src', value );
				$button_remove.css( 'visibility', 'visible' );
			} else {
				$foto.attr( 'src', default_image );
				$button_remove.css( 'visibility', 'hidden' );
			}
		}
		function add() {
			var image = wp.media( { 
				title: 'Выбор фото',
				multiple: false,
				library: {
					type: [ 'image' ]
				},
			} ).open()
			.on( 'select', function( e ) {
				var uploaded_image = image.state().get( 'selection' ).first();
				var image_url = uploaded_image.toJSON().url;
				console.log( image_url );
				$foto.attr( 'src', image_url );
				$control.val( image_url );
				check_default();
			} );
		}
		function remove() {
			$foto.attr( 'src', default_image );
			$control.val( '' );
			check_default();
		}
		$button_remove.click( remove );
		$button_add.click( add );
		check_default();
	} );


} )( jQuery );
