<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


?>


<div class="col-xs-12">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'person-entry person-entry--item', get_the_ID() ); ?>>
		<h4 class="person-entry-title">
			<a href="<?php the_permalink( get_the_ID() ); ?>">
				<?php the_title( '', '', true ); ?>
			</a>
		</h4>
		<?php if ( has_excerpt( get_the_ID() ) ) : ?>
			<p class="person-entry-excerpt"><?php echo get_the_excerpt( get_the_ID() ); ?></p>
		<?php endif; ?>
	</article>
</div>