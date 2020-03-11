<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Fired during plugin activation
 *
 * @link       chomovva.ru
 * @since      2.0.0
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 * @author     chomovva <chomovva@gmail.com>
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function activate() {
		$contacts = get_posts( array(
			'numberposts' => -1,
			'post_type'   => 'contact',
		) );
		if ( is_array( $contacts ) && ! empty( $contacts ) ) {
			foreach ( $contacts as $contact ) {
				
				// исправляем фото
				self::update_contact_foto( $contact->ID );

			}
		}
		$org_units = get_terms( array(
			'taxonomy'    => 'org_units',
			'hide_empty'  => false,
		) );
		if ( is_array( $org_units ) && ! empty( $org_units ) ) {
			foreach ( $org_units as $org_unit ) {
				
				// исправляем руководителя подразделения и страницу с описанием
				self::update_org_unit_general_information( $contact->ID );

			}
		}
	}



	protected static function update_contact_foto( $post_id ) {
		$old_foto =  get_post_meta( $post_id, '_pstu_foto', true );
		if ( ! empty( $old_foto ) && is_array( $old_foto ) && isset( $old_foto[ 'foto_3_4' ] ) && ! empty( $old_foto[ 'foto_3_4' ] ) ) {
			$result = update_post_meta( $post_id, 'foto', array(
				'profil_foto' => $old_foto[ 'foto_3_4' ],
			) );
			if ( ( bool ) $result ) {
				delete_post_meta( $post_id, '_pstu_foto' );
			}
		}
	}



	protected static function update_org_unit_general_information( $term_id ) {
		$old_leader = get_term_meta( $term_id, '_pstu_leader', true );
		$about_page_id = get_term_meta( $term_id, '_pstu_about_page_id', true );
		$result = update_term_meta( $term_id, 'general_information', array(
			'leader_id'     => $old_leader,
			'about_page_id' => $about_page_id,
		) );
		if ( ( bool ) $result && ! is_wp_error( $result ) ) {
			delete_term_meta( $term_id, '_pstu_leader' );
			delete_term_meta( $term_id, '_pstu_first_deputy' );
			delete_term_meta( $term_id, '_pstu_about_page_id' );
		}
	}


}
