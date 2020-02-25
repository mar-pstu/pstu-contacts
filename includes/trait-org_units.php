<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Методы контактов
 *
 * @since      2.0.0
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 * @author     chomovva <chomovva@gmail.com>
 */
trait OrgUnits {


	/**
	 * Возвращает список метаполей контакта
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function get_org_units_meta_keys() {
		return array(
			new SettingsSection( 'contacts', __( 'Контактная информация', $this->plugin_name ), array(
				new Field( 'email', __( 'Электронная почта', $this->plugin_name ), '' ),
				new Field( 'tel', __( 'Телефон', $this->plugin_name ), '' ),
				new Field( 'address', __( 'Адрес', $this->plugin_name ), '' ),
				new Field( 'time', __( 'Время работы', $this->plugin_name ), '' ),
			) ),
			new SettingsSection( 'socials', __( 'Профили социальных сетей', $this->plugin_name ), array(
				new Field( 'facebook', __( 'Facebook', $this->plugin_name ), '' ),
				new Field( 'twitter', __( 'Twitter', $this->plugin_name ), '' ),
				new Field( 'linkedin', __( 'LinkedIn', $this->plugin_name ), '' ),
				new Field( 'youtube', __( 'YouTube', $this->plugin_name ), '' ),
				new Field( 'instagram', __( 'Instagram', $this->plugin_name ), '' ),
			) ),
			new SettingsSection( 'web', __( 'WEB', $this->plugin_name ), array(
				new Field( 'wikipedia', __( 'Wikipedia', $this->plugin_name ), '' ),
			) ),
		);
	}


}