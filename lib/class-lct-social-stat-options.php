<?php
/**
 * Lazy Cat Themes Social Media Plugin : Options screens
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

require_once( LCT_SOCIAL_MEDIA_SOCIAL_STATS_DIR . '/class-lct-social-stats-factory.php' );
require_once( LCT_SOCIAL_MEDIA_SOCIAL_STATS_DIR . '/class-lct-follower-counter.php' );

/**
 * Class for configuration of all admin user options
 */
class LCT_Social_Stat_Options {

	/**
	 * Initialise all the options screens or social stats
	 */
	public static function init() {

		/* Make sure Redux framework exists */
		if ( ! class_exists( 'Redux' ) ) {
			return;
		}

		//* This is your option name where all the Redux configuration data is stored.
		$option_name = LCT_SOCIAL_MEDIA_OPTIONS_NAME;

		//* Get current option values (used for setting defaults)
		$option_data = get_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME );

		//* Base Redux options
		$args = array(
			'opt_name'            => $option_name,
			// This is where your data is stored in the database and also becomes your global variable name.
			'display_name'        => __( 'Social Media by Lazy Cat Themes', 'lct-social-media' ),
			// Name that appears at the top of your panel
			'display_version'     => LCT_SOCIAL_MEDIA_VERSION,
			// Version that appears at the top of your panel
			'menu_type'           => 'menu',
			//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
			'allow_sub_menu'      => true,
			// Show the sections below the admin menu item or not
			'menu_title'          => __( 'Social Media', 'lct-social-media' ),
			'page_title'          => __( 'Social Media', 'lct-social-media' ),
			// You will need to generate a Google API key to use this feature.
			// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
			'async_typography'    => true,
			// Use a asynchronous font on the front end or font string
			'dev_mode'            => false,
			// Show the time the page took to load, etc
			'update_notice'       => false,
			// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
			'customizer'          => false,
			// Enable basic customizer support
			'show_options_object' => false,
			//Allows hiding of options object
			'page_parent'         => 'themes.php',
			// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
			'page_permissions'    => 'manage_options',
			// Permissions needed to access the options panel.
			'menu_icon'           => 'dashicons-share',
			// Specify a custom URL to an icon
			'last_tab'            => '',
			// Force your panel to always open to a specific tab (by id)
			'page_icon'           => 'icon-themes',
			// Icon displayed in the admin panel next to your menu_title
			'page_slug'           => '',
			// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
			'save_defaults'       => true,
			// On load save the defaults to DB before user clicks save or not
			'default_show'        => false,
			// If true, shows the default value next to each field that is not the default value.
			'default_mark'        => '',
			// What to print by the field's title if the value shown is default. Suggested: *
			'show_import_export'  => false,
			// Shows the Import/Export panel when not used as a field.

			// CAREFUL -> These options are for advanced use only
			'transient_time'      => 60 * MINUTE_IN_SECONDS,
			'output'              => true,
			// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
			'output_tag'          => true,
			// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head

			// HINTS
			'hints'               => array(
				'icon'          => 'el el-question-sign',
				'icon_position' => 'right',
				'icon_color'    => 'black',
				'icon_size'     => 'normal',
				'tip_style'     => array(
					'color'   => 'lightyellow',
					'shadow'  => true,
					'rounded' => false,
					'style'   => '',
				),
				'tip_position'  => array(
					'my' => 'top left',
					'at' => 'bottom right',
				),
				'tip_effect'    => array(
					'show' => array(
						'effect'   => 'slide',
						'duration' => '200',
						'event'    => 'mouseover',
					),
					'hide' => array(
						'effect'   => 'slide',
						'duration' => '200',
						'event'    => 'click mouseleave',
					),
				)
			),
			'intro_text'  => __('You can access the full help documentation <a target="_blank" href="https://plugins.lazycatthemes.com">here</a>.', 'lct-social-media' )
		);

		//* Create overall options section
		Redux::setSection( $option_name, array(
			'title' => __( 'Options', 'lct-social-media' ),
			'id'    => 'general_options',
			'icon'  => 'el el-home',
		) );

		//* Allow the user to control the display order of the social networks
		Redux::setSection( $option_name, array(
			'title'      => __( 'Display Order', 'lct-social-media' ),
			'id'         => 'display_order',
			'icon'       => 'el el-th-list',
			'subsection' => true,
			'fields'     => array(
				array(
					'id'       => 'social_network_order',
					'type'     => 'sorter',
					'title'    => __( 'Social Network Order', 'lct-social-media' ),
					'compiler' => 'true',
					'options'  => array(
						'display order' => LCT_Social_Stats_Factory::get_all_supported_social_networks_key_and_name()
					)
				)
			)
		) );

		//* The setup of each individual Social Media Network
		Redux::setSection( $option_name, array(
			'title' => __( 'Social Network Setup', 'lct-social-media' ),
			'id'    => 'social_counters',
			'icon'  => 'el el-person',
		) );

		self::set_facebook_options( $option_name, $option_data );
		self::set_twitter_options( $option_name, $option_data );
		self::set_google_plus_options( $option_name, $option_data );
		self::set_pinterest_options( $option_name, $option_data );
		self::set_youtube_options( $option_name, $option_data );
		self::set_instagram_options( $option_name, $option_data );
		self::set_email_options( $option_name, $option_data );
		self::set_bloglovin_options( $option_name, $option_data );
		self::set_stumbleupon_options( $option_name, $option_data );
		self::set_linkedin_options( $option_name, $option_data );

		Redux::setArgs( $option_name, $args );

	}

	/**
	 * Facebook options which includes page name for simple mode and page ID for follower stats
	 *
	 * @param string $option_name Key for options table
	 */
	static private function set_facebook_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => __( 'Facebook', 'lct-social-media' ),
			'id'         => 'facebook_counters',
			'icon'       => 'el el-facebook',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_FACEBOOK_KEY ),
				self::get_is_share_enabled_option( LCT_SOCIAL_MEDIA_FACEBOOK_KEY, $option_data ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_FACEBOOK_KEY ),
				array(
					'id'                => 'facebook_page_name',
					'type'              => 'text',
					'title'             => __( 'Facebook Page Name', 'lct-social-media' ),
					'required'          => array(
							array( 'is_facebook_simple', 'equals', true ),
							array( 'is_facebook_enabled', 'equals', true ) ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'facebook_page_id',
					'type'              => 'text',
					'title'             => __( 'Facebook Page ID', 'lct-social-media' ),
					'required'          => array( 'is_facebook_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_FACEBOOK_KEY, __( 'Facebook', 'lct-social-media' ) ),
				self::get_share_window_width( LCT_SOCIAL_MEDIA_FACEBOOK_KEY, '600' )
			)
		) );
	}

	/**
	 *
	 * Twitter options which includes Twitter account name for simple mode and 4 keys/secrets for follower count
	 *
	 * @param string $option_name Key for options table
	 */
	static private function set_twitter_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => __( 'Twitter', 'lct-social-media' ),
			'id'         => 'twitter_counters',
			'icon'       => 'el el-twitter',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_TWITTER_KEY ),
				self::get_is_share_enabled_option( LCT_SOCIAL_MEDIA_TWITTER_KEY, $option_data ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_TWITTER_KEY ),
				array(
					'id'                => 'twitter_name',
					'type'              => 'text',
					'title'             => __( 'Twitter Name', 'lct-social-media' ),
					'required'          => array( 'is_twitter_simple', 'equals', true ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'twitter_consumer_key',
					'type'              => 'text',
					'title'             => __( 'Consumer Key (API Key)', 'lct-social-media' ),
					'required'          => array( 'is_twitter_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'twitter_consumer_secret',
					'type'              => 'text',
					'title'             => __( 'Consumer Secret (API Secret)', 'lct-social-media' ),
					'required'          => array( 'is_twitter_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'twitter_access_token',
					'type'              => 'text',
					'title'             => __( 'Access Token', 'lct-social-media' ),
					'required'          => array( 'is_twitter_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'twitter_access_token_secret',
					'type'              => 'text',
					'title'             => __( 'Access Token Secret', 'lct-social-media' ),
					'required'          => array( 'is_twitter_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_TWITTER_KEY, __( 'Twitter', 'lct-social-media' ) ),
				self::get_share_window_width( LCT_SOCIAL_MEDIA_TWITTER_KEY, '600' ),
				array(
					'id'       => 'twitter_share_height',
					'type'     => 'text',
					'title'    => __( 'Share Window Height', 'lct-social-media' ),
					'validate' => 'numeric',
					'required' => array( 'is_twitter_simple', 'equals', true ),
					'default'  => '400'
				)
			)
		) );

	}

	/**
	 *
	 * Google+ options which includes Google+ ID name for simple mode and a Google+ API key for follower count
	 *
	 * @param string $option_name Key for options table
	 */
	static private function set_google_plus_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => __( 'Google+', 'lct-social-media' ),
			'id'         => 'google_counters',
			'icon'       => 'el el-googleplus',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY ),
				self::get_is_share_enabled_option( LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY, $option_data ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY ),
				array(
					'id'                => 'google-plus_id',
					'type'              => 'text',
					'title'             => __( 'Google+ ID', 'lct-social-media' ),
					'required'          => array( 'is_google-plus_enabled', 'equals', true ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'google-plus_api_key',
					'type'              => 'text',
					'title'             => __( 'Google API key', 'lct-social-media' ),
					'required'          => array( 'is_google-plus_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY, __( 'Google+', 'lct-social-media' ) ),
				self::get_share_window_width( LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY, '525' )
			)
		) );

	}

	/**
	 *
	 * Google+ options which includes an Instagram account name for simple mode and an Instagram Access Token for follower count
	 *
	 * @param string $option_name Key for options table
	 */
	static private function set_instagram_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => __( 'Instagram', 'lct-social-media' ),
			'id'         => 'instagram_counters',
			'icon'       => 'el el-instagram',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_INSTAGRAM_KEY ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_INSTAGRAM_KEY ),
				array(
					'id'                => 'instagram_name',
					'type'              => 'text',
					'title'             => __( 'Instagram Name', 'lct-social-media' ),
					'required'          => array( 'is_instagram_simple', 'equals', true ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'instagram_access_token',
					'type'              => 'text',
					'title'             => __( 'Instagram Access Token', 'lct-social-media' ),
					'required'          => array( 'is_instagram_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_INSTAGRAM_KEY, __( 'Instagram', 'lct-social-media' ) )
			)
		) );

	}

	/**
	 *
	 * Pinterest options which includes a username for both simple and complex accounts
	 *
	 * @param string $option_name Key for options table
	 */
	static private function set_pinterest_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => __( 'Pinterest', 'lct-social-media' ),
			'id'         => 'pinterest_counters',
			'icon'       => 'el el-pinterest',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_PINTEREST_KEY ),
				self::get_is_share_enabled_option( LCT_SOCIAL_MEDIA_PINTEREST_KEY, $option_data ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_PINTEREST_KEY ),
				array(
					'id'                => 'pinterest_username',
					'type'              => 'text',
					'title'             => __( 'Pinterest Username', 'lct-social-media' ),
					'required'          => array( 'is_pinterest_enabled', 'equals', true ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_PINTEREST_KEY, __( 'Pinterest', 'lct-social-media' ) )
			)
		) );

	}

	/**
	 *
	 * YouTube options which includes an YouTube channel ID for simple mode and a YouTube API key for follower count
	 *
	 * @param string $option_name Key for options table
	 */
	static private function set_youtube_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => __( 'YouTube', 'lct-social-media' ),
			'id'         => 'youtube_counters',
			'icon'       => 'el el-youtube',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_YOUTUBE_KEY ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_YOUTUBE_KEY ),
				array(
					'id'                => 'youtube_channel_id',
					'type'              => 'text',
					'title'             => __( 'Channel ID', 'lct-social-media' ),
					'required'          => array( 'is_youtube_enabled', 'equals', true ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				array(
					'id'                => 'youtube_api_key',
					'type'              => 'text',
					'title'             => __( 'YouTube API Key', 'lct-social-media' ),
					'required'          => array( 'is_youtube_simple', 'equals', false ),
					'validate_callback' => array( 'LCT_Social_Stat_Options', 'trim_field' )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_YOUTUBE_KEY, __( 'YouTube', 'lct-social-media' ) )
			)
		) );
	}

	/**
	 *
	 * Email options which include a body message and mailto: address
	 *
	 * @param string $option_name Key for options table
	 */
	static function set_email_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => __( 'Email', 'lct-social-media' ),
			'id'         => 'email_counters',
			'icon'       => 'el el-envelope',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_EMAIL_KEY ),
				self::get_is_share_enabled_option( LCT_SOCIAL_MEDIA_EMAIL_KEY, $option_data ),
				array(
					'id'       => 'email_body_message',
					'type'     => 'text',
					'title'    => __( 'Body Message', 'lct-social-media' ),
					'default'  => __( 'Check out this cool post! %s', 'lct-social-media' ),
					'required' => array( 'is_email_enabled', 'equals', true )
				),
				array(
					'id'       => 'email_mailto_address',
					'type'     => 'text',
					'title'    => __( 'MailTo Address', 'lct-social-media' ),
					'default'  => __( '', 'lct-social-media' ),
					'required' => array( 'is_email_enabled', 'equals', true )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_EMAIL_KEY, __( 'Email', 'lct-social-media' ) )
			)
		) );

	}

	/**
	 *
	 * Bloglovin' options which include a BlogLovin' username
	 *
	 * @param string $option_name Key for options table
	 */
	static function set_bloglovin_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => 'Bloglovin\'',
			'id'         => 'bloglovin_counters',
			'icon'       => 'el el-heart',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_BLOGLOVIN_KEY ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_BLOGLOVIN_KEY ),
				array(
					'id'    => 'bloglovin_username',
					'type'  => 'text',
					'title' => __( 'Bloglovin\' Blog Name', 'lct-social-media' ),
					'required' => array( 'is_bloglovin_enabled', 'equals', true )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_BLOGLOVIN_KEY, __( 'Bloglovin\'', 'lct-social-media' ) )
			)
		) );

	}

	/**
	 * LinkedIn options which includes a URL
	 *
	 * @param $option_name
	 */
	static function set_linkedin_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => 'LinkedIn',
			'id'         => 'linkedin_counters',
			'icon'       => 'el el-linkedin',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_LINKEDIN_KEY ),
				self::get_is_share_enabled_option( LCT_SOCIAL_MEDIA_LINKEDIN_KEY, $option_data ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_LINKEDIN_KEY ),
				array(
					'id'    => 'linkedin_url',
					'type'  => 'text',
					'title' => __( 'LinkedIn Full URL', 'lct-social-media' ),
					'required' => array( 'is_linkedin_enabled', 'equals', true )
				),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_LINKEDIN_KEY, __( 'LinkedIn', 'lct-social-media' ) )
			)
		) );

	}

	/**
	 *
	 * StumbleUpon options which include a StumbleUpon page name
	 *
	 * @param string $option_name Key for options table
	 */
	static function set_stumbleupon_options( $option_name, $option_data ) {

		Redux::setSection( $option_name, array(
			'title'      => 'StumbleUpon',
			'id'         => 'stumbleupon_counters',
			'icon'       => 'el el-stumbleupon',
			'subsection' => true,
			'fields'     => array(
				self::get_is_enabled_option( LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY ),
				self::get_is_share_enabled_option( LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY, $option_data ),
				self::get_is_simple_option( LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY ),
				array(
					'id'    => 'stumbleupon_username',
					'type'  => 'text',
					'title' => __( 'StumbleUpon Username', 'lct-social-media' ),
					'required' => array( 'is_stumbleupon_enabled', 'equals', true )
				),
				self::get_share_window_width( LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY, '600' ),
				self::get_tooltip_option( LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY, __( 'StumbleUpon', 'lct-social-media' ) )
			)
		) );

	}

	/**
	 *
	 * @param $key
	 * @param $default
	 *
	 * @return array
	 */
	static private function get_share_window_width( $key, $default ) {
		return array(
			'id'       => $key . '_share_width',
			'type'     => 'text',
			'title'    => __( 'Share Window Width', 'lct-social-media' ),
			'validate' => 'numeric',
			'default'  => $default,
			'required'  => array( 'is_' . $key . '_enabled', 'equals', true )
		);
	}

	/**
	 * 	 Shorthand for getting the is_enabled current value safely
	 *
	 * @param $key
	 * @param $option_data
	 *
	 * @return bool
	 */
	static private function get_is_enabled_key_if_exists( $key, $option_data ) {
		if ( !$option_data ) {
			//On first load, default to zero
			return false;
		}

		$full_key_name = 'is_' . $key . '_enabled';
		return array_key_exists( $full_key_name, $option_data ) ? $option_data[$full_key_name] : false;
	}

	/**
	 * @param $key
	 *
	 * @return array
	 */
	static private function get_is_enabled_option( $key ) {
		return array(
			'id'      => 'is_' . $key . '_enabled',
			'type'    => 'checkbox',
			'title'   => __( 'Is Enabled', 'lct-social-media' ),
			'default' => false
		);
	}

	/**
	 * @param $key
	 * @param $option_data
	 *
	 * @return array
	 */
	static private function get_is_share_enabled_option( $key, $option_data ) {
		return array(
			'id'        => 'is_' . $key . '_share_enabled',
			'type'      => 'checkbox',
			'title'     => __( 'Is Share Enabled', 'lct-social-media' ),
			'default'   => self::get_is_enabled_key_if_exists( $key, $option_data ),
			'hint'      => array(
				'content' => __(
					'This enables users to share through this social sharing medium even if you don\'t have an account.',
					'lct-social-media' ) )
		);
	}

	/**
	 * @param $key
	 *
	 * @return array
	 */
	static private function get_is_simple_option( $key ) {
		return array(
			'id'        => 'is_' . $key . '_simple',
			'type'      => 'checkbox',
			'title'     => __( 'Is Simple Mode', 'lct-social-media' ),
			'required'  => array( 'is_' . $key . '_enabled', 'equals', true ),
			'default'   => true,
			'hint'      => array(
				'content' => __(
					'Simple mode requires fewer configuration options to fill in but does not give you a followers count',
					'lct-social-media' )
			)
		);
	}

	/**
	 * @param $key
	 * @param $default
	 *
	 * @return array
	 */
	static private function get_tooltip_option( $key, $default ) {
		return array(
			'id'      => $key . '_tooltip',
			'type'    => 'text',
			'title'   => __( 'Tooltip', 'lct-social-media' ),
			'default' => $default,
			'required'  => array( 'is_' . $key . '_enabled', 'equals', true )
		);
	}

	/**
	 *
	 * Trim any IDs or keys or URL suffixes
	 *
	 * @param $field
	 * @param $value
	 * @param $existing_value
	 *
	 * @return array
	 */
	static public function trim_field( $field, $value, $existing_value ) {
		$field_details['value']          = trim( $value );
		$field_details['existing_value'] = trim( $existing_value );

		return $field_details;
	}

}


