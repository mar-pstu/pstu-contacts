<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Класс для группировки полей контактов и 
 *
 * @since      2.0.0
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 * @author     chomovva <chomovva@gmail.com>
 */
class SettingsSection {


	protected $key;


	public $label;


	protected $fields;


	function __construct( $key, $label, $fields = array() ) {
		$this->key = $key;
		$this->label = $label;
		$this->fields = ( is_array( $fields ) ) ? $fields : array();
	}


	public function get_key() {
		return $this->key;
	}


	public function get_fields() {
		return $this->fields;
	}


	public function add_field( $field ) {
		if ( $field instanceof Field ) {
			$this->fields[] = $field;
			return true;
		} else {
			return false;
		}
	}


}