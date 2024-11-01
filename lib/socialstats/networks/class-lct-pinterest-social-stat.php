<?php
/**
 * Lazy Cat Themes Social Media Plugin : Pinterest implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

class LCT_Pinterest_Social_Stat extends LCT_Social_Stat {

	/**
	 * LCT_Pinterest_Social_Stat constructor.
	 *
	 * @param $key
	 */
	public function __construct( $key ) {
		$this->assign_key_and_name( $key );
	}


	/**
	 * Complex mode counts followers based on the website.
	 *
	 * @param array $options
	 * @param bool $is_share
	 */
	public function populate_data( $options, $is_share = false ) {

		$pinterest_username = $options['pinterest_username'];
		$this->url = "http://pinterest.com/$pinterest_username/";

		if ( ( ! $options['is_pinterest_simple'] ) && ( ! $is_share ) ) {
			$meta_tags = $this->safe_get_meta_tags( $this->url );
			$this->followers = $meta_tags['pinterestapp:followers'];
		}

	}

	/**
	 * @return bool
	 */
	public function is_shareable() {
		return true;
	}

	/**
	 * @return string
	 */
	public function get_font_awesome_name()
	{
		return "pinterest";
	}


	/**
	 * @param string $post_title
	 * @param string $post_url
	 * @param string $title
	 *
	 * @return string
	 */
	public function get_share_link( $post_title, $post_url, $title ) {
		$share_html = '<a target="_blank" title="'.sprintf( __( 'Share on %s', 'lct-social-media') ,$title ).'" ';
		$share_html .= ' href="//pinterest.com/pin/create/link/?url='.$post_url.'&description='.$post_title.'" data-pin-do="skipLink" data-pin-color="white" data-pin-height="28" rel="nofollow">' .
		               LCT_SOCIAL_MEDIA_PLACEHOLDER . '</a>';
		return $share_html;
	}

}
