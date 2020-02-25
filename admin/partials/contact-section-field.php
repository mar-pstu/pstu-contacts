<?php

if ( ! defined( 'ABSPATH' ) ) {	exit; };

?>

<div class="form-group">

	<label class="form-label" for="<?php echo esc_attr( $id ); ?>">
		<?php echo $label; ?>
	</label>

	<?php echo $control; ?>

</div>