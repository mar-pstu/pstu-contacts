<?php


namespace pstu_contacts;


/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              chomovva.ru
 * @since             2.0.0
 * @package           Pstu_contacts
 *
 * @wordpress-plugin
 * Plugin Name:       Контакты ПГТУ
 * Plugin URI:        cct.pstu.edu
 * Description:       Публикует список контактов и подразделений университета
 * Version:           2.0.0
 * Author:            chomovva
 * Author URI:        chomovva.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pstu_contacts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PSTU_CONTACTS_VERSION', '2.0.0' );
define( 'PSTU_CONTACTS_PLUGIN_NAME', 'pstu_contacts' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pstu_contacts-activator.php
 */
function activate_pstu_contacts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pstu_contacts-deactivator.php
 */
function deactivate_pstu_contacts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pstu_contacts' );
register_deactivation_hook( __FILE__, 'deactivate_pstu_contacts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_pstu_contacts() {
	$plugin = new Manager();
	$plugin->run();
}

run_pstu_contacts();