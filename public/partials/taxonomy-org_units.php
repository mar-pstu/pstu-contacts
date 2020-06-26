<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


global $post;


get_header();


$queried_object = get_queried_object();

$child_our_units = get_terms( array(
	'taxonomy'   => 'org_units',
	'hide_empty' => false,
	'parent'     => $queried_object->term_id,
) );

$general_information = array_merge( array(
	'leader_id',
	'about_page_id',
), get_term_meta( $queried_object->term_id, 'general_information', true ) );

$leader = get_post( $general_information[ 'leader_id' ], OBJECT, 'raw' );

?>


<div class="container">
	
	<h1><?php single_term_title( '', true ); ?></h1>

	<div class="row">
		
		<div class="col-xs-12 col-sm">
			<?php do_action( 'pstu_contacts_the_single_org_units_info', $queried_object->term_id, 'contacts', 'p' ); ?>
			<?php if ( ! empty( $general_information[ 'about_page_id' ] ) ) : ?>
				<p class="small">
					<a class="btn btn-primary" href="<?php echo get_permalink( $general_information[ 'about_page_id' ], false ); ?>">
						<?php _e( 'Страница с подробной информацией о работе подразделения', PSTU_CONTACTS_PLUGIN_NAME ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( ! is_wp_error( $leader ) ) : setup_postdata( $post = $leader ); ?>
			<div class="col-xs-12 col-sm-4 first-sm">
				<a id="post-<?php the_ID(); ?>" href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>" <?php post_class( 'person-entry person-entry--card', get_the_ID() ); ?> >
					<?php do_action( 'pstu_contact_profil_foto', get_the_ID() ); ?>
				</a>
			</div>
		<?php endif; wp_reset_postdata(); ?>

	</div>

	<?php

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
				if ( get_the_ID() != $general_information[ 'leader_id' ] ) {
					include dirname( __FILE__ ) . '/person-bar.php';
				}
			}
		}

	?>

</div>

<? get_footer();