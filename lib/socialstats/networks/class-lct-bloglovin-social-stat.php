<?php
/**
 * Lazy Cat Themes Social Media Plugin : Bloglovin implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

class LCT_Bloglovin_Social_Stat extends LCT_Social_Stat {

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
	private function get_follower_count( $url ) {
		$html = $this->safe_file_get_contents( $url );

		//* Stop warnings being thrown
		libxml_use_internal_errors( true );

		//* Load into a DOM to get the follower count tag
		$doc = new DOMDocument();
		$doc->loadHTML( $html );

		//* Use XPath to query html
		$xpath = new DOMXpath( $doc );

		$node_list = $xpath->query( '//li[@class="tabbar-tab"]' );

		//* There should be 3 tabbar-tab li nodes, we want the 2nd one
		if ( 3 === $node_list->length ) {
			return trim( str_replace( 'Followers', '', $node_list->item( 1 )->textContent ) );
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

		$bloglovin_username = $options['bloglovin_username'];
		$this->url          = "https://www.bloglovin.com/blogs/$bloglovin_username/";

		if ( ( ! $options['is_bloglovin_simple'] ) && ( ! $is_share ) ) {
			$this->followers = self::get_follower_count( $this->url );
		}

	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name() {
		return "heart";
	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return false;
	}

}