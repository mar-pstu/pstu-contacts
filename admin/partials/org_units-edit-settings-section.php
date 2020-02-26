<?php

if ( ! defined( 'ABSPATH' ) ) {	exit; };

?>

	</tbody>

</table>


<?php if ( ! empty( $title ) ) : ?>
	<h3>
		<?php echo $title; ?>
	</h3>
<?php endif; ?>

<table class="form-table <?php echo esc_attr( $key ); ?>" role="presentation"  id="<?php echo esc_attr( $key ); ?>">

	<tbody>

		<?php echo $content; ?>