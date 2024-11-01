<?php
/**
 * Lazy Cat Themes Social Media Plugin : Twitter implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

use Abraham\TwitterOAuth\TwitterOAuth;

class LCT_Twitter_Social_Stat extends LCT_Social_Stat {

	/**
	 * @param $key
	 */
	public function __construct( $key ) {
		$this->assign_key_and_name( $key );
	}

	/**
	 * Simple mode just throws together a simple URL. The complex mode connects to the Twitter API using OAuth to get the
	 * follower count and screen name
	 *
	 * @param array $options
	 * @param bool $is_share
	 *
	 * @throws Exception
	 */
	public function populate_data( $options, $is_share = false ) {

		if ( ( $options['is_twitter_simple'] ) || ( $is_share ) ) {
			$this->url = sprintf( 'http://www.twitter.com/%s', esc_attr( $options['twitter_name'] ) );
		} else {

			$connection = new TwitterOAuth(
				$options['twitter_consumer_key'],
				$options['twitter_consumer_secret'],
				$options['twitter_access_token'],
				$options['twitter_access_token_secret'] );
			$verify_credentials_result = $connection->get( "account/verify_credentials" );
			if ( ! isset( $verify_credentials_result->followers_count ) ) {
				throw new Exception( __( 'Error obtaining Twitter details, please check the settings and try again', 'lct-social-media' ) );
			}

			$this->followers = $verify_credentials_result->followers_count;
			$this->url       = "http://twitter.com/$verify_credentials_result->screen_name";
		}
	}

	/**
	 * @param string $post_title
	 * @param string $post_url
	 * @param string $title
	 *
	 * @return string
	 */
	public function get_share_link( $post_title, $post_url, $title ) {

		//Extract twitter username from full URL (find last dash and take extensions)
		$last_dash        = strrpos( $this->url, '/' ) + 1;
		$twitter_username = substr( $this->url, $last_dash );

		$twitter_link = "https://twitter.com/share?url=" . $post_url . "&text=" . $post_title . "&tags=";
		if ( $twitter_username )  {
			$twitter_link .= "&via=$twitter_username";
		}

		return $this->get_shared_popup_link( $twitter_link, $title, $this->width, $this->height );

	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "twitter";
	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return true;
	}

}