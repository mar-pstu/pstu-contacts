<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Класс для регистрации шорткода вывода руководителя подразделения
 *
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public
 * @author     chomovva <chomovva@gmail.com>
 */
class PublicShortcodeaContactsCatalog extends Shortcode {


	public function manager( $atts ) {
		$html = '';
		$atts = shortcode_atts( array(
			'headings_level' => 'h3',
		), $atts, $this->shortcode_name );
		$contacts = get_posts( array(
			'numberposts' => -1,
			'orderby'     => 'title',
			'order'       => 'ASC',
			'post_type'   => 'contact',
		) );
		if ( is_array( $contacts ) && ! empty( $contacts ) ) {
			$letter = '';
			$html .= "<ul>\r\n";
			foreach( $contacts as $contact ) {
				$first_letter = mb_strtoupper( mb_substr( trim( $contact->post_title ), 0, 1 ) );
				$title = trim( apply_filters( 'the_title', $contact->post_title ) );
				if ( $first_letter != $letter ) {
					$letter = $first_letter;
					$html .= sprintf( 
						'</ul><%1$s>%2$s</%1$s><ul>',
						$atts[ 'headings_level' ],
						$letter
					);
				}
				$html .= sprintf(
					'<li><a href="%1$s">%2$s</a> %3$s</li>',
					get_permalink( $contact->ID ),
					$contact->post_title,
					( empty( trim( $contact->post_excerpt ) ) ) ? '' : '<em><small>(' . strip_tags( $contact->post_excerpt ) . ')</small></em>'
				);
			}
			$html .= "</ul>\r\n";
		}
		return $html;
	}


}