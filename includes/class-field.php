<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


class Field {


	protected $key;


	public $label;


	public $value;


	function __construct( $key, $label, $value = '' ) {
		$this->key = $key;
		$this->label = $label;
		$this->value = $value;
	}


	public function get_key() {
		return $this->key;
	}


}