<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


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
class AdminContact extends Part {


	use Contacts;

	use Controls;

	use Helpers;


	/**
	 * Массив метаполей контакта
	 * 
	 * @since    2.0.0
	 * @access   protected
	 * @var      array    $meta_sections    Массив метаполей контакта
	 */
	protected $meta_sections;


	function __construct( $slug, $textdomain ) {
		parent::__construct( $slug, $textdomain );
		$this->meta_sections = $this->get_contacts_meta_sections();
	}

	
	/**
	 * Добавляет дополнительные блоки (meta box) на страницы редактирования/создания постов
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function add_meta_boxes() {
		add_meta_box(
			"{$this->plugin_name}_fields_list",
			__( 'Свойства контакта', $this->plugin_name ),
			array( $this, 'render_metabox_content' ),
			'contact',
			'advanced',
			'high',
			null
		);
	}


	/**
	 * Функция, которая выводит на экран HTML содержание блока
	 *
	 * @since    2.0.0
	 * @var      WP_Post    $post    Объект текущего поста
	 * @var      array      $meta    Регистрационная информация о блоке
	 */
	public function render_metabox_content( $post, $meta ) {
		wp_nonce_field( "{$this->plugin_name}_meta", "{$this->plugin_name}_meta_nonce" );
		$this->render_metabox_section_foto( $post->ID );
		$this->render_metabox_sections( $post->ID );
	}


	/**
	 * Выводит секции "свойств" контакта ( адреса, социальные сети и прочее )
	 *
	 * @since    2.0.0
	 * @var      int    $post_id    Идентификатор текущего поста
	 */
	protected function render_metabox_sections( $post_id ) {
		foreach ( $this->meta_sections as $section ) {
			$meta = get_post_meta( $post_id, $section->get_key(), true );
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
				include dirname( __FILE__ ) . '\partials\contact-section-field.php';
			}
			$content = ob_get_contents();
			ob_end_clean();
			$title = $section->title;
			$key = $section->get_key();
			include dirname( __FILE__ ) . '\partials\settings-section.php';
		}
	}


	/**
	 * Выводит секция для добавления фото контакта
	 *
	 * @since    2.0.0
	 * @var      int    $post_id    Идентификатор текущего поста
	 */
	protected function render_metabox_section_foto( $post_id ) {
		wp_enqueue_media();
		$plugin_name = $this->plugin_name;
		$default = plugin_dir_url( __FILE__ ) . 'images/frame.svg';
		$value = get_post_meta( $post_id, "{$this->plugin_name}_foto", true );
		include dirname( __FILE__ ) . '\partials\contact-section-foto.php';
	}


	/**
	 * Проверка полученного поля перед сохранением в базу
	 *
	 * @since    2.0.0
	 * @var      string    $key      Идентификатор поля
	 * @var      string    $value    Новое значение металополя
	 */
	protected function sanitize_field( $key, $new_value ) {
		$new_value = trim( $new_value );
		if ( ! empty( $new_value ) ) {
			switch ( $key ) {
				// scientometrics - возможно будут ID, но сейчас пока эти поля очень и очень редко заполняются
				case 'orcid_id':
				case 'google_scholar':
				case 'researcher_id':
				case 'scopus_id':
				case 'research_gate_id':
				case 'pstu_eir':
				// socials
				case 'google_plus':
				case 'facebook':
				case 'twitter':
				case 'linkedin':
				case 'youtube':
				case 'instagram':
				// web
				case 'wikipedia':
					$new_value = implode( ", ", wp_extract_urls( $new_value ) );
					break;
				case 'email':
					$new_value = implode( ", ", $this->parse_emails_list( $new_value ) );
					break;
				case 'tel':
					$new_value = implode( ", ", $this->parse_list_of_telephone_numbers( $new_value ) );
					break;
				case 'address':
				case 'time':
				case 'schedule_admission':
				default:
					$new_value = sanitize_text_field( $new_value );
					break;
			}
		}
		return $new_value;
	}


	/**
	 * Сохранение медатанных поста
	 *
	 * @since    2.0.0
	 * @var      int    $post_id    Идентификатор поста
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST[ "{$this->plugin_name}_meta_nonce" ] ) ) return;
		if ( ! wp_verify_nonce( $_POST[ "{$this->plugin_name}_meta_nonce" ], "{$this->plugin_name}_meta" ) ) { wp_nonce_ays(); return; }
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( wp_is_post_revision( $post_id ) ) return;
		if ( 'page' == $_POST[ 'post_type' ] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_nonce_ays();
			return;
		}
		if ( isset( $_REQUEST[ "{$this->plugin_name}_foto" ] ) ) {
			$foto = sanitize_url( trim( $_REQUEST[ "{$this->plugin_name}_foto" ] ) );
			if ( empty( $foto ) ) {
				delete_post_meta( $post_id, "{$this->plugin_name}_foto" );
			} else {
				update_post_meta( $post_id, "{$this->plugin_name}_foto", $foto );
			}
		}
		foreach ( $this->meta_sections as $section ) {
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
				delete_post_meta( $post_id, $section->get_key() );
			} else {
				update_post_meta( $post_id, $section->get_key(), $meta_value );
			}
		}
	}


	/**
	 * Регистрирует стили для админки
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}


	/**
	 * Регистрирует скрипты для админки
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
	}



}