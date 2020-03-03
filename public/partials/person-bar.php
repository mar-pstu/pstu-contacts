<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


?>


<div class="col-xs-12">
	<article  id="post-<?php the_ID(); ?>" <?php post_class( 'person-entry person-entry--bar', get_the_ID() ); ?> role="listitem">
		<div class="row middle-xs">
			<div class="col-xs-12 col-sm-3 col-md-4">
				<?php do_action( 'pstu_contacts_single_profil_foto', get_the_ID() ); ?>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-8">
				<h4 class="person-entry-title">
					<a href="<?php the_permalink( get_the_ID() ) ?>"><?php the_title( '', '', true ); ?></a>
				</h4>
				<?php if ( has_excerpt( get_the_ID() ) ) : ?>
					<p class="person-entry-excerpt"><?php echo get_the_excerpt( get_the_ID() ); ?></p>
				<?php endif; ?>
				<?php do_action( 'pstu_contacts_the_single_contact_info', get_the_ID(), 'contacts', 'p' ); ?>
				<p>
					<a href="<?php the_permalink( get_the_ID() ) ?>"><?php _e( 'Подробней', PSTU_CONTACTS_PLUGIN_NAME ); ?></a>
				</p>
			</div>
		</div>
	</article>
</div>