<?php


namespace pstu_contacts;


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
class PublicContact extends Part {


	public function render_single_content( $content ) {
		if ( 'contact' == get_post_type( get_the_ID() ) ) {
			ob_start();
			include dirname( __FILE__ ) . '\partials\single-contact.php';
			$content = ob_get_contents();
			ob_end_clean();
		}
		// echo "<pre>";
		// var_dump( $content );
		// echo "</pre>";
		return $content;
	}


	/**
	 * Регистрирует стили для публичной части сайта
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flexboxgrid.css', array(), '6.3.2', 'all' );
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
	 * Регистрирует скрипты для публичной части сайта
	 *
	 * @since    2.0.0
	 */
	public function get_contact_profil_foto_url( $post_id ) {
		$foto = get_post_meta( $post_id, 'foto', true );
		$result = ( isset( $foto[ 'profil_foto' ] ) && ! empty( $foto[ 'profil_foto' ] ) ) ? $foto[ 'profil_foto' ] : plugins_url( 'public/images/user.svg' , dirname( __FILE__ ) );
		return $result;
	}


	public function get_contact_profil_foto( $post_id ) {
		return sprintf(
			'<figure><img class="contact-profil-foto" src="%1$s" alt="%2$s"><figcaption>%2$s</figcaption></figure>',
			$this->get_contact_profil_foto_url( $post_id ),
			esc_attr( get_the_title( $post_id ) )
		);
	}


	public function the_contact_profil_foto( $post_id ) {
		echo $this->get_contact_profil_foto( $post_id );
	}


	public function render_meta_section( $post_id, $key, $header_tag = 'h3' ) {
		foreach ( apply_filters( 'pstu_contacts_get_meta_sections', 'contact' ) as $section ) {
			if ( $section->get_key() != $key ) {
				continue;
			}
			$content = '';
			$meta = get_post_meta( $post_id, $key, true );
			foreach ( $section->get_fields() as $field ) {
				if ( ( isset( $meta[ $field->get_key() ] ) ) && ! empty( $meta[ $field->get_key() ] ) ) {
					$content .= '<li>' . $field->label . ': ' . $meta[ $field->get_key() ] . '</li>';
				}
			}
			if ( ! empty( $content ) ) {
				$header = ( ( bool ) $header_tag ) ? sprintf( '<%1$s>%2$s</%1$s>', $header_tag, $section->title ) : '';
				printf(
					'<section id="post-%1$s-%2$s" class="%2$s"> %3$s <ul>%4$s</ul> </section>',
					$post_id,
					$key,
					$header,
					$content
				);
			}

		}
	}


}
