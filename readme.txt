=== Plugin Name ===
Contributors: dun_edwards
Tags: social share buttons, social share, social share plugin, share plugin, social media, social media share
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 1.1.7
Version: 1.1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a plugin that supports sharing of social media (with follower count) for the 10 most common social media providers.

== Description ==

Social Media by Lazy Cat Themes was created as a simple plugin for use by the Lazy Cat Themes family of recipe-based Wordpress themes. The plugin has two modes:

* A simple mode where the social networks can be setup quickly
* An advanced mode where the social networks can be setup to provide a fan or follower count

Included networks are:

* Facebook
* Twitter
* Google+
* YouTube
* Pinterest
* Instagram
* Email
* Bloglovin'
* StumbleUpon
* LinkedIn

The plugin has a display widget, a shortcode and an API and uses FontAwesome to display social icons.

= Docs & Support =

You can find full documentation [here](https://plugins.lazycatthemes.com/social-media).

== Installation ==

1. Upload the entire `lct-social-media` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. You will find a 'Social Media' menu in your WordPress admin panel.

For usage, you can also have a look at the [plugin homepage](https://plugins.lazycatthemes.com/social-media).

== Frequently Asked Questions ==

= Can I use my existing WordPress theme? =
Yes! This plugin is designed to work in any Wordpress theme. It uses FontAwesome to display social network icons so the colors can easily be changed to adapt to any theme.

= Where can I find documentation? =
Our documentation can be found at the [plugin homepage](https://plugins.lazycatthemes.com/social-media).

= What is the difference between simple and advanced Social Network configuration =
Simple configuration is where you simply put the name of the relevant social network page. If you want the plugin to use an API to calculate a follower count then you may need to create an API key and put this in the configuration. Full details on how to do this are available [here](http://www.lazycatthemes.com/document/advanced-configuration-for-follower-count/).

= Is there a widget? =
Yes! There is a widget that supports the following features:

* Icon strip alignment (left, center, right)
* Icon size (big or small)
* Whether the strip is for the social site itself or to display share icons
* Whether to show the follower count

= How do I embed social media icons in a post or page? =
There is a shortcode that supports all of the features listed in the question above.

= Is there a way just to obtain back the data using PHP? =
Yes! There is a developer API that returns all the configured social networking data as an array.

== Screenshots ==

1. **Easy Setup** - Easy setup by putting in the bare minimum information necessary with an intuitive, easy-to-use interface.
2. **Follower Count** - By utilising API keys for the social networks, this plugin can display follower and fan counts.
3. **Includes a Display Widget** - A display widget allows you to display social networking in any widget-enabled area of your site.
4. **Elegant Display** - Font Awesome is used to elegantly display the social networks in a way that can easily be customized using CSS.
5. **10 Networks Supported** - The current version supports Facebook, Twitter, Google+, Pinterest, YouTube, Instagram, Bloglovin', StumbleUpon, LinkedIn and Email


== Changelog ==

= 1.0 =
* Initial Version

= 1.0.1 =
* Added a link to documentation on the options screen

= 1.0.2 =
* Change to trigger update

= 1.0.3 =
* Removed unused constants

= 1.0.4 =
* Fixed bug in Bloglovin so it is usable again

= 1.0.5 =
* Fixed display error in short code

= 1.0.6 =
* Fixed error introduced in 1.0.5

= 1.1.0 =
* Added support for LinkedIn (with simple mode)
* Fixed minor bug with shortcodes

= 1.1.1 =
* Updated readme to show LinkedIn as a supported network

= 1.1.2 =
* Same as 1.1.1

= 1.1.3 =
* Added a separate share option so that you can turn on sharing in a social network even if you don't use it

= 1.1.4 =
* Fixed exception that occurs with followe count on share

= 1.1.5 =
* Hide out of scope options when social network is not enabled

= 1.1.6 =
* Fix so that social networking and sharing work side-by-side properly

= 1.1.7 =
* Added the network_key to the social sharing data