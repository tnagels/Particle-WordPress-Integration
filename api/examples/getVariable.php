<?php
/*
 * @project phpParticle
 * @file    examples/getVariable.php
 * @authors Harrison Jones (harrison@hhj.me)
 * @date    March 16, 2015
 * @brief   Examples file. Flash the code in phpParticle.firmware.cpp to your Particle Core and try these functions out
 */

// For testing purposes we want to be as strict as possible
error_reporting(E_STRICT);

// Include the required files. You will need to rename phpParticle.config.sample.php to phpParticle.config.php and then set the values within to use this example
if((@include '../phpParticle.class.php') === false)  die("Unable to load phpParticle class");
if((@include '../phpParticle.config.php') === false)  die("Unable to load phpParticle configuration file");

// Grab a new instance of our phpParticle object
$particle = new phpParticle();

// Set the internal debug to true. Note, calls made to $particle->debug(...) by you ignore this line and display always
$particle->setDebug(true);
// Set the debug calls to display pretty HTML format. Other option is "TEXT". Note, calls made to $particle->debug(...) display as set here
$particle->setDebugType("HTML");

// Set our access token (set in the phpConfig.config.php file)
$particle->setAccessToken($accessToken);

// Grab the current uptime of your core (requires a modified version of tinker on your Particle Core)
$particle->debug("Particle Variable");
if($particle->getVariable($deviceID, "uptime") == true)
{
    $particle->debug_r($particle->getResult());
}
else
{
    $particle->debug("Error: " . $particle->getError());
    $particle->debug("Error Source" . $particle->getErrorSource());
}
?>