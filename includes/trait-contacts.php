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
trait Contacts {


	/**
	 * Возвращает список метаполей контакта
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function get_contacts_meta_sections() {
		return array(
			new SettingsSection( 'scientometrics', __( 'Наукометрика', $this->plugin_name ), array(
				new Field( 'orcid_id', __( 'ORCID iD', $this->plugin_name ), '', false ),
				new Field( 'google_scholar', __( 'Google Schola', $this->plugin_name ), '', false ),
				new Field( 'researcher_id', __( 'Researcher iD', $this->plugin_name ), '', false ),
				new Field( 'scopus_id', __( 'Scopus iD', $this->plugin_name ), '', false ),
				new Field( 'research_gate_id', __( 'ResearchGate iD', $this->plugin_name ), '', false ),
				new Field( 'pstu_eir', __( 'Электронный репозитoрий НТБ ПГТУ', $this->plugin_name ), '', false ),
			) ),
			new SettingsSection( 'contacts', __( 'Контактная информация', $this->plugin_name ), array(
				new Field( 'email', __( 'Электронная почта', $this->plugin_name ), '', false ),
				new Field( 'tel', __( 'Телефон', $this->plugin_name ), '', false ),
				new Field( 'address', __( 'Адрес', $this->plugin_name ), '', false ),
				new Field( 'time', __( 'Время работы', $this->plugin_name ), '', false ),
				new Field( 'schedule_admission', __( 'График приёма', $this->plugin_name ), '', false ),
			) ),
			new SettingsSection( 'socials', __( 'Профили социальных сетей', $this->plugin_name ), array(
				new Field( 'google_plus', __( 'Google Plus', $this->plugin_name ), '', false ),
				new Field( 'facebook', __( 'Facebook', $this->plugin_name ), '', false ),
				new Field( 'twitter', __( 'Twitter', $this->plugin_name ), '', false ),
				new Field( 'linkedin', __( 'LinkedIn', $this->plugin_name ), '', false ),
				new Field( 'youtube', __( 'YouTube', $this->plugin_name ), '', false ),
				new Field( 'instagram', __( 'Instagram', $this->plugin_name ), '', false ),
			) ),
			new SettingsSection( 'web', __( 'WEB', $this->plugin_name ), array(
				new Field( 'wikipedia', __( 'Wikipedia', $this->plugin_name ), '', false ),
			) ),
		);
	}


}