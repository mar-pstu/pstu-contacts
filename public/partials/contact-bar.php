<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


?>


<div  id="post-<?php the_ID(); ?>" <?php post_class( 'contact-entry contact-entry--bar', get_the_ID() ); ?> >
	<div class="row">
		<div class="col-xs-12 col-sm-3">
			<?php do_action( 'pstu_contacts_single_profil_foto', get_the_ID() ); ?>
		</div>
		<div class="col-xs-12 col-sm-9">
			<h3 class="contact-entry-title">
				<a href="<?php the_permalink( get_the_ID() ) ?>"><?php the_title( '', '', true ); ?></a>
			</h3>
			<?php if ( has_excerpt( get_the_ID() ) ) : ?>
				<p class="contact-entry-excerpt"><?php echo get_the_excerpt( get_the_ID() ); ?></p>
			<?php endif; ?>
			<?php do_action( 'pstu_contacts_the_single_contact_info', get_the_ID(), 'contacts', 'h4' ); ?>
			<p>
				<a href="<?php the_permalink( get_the_ID() ) ?>"><?php _e( 'Подробней', PSTU_CONTACTS_PLUGIN_NAME ); ?></a>
			</p>
		</div>
	</div>
</div>