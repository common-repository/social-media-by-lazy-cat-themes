<?php
/*
	Plugin Name: Social Media by Lazy Cat Themes
	Plugin URI: https://plugins.lazycatthemes.com/social-media
	Description: This is a plugin that supports sharing of social media for the 10 most common social media providers. It also provides an optional follower count display, a widget, a shortcode and an API for displaying social network information.
	Version: 1.1.7
	Author: Lazy Cat Themes
	Author URI: https://lazycatthemes.com
	License: GPL-2.0+
	Text Domain: lct-social-media
	Domain Path: /lib/languages

	Lazy Cat Themes Social Media Plugin is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.

	Lazy Cat Themes Social Media Plugin is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Lazy Cat Themes Social Media Plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html
*/

//* Prevent direct access to the plugin
if ( ! defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}

//* General Constants
define( 'LCT_SOCIAL_MEDIA_VERSION', '1.1.7' );
define( 'LCT_SOCIAL_MEDIA_LIB_DIR', dirname( __FILE__ ) . '/lib/' );
define( 'LCT_SOCIAL_MEDIA_SOCIAL_STATS_DIR', LCT_SOCIAL_MEDIA_LIB_DIR . '/socialstats/' );
define( 'LCT_SOCIAL_MEDIA_CSS_DIR', LCT_SOCIAL_MEDIA_LIB_DIR . '/css/' );
define( 'LCT_SOCIAL_MEDIA_JS_DIR', LCT_SOCIAL_MEDIA_LIB_DIR . '/js/' );
define( 'LCT_SOCIAL_MEDIA_WIDGETS_DIR', LCT_SOCIAL_MEDIA_LIB_DIR . '/widgets/' );
define( 'LCT_SOCIAL_MEDIA_VENDOR_DIR', LCT_SOCIAL_MEDIA_LIB_DIR . '/vendor/' );
define( 'LCT_SOCIAL_MEDIA_OPTIONS_NAME', 'lct-social-media-plugin-options' );
define( 'LCT_SOCIAL_MEDIA_CACHED_STATS_OPTIONS_NAME', 'lct-social-media-cached' );



//* Social media keys
define( 'LCT_SOCIAL_MEDIA_FACEBOOK_KEY', 'facebook' );
define( 'LCT_SOCIAL_MEDIA_TWITTER_KEY', 'twitter' );
define( 'LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY', 'google-plus' );
define( 'LCT_SOCIAL_MEDIA_YOUTUBE_KEY', 'youtube' );
define( 'LCT_SOCIAL_MEDIA_PINTEREST_KEY', 'pinterest' );
define( 'LCT_SOCIAL_MEDIA_INSTAGRAM_KEY', 'instagram' );
define( 'LCT_SOCIAL_MEDIA_EMAIL_KEY', 'email' );
define( 'LCT_SOCIAL_MEDIA_BLOGLOVIN_KEY', 'bloglovin' );
define( 'LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY', 'stumbleupon' );
define( 'LCT_SOCIAL_MEDIA_LINKEDIN_KEY', 'linkedin' );

//* Actions to link into
add_action( 'plugins_loaded', array( 'LCT_Social_Media', 'lct_social_media_init' ) );
add_action( 'redux/options/' . LCT_SOCIAL_MEDIA_OPTIONS_NAME . '/saved', array( 'LCT_Social_Media', 'options_write' ) );

//* Remove transients on deactivate
register_deactivation_hook( __FILE__, array( 'LCT_Social_Media', 'remove_transient_option' ) );

//* Remove options keys on uninstall
register_uninstall_hook( __FILE__, array( 'LCT_Social_Media', 'remove_options_keys' ) );

/**
 * Class LCT_Social_Media
 */
class LCT_Social_Media {

	/**
	 * Initialises the social media plugin
	 *
	 */
	static public function lct_social_media_init() {

		require_once( LCT_SOCIAL_MEDIA_LIB_DIR . 'class-lct-schema-organization.php' );
		require_once( LCT_SOCIAL_MEDIA_LIB_DIR . 'class-lct-social-media-requires.php' );
		require_once( LCT_SOCIAL_MEDIA_LIB_DIR . 'class-lct-social-stat-options.php' );
		require_once( LCT_SOCIAL_MEDIA_LIB_DIR . 'class-lct-stats-api.php' );
		require_once( LCT_SOCIAL_MEDIA_LIB_DIR . 'class-lct-stats-shortcodes.php' );
		require_once( LCT_SOCIAL_MEDIA_SOCIAL_STATS_DIR . 'class-lct-follower-counter.php' );

		require_once( LCT_SOCIAL_MEDIA_SOCIAL_STATS_DIR . 'class-lct-social-stats-factory.php' );
		require_once( LCT_SOCIAL_MEDIA_WIDGETS_DIR . 'class-lct-widget-loader.php' );
		require_once( LCT_SOCIAL_MEDIA_CSS_DIR . 'class-lct-social-media-plugin-styles.php');
		require_once( LCT_SOCIAL_MEDIA_JS_DIR . 'class-lct-social-media-plugin-scripts.php');

		//* Vendor includes
		require_once( LCT_SOCIAL_MEDIA_VENDOR_DIR . 'twitteroauth/autoload.php' );
		require_once( LCT_SOCIAL_MEDIA_VENDOR_DIR . 'class-tgm-plugin-activation.php' );

		//* Disables redux notice that times out
		$GLOBALS['redux_notice_check'] = false;

		//* Load language translations
		load_plugin_textdomain( 'lct-social-media', false, dirname( plugin_basename( __FILE__ ) ) . '/lib/languages' );

		if ( is_admin() ) {
			//* We only need options screen is we're admin
			LCT_Social_Stat_Options::init();
		}

	}

	/**
	 * Calculate the stats on an options save
	 *
	 */
	static public function options_write() {
		do_action( 'calc_followers' );
	}


	/**
	 * Remove the options keys on uninstall
	 */
	static public function remove_options_keys() {

		//* Delete the option key
		delete_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME );
		//* Delete the social network cached stats
		delete_option( LCT_SOCIAL_MEDIA_CACHED_STATS_OPTIONS_NAME );
		//* Delete the transient (that isn't a real transient) on uninstall
		delete_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME . '-transients' );
		//* Remove the widget
		delete_option( 'widget_lct_social_media_widget' );

	}


}

