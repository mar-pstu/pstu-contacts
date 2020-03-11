<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Выводит краткое описание подразделения
 *
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public
 * @author     chomovva <chomovva@gmail.com>
 */
class PublicShortcodeOrgUnitContactInfo extends Shortcode {


	public function manager( $atts ) {
		$html = '';
		$atts = shortcode_atts( array(
			'id' => false,
		), $atts, $this->get_shortcode_name() );
		if ( ( bool ) $atts[ 'id' ] ) {
			$html = PublicOrgUnits::render_meta_section( $atts[ 'id' ], 'contacts', false );
			if ( ! empty( $html ) ) {
				$html = sprintf(
					'<p><strong><a href="%1$s">%2$s</a></strong></p> %3$s',
					get_term_link( $atts[ 'id' ], 'org_units' ),
					get_term_field( 'name', $atts[ 'id' ], 'org_units', 'display' ),
					$html
				);
			}
		}
		return $html;
	}



}