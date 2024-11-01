<?php
/**
 * Lazy Cat Themes Social Media Plugin : Instagram implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

class LCT_Instagram_Social_Stat extends LCT_Social_Stat {

	/**
	 * LCT_Instagram_Social_Stat constructor.
	 *
	 * @param $key
	 */
	public function __construct( $key ) {
		$this->assign_key_and_name( $key );
	}

	/**
	 * If simple, then append instagram URL, if complicated use the API to get the stats
	 *
	 * @param array $options
	 * @param bool $is_share
	 */
	public function populate_data( $options, $is_share = false ) {

		if ( $options['is_instagram_simple'] || ( $is_share ) ) {
			$this->url = sprintf( 'http://www.instagram.com/%s', esc_attr( $options['instagram_name'] ) );
		} else {
			//* Get the stats using the Instagram API
			$instagram_access_token = $options['instagram_access_token'];
			$user_id                = explode( '.', $instagram_access_token )[0];
			$instagram_data_json = $this->safe_file_get_contents(
				"https://api.instagram.com/v1/users/$user_id/?access_token=$instagram_access_token" );
			$instagram_data      = json_decode( $instagram_data_json );

			$this->followers = $instagram_data->data->counts->followed_by;
			$this->url       = 'https://instagram.com/' . $instagram_data->data->username;
		}
	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "instagram";
	}

	/**
	 * No sharing on instagram, that stuff is App-based (I think)
	 * @return bool
	 */
	public function is_shareable() {
		return false;
	}
}