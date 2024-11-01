<?php

/**
 * Lazy Cat Themes Social Media Plugin : YouTube implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */
class LCT_YouTube_Social_Stat extends LCT_Social_Stat {

	/**
	 * @param $key
	 */
	public function __construct( $key ) {
		$this->assign_key_and_name( $key );
	}

	/**
	 * Append the URL if in simple mode, or use the google API to get the stats if in complex mode
	 * @param array $options
	 * @param bool $is_share
	 */
	public function populate_data( $options, $is_share = false ) {

		if ( $options['is_youtube_simple'] || ( $is_share ) ) {
			$this->url = sprintf( 'https://www.youtube.com/channel/%s', esc_attr( $options['youtube_channel_id'] ) );
		} else {

			$channel_id = $options['youtube_channel_id'];
			$api_key    = $options['youtube_api_key'];

			$youtube_url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$channel_id&key=$api_key";

			$youtube_json = $this->safe_file_get_contents( $youtube_url, 'YouTube' );
			$youtube_data = json_decode( $youtube_json, true );

			$this->followers = $youtube_data['items'][0]['statistics']['subscriberCount'];
			$this->url       = sprintf( 'http://www.youtube.com/channel/', $channel_id );
		}
	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "youtube";
	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return false;
	}
}