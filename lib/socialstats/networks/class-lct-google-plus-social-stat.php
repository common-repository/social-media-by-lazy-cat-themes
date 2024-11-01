<?php
/**
 * Lazy Cat Themes Social Media Plugin : Google+ implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

class LCT_GooglePlus_Social_Stat extends LCT_Social_Stat {

	/**
	 * @param $key
	 */
	public function __construct( $key ) {
		$this->assign_key_and_name( $key );
	}

	/**
	 * If simple, then just paste URL together using ID, else use api to get "circled by" count
	 *
	 * @param array $options
	 * @param bool $is_share
	 */
	public function populate_data( $options, $is_share = false ) {

		if ( $options['is_google-plus_simple'] || ( $is_share ) ) {
			$this->url = sprintf( 'https://plus.google.com/%s', esc_attr( $options['google-plus_id'] ) );
		} else {
			//* use Google+ API to get count
			$page_id = $options['google-plus_id'];
			$api_key = $options['google-plus_api_key'];
			$google_plus_url = "https://www.googleapis.com/plus/v1/people/$page_id?key=$api_key";
			$google_plus_json = $this->safe_file_get_contents( $google_plus_url );

			$google_plus_data = json_decode( $google_plus_json );
			$this->followers  = $google_plus_data->circledByCount;
			$this->url        = $google_plus_data->url;
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
		$google_link = "https://plus.google.com/share?url=" . $post_url;
		return $this->get_shared_popup_link( $google_link, $title, $this->width, null );
	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "google-plus";
	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return true;
	}
}