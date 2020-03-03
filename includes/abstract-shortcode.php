<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Абстрактный класс для создания шорткодов
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 * @author     chomovva <chomovva@gmail.com>
 */
abstract class Shortcode extends Part {


	protected $shortcode_name;


	function __construct( $shortcode_name, $plugin_name, $version ) {
		parent::__construct( $plugin_name, $version );
		$this->shortcode_name = $shortcode_name;
	}


	public function manager( $atts ) {
		return '';
	}


	public function get_name() {
		return $this->shortcode_name;
	}


}