<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


get_header();


$queried_object = get_queried_object();

$child_our_units = get_terms( array(
	'taxonomy'   => 'org_units',
	'hide_empty' => false,
	'parent'     => $queried_object->term_id,
) );


?>


<div class="container">
	
	<h1><?php single_term_title( '', true ); ?></h1>

	<?php

		do_action( 'pstu_contacts_the_single_org_units_info', $queried_object->term_id, 'contacts', 'h2' );

		echo do_shortcode( term_description() );

		if ( is_array( $child_our_units ) && ! empty( $child_our_units ) ) {

			echo '<h2>' . __( 'Дочерние подразделения', PSTU_CONTACTS_PLUGIN_NAME ) . '</h2>';

			echo '<ul class="lead">';

			foreach ( $child_our_units as $child_our_unit ) {
				printf(
					'<li><a href="%1$s">%2$s</a></li>',
					get_term_link( $child_our_unit, 'org_units' ),
					apply_filters( 'single_term_title', $child_our_unit->name )
				);
			}

			echo '</ul>';

		}

		if ( have_posts() ) {

			echo '<h2>' . __( 'Сотрудники подразделения', PSTU_CONTACTS_PLUGIN_NAME ) . '</h2>';

			while ( have_posts() ) {
				the_post();
				include dirname( __FILE__ ) . '\contact-bar.php';
			}
		}

	?>

</div>

<? get_footer();