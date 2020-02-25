<?php

if ( ! defined( 'ABSPATH' ) ) {	exit; };

?>

<section class="settings-section <?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">

	<h3><?php echo $label; ?></h3>

	<?php echo $content; ?>

</section>