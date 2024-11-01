<?php
/**
 * Lazy Cat Themes Social Media Plugin : Social stat factory
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

//* Social stat abstract class
require_once( __DIR__ . '/class-lct-social-stat.php' );

//* Social stat wrapper to control cache
require_once( __DIR__ . '/class-lct-social-stat-holder.php' );

//* Social network implementations
require_once( __DIR__ . '/networks/class-lct-bloglovin-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-email-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-facebook-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-google-plus-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-instagram-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-linkedin-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-pinterest-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-stumbleupon-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-twitter-social-stat.php' );
require_once( __DIR__ . '/networks/class-lct-youtube-social-stat.php' );

/**
 * Class factor for creating social network implementations
 */
class LCT_Social_Stats_Factory {

	/**
	 * @param string $key
	 *
	 * @return LCT_Social_Stat or derived class
	 */
	public static function create( $key, $is_share = false ) {

		$option_key_name =
			( $is_share ) ?
			'is_' . $key . '_share_enabled' :
			'is_' . $key . '_enabled';

		//* Create appropriate social media class
		if ( get_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME )[$option_key_name] ) {
			switch ( $key ) {
				case LCT_SOCIAL_MEDIA_FACEBOOK_KEY:
					return new LCT_Facebook_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_TWITTER_KEY:
					return new LCT_Twitter_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY:
					return new LCT_GooglePlus_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_YOUTUBE_KEY:
					return new LCT_YouTube_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_PINTEREST_KEY:
					return new LCT_Pinterest_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_INSTAGRAM_KEY:
					return new LCT_Instagram_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_EMAIL_KEY:
					return new LCT_Email_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_BLOGLOVIN_KEY:
					return new LCT_Bloglovin_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY:
					return new LCT_Stumbleupon_Social_Stat( $key );

				case LCT_SOCIAL_MEDIA_LINKEDIN_KEY:
					return new LCT_LinkedIn_Social_Stat( $key );

				default:
					die( "$key not supported" );
			}
		}
		//* Nothing created here
		return null;
	}

	/**
	 * Get a list of supported social networks
	 * @return array
	 */
	public static function get_all_supported_social_networks() {
		return array(
			LCT_SOCIAL_MEDIA_FACEBOOK_KEY,
			LCT_SOCIAL_MEDIA_TWITTER_KEY,
			LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY,
			LCT_SOCIAL_MEDIA_YOUTUBE_KEY,
			LCT_SOCIAL_MEDIA_PINTEREST_KEY,
			LCT_SOCIAL_MEDIA_INSTAGRAM_KEY,
			LCT_SOCIAL_MEDIA_EMAIL_KEY,
			LCT_SOCIAL_MEDIA_BLOGLOVIN_KEY,
			LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY,
			LCT_SOCIAL_MEDIA_LINKEDIN_KEY
		);
	}

	/**
	 * Create a cross-reference of keys and names
	 * @return array
	 */
	public static function get_all_supported_social_networks_key_and_name() {
		return array(
			LCT_SOCIAL_MEDIA_FACEBOOK_KEY    => 'Facebook',
			LCT_SOCIAL_MEDIA_TWITTER_KEY     => 'Twitter',
			LCT_SOCIAL_MEDIA_GOOGLE_PLUS_KEY => 'Google+',
			LCT_SOCIAL_MEDIA_YOUTUBE_KEY     => 'YouTube',
			LCT_SOCIAL_MEDIA_PINTEREST_KEY   => 'Pinterest',
			LCT_SOCIAL_MEDIA_INSTAGRAM_KEY   => 'Instagram',
			LCT_SOCIAL_MEDIA_EMAIL_KEY       => 'Email',
			LCT_SOCIAL_MEDIA_BLOGLOVIN_KEY   => 'Bloglovin\'',
			LCT_SOCIAL_MEDIA_STUMBLEUPON_KEY => 'StumbleUpon',
			LCT_SOCIAL_MEDIA_LINKEDIN_KEY    => 'LinkedIn'
		);

	}


}