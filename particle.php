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

<?php
add_action( 'admin_menu', 'particle_add_admin_menu' );
add_action( 'admin_init', 'particle_settings_init' );


function particle_add_admin_menu(  ) {

	add_menu_page( 'particle', 'particle', 'manage_options', 'particle', 'particle_options_page' );

}


function particle_settings_init(  ) {

	register_setting( 'pluginPage', 'particle_settings' );

	add_settings_section(
		'particle_pluginPage_section',
		__( 'Your section description', 'wordpress' ),
		'particle_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'particle_checkbox_field_0',
		__( 'Settings field description', 'wordpress' ),
		'particle_checkbox_field_0_render',
		'pluginPage',
		'particle_pluginPage_section'
	);

	add_settings_field(
		'particle_text_field_1',
		__( 'Settings field description', 'wordpress' ),
		'particle_text_field_1_render',
		'pluginPage',
		'particle_pluginPage_section'
	);


}


function particle_checkbox_field_0_render(  ) {

	$options = get_option( 'particle_settings' );
	?>
	<input type='checkbox' name='particle_settings[particle_checkbox_field_0]' <?php checked( $options['particle_checkbox_field_0'], 1 ); ?> value='1'>
	<?php

}


function particle_text_field_1_render(  ) {

	$options = get_option( 'particle_settings' );
	?>
	<input type='text' name='particle_settings[particle_text_field_1]' value='<?php echo $options['particle_text_field_1']; ?>'>
	<?php

}


function particle_settings_section_callback(  ) {

	echo __( 'This section description', 'wordpress' );

}


function particle_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>particle</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>
