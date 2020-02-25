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
	 * Уникальный идентификатор для получения строки перевода.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $textdomain;	


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
		if ( defined( 'PSTU_CONTACTS_VERSION' ) ) {
			$this->version = PSTU_CONTACTS_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'pstu_contacts';
		$this->textdomain = 'pstu-contacts';
		$this->load_dependencies();
		$this->set_locale();
		$this->register_objects();
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

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/trait-contacts.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/trait-org_units.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstract-part.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-field.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-settings-section.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-register-objects.php';		

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-org_units.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-contact.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public.php';

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
	private function register_objects() {
		$plugin_register_objects = new Register_Objects( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_register_objects, 'register_post_types' );
		$this->loader->add_action( 'init', $plugin_register_objects, 'register_taxonomies' );
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$part_admin_contact = new AdminContact( $this->get_plugin_name(), $this->get_version() );
		$part_admin_org_units = new AdminOrgUnits( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'create_org_units', $part_admin_org_units, 'save_taxonomy_meta' );
		$this->loader->add_action( 'edited_org_units', $part_admin_org_units, 'save_taxonomy_meta' );
		$this->loader->add_action( 'org_units_add_form_fields', $part_admin_org_units, 'add_taxonomy_fields' );
		$this->loader->add_action( 'org_units_edit_form_fields', $part_admin_org_units, 'edit_taxonomy_fields', 10, 2 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_org_units, 'enqueue_styles', 10, 0 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_org_units, 'enqueue_scripts', 10, 0 );
		$this->loader->add_action( 'add_meta_boxes', $part_admin_contact, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $part_admin_contact, 'save_post', 10, 1 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_contact, 'enqueue_styles', 10, 0 );
		$this->loader->add_action( 'admin_enqueue_scripts', $part_admin_contact, 'enqueue_scripts', 10, 0 );
	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// $plugin_public = new Pstu_contacts_Public( $this->get_plugin_name(), $this->get_version() );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    string    The name of the plugin.
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
