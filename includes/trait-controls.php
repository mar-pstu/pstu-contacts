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


}