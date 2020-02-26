<?php

if ( ! defined( 'ABSPATH' ) ) {	exit; };

?>

<div class="settings-section <?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">

	<?php if ( ! empty( $title ) ) : ?>
		<h3><?php echo $title; ?></h3>
	<?php endif; ?>

	<?php echo $content; ?>

</div>