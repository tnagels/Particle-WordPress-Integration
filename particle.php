<?php
/*
Plugin Name: Particle Integration
Plugin URI: http://www.thomasnagels.be
Description: Integration of Particle Variables and Functions in WordPress
Version: The Plugin's Version Number, e.g.: 1.0
Author: Thomas Nagels
Author URI: http://www.thomasnagels.be
License: Commercial
*/

// Admin Menu & Page

add_action( 'admin_menu', 'particle_add_admin_menu' );
add_action( 'admin_init', 'particle_settings_init' );


function particle_add_admin_menu(  ) {

	add_menu_page( 'Particle', 'Particle', 'manage_options', 'particle', 'particle_options_page' );

}


function particle_settings_init(  ) {

	register_setting( 'pluginPage', 'particle_settings' );

	add_settings_section(
		'particle_pluginPage_device_section',
		__( 'Device settings', 'wordpress' ),
		'particle_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'particle_token',
		__( 'API token', 'wordpress' ),
		'particle_token_render',
		'pluginPage',
		'particle_pluginPage_device_section'
	);

	add_settings_field(
		'particle_device_id',
		__( 'Device ID', 'wordpress' ),
		'particle_device_id_render',
		'pluginPage',
		'particle_pluginPage_device_section'
	);

	add_settings_field(
		'particle_enable',
		__( 'Enable device', 'wordpress' ),
		'particle_enable_render',
		'pluginPage',
		'particle_pluginPage_device_section'
	);


}


function particle_token_render(  ) {

	$options = get_option( 'particle_settings' );
	?>
	<input type='text' name='particle_settings[particle_token]' value='<?php echo $options['particle_token']; ?>'>
	<?php

}


function particle_device_id_render(  ) {

	$options = get_option( 'particle_settings' );
	?>
	<input type='text' name='particle_settings[particle_device_id]' value='<?php echo $options['particle_device_id']; ?>'>
	<?php

}


function particle_enable_render(  ) {

	$options = get_option( 'particle_settings' );
	?>
	<input type='checkbox' name='particle_settings[particle_enable]' <?php checked( $options['particle_enable'], 1 ); ?> value='1'>
	<?php

}


function particle_settings_section_callback(  ) {

	echo __( 'Settings for the Particle API and the device', 'wordpress' );

}


function particle_options_page(  ) {

	?>
	<div class="wrap">
		<h1>Particle</h1>

		<form action='options.php' method='post'>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		</form>
	</div>
	<?php

}

?>
