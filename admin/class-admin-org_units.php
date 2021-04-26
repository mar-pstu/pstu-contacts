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


	use Controls;


	use Helpers;


	/**
	 * Сохранение произвольных полей таксономии
	 *
	 * @since    2.0.0
	 * @param    int      $term_id         Идентификатор термина
	 */
	public function save_taxonomy_meta( $term_id ) {
		if ( ! current_user_can( 'edit_term', $term_id ) ) return;
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], "update-tag_{$term_id}" ) && ! wp_verify_nonce( $_POST[ '_wpnonce_add-tag' ], 'add-tag' ) ) return;
		// сохранение стандартных секций настроек
		foreach ( apply_filters( 'pstu_contacts_get_meta_sections', 'org_units' ) as $section ) {
			$meta_value = array();
			foreach ( $section->get_fields() as &$field ) {
				if ( isset( $_REQUEST[ $section->get_key() ][ $field->get_key() ] ) ) {
					$new_value = $this->sanitize_field( $field->get_key(), $_REQUEST[ $section->get_key() ][ $field->get_key() ] );
					if ( ! empty( $new_value ) ) {
						$meta_value[ $field->get_key() ] = $new_value;
					}
				}
			}
			if ( empty( $meta_value ) ) {
				delete_term_meta( $term_id, $section->get_key() );
			} else {
				update_term_meta( $term_id, $section->get_key(), $meta_value );
			}
		}
		delete_metadata( 'post', '', "org_units_{$term_id}_order", '', true );
		if ( isset( $_POST[ $this->plugin_name . '_contacts_order' ] ) ) {
			$order = wp_parse_id_list( $_POST[ $this->plugin_name . '_contacts_order' ] );
			// update_term_meta( $term_id, 'order_contacts', $order );
			for ( $i = 0; $i < count( $order ); $i++ ) { 
				update_post_meta( $order[ $i ], "org_units_{$term_id}_order", str_pad( ( $i + 1 ), 3, '0', STR_PAD_LEFT ) );
			}
		}
	}


	/**
	 * Проверка полученного поля перед сохранением в базу
	 *
	 * @since    2.0.0
	 * @var      string    $key      Идентификатор поля
	 * @var      string    $value    Новое значение металополя
	 */
	protected function sanitize_field( $key, $value ) {
		$value = trim( $value );
		if ( ! empty( $value ) ) {
			switch ( $field ) {
				case 'leader':
				case 'about_page_id':
					$value = sanitize_key( $value );
					break;
				case 'email':
					$value = implode( ", ", $this->parse_emails_list( $value ) );
					break;
				case 'tel':
					$value = implode( ", ", $this->parse_list_of_telephone_numbers( $value ) );
					break;
				case 'address':
				default:
					$value = sanitize_text_field( $value );
					break;
			}
		}
		return $value;
	}


	/**
	 * Выводит поля при создании нового термина
	 *
	 * @since    2.0.0
	 * @param    string    $taxonomy_slug   Идентификатор таксономии
	 */
	public function add_taxonomy_fields( $taxonomy_slug ) {
		$this->render_default_sections( false, dirname( __FILE__ ) . '/partials/settings-section.php', dirname( __FILE__ ) . '/partials/org_units-add-section-field.php' );
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
		$this->render_default_sections( $term->term_id, dirname( __FILE__ ) . '/partials/org_units-edit-settings-section.php', dirname( __FILE__ ) . '/partials/org_units-edit-section-field.php' );
		// секция сортировок, появляется только при редактировании подразделения
		ob_start();
		$label = __( 'Сортировка контактов в подразделении', $this->plugin_name );
		$id = $this->plugin_name . '_contacts_order';
		$control = $this->render_order_control( $id, get_posts( array(
			'numberposts' => -1,
			'post_type'   => 'contact',
			'meta_query'  => array(
				'relation'  => 'OR',
				[
					'meta_exists_clause' => array(
						'key'      => "org_units_{$term->term_id}_order",
						'compare'  => 'NOT EXISTS',
					),
				],
				[
					'relation'  => 'AND',
					'meta_exists_clause' => array(
						'key'      => "org_units_{$term->term_id}_order",
						'compare'  => 'EXISTS',
					),
					'meta_value_clause' => array(
						'key'      => "org_units_{$term->term_id}_order",
						'type'     => 'NUMERIC',
					),
				],
			),
			'tax_query'   => array(
				'relation'  => 'AND',
				array(
					'taxonomy' => 'org_units',
					'field'    => 'term_id',
					'terms'    => $term->term_id,
					'operator' => 'IN',
					'include_children' => false,
				),
			),
			'orderby'     => array(
				'meta_exists_clause' => 'ASC',
				'meta_value_clause'  => 'DESC',
			),
		) ), array( 'id' => $id ) );
		if ( empty( $control ) ) {
			$control = __( 'Контакты не добавлены в подразделение', $this->plugin_name );
		}
		include dirname( __FILE__ ) . '/partials/org_units-edit-section-field.php';
		$title = '';
		$key = $this->plugin_name . '_orders';
		$content = ob_get_contents();
		ob_end_clean();
		wp_add_inline_script( 'jquery-ui-sortable', "jQuery( document ).ready( function () { jQuery( '#{$id}' ).sortable(); } );", 'after' );
		include dirname( __FILE__ ) . '/partials/org_units-edit-settings-section.php';
	}


	/**
	 * Формирует секцию со "стандартными" параметрами подразделения
	 * 
	 * @since    2.0.0
	 * @var      int       $term_id          Идентификатор подразделения
	 * @var      string    $section_path     Путь к шаблону секции ( при добавлении и редактировании подразделения шаблоны разные )
	 * @var      string    $field_path       Путь к шаблону секции ( при добавлении и редактировании подразделения шаблоны разные )
	 */ 
	protected function render_default_sections( $term_id, $section_path, $field_path ) {
		foreach ( apply_filters( 'pstu_contacts_get_meta_sections', 'org_units' ) as $section ) {
			$meta = ( ( bool ) $term_id ) ? get_term_meta( $term_id, $section->get_key(), true ) : array();
			ob_start();
			foreach ( $section->get_fields() as &$field ) {
				if ( isset( $meta[ $field->get_key() ] ) ) {
					$field->value = $meta[ $field->get_key() ];
				}
				$label = $field->label;
				$id = $this->plugin_name . '_' . $field->get_key();
				$name = $section->get_key() . '['. $field->get_key() . ']';
				$control = '';
				if ( 'about_page_id' == $field->get_key() ) {
					// выбор странциы с описанием
					$control = $this->render_dropdown( $name, get_pages( array(
						'sort_order'   => 'ASC',
						'sort_column'  => 'post_title',
					) ), array(
						'id'               => $id,
						'show_option_none' => '-',
						'selected'         => $field->value
					) );
					if ( empty( $control ) ) {
						$control = __( 'Страницы не созданиы', $this->plugin_name );
					} else {
						wp_add_inline_script( 'select2', "jQuery( document ).ready( function() { jQuery( '#{$id}' ).select2(); } );", 'after' );
					}
				} elseif ( 'leader_id' == $field->get_key() ) {
					// выбор руководителя
					$control = $this->render_dropdown( $name, get_posts( array(
						'numberposts' => -1,
						'post_type'   => 'contact',
					) ), array(
						'id'               => $id,
						'show_option_none' => '-',
						'selected'         => $field->value,
					) );
					if ( empty( $control ) ) {
						$control = __( 'Контакты не добавлены в подразделение', $this->plugin_name );
					} else {
						wp_add_inline_script( 'select2', "jQuery( document ).ready( function() { jQuery( '#{$id}' ).select2(); } );", 'after' );
					}
				} else {
					// стандартный тектовые поля
					$control = $this->render_input( $name, 'text', array(
						'value' => $field->value,
						'class' => 'form-control',
						'id'    => $id,
					) );
				}
				include $field_path;
			}
			// переменные секции
			$title = $section->title;
			$key = $section->get_key();
			$content = ob_get_contents();
			ob_end_clean();
			include $section_path;
		}
	}



	/**
	 * Регистрирует стили для админки
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), '4.0.13', 'all' );
	}


	/**
	 * Регистрирует скрипты для админки
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.js', array( 'jquery' ), '4.0.13', false );
	}

}
