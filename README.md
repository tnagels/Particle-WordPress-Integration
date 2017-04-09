# Particle-WordPress Integration
This is a plugin for [WordPress](http://wordpress.org) providing [Particle Could API](http://particle.io) integration.
## History
After getting to know Particle during my work at [Rombit](http://www.rombit.be), this plugin was created as a way to experiment with Particle from within my own blog. It started out as a simple idea for a coffee-counter (I drink too much of the stuff) and once I started, I wanted to create a full plugin.
## Functionality
In its current form the plugin adds shortcode functionality for Particle to your WordPress blog. By embedding a shortcode, you can include variables & call functions from one particle device in your blog. In this way you could link the real world with this information on your site.
Currently the shortcodes are only executed when loading a page, so it falls short of full interactivity. But the idea is there, so consider this a proof-of-concept.
## How to use
*Please note that this implementation of the Particle Cloud API uses the default token for a user. This is only usable for proof-of-concepts. For full production implementation other mechanisms should be used.*
### Installation
1. Install the plugin in your WordPress plugin folder as usual.
2. Go to [Particle Build](http://build.particle.io) and find your token.
3. go to [Particle Console](http://console.particle.io) and find your device ID.
4. Go to the WordPress Admin pages, navigate to "Particle" and enter your Particle ID and token, and enable the device.
After this you should see a list of cloud variables and functions of your Particle. Next to them you will see the minimal Shortcodes to use them.
### Usage
Minimal usage is as shown in the admin pages. Shortcodes always start with "particle" followed by a number of parameters.
#### Status
A number of status messages are available as a shortcode. These are listed in the admin interface. For example, if you want to retrieve your device's name, you enter following shortcode: `[particle status='name']`.
#### Variables
For calling variables, you simply have to enter the variable name. So if you have cloud variable "ledStatus" in particle, you can call it as follows: `[particle variable='ledstatus']`.
#### Functions
If you want to call a cloud function, you need to pass two parameters, the function name and the value you want to pass. The shortcode (by default) displays the function's result. Example `[particle function='toggleLed' value='go']`
#### Options
There are two options for the shortcode:
* default: this is the information given when there is an error in the communication with the Particle device, for example when it is not online. This could be used for a variable `ledStatus` as follows `[particle variable='ledstatus' default='not connected']`.
* result: this overrides any output from the shortcode. Typically this is used when you do not want to show the result, as follows: `[particle function='toggleLed' value='go' result='']`.
## Plugin status
As said the plugin is only a minimal implementation. Ideally, the functionality is also exposed over the WordPress API so it can be called from JavaScript. If I will implement this is anyone's guess (including me).
[x] Particle communication
[x] Shortcode implementation
[ ] WordPress API implementation
[ ] Particle authentication  mechanisms
