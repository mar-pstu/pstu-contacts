<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


class Field {


	protected $key;


	public $label;


	public $value;


	protected $required;


	function __construct( $key, $label, $value, $required ) {
		$this->key = $key;
		$this->label = $label;
		$this->value = $value;
		$this->required = ( bool ) $required;
	}


	public function get_key() {
		return $this->key;
	}


	public function is_required() {
		return $this->required;
	}


}