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
		$options = get_option( PSTU_CONTACTS_PLUGIN_NAME );
		if ( ! is_array( $options ) && ! array_key_exists( 'version', $options ) && empty( $options[ 'version' ] ) ) {
			$options = [
				'version'           => PSTU_CONTACTS_VERSION,
				'updating_progress' => false,
			];
			update_option( PSTU_CONTACTS_PLUGIN_NAME, $options );
		}
	}



}
