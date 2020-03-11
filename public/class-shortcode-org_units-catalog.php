<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Класс для регистрации шорткода вывода руководителя подразделения
 *
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public
 * @author     chomovva <chomovva@gmail.com>
 */
class PublicShortcodeaOrgUnitsCatalog extends Shortcode {


	protected $org_units;


	public function manager( $atts ) {
		$result = array();
		$this->org_units = get_terms( array(
			'taxonomy'   => 'org_units',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		) );
		if ( is_array( $this->org_units ) && ! empty( $this->org_units ) ) {
			$result[] = $this->render_list( wp_list_filter( $this->org_units, array( 'parent' => 0 ), 'AND' ) );
		}
		return implode( "\r\n", $result );
	}


	public function render_list( $items ) {
		$result = array();
		if ( ! empty( $items ) ) {
			$result = array_map( array( $this, 'render_item' ), $items );
		}
		return ( empty( $result ) ) ? '' : '<ul>' . implode( "\r\n", $result ) . '</ul>';
	}


	public function render_item( $item ) {
		return sprintf(
			'<li><a href="%1$s">%2$s</a>%3$s</li>',
			get_term_link( $item, 'org_units' ),
			apply_filters( 'single_term_title', $item->name ),
			$this->render_list( wp_list_filter( $this->org_units, array( 'parent' => $item->term_id ), 'AND' ) )
		);
	}


}