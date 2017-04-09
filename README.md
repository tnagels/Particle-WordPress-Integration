# Particle-WordPress Integration
This is a plugin for [WordPress](http://wordpress.org) providing [Particle](http://particle.io) integration.
## History
After getting to know Particle during my work at [Rombit](http://www.rombit.be), this plugin was created as a way to experiment with Particle from within my own blog. It started out as a simple idea for a coffee-counter (I drink too much of the stuff) and once I started, I wanted to create a full plugin.
## Functionality
In its current form the plugin adds shortcode functionality for Particle to your WordPress blog. By embedding a shortcode, you can include variables & call functions from one particle device in your blog. In this way you could link the real world with this information on your site.
Currently the shortcodes are only executed when loading a page, so it falls short of full interactivity. But the idea is there, so consider this a proof-of-concept.
## How to use
### Installation
1. Install the plugin in your WordPress plugin folder as usual.
2. Go to [Particle Build](http://build.particle.io) and find your token.
3. go to [Particle Console](http://console.particle.io) and find your device ID.
4. Go to the WordPress Admin pages, navigate to "Particle" and enter your Particle ID and token, and enable the device.
After this you should see a list of cloud variables and functions of your Particle. Next to them you will see the minimal Shortcodes to use them.
### Usage
Minimal usage is as shown in the admin pages. Shortcodes always start with "particle" followed by a number of parameters.
#### variables
For calling variables, you simply have to enter the variable name. So if you have cloud variable "ledStatus" in particle, you can call it as follows: `[particle variable='ledstatus']`
