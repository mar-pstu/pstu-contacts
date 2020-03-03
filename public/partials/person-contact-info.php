<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


?>


<article id="post-<?php the_ID(); ?>" <?php post_class( 'person-entry person-entry--contact-info', get_the_ID() ); ?> >
	<p><strong><a href="<?php the_permalink( get_the_ID() ); ?>"><?php the_title( '', '', true ); ?></a></strong></p>
	<?php do_action( 'pstu_contacts_the_single_contact_info', get_the_ID(), 'contacts', false ); ?>
</article>