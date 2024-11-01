<?php
/**
 * Lazy Cat Themes Social Media Plugin : LinkedIn implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */


class LCT_LinkedIn_Social_Stat extends LCT_Social_Stat {

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
		$this->url = esc_attr( $options['linkedin_url'] );
	}

	/**
	 * @param string $post_title
	 * @param string $post_url
	 * @param string $title
	 *
	 * @return string
	 */
	public function get_share_link( $post_title, $post_url, $title ) {

		$linkedin_link = "https://www.linkedin.com/shareArticle?mini=true&url=" . $post_url . "&title=" . $post_title . "&source=" . get_bloginfo( 'name' );

		return $this->get_shared_popup_link( $linkedin_link, $title, 520, 570 );
	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "linkedin";
	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return true;
	}

}