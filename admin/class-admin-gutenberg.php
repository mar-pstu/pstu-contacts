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
			wp_enqueue_style( "{$this->plugin_name}-editor", plugin_dir_url( __FILE__ ) . 'css/editor.css', array(), $this->version, 'all' );
			wp_enqueue_script( "{$this->plugin_name}-editor-org-unit", plugin_dir_url( __FILE__ ) . 'js/org-unit.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ), $this->version, true );
			wp_enqueue_script( "{$this->plugin_name}-editor-org-unit-leader", plugin_dir_url( __FILE__ ) . 'js/org-unit-leader.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ), $this->version, true );
			wp_enqueue_script( "{$this->plugin_name}-editor-single-contact", plugin_dir_url( __FILE__ ) . 'js/single-contact.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ), $this->version, true );
			wp_enqueue_script( "{$this->plugin_name}-editor-org-unit-description", plugin_dir_url( __FILE__ ) . 'js/org-unit-description.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ), $this->version, true );
			wp_enqueue_script( "{$this->plugin_name}-editor-org-unit-contact-info", plugin_dir_url( __FILE__ ) . 'js/org-unit-contact-info.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ), $this->version, true );
			wp_enqueue_script( "{$this->plugin_name}-editor-person-contact-info", plugin_dir_url( __FILE__ ) . 'js/person-contact-info.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ), $this->version, true );
			wp_enqueue_script( "{$this->plugin_name}-editor-contacts-catalog", plugin_dir_url( __FILE__ ) . 'js/contacts-catalog.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n' ), $this->version, true );
			wp_enqueue_script( "{$this->plugin_name}-editor-org-units-catalog", plugin_dir_url( __FILE__ ) . 'js/org-units-catalog.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n' ), $this->version, true );
			wp_localize_script( 'wp-blocks', 'rest_url', get_rest_url() );
		}
	}



	public function register_blocks() {
		$alias = str_replace( '_', '-', $this->plugin_name );
		register_block_type( "{$alias}/org-unit", array(
			'editor_script' => "{$this->plugin_name}-editor-org-unit",
		) );
		register_block_type( "{$alias}/org-unit-leader", array(
			'editor_script' => "{$this->plugin_name}-editor-org-unit-leader",
		) );
		register_block_type( "{$alias}/single-contact", array(
			'editor_script' => "{$this->plugin_name}-editor-single-contact",
		) );
		register_block_type( "{$alias}/org-unit-description", array(
			'editor_script' => "{$this->plugin_name}-editor-org-unit-description",
		) );
		register_block_type( "{$alias}/org-unit-contact-info", array(
			'editor_script' => "{$this->plugin_name}-editor-org-unit-contact-info",
			'editor_style'  => "{$this->plugin_name}-editor",
		) );
		register_block_type( "{$alias}/person-contact-info", array(
			'editor_script' => "{$this->plugin_name}-editor-person-contact-info",
		) );
		register_block_type( "{$alias}/contacts-catalog", array(
			'editor_script' => "{$this->plugin_name}-editor-contacts-catalog",
		) );
		register_block_type( "{$alias}/org-units-catalog", array(
			'editor_script' => "{$this->plugin_name}-editor-org-units-catalog",
		) );
	}


}