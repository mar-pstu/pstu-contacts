<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


?>


<a id="post-<?php the_ID(); ?>" href="<?php the_permalink( get_the_ID() ); ?>" <?php post_class( 'contact-entry contact-entry--item', get_the_ID() ); ?> >
	<h3 class="contact-entry-title"><?php the_title( '', '', true ); ?></h3>
	<?php if ( has_excerpt( get_the_ID() ) ) : ?>
		<p class="small"><?php echo get_the_excerpt( get_the_ID() ); ?></p>
	<?php endif; ?>
</a>