<?php


namespace pstu_contacts;


/**
 * Создаёт страницу настроек плагина в админке
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/admin
 * @author     chomovva <chomovva@gmail.com>
 */
class SettingsPage extends Part {


	public function add_page() {
		add_submenu_page(
			'edit.php?post_type=contact',
			__( 'Настройки', $this->plugin_name ),
			__( 'Настройки', $this->plugin_name ),
			'manage_options',
			$this->plugin_name . '_settings',
			array( $this, 'render_page' ),
			null
		);
	}


	public function render_page() {
		echo '<div class="wrap">';
		echo '<h2>'. get_admin_page_title() .'</h2>';
		echo '</div>';
	}


	public function register_settings() {
		// регистрируем настройки
	}


	public function render_setting( $args ) {
		// выводим настройку или поле
	}


	protected function validate_settings( $fields ) {
		return $fields;
	}


}