<?php
/**
 * Lazy Cat Themes Social Media Plugin : Facebook implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

class LCT_Facebook_Social_Stat extends LCT_Social_Stat {

	/**
	 * @param $key
	 */
	public function __construct( $key ) {
		$this->assign_key_and_name( $key );
	}

	/**
	 * If simple mode, just set the Facebook URL, If complex mode use the API to get url and fan count
	 *
	 * @param array $options
	 * @param bool $is_share
	 */
	public function populate_data( $options, $is_share = false ) {

		if ( $options['is_facebook_simple'] || ( $is_share ) ) {
			$this->url = sprintf( 'http://www.facebook.com/%s', esc_attr( $options['facebook_page_name'] ) );
		} else {
			//* Make an API call to get stats
			$facebook_page_id = $options['facebook_page_id'];
			$facebook_url     = "http://api.facebook.com/method/fql.query?format=json&query=select+fan_count,%20page_url+from+page+where+page_id=$facebook_page_id";
			$page_stats_json = $this->safe_file_get_contents( $facebook_url );
			$page_stats      = json_decode( $page_stats_json )[0];

			$this->followers = $page_stats->fan_count;
			$this->url       = $page_stats->page_url;

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

		$fb_link = "http://www.facebook.com/sharer.php?u=" . $post_url;

		//Facebook adjust height automatically so we pass through null here
		return $this->get_shared_popup_link( $fb_link, $title, $this->width, null );

	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "facebook";
	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return true;
	}
}