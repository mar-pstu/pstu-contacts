<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Регистрация блоков гутенберги
 *
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public
 * @author     chomovva <chomovva@gmail.com>
 */
class AdminGutenberg extends Part {



	public function enqueue_block_assets() {
		if ( ! wp_doing_ajax() ) {
			wp_enqueue_style( "{$this->plugin_name}-org-unit", plugin_dir_url( __FILE__ ) . 'css/org_unit.css', array(), $this->version, 'all' );
			wp_enqueue_script( "{$this->plugin_name}-org-unit", plugin_dir_url( __FILE__ ) . 'js/org_unit.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ), $this->version, true );
			wp_localize_script( 'wp-blocks', 'rest_url', get_rest_url() );
		}
	}



	public function register_blocks() {
		$alias = str_replace( '_', '-', $this->plugin_name );
		register_block_type( "{$alias}/org-unit", array(
			'editor_style' => "{$this->plugin_name}-editor-org-unit",
			'editor_script' => "{$this->plugin_name}-editor-org-unit",
		) );
		// register_block_type( "{$this->plugin_name}/org_unit-leader", array(
		// 	'editor_style' => "{$this->plugin_name}-editor-org_unit-leader",
		// 	'editor_script' => "{$this->plugin_name}-editor-org_unit-leader",
		// ) );
		// register_block_type( "{$this->plugin_name}/single-contact", array(
		// 	'editor_style' => "{$this->plugin_name}-editor-single-contact",
		// 	'editor_script' => "{$this->plugin_name}-editor-single-contact",
		// ) );
	}


}