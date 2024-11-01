<?php

/**
 * Lazy Cat Themes Social Media Plugin : StumbleUpon implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */
class LCT_Stumbleupon_Social_Stat extends LCT_Social_Stat {

	/**
	 * @param string $key
	 */
	public function __construct( $key ) {
		$this->assign_key_and_name( $key );
	}

	/**
	 *
	 * We have to load the actual web page and scrape to get followers
	 *
	 * @param string $url
	 *
	 * @return null|string
	 */
	private function get_follower_count( $url  ) {
		$html = $this->safe_file_get_contents(
			$url .
			'/connections/followers?_nospa=true&_notoolbar=true&_notoolbar=true&_nospa=true' );

		//* Stop warnings being thrown
		libxml_use_internal_errors( true );

		//* Load into a DOM to get the follower count tag
		$doc = new DOMDocument();
		$doc->loadHTML( $html );

		//* Use XPath to query html
		$xpath = new DOMXpath( $doc );

		$node_list = $xpath->query( "//li/a[mark='Followers']/mark[@class='nav-tertiary-count']" );
		
		if ( 1 === $node_list->length ) {
			//* First of the two matching nodes is the follower count
			return $node_list->item( 0 )->nodeValue;
		}

		//* Unable to obtain a follower count
		return null;
	}

	/**
	 * Gets followers with screen scraping
	 *
	 * @param array $options
	 * @param bool $is_share
	 */
	public function populate_data( $options, $is_share = false ) {

		$stumbleupon_username = strtolower( $options['stumbleupon_username'] );
		$this->url            = "http://www.stumbleupon.com/stumbler/$stumbleupon_username";

		if ( ( ! $options['is_stumbleupon_simple'] ) && ( ! $is_share ) ) {
			$this->followers = self::get_follower_count( $this->url );
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

		$twitter_link = "http://www.stumbleupon.com/submit?url=" . $post_url . "&title=" . $post_title;

		//Facebook adjust height automatically so we pass through null here
		return $this->get_shared_popup_link( $twitter_link, $title, $this->width, null );

	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "stumbleupon";
	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return true;
	}

}