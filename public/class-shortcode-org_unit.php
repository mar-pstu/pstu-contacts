<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Класс для решистрации шорткода и компонента гутенберга.
 * Вывод списк контактов подразделения.
 *
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public
 * @author     chomovva <chomovva@gmail.com>
 */
class PublicShortcodeOrgUnit extends Shortcode {


	public function manager( $atts ) {
		global $post;
		$html = '';
		$atts = shortcode_atts( array(
			'id'                => false,
			'leader'            => 1,
			'contact_template'  => 'card',
		), $atts, $this->shortcode_name );
		if ( ! in_array( $atts[ 'contact_template' ], array( 'card', 'bar', 'item' ) ) ) {
			$atts[ 'contact_template' ] = 'card';
		}
		$org_unit = get_term( $atts[ 'id' ], 'org_units', OBJECT, 'raw' );
		if ( is_object( $org_unit ) && ! is_wp_error( $org_unit ) ) {
			$general_information = get_term_meta( $org_unit->term_id, 'general_information', true );
			$leader_id = '';
			$leader_id = ( isset( $general_information[ 'leader_id' ] ) && ! empty( $general_information[ 'leader_id' ] ) ) ? $general_information[ 'leader_id' ] : '';
			$contacts = get_posts( array(
				'numberposts' => -1,
				'post_type'   => 'contact',
				'exclude'     => $leader_id,
				'orderby'     => array(
					'meta_exists_clause' => 'ASC',
					'meta_value_clause'  => 'DESC',
				),
				'meta_query'  => [
					'relation'  => 'OR',
					[
						'meta_exists_clause' => array(
							'key'      => "org_units_{$atts[ 'id' ]}_order",
							'compare'  => 'NOT EXISTS',
						),
					],
					[
						'relation'  => 'AND',
						'meta_exists_clause' => array(
							'key'      => "org_units_{$atts[ 'id' ]}_order",
							'compare'  => 'EXISTS',
						),
						'meta_value_clause' => array(
							'key'      => "org_units_{$atts[ 'id' ]}_order",
							'type'     => 'numeric',
						),
					],
				],
				'tax_query'   => array(
					'relation'  => 'AND',
					array(
						'taxonomy'  => 'org_units',
						'field'     => 'term_id',
						'terms'	    => $org_unit->term_id,
						'operator'  => 'IN',
					),
				),
			) );
			if ( is_array( $contacts ) && ! empty( $contacts ) ) {
				ob_start();
				foreach ( $contacts as $post ) {
					setup_postdata( $post );
					include dirname( __FILE__ ) . "/partials/person-{$atts[ 'contact_template' ]}.php";
				}
				wp_reset_postdata();
				$html = ob_get_contents();
				ob_end_clean();
			}
			$html = '<div class="row">' . $html . '</div>';
			if ( ( bool ) $atts[ 'leader' ] ) {
				$leader = get_post( $leader_id , OBJECT, 'raw' );
				if ( is_object( $leader ) && ! is_wp_error( $leader ) ) {
					ob_start();
					setup_postdata( $post = $leader );
					include dirname( __FILE__ ) . "/partials/person-bar.php";
					wp_reset_postdata();
					$html = '<p class="lead"><b>' . __( 'Руководитель', $this->plugin_name ) . '</b></p>' . ob_get_contents() . '<p class="lead font-bold">' . __( 'Коллектив', $this->plugin_name ) . '</p>' . $html;
					ob_end_clean();
				}
			}
		}
		return $html;
	}



}