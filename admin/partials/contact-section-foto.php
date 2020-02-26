<?php

if ( ! defined( 'ABSPATH' ) ) {	exit; };

?>

<section id="photo-selection-section" class="settings-section">

	<h3><?php _e( 'Фото', $plugin_name ); ?></h3>

	<input type="hidden" id="foto-control" name="<?php echo $plugin_name; ?>_foto" value="<?php echo $value; ?>" data-default="<?php echo esc_attr( $default ); ?>">

	<div class="form-group">
		
		<figure id="foto-figure" class="foto-figure">
			<img id="foto-image" class="foto-image" src="<?php echo ( empty( $value ) ) ? $default : $value; ?>" alt="">
		</figure>

		<div class="text-center">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea ipsum deleniti, quos maxime harum doloremque magnam velit vitae, architecto repellat facilis autem. Eaque cum illum unde ipsam sapiente odit modi.</p>
			<button id="add-foto" class="button button-primary" type="button"><?php _e( 'Выбор фото', $plugin_name ); ?></button>
			<button id="remove-foto" class="button" type="button"><?php _e( 'Удалить фото', $plugin_name ); ?></button>
		</div>

	</div>


</section>