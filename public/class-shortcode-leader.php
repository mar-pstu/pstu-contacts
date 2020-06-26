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
class PublicShortcodeOrgUnitLeader extends Shortcode {


	public function manager( $atts ) {
		global $post;
		$html = '';
		$atts = shortcode_atts( array(
			'id'                => false,
		), $atts, $this->get_shortcode_name() );
		$general_information = get_term_meta( $atts[ 'id' ], 'general_information', true );
		if ( isset( $general_information[ 'leader_id' ] ) && ! empty( $general_information[ 'leader_id' ] ) ) {
			$leader = get_post( $general_information[ 'leader_id' ], OBJECT, 'raw' );
			if ( is_object( $leader ) && ! is_wp_error( $leader ) ) {
				setup_postdata( $post = $leader );
				ob_start();
				include dirname( __FILE__ ) . "/partials/person-bar.php";
				$html = '<div class="row">' . ob_get_contents() . '</div>';
				ob_end_clean();
				wp_reset_postdata();
			}
		}
		return $html;
	}


}