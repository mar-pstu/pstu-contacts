<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Вспомогательные методы
 *
 * @since      2.0.0
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 * @author     chomovva <chomovva@gmail.com>
 */
trait Helpers {


	function parse_emails_list( $value ) {
		return array_filter( wp_parse_list( $value ), 'is_email' );
	}


	function parse_list_of_telephone_numbers( $value ) {
		$result = __return_empty_array();
		$items = preg_split( "/ (,|;) /", $value );
		foreach ( $items as &$item ) {
			$item = trim( $item );
			if ( ! empty( $item ) ) {
				$result[] = $item;
			}
		}
		return $result;
	}


}