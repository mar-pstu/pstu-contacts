<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


trait Controls {


	function render_atts( $atts ) {
		$html = __return_empty_string();
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $key => $value ) {
				$html .= ' ' . $key . '="' . $value . '"';
			}
		}
		return $html;
	}


	function render_input( $name, $type="text", $atts = array() ) {
		$atts[ 'name' ] = $name;
		$atts[ 'type' ] = ( in_array( $type, array( 'number', 'email', 'password', 'hidden', 'date', 'datetime' ) ) ) ? $type : 'text';
		return '<input ' . $this->render_atts( $atts ) . ' >';
	}


	function render_dropdown( $name, $choices, $args = array() ) {
		$args = array_merge( array(
			'id'                => $name,
			'class'             => 'postform',
			'selected'          => '',
			'multiple'          => false,
			'echo'              => false,
			'show_option_none'  => '',
			'option_none_value' => '',
		), $args );
		$output = __return_empty_array();
		if ( is_array( $choices ) && ! empty( $choices ) ) {
			if ( is_object( $choices[ 0 ] ) ) {
				$choices = wp_list_pluck( $choices, 'post_title', 'ID' );
			}
			if ( $args[ 'show_option_none' ] ) {
				$output[] = sprintf( '<option value="%1$s">%2$s</option>', esc_attr( $args[ 'option_none_value' ] ), $args[ 'show_option_none' ] );
			}
			foreach ( $choices as $value => $label ) {
				$output[] = sprintf( '<option value="%1$s" %2$s>%3$s</option>', $value, selected( $args[ 'selected' ], $value, false ), $label );
			}
		}
		$html = ( empty( $output ) ) ? '' : sprintf(
			'<select name="%1$s" id="%2$s" %3$s>%4$s</select>',
			$name,
			$args[ 'id' ],
			( bool ) $args[ 'multiple' ] ? 'multiple' : '',
			implode( "\r\n", $output )
		);
		if ( $args[ 'echo' ] ) {
			echo $html;
		}
		return $html;
	}


	function render_order_control( $name, $choices, $args = array() ) {
		$args = array_merge( array(
			'id'                => $name,
			'class'             => '',
			'echo'              => false,
		), $args );
		$output = __return_empty_array();
		if ( is_array( $choices ) && ! empty( $choices ) ) {
			if ( is_object( $choices[ 0 ] ) ) {
				$choices = wp_list_pluck( $choices, 'post_title', 'ID' );
			}
			foreach ( $choices as $value => $label ) {
				$output[] = sprintf( '<li class="order-item"><input name="%1$s[]" type="hidden" value="%2$s">%3$s %4$s</li>', $name, $value, $label, get_post_meta( $value, 'org_units_2749_order', true ) );
			}
		}
		$html = ( empty( $output ) ) ? '' : sprintf(
			'<ol id="%1$s" class="order-list %2$s">%3$s</ol><p class="description">%4$s</p>',
			$args[ 'id' ],
			$args[ 'class' ],
			implode( "\r\n", $output ),
			__( 'Перетаскивайте пункты с помощью мыши', $this->plugin_name )
		);
		if ( $args[ 'echo' ] ) {
			echo $html;
		}
		return $html;
	}


	function render_photo_selection_control( $name, $args = array() ) {
		$args = array_merge( array(
			'id'                => $name,
			'echo'              => false,
			'default'           => '',
			'value'             => '',
			'description'       => '',
		), $args );
		ob_start();
		?>
			<div class="photo-selection-control" data-default="<?php echo esc_attr( $args[ 'default' ] ); ?>">
				<input type="hidden" class="foto-control" id="<?php echo $args[ 'id' ]; ?>" name="<?php echo $name; ?>" value="<?php echo $args[ 'value' ]; ?>">
				<figure class="foto-figure add-foto"><img class="foto-image" src="<?php echo ( empty( $args[ 'value' ] ) ) ? $args[ 'default' ] : $args[ 'value' ]; ?>" alt=""></figure>
				<?php echo $args[ 'description' ]; ?>
				<p>
					<button class="button button-primary add-foto" type="button"><?php _e( 'Выбор фото', $this->plugin_name ); ?></button>
					<button class="button remove-foto" type="button"><?php _e( 'Удалить фото', $this->plugin_name ); ?></button>
				</p>
			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		if ( $args[ 'echo' ] ) {
			echo $html;
		}
		return $html;
	}


}