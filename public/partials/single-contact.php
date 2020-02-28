<?php


namespace pstu_contacts;


if ( ! defined( 'ABSPATH' ) ) {	exit; };


/**
 * Шаблон 
 *
 * Это файл шаблон для публичной части пралгина
 *
 * @link       chomovva.ru
 * @since      2.0.0
 *
 * @package    Pstu_contacts
 * @subpackage Pstu_contacts/public/partials
 */

?>

<div class="row middle-xs">
	
	<div class="col-xs-12 col-sm-4">
		<?php $this->the_contact_profil_foto( get_the_ID() ); ?>
	</div>

	<div class="col-xs-12 col-sm-8">
		<?php
			echo get_the_term_list( get_the_ID(), 'org_units', __( 'Подразделения: ', PSTU_CONTACTS_PLUGIN_NAME ), ',', '' );
			$this->render_meta_section( get_the_ID(), 'contacts' );
		?>
	</div>

</div>

<?php

$this->render_meta_section( get_the_ID(), 'scientometrics' );
$this->render_meta_section( get_the_ID(), 'socials' );
$this->render_meta_section( get_the_ID(), 'web' );

echo $content;