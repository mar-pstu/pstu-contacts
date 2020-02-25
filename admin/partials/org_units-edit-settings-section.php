<?php

if ( ! defined( 'ABSPATH' ) ) {	exit; };

?>

	</tbody>

</table>

<h3>
	<?php echo $label; ?>
</h3>

<table class="form-table <?php echo esc_attr( $key ); ?>" role="presentation"  id="<?php echo esc_attr( $key ); ?>">

	<tbody>

		<?php echo $content; ?>