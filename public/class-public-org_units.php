<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public
 * @author     chomovva <chomovva@gmail.com>
 */
class PublicOrgUnits extends Part {


	public function archive_template_include( $template ) {
		if ( is_tax( 'org_units' ) ) {
			$template = get_theme_file_path( 'taxonomy-org_units.php' );
			if ( ( bool ) $template ) {
				$template = dirname( __FILE__ ) . '/partials/taxonomy-org_units.php';
			}
		}
		return apply_filters( 'org_units_archive_template_include', $template );
	}


	public static function render_meta_section( $term_id, $key, $header_tag = 'h3' ) {
		foreach ( apply_filters( 'pstu_contacts_get_meta_sections', 'org_units' ) as $section ) {
			if ( $section->get_key() != $key ) {
				continue;
			}
			$content = '';
			$meta = get_term_meta( $term_id, $key, true );
			foreach ( $section->get_fields() as $field ) {
				if ( ( isset( $meta[ $field->get_key() ] ) ) && ! empty( $meta[ $field->get_key() ] ) ) {
					$content .= '<li>' . $field->label . ': ' . $meta[ $field->get_key() ] . '</li>';
				}
			}
			if ( ! empty( $content ) ) {
				$header = sprintf( '<%1$s>%2$s</%1$s>', $header_tag, $section->title );
				printf(
					'<section id="post-%1$s-%2$s" class="%2$s">%3$s<ul>%4$s</ul></section>',
					$term_id,
					$key,
					$header,
					$content
				);
			}
		}
	}


	/**
	 * Регистрирует стили для публичной части сайта
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flexboxgrid.css', array(), '6.3.2', 'all' );
	}

	/**
	 * Регистрирует скрипты для публичной части сайта
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pstu_contacts-public.js', array( 'jquery' ), $this->version, false );
	}



	/**
	 * Изменение порядка вывода постов
	 * 
	 * @since    2.0.0
	 * @var      WP_Query   $query     Объект запроса, передаётся по ссылке
	 */
	public function change_order( $query ) {
		if ( isset( $query->queried_object->taxonomy ) && 'org_units' ==  $query->queried_object->taxonomy ) {
			$query->set( 'orderby', array(
				'meta_exists_clause' => 'ASC',
				'meta_value_clause'  => 'DESC',
			) );
			$query->set( 'meta_query', array(
				'relation'  => 'AND',
				'meta_exists_clause' => array(
					'key'      => "org_units_{$query->queried_object->term_id}_order",
					'compare'  => 'EXISTS',
				),
				'meta_value_clause' => array(
					'key'      => "org_units_{$query->queried_object->term_id}_order",
					'type'     => 'numeric',
				),
			) );
		}
	}


}
