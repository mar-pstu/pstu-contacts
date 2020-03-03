<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


?>


<div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'person-entry person-entry--card', get_the_ID() ); ?> role="listitem">
		<a href="<?php the_permalink( get_the_ID() ) ?>">
			<?php do_action( 'pstu_contact_profil_foto', get_the_ID() ); ?>
		</a>
	</article>
</div>