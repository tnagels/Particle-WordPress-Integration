<?php
/*
 * @project phpParticle
 * @file    examples/claimDevice.php
 * @authors Devin Pearson (devin@blackhat.co.za)
 * @date    March 18, 2015
 * @brief   Examples file.
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

// claim a device on your account
$particle->debug("Claim Device");
if($particle->claimDevice($deviceID) == true)
{
    $particle->debug_r($particle->getResult());
}
else
{
    $particle->debug("Error: " . $particle->getError());
    $particle->debug("Error Source" . $particle->getErrorSource());
}
?>