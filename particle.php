<?php
/*
Plugin Name: Particle Integration
Plugin URI: http://thomasnagels.be/particle-wordpress-integration/
Description: Integration of Particle Variables and Functions in WordPress
Version: 1.0
Author: Thomas Nagels
Author URI: http://thomasnagels.be
License: GPLv2 or later
*/

// Admin Menu & Page

add_action( 'admin_menu', 'particle_add_admin_menu' );
add_action( 'admin_init', 'particle_settings_init' );

$allowed_status = array('name','connected_string','status','error','last_heard_string','last_ip_address');

// Include the required files. You will need to rename phpParticle.config.sample.php to phpParticle.config.php and then set the values within to use this example
if((@include 'api/phpParticle.class.php') === false)  die("Unable to load phpParticle class");

function particle_add_admin_menu(  ) {

	add_menu_page( 'Particle', 'Particle', 'manage_options', 'particle', 'particle_options_page' );

}

function particle_action( $params ) {
	$return_value = null;
	$error_value = null;
	global $allowed_status;
	particle_update_status ();
	$status = get_option( 'particle_status');

	if (array_key_exists ('status', $params )) {
		if (array_key_exists ($params['status'], $status)) {
			if (in_array($params['status'], $allowed_status)){
				$return_value = $status[$params['status']];
			}
			else $error_value = '(status not allowed)';
		}
		else $error_value = '(unknown status)';
	}

	elseif (array_key_exists ('function', $params )) {
		if (in_array ($params['function'], $status['functions'])) {
			if (array_key_exists ('value', $params )) {
				$return_value =  particle_call_function($params['function'], $params['value']);
			}
			else $error_value =  '(function not allowed)';
		}
		else $error_value =  '(unknown function)';
	}

	elseif (array_key_exists ('setup', $params )) {
		if ($params['setup'] == 'signal') {
			$return_value =  particle_signal();
		}
		else $error_value =  '(unknown setup task)';
	}

	elseif (array_key_exists ('variable', $params )) {
		if (array_key_exists ($params['variable'], $status['variables'])) {
			$return_value =  $status['variables'][$params['variable']];
		}
		else $error_value =  '(unknown variable)';
	}

	else $error_value = '(unknown parameter)';
	if (!empty($error_value))
	{
		if (array_key_exists ('default', $params )) {
			return $params['default'];
		}
		else return $error_value;
	}
	else {
		if (array_key_exists ('result', $params )) {
			return $params['result'];
		}
		else return $return_value;
	}
	return '(you should never see this)';
}

add_shortcode( 'particle', 'particle_action' );


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
	global $allowed_status;
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
		<p>Current status of the device.</p>
		<table class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th><?php _e('Name', 'wordpress'); ?></th>
					<th><?php _e('Value', 'wordpress'); ?></th>
					<th><?php _e('Shortcode Parameter', 'wordpress'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php _e('Name', 'wordpress'); ?></th>
					<th><?php _e('Value', 'wordpress'); ?></th>
					<th><?php _e('Shortcode Parameter', 'wordpress'); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php
					foreach ($allowed_status as $allowed) {
				?>
				<tr><td><?= $allowed ?></th><td>	<?= $status[$allowed] ?> </td><td>status='<?= $allowed ?>'</td></tr>
				<?php
					}
				?>
			</tbody>
		</table>
		<h2>Variables</h2>
		<p>Known Cloud Variables defined in the device.</p>
		<table class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th><?php _e('Name', 'wordpress'); ?></th>
					<th><?php _e('Value', 'wordpress'); ?></th>
					<th><?php _e('Shortcode Parameter', 'wordpress'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php _e('Name', 'wordpress'); ?></th>
					<th><?php _e('Value', 'wordpress'); ?></th>
					<th><?php _e('Shortcode Parameter', 'wordpress'); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php
					foreach ($status['variables'] as $variable => $value) {
				?>
				<tr>
					<td><?= $variable ?></td>
					<td><?= $value ?></td>
					<td>variable='<?= $variable ?>'</td>
				</tr>
				<?php
					}
				?>
		</tbody>
		</table>
		<h2>Functions</h2>
		<p>Known Cloud Functions defined in the device.</p>
		<table class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th><?php _e('Name', 'wordpress'); ?></th>
					<th>&nbsp;</th>
					<th><?php _e('Shortcode Parameter', 'wordpress'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php _e('Name', 'wordpress'); ?></th>
					<th>&nbsp;</th>
					<th><?php _e('Shortcode Parameter', 'wordpress'); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php
					foreach ($status['functions'] as $key => $function) {
				?>
				<tr>
					<td><?= $function ?></td>
					<th>&nbsp;</th>
					<td>function='<?= $function ?>' value = '(value)'</td>
				</tr>
				<?php
					}
				?>
		</tbody>
		</table>
	</div>
	<?php
	//print_r($status);

}

// Particle communication functions

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
			$status['last_heard'] = strtotime($status['last_heard']);
			$status['error'] = "none";
			if ($status['connected']=='1') $status['connected_string'] = 'true';
			 else $status['connected_string'] = 'false';
			$status['last_heard_string'] = date('Y-m-d H:i:s e', $status['last_heard']);
			foreach ($status['variables'] as $variable => $value) {
				if($particle->getVariable($options['particle_device_id'], $variable) == true)
				{
						$result = $particle->getResult();
				    $status['variables'][$variable] = $result['result'];
				}
				else
				{
					$status['error'] = $particle->getError();
				}
			}
		} else {
				$status['connected'] = '';
				$status['error'] = $particle->getError();
		}
	} else {
		$status['connected'] = '';
	}
	update_option('particle_status', $status);
}

function particle_call_function ($function, $value) {
	$options = get_option( 'particle_settings' );
	$status = get_option( 'particle_status');
	if ($options['particle_enable'] == '1') {
		$particle = new phpParticle();
		$particle->setDebug(false);
		$particle->setAccessToken($options['particle_token']);
		if($particle->callFunction($options['particle_device_id'], $function, $value) == true) {
			$result = $particle->getResult();
			return sanitize_text_field($result['return_value']);
		}
		else
		{
			$status['error'] = $particle->getError();
			return null;
		}
	}
}
function particle_signal () {
	$options = get_option( 'particle_settings' );
	$status = get_option( 'particle_status');
	if ($options['particle_enable'] == '1') {
		$particle = new phpParticle();
		$particle->setDebug(false);
		$particle->setAccessToken($options['particle_token']);
		if($particle->signalDevice($options['particle_device_id'],1) == true) {
			$result = $particle->getResult();
			return 'shouthing rainbows';
		}
		else
		{
			$status['error'] = $particle->getError();
			return '(signalling error)';
		}
	}
}
?>
