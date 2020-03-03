<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Регистрирует произвольные типы записи и ппроизвольные таксономии
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 * @author     chomovva <chomovva@gmail.com>
 */
class Init {


	/**
	 * Уникальный идентификатор для получения строки перевода.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $plugin_name;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param    string    $plugin_name        Уникальный идентификатор для получения строки перевода.
	 */
	function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
	}


	/**
	 * Регистрирует тип записи "Контакт"
	 *
	 * @since    2.0.0
	 */
	public function register_post_types() {
		register_post_type( 'contact', array(
			'label'  => null,
			'labels' => array(
				'name'               => __( 'Контакты', $this->plugin_name ),
				'singular_name'      => __( 'Контакт', $this->plugin_name ),
				'add_new'            => __( 'Добавить контакт', $this->plugin_name ),
				'add_new_item'       => __( 'Добавить новый контакт', $this->plugin_name ),
				'edit_item'          => __( 'Редактировать контакт', $this->plugin_name ),
				'new_item'           => __( 'Новый контакт', $this->plugin_name ),
				'view_item'          => __( 'Смотреть контакт', $this->plugin_name ),
				'search_items'       => __( 'Искать контакт', $this->plugin_name ),
				'not_found'          => __( 'Не найдено', $this->plugin_name ),
				'not_found_in_trash' => __( 'Не в корзине не найдено', $this->plugin_name ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Контакты', $this->plugin_name ),
			),
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => null,
			'show_in_rest'        => null,
			'rest_base'           => null,
			'menu_position'       => '6.67',
			'menu_icon'           => 'dashicons-groups',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'taxonomies'          => array( 'org_units' ),
			'has_archive'         => true,
			'rewrite'             => true,
			'query_var'           => true,
		) );
	}


	/**
	 * Регистрирует тип записи "Подразделение"
	 *
	 * @since    2.0.0
	 */
	public function register_taxonomies() {
		register_taxonomy( 'org_units', array( 'contact' ), array(
			'label'                 => '', // определяется параметром $labels->name
			'labels'                => array(
				'name'              => __( 'Подразделения', $this->plugin_name ),
				'singular_name'     => __( 'Подразделение', $this->plugin_name ),
				'search_items'      => __( 'Найти запись', $this->plugin_name ),
				'all_items'         => __( 'Все записи', $this->plugin_name ),
				'view_item '        => __( 'Просмотреть запись', $this->plugin_name ),
				'parent_item'       => __( 'Родительская запись', $this->plugin_name ),
				'parent_item_colon' => __( 'Родительская запись', $this->plugin_name ),
				'edit_item'         => __( 'Редактировать запись', $this->plugin_name ),
				'update_item'       => __( 'Обновить запись', $this->plugin_name ),
				'add_new_item'      => __( 'Добавить новое подразделение', $this->plugin_name ),
				'new_item_name'     => __( 'Новое подразделение', $this->plugin_name ),
				'menu_name'         => __( 'Подразделения', $this->plugin_name ),
			),
			'description'           => '',
			'public'                => true,
			'publicly_queryable'    => true,
			'query_var'             => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_tagcloud'         => true,
			'show_in_rest'          => true,
			'rest_base'             => null,
			'hierarchical'          => true,
			'update_count_callback' => '',
			'rewrite'               => true,
			'capabilities'          => array(),
			'meta_box_cb'           => 'post_categories_meta_box',
			'show_admin_column'     => true,
			'_builtin'              => false,
			'show_in_quick_edit'    => null,
		) );
	}


	/**
	 * Возвращает список метаполей контакта
	 *
	 * @since    2.0.0
	 * @access   protected
	 */
	protected function get_contact_meta_sections() {
		return array(
			new SettingsSection( 'foto', __( 'Фото', $this->plugin_name ), array(
				new Field( 'profil_foto', __( 'Фото профиля', $this->plugin_name ), '', false ),
			) ),
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



	/**
	 * Возвращает список метаполей подразделения
	 *
	 * @since    2.0.0
	 * @access   protected
	 */
	protected function get_org_units_meta_sections() {
		return array(
			new SettingsSection( 'general_information', __( 'Общая информация', $this->plugin_name ), array(
				new Field( 'leader_id', __( 'Руководитель подразделения', $this->plugin_name ), '', false ),
				new Field( 'about_page_id', __( 'Страница с описанием', $this->plugin_name ), '', false ),
			) ),
			new SettingsSection( 'contacts', __( 'Контактная информация', $this->plugin_name ), array(
				new Field( 'email', __( 'Электронная почта', $this->plugin_name ), '', false ),
				new Field( 'tel', __( 'Телефон', $this->plugin_name ), '', false ),
				new Field( 'address', __( 'Адрес', $this->plugin_name ), '', false ),
				new Field( 'time', __( 'Время работы', $this->plugin_name ), '', false ),
			) ),
			new SettingsSection( 'socials', __( 'Профили социальных сетей', $this->plugin_name ), array(
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


	/**
	 * Возвращает список метаполей подразделения
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function get_meta_sections( $key ) {
		$result = array();
		switch ( $key ) {
			case 'contact':
				$result = $this->get_contact_meta_sections();
				break;
			case 'org_units':
				$result = $this->get_org_units_meta_sections();
				break;
		}
		return $result;
	}



}
