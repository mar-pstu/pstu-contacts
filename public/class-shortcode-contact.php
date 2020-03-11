<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Класс для регистрации шорткода вывода краткой информации о контакте
 *
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public
 * @author     chomovva <chomovva@gmail.com>
 */
class PublicShortcodeContact extends Shortcode {


	public function manager( $atts ) {
		global $post;
		$html = '';
		$atts = shortcode_atts( array(
			'id'                => false,
			'contact_template'  => 'card',
		), $atts, $this->get_shortcode_name() );
		if ( $atts[ 'id' ] ) {
			$contact = get_post( $atts[ 'id' ], OBJECT, 'raw' );
			if ( is_object( $contact ) && ! is_wp_error( $contact ) ) {
				if ( ! in_array( $atts[ 'contact_template' ], array( 'card', 'bar', 'item' ) ) ) {
					$atts[ 'contact_template' ] = 'card';
				}
				setup_postdata( $post = $contact );
				ob_start();
				include dirname( __FILE__ ) . "\partials\person-{$atts[ 'contact_template' ]}.php";
				$html = '<div class="row">' . ob_get_contents() . '</div>';
				ob_end_clean();
				wp_reset_postdata();
			}
		}
		return $html;
	}



}