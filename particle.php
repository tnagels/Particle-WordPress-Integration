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

// Include the required files. You will need to rename phpParticle.config.sample.php to phpParticle.config.php and then set the values within to use this example
if((@include 'api/phpParticle.class.php') === false)  die("Unable to load phpParticle class");

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

	echo __( 'Settings for the Particle API and the device.', 'wordpress' );

}


function particle_options_page(  ) {
	particle_update_status();
	?>
	<div class="wrap">
		<h1>Particle</h1>

		<form action='options.php' method='post'>
	<?php
	settings_fields( 'pluginPage' );
	do_settings_sections( 'pluginPage' );
	submit_button();
	particle_update_status();
	$status = get_option( 'particle_status');
	?>
		</form>
		<h2>Device Status</h2>
		<p>Current status of the device. Only updated on page reloads!</p>
		<table class="form-table">
			<tbody>
				<tr><th scope="row">Name</th><td>	<?= $status['name'] ?> </td></tr>
				<tr><th scope="row">Connected</th><td> <?= ($status['connected']=='1') ? 'True' : 'False' ?> </td></tr>
				<tr><th scope="row">Error</th><td> <?= $status['error'] ?> </td></tr>
				<tr><th scope="row">Last heard</th><td> <?= $status['last_heard'] ?> </td></tr>
				<tr><th scope="row">Last IP</th><td> <?= $status['last_ip_address'] ?> </td></tr>
			</tbody>
		</table>

	</div>
	<?php

}

function particle_update_status () {
	static $firstrun;
	if ($firstrun !== null) return;
	$firstrun = 1;
	$options = get_option( 'particle_settings' );
	$status = get_option( 'particle_status');
	if ($options['particle_enable'] == '1') {
		$particle = new phpParticle();
		$particle->setDebug(false);
		$particle->setAccessToken($options['particle_token']);
		if($particle->getAttributes($options['particle_device_id']) == true)
		{
			$status = $particle->getResult();
			$status['error'] = "None";
		} else {
				$status['connected'] = '';
				$status['error'] = $particle->getError();
		}
	} else {
		$status['connected'] = '';
	}
	update_option('particle_status', $status);
}

/**function particle_show_status (  ) {
	$options = get_option( 'particle_settings' );
	$status =
	$particle = new phpParticle();
	$particle->setDebug(false);
	$particle->setAccessToken($options['particle_token']);
	if($particle->getAttributes($options['particle_device_id']) == true)
	{
	    $status = $particle->getResult();
			set_op
			?>
			<?php
			print_r($data);
	}
	else
	{
	    echo("Error: " . $particle->getError());
	    echo("Error Source" . $particle->getErrorSource());
	}
} **/

?>
