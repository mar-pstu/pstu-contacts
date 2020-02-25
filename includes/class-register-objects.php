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
class Register_Objects {


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
				'name'               => __( 'Контакти', $this->plugin_name ),
				'singular_name'      => __( 'Контакт', $this->plugin_name ),
				'add_new'            => __( 'Додати контакт', $this->plugin_name ),
				'add_new_item'       => __( 'Додати новий контакт', $this->plugin_name ),
				'edit_item'          => __( 'Редагувати контакт', $this->plugin_name ),
				'new_item'           => __( 'Новый контакт', $this->plugin_name ),
				'view_item'          => __( 'Дивитись контакт', $this->plugin_name ),
				'search_items'       => __( 'Шукати контакт', $this->plugin_name ),
				'not_found'          => __( 'Не знайдено', $this->plugin_name ),
				'not_found_in_trash' => __( 'Не знайдено у кошику', $this->plugin_name ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Контакти', $this->plugin_name ),
			),
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
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
			'taxonomies'          => array(),
			'has_archive'         => false,
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
				'name'              => __( 'Підрозділи', $this->plugin_name ),
				'singular_name'     => __( 'Підрозділ', $this->plugin_name ),
				'search_items'      => __( 'Знайти запис', $this->plugin_name ),
				'all_items'         => __( 'Всі записи', $this->plugin_name ),
				'view_item '        => __( 'Переглянути запис', $this->plugin_name ),
				'parent_item'       => __( 'Батьківський запис', $this->plugin_name ),
				'parent_item_colon' => __( 'Батьківський запис', $this->plugin_name ),
				'edit_item'         => __( 'Редагувати запис', $this->plugin_name ),
				'update_item'       => __( 'Оновити запис', $this->plugin_name ),
				'add_new_item'      => __( 'Додати новий підрозділ', $this->plugin_name ),
				'new_item_name'     => __( 'Новий підрозділ', $this->plugin_name ),
				'menu_name'         => __( 'Підрозділи', $this->plugin_name ),
			),
			'description'           => '',
			'public'                => true,
			'publicly_queryable'    => null,
			'show_in_nav_menus'     => false,
			'show_ui'               => true,
			'show_tagcloud'         => false,
			'show_in_rest'          => null,
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


}
