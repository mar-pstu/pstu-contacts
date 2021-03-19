<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/includes
 * @author     chomovva <chomovva@gmail.com>
 */
class Manager {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Pstu_contacts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;


	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		$this->version = ( defined( 'PSTU_CONTACTS_VERSION' ) ) ? PSTU_CONTACTS_VERSION : '2.0.0';
		$this->plugin_name =  ( defined( 'PSTU_CONTACTS_PLUGIN_NAME' ) ) ? PSTU_CONTACTS_PLUGIN_NAME : 'pstu_contacts';
		$this->load_dependencies();
		$this->set_locale();
		$this->init();
		if ( is_admin() ) {
			$this->define_admin_hooks();
		} else {
			$this->define_public_hooks();
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pstu_contacts_Loader. Orchestrates the hooks of the plugin.
	 * - Pstu_contacts_i18n. Defines internationalization functionality.
	 * - Pstu_contacts_Admin. Defines all hooks for the admin area.
	 * - Pstu_contacts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/trait-helpers.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/trait-controls.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstract-part.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstract-shortcode.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-field.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-settings-section.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-init.php';		

		/**
		 * Класс для запуска хукув, фильтров и шорткодов
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loader.php';

		/**
		 * Класс локализации плагина
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

		/**
		 * Классы, которые отвечают за админку
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/abstruct-admin-part.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-settings-manager.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-update.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-org_units.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-contact.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-gutenberg.php';

		/**
		 * Классы, которые отвечают за публичную часть сайта
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public-contact.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public-org_units.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-org_unit.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-leader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-contact.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-org_unit-description.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-org_unit-contacts-info.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-contacts-catalog.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-org_units-catalog.php';

		$this->loader = new Loader();

	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pstu_contacts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new I18n( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}


	/**
	 * Регистрирует новые типы постов и таксономии
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function init() {
		$plugin_register_objects = new Init( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_register_objects, 'register_post_types' );
		$this->loader->add_action( 'init', $plugin_register_objects, 'register_taxonomies' );
		$this->loader->add_filter( $this->get_plugin_name() . '_get_meta_sections', $plugin_register_objects, 'get_meta_sections', 10, 1 );
		$update_tab_class = new AdminUpdateTab( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $update_tab_class, 'check_update', 10, 0 );
		$this->loader->add_filter( $this->get_plugin_name() . '_settings-tabs', $update_tab_class, 'add_settings_tab', 10, 1 );
		$this->loader->add_action( $this->get_plugin_name() . '_settings-form_' . $update_tab_class->get_tab_name(), $update_tab_class, 'render_tab', 10, 1 );
		$this->loader->add_action( $this->get_plugin_name() . '_settings-ajax_' . $update_tab_class->get_tab_name(), $update_tab_class, 'run_ajax', 10, 0 );
		$this->loader->add_action( 'get_header', $update_tab_class, 'enable_maintenance_mode', 10, 0 );
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$update_tab_class = new AdminUpdateTab( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $update_tab_class, 'enqueue_scripts', 10, 0 );

		// страница настроек плагина
		$settings_manager_class = new AdminSettingsManager( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $settings_manager_class, 'add_page' );
		$this->loader->add_action( 'current_screen', $settings_manager_class, 'run_tab' );
		$this->loader->add_action( 'admin_init', $settings_manager_class, 'register_settings', 10, 0 );
		$this->loader->add_action( 'wp_ajax_' . $this->get_plugin_name() . '_settings', $settings_manager_class, 'run_ajax', 10, 0 );
		
		$part_admin_org_units = new AdminOrgUnits( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'create_org_units', $part_admin_org_units, 'save_taxonomy_meta', 10, 1 );
		$this->loader->add_action( 'edited_org_units', $part_admin_org_units, 'save_taxonomy_meta', 10, 1 );
		$this->loader->add_action( 'org_units_add_form_fields', $part_admin_org_units, 'add_taxonomy_fields' );
		$this->loader->add_action( 'org_units_edit_form_fields', $part_admin_org_units, 'edit_taxonomy_fields', 10, 2 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_org_units, 'enqueue_styles', 10, 0 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_org_units, 'enqueue_scripts', 10, 0 );
		
		$part_admin_contact = new AdminContact( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'add_meta_boxes', $part_admin_contact, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $part_admin_contact, 'save_post', 10, 1 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_contact, 'enqueue_styles', 10, 0 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_contact, 'enqueue_scripts', 10, 0 );
		
		$part_settings_page = new AdminGutenberg( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $part_settings_page, 'register_blocks', 10, 0 );
		$this->loader->add_action( 'enqueue_block_assets', $part_settings_page, 'enqueue_block_assets', 10, 0 );

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		
		$part_public_contact = new PublicContact( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $part_public_contact, 'enqueue_styles', 10, 0 );
		$this->loader->add_action( 'wp_enqueue_scripts', $part_public_contact, 'enqueue_scripts', 10, 0 );
		$this->loader->add_action( $this->get_plugin_name() . '_single_profil_foto', $part_public_contact, 'the_contact_profil_foto', 10, 2 );
		$this->loader->add_action( $this->get_plugin_name() . '_the_single_contact_info', $part_public_contact, 'render_meta_section', 10, 3 );
		$this->loader->add_filter( 'the_content', $part_public_contact, 'render_single_content', 10, 1 );
		
		$part_public_org_units = new PublicOrgUnits( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $part_public_org_units, 'enqueue_styles', 10, 0 );
		$this->loader->add_action( 'wp_enqueue_scripts', $part_public_org_units, 'enqueue_scripts', 10, 0 );
		// $this->loader->add_action( 'pre_get_posts', $part_public_org_units, 'change_order', 10, 1 );
		$this->loader->add_action( 'pstu_contacts_the_single_org_units_info', $part_public_org_units, 'render_meta_section', 10, 3 );
		$this->loader->add_action( 'pstu_contact_profil_foto', $part_public_contact, 'the_contact_profil_foto', 10, 1 );
		$this->loader->add_filter( 'template_include', $part_public_org_units, 'archive_template_include', 10, 1 );
		
		$part_public_shortcode_org_unit = new PublicShortcodeOrgUnit( 'pstu_org_unit', $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( $part_public_shortcode_org_unit->get_shortcode_name(), $part_public_shortcode_org_unit, 'manager' );
		
		$part_public_shortcode_leader = new PublicShortcodeOrgUnitLeader( 'pstu_org_unit_leader', $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( $part_public_shortcode_leader->get_shortcode_name(), $part_public_shortcode_leader, 'manager' );
		
		$part_public_shortcode_contact = new PublicShortcodeContact( 'pstu_contact', $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( $part_public_shortcode_contact->get_shortcode_name(), $part_public_shortcode_contact, 'manager' );
		
		$part_public_shortcode_org_unit_description = new PublicShortcodeOrgUnitDescription( 'pstu_org_unit_description', $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( $part_public_shortcode_org_unit_description->get_shortcode_name(), $part_public_shortcode_org_unit_description, 'manager' );

		$part_public_shortcode_org_unit_contact_info = new PublicShortcodeOrgUnitContactInfo( 'pstu_person_contacts_info', $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( $part_public_shortcode_org_unit_contact_info->get_shortcode_name(), $part_public_shortcode_org_unit_contact_info, 'manager' );

		$part_public_shortcode_contacts_catalog = new PublicShortcodeaContactsCatalog( 'contacts_catalog', $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( $part_public_shortcode_contacts_catalog->get_shortcode_name(), $part_public_shortcode_contacts_catalog, 'manager' );

		$part_public_shortcode_org_units_catalog = new PublicShortcodeaOrgUnitsCatalog( 'org_units_catalog', $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( $part_public_shortcode_org_units_catalog->get_shortcode_name(), $part_public_shortcode_org_units_catalog, 'manager' );
	}


	/**
	 * Запускает все зарегистрированные хуки WordPress
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}


	/**
	 * Имя плагина используется для уникальной идентификации его
	 * в контексте WordPress, как уникальный идентификатор для получения
	 * строки перевода и при сохранении некоторых настроек.
	 *
	 * @since     2.0.0
	 * @return    string    Идентификатор плагина
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @return    Pstu_contacts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}


	/**
	 * Возвращает номер версии
	 *
	 * @since     2.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
