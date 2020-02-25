<?php


namespace pstu_contacts;


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/admin
 * @author     chomovva <chomovva@gmail.com>
 */
class AdminOrgUnits extends Part {


	use OrgUnits;


	use Controls;


	use Helpers;


	/**
	 * Сохранение произвольных полей таксономии
	 *
	 * @since    2.0.0
	 * @param    int      $term_id         Идентификатор термина
	 */
	public function save_taxonomy_meta( $term_id ) {
		//
	}


	/**
	 * Проверка полученного поля перед сохранением в базу
	 *
	 * @since    2.0.0
	 * @var      string    $key      Идентификатор поля
	 * @var      string    $value    Новое значение металополя
	 */
	protected function sanitize_field( $key, $new_value ) {
		return $new_value;
	}


	/**
	 * Выводит поля при создании нового термина
	 *
	 * @since    2.0.0
	 * @param    string    $taxonomy_slug   Идентификатор таксономии
	 */
	public function add_taxonomy_fields( $taxonomy_slug ) {
		foreach ( $this->get_org_units_meta_keys() as $section ) {
			ob_start();
			foreach ( $section->get_fields() as &$field ) {
				$label = $field->label;
				$id = $this->plugin_name . '_' . $field->get_key();
				$control = $this->render_input( $section->get_key() . '['. $field->get_key() . ']', 'text', array(
					'value' => $field->value,
					'class' => 'form-control',
					'id'    => $id,
				) );
				include dirname( __FILE__ ) . '\partials\org_units-add-section-field.php';
			}
			$label = $section->label;
			$key = $section->get_key();
			$content = ob_get_contents();
			ob_end_clean();
			include dirname( __FILE__ ) . '\partials\settings-section.php';
		}
	}


	/**
	 * Добавляет дополнительные поля (произвольные поля или метаполя) на страницу
	 * редактирования элементов таксономии
	 *
	 * @since    2.0.0
	 * @param    WP_Term   $term             Термин
	 * @param    string    $taxonomy         Идентификатор таксономии
	 */
	public function edit_taxonomy_fields( $term, $taxonomy ) {
		foreach ( $this->get_org_units_meta_keys() as $section ) {
			$meta = get_term_meta( $term->term_id, $section->get_key(), true );
			ob_start();
			foreach ( $section->get_fields() as &$field ) {
				if ( isset( $meta[ $field->get_key() ] ) ) {
					$field->value = $meta[ $field->get_key() ];
				}
				$label = $field->label;
				$id = $this->plugin_name . '_' . $field->get_key();
				$control = $this->render_input( $section->get_key() . '['. $field->get_key() . ']', 'text', array(
					'value' => $field->value,
					'class' => 'form-control',
					'id'    => $id,
				) );
				include dirname( __FILE__ ) . '\partials\org_units-edit-section-field.php';
			}
			$label = $section->label;
			$key = $section->get_key();
			$content = ob_get_contents();
			ob_end_clean();
			include dirname( __FILE__ ) . '\partials\org_units-edit-settings-section.php';
		}
	}


	/**
	 * Регистрирует стили для админки
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), '4.0.13', 'all' );
	}


	/**
	 * Регистрирует скрипты для админки
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.js', array( 'jquery' ), '4.0.13', false );
	}

}
