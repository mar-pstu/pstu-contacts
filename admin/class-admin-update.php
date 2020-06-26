<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Класс отвечающий за страницу экспорта
 * конкурсных работ
 *
 * @package    pstu_contacts
 * @subpackage pstu_contacts/admin
 * @author     chomovva <chomovva@gmail.com>
 */
class AdminUpdateTab extends AdminPart {


	use Controls;


	/**
	 * Идентификатор вкладки настроек
	 * @var string
	 */
	protected $tab_name;


	/**
	 * Название вкладки настроек
	 * @var string
	 */
	protected $tab_label;


	/**
	 * Параметры плагина
	 * @var array
	 */
	protected $options;


	function __construct( $plugin_name, $version ) {
		parent::__construct( $plugin_name, $version );
		$this->tab_name = 'update';
		$this->tab_label = __( 'Обновление', $this->plugin_name );
		$this->options = get_option( $this->plugin_name, [] );
		if ( ! is_array( $this->options ) ) {
			$this->options = [];
		}
		$this->options = array_merge( [
			'version'           => '',
			'updating_progress' => false,
		], $this->options );
	}


	public function check_update() {
		if ( empty( $this->options[ 'version' ] ) ) {
			$this->add_admin_notice( __( 'Невозможно определить версию базы данных. Возможно при установке плагина произошка ошибка. Если плагин установлен поверх версии 1.0.0, то сделайте резервную копию и запустите обновление, активируей плагин заново.', $this->plugin_name ), 'error', false );
		} elseif ( $this->version < $this->options[ 'version' ] ) {
			$this->add_admin_notice( __( 'Установлена устаревшая версия плагина. Сделайте резервную копию и обновитесь.', $this->plugin_name ), 'error', false );
		} elseif ( $this->version > $this->options[ 'version' ] ) {
			$this->add_admin_notice( __( 'Плагин запущен с устаревшей версией базы данных. Для корректной работы необходимо обновить базу данных. Сделайте резервную копию и нажмите кнопку "Запустить обновление".', $this->plugin_name ), 'error', false );
		}
	}


	/**
	 * Фильтр, который добавляет вкладку с опциями
	 * на страницу настроектплагина
	 * @since    2.0.0
	 * @param    array     $tabs     исходный массив вкладок идентификатор вкладки=>название
	 * @return   array     $tabs     отфильтрованный массив вкладок идентификатор вкладки=>название
	 */
	public function add_settings_tab( $tabs ) {
		$tabs[ $this->tab_name ] = $this->tab_label;
		return $tabs;
	}


	/**
	 * Возвращает идентификатор вкладки для регистрации на 
	 * странице настроек плагина
	 * @return   string    идентификатор вкладки
	 */
	public function get_tab_name() {
		return $this->tab_name;
	}


	/**
	 * Генерируем html код страницы настроек
	 */
	public function render_tab() {
		?>
			<p><?php printf( __( 'Версия плагина: %s', $this->plugin_name ), $this->version ); ?></p>
			<p><?php printf( __( 'Версия базы данных: %s', $this->plugin_name ), ( empty( $this->options[ 'version' ] ) ) ? __( 'не определена', $this->plugin_name ) : $this->options[ 'version' ] ); ?></p>
		<?
		if ( $this->version == $this->options[ 'version' ] ) {
			?>
				<p>
					<?php printf( __( 'Версия плагина совпадает с версией базы данных. Дополнительные действия не требуются.', $this->plugin_name ), $this->version ); ?>
				</p>
			<?php
		} elseif ( $this->version > $this->options[ 'version' ] ) {
			?>
				<form id="settings-update-form">
					<?php
						wp_nonce_field( __FILE__, 'update_nonce' );
						echo $this->render_input( 'tab', 'hidden', [ 'value' => $this->tab_name ] );
						echo $this->render_input( 'action', 'hidden', [ 'value' => $this->plugin_name . '_settings' ] );
						submit_button( __( 'Запустить обновление', $this->plugin_name ), 'primary', 'submit', true, null );
					?>
				</form>
				<div id="settings-update-result"></div>
			<?php
		}
	}



	/**
	 * Генерируем html код страницы настроек
	 */
	public function run_ajax() {
		if (
			isset( $_POST[ 'tab' ] )
			&& $this->get_tab_name() == $_POST[ 'tab' ]
			&& isset( $_POST[ 'update_nonce' ] )
			&& wp_verify_nonce( $_POST[ 'update_nonce' ], __FILE__ )
			&& $this->version > $this->options[ 'version' ]
		) {
			wp_send_json_success( $this->update_db( absint( $_POST[ 'offset' ] ) ), 'success' );
		}
	}


	/**
	 * Переносит данные со старого формата в новый, т.е.
	 * с версии 1.0.0 на версию 2.0.0
	 * @since    2.0.0
	 */
	protected function update_db( int $contacts_offset = 0, int $org_units_offset = 0 ) {
		// wp_send_json_success( [
		// 	'done' => 'false',
		// 	'data' => var_export( \WP_Upgrader() ),
		// ] );
		$result = [
			'done'    => true,
			'message' => '',
		];
		if ( ! $this->options[ 'updating_progress' ] ) {
			$this->options[ 'updating_progress' ] = [
				'contact'   => 0,
				'org_units' => 0,
			];
		}

		// обновление контакторв
		$count_contact = wp_count_posts( 'contact' )->publish;
		if ( $count_contact > $this->options[ 'updating_progress' ][ 'contact' ] ) {
			$result[ 'done' ] = false;
			$contacts = get_posts( [
				'numberposts' => 10,
				'offset'      => $this->options[ 'updating_progress' ][ 'contact' ],
				'post_type'   => 'contact',
			] );
			if ( is_array( $contacts ) ) {
				foreach ( $contacts as $contact ) {
					$this->update_contact_foto( $contact->ID );
					$this->update_contact_sections( $contact->ID );
				}
				$this->options[ 'updating_progress' ][ 'contact' ] += count( $contacts );
				$result[ 'message' ] .= sprintf( __( '<p>Обновлено %s контактов из %s</p>', $this->plugin_name ), $this->options[ 'updating_progress' ][ 'contact' ], $count_contact );
			} else {
				$result[ 'message' ] .= '<p>' . __( 'Ошибка обновления контактов!', $this->plugin_name ) . '</p>';
			}
		} else {
			// сообщение об успешном обновлении
			$result[ 'message' ] .= '<p>' . __( 'Контакты обновлены!', $this->plugin_name ) . '</p>';
			// обновление подразделений
			$count_org_units = wp_count_terms( 'org_units', [ 'hide_empty' => 0 ] );
			if ( $count_org_units > $this->options[ 'updating_progress' ][ 'org_units' ] ) {
				$result[ 'done' ] = false;
				$org_units = get_terms( array(
					'taxonomy'    => 'org_units',
					'offset'      => $this->options[ 'updating_progress' ][ 'org_units' ],
					'hide_empty'  => false,
					'number'      => 10,
				) );
				if ( is_array( $org_units ) ) {
					foreach ( $org_units as $org_unit ) {
						$this->update_org_unit_general_information( $org_unit->term_id );
					}
					$this->options[ 'updating_progress' ][ 'org_units' ] += count( $org_units );
					$result[ 'message' ] .= sprintf( __( '<p>Обновлено %s подразделений из %s</p>', $this->plugin_name ), $this->options[ 'updating_progress' ][ 'org_units' ], $count_org_units );
				} else {
					$result[ 'message' ] .= '<p>' . __( 'Ошибка обновления подразделений!', $this->plugin_name ) . '</p>';
				}
			} else {
				$result[ 'message' ] .= '<p>' . __( 'Список подразделений обновлён!', $this->plugin_name ) . '</p>';
			}
		}
		// завершаем обновление
		if ( $result[ 'done' ] ) {
			$this->options[ 'version' ] = $this->version;
			$result[ 'message' ] .= '<p>' . __( 'Обновление завершено! Перезагрузка ...', $this->plugin_name ) . '</p>';
			$this->options[ 'updating_progress' ] = false;
		}
		update_option( $this->plugin_name, $this->options );
		return $result;
	}


	/**
	 * Регистрирует скрипты для админки
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {
		parent::enqueue_scripts();
		$count_contact = wp_count_posts( 'contact' )->publish;
		$count_org_units = wp_count_terms( 'org_units', [ 'hide_empty' => 0 ] );
		wp_enqueue_script( "{$this->plugin_name}-update", plugin_dir_url( __FILE__ ) . 'js/update.js',  array( 'jquery' ), $this->version, false );
	}


	/**
	 * Обновление фото контакта
	 * @since    2.0.0
	 * @param    int      $post_id     идентификатор контакта
	 */
	protected function update_contact_foto( $post_id ) {
		$old_foto =  get_post_meta( $post_id, '_pstu_foto', true );
		if ( ! empty( $old_foto ) && is_array( $old_foto ) && isset( $old_foto[ 'foto_3_4' ] ) && ! empty( $old_foto[ 'foto_3_4' ] ) ) {
			$result = update_post_meta( $post_id, 'foto', array(
				'profil_foto' => $old_foto[ 'foto_3_4' ],
			) );
			if ( ( bool ) $result ) {
				delete_post_meta( $post_id, '_pstu_foto' );
			}
		}
	}


	/**
	 * Обновление секций контактов (наукометрика, контактная информация, социальные сети и web)
	 * @since    2.0.0
	 * @param    int      $post_id     идентификатор контакта
	 */
	protected function update_contact_sections( $post_id ) {
		foreach ( apply_filters( 'pstu_contacts_get_meta_sections', 'contact' ) as $section ) {
			if ( 'foto' == $section->get_key() ) {
				continue;
			}
			$new_value = [];
			$old_value = get_post_meta( $post_id, '_pstu_' . $section->get_key(), true );
			if ( ! is_array( $old_value ) ) {
				continue;
			}
			foreach ( $section->get_fields() as &$field ) {
				if ( array_key_exists( $field->get_key(), $old_value ) ) {
					$new_value[ $field->get_key() ] = $old_value[ $field->get_key() ];
				}
			}
			$result = update_post_meta( $post_id, $section->get_key(), $new_value );
			if ( ( bool ) $result ) {
				delete_post_meta( $post_id, '_pstu_' . $section->get_key() );
			}
		}
	}


	/**
	 * Обновление основной контактной информации о подразделении
	 * @since    2.0.0
	 * @param    int      $post_id     идентификатор подразделения
	 */
	protected function update_org_unit_general_information( $term_id ) {
		$old_leader = get_term_meta( $term_id, '_pstu_leader', true );
		$about_page_id = get_term_meta( $term_id, '_pstu_about_page_id', true );
		$result = update_term_meta( $term_id, 'general_information', array(
			'leader_id'     => $old_leader,
			'about_page_id' => $about_page_id,
		) );
		if ( ( bool ) $result && ! is_wp_error( $result ) ) {
			delete_term_meta( $term_id, '_pstu_leader' );
			delete_term_meta( $term_id, '_pstu_first_deputy' );
			delete_term_meta( $term_id, '_pstu_about_page_id' );
		}
	}


	/**
	 * Закрываем сайт на время обновления
	 **/
	public function enable_maintenance_mode() {
		if ( $this->options[ 'updating_progress' ] && ! wp_doing_ajax() ) {
			wp_die( sprintf(
				'<h1>%1$s</h1><p>%2$s</p>',
				__( 'Сайт закрыт на обслуживание', $this->plugin_name ),
				__( 'Обновите страницу через несколько минут', $this->plugin_name )
			) );
		}
	}
	



}