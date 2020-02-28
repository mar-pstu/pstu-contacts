<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


?>


<div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
	<div id="post-<?php the_ID(); ?>" <?php post_class( 'contact-entry contact-entry--card', get_the_ID() ); ?> >
		<?php do_action( 'pstu_contact_profil_foto', get_the_ID() ); ?>
	</div>
</div>