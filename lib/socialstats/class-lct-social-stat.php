<?php
/**
 * Lazy Cat Themes Social Media Plugin : Abstract class for modelling a social networks stats
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

//* Default height for share dialogs that are displayed using javascript
const DEFAULT_WINDOW_HEIGHT = 400;

/**
 * Class LCT_Social_Stat
 */
abstract class LCT_Social_Stat {

	/**
	 * unique key for social network
	 * @var string
	 */
	public $key;

	/**
	 * the display name for the social network
	 * @var string
	 */
	public $name;

	/**
	 * the number of followers or fans of the social network
	 * @var int
	 */
	public $followers;

	/**
	 * the tooltip to be shown when highlighting the social media icon
	 * @var string
	 */
	public $tooltip;

	/**
	 * the url for the social media site (ie www.facebook.com/mypage)
	 * @var string
	 */
	public $url;

	/**
	 * the width of the sharing popup window
	 * @var int
	 */
	public $width;

	/**
	 * the height of the sharing popup window
	 * @var int
	 */
	public $height;

	/**
	 * the body of an email message for the Email social option
	 * @var string
	 */
	public $body;

	/**
	 * populates the fields based on $options using populate_data polymorphic method
	 * @param array $options
	 * @param bool $is_share
	 */
	public function populate_all_data( $options, $is_share = false ) {

		if ( array_key_exists( $this->key . '_tooltip', $options ) ) {
			$this->tooltip = $options[ $this->key . '_tooltip' ];
		}
		if ( array_key_exists( $this->key . '_share_width', $options ) ) {
			$this->width = $options[ $this->key . '_share_width' ];
		}
		if ( array_key_exists( $this->key . '_share_height', $options ) ) {
			$this->height = $options[ $this->key . '_share_height' ];
		}
		if ( array_key_exists( $this->key . '_email_body_message', $options ) ) {
			$this->body = $options[ $this->key . '_email_body_message' ];
		}

		$this->populate_data( $options, $is_share );

	}

	/**
	 * populates data dependant on each social network
	 * @param array $options
	 * @param bool $is_share
	 *
	 */
	abstract public function populate_data( $options, $is_share = false );

	/**
	 * not all networks support browser sharing (eg Instagram)
	 * @return bool Is a shareable social network
	 */
	abstract public function is_shareable();

	/**
	 * get the font awesome name for social network which defines the icon
	 * @return string
	 */
	abstract public function get_font_awesome_name();

	/**
	 * get a link designed for social sharing
	 *
	 * @param string $post_title
	 * @param string $post_url
	 * @param string $title
	 *
	 * @return string|null
	 */
	public function get_share_link( $post_title, $post_url, $title ) {
		/* By default, we don't support sharing. It is up to social networks to override to implement this behaviour */
		return null;
	}

	/**
	 * Extracts the HTTP response code from the headers method
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	protected function get_http_response_code( $url ) {
		$headers = get_headers( $url );
		return substr( $headers[0], 9, 3 );
	}

	/**
	 * Creates a centred social network sharing popup link
	 *
	 * @param string $social_link
	 * @param string $title
	 * @param int $width
	 * @param int $height
	 *
	 * @return string
	 */
	protected function get_shared_popup_link( $social_link, $title, $width, $height ) {
		$link_html = "<a href='javascript: void(0);' title='" . sprintf( __( 'Share on %s', 'lct-social-media' ), $title ) . "' ";
		$link_html .= "onclick='PopupCenter( \"$social_link\", \"sharer\", \"$width\"";
		if ( empty( $height ) ) {
			$height = DEFAULT_WINDOW_HEIGHT;
		}
		$link_html .= ",\"$height\")'/>" . LCT_SOCIAL_MEDIA_PLACEHOLDER . '</a>';

		return $link_html;
	}

	/**
	 * Throws an exception so that we can have shared exception handling in LCT_Follower_Count
	 *
	 * @param string $url
	 *
	 * @throws Exception
	 */
	protected function throw_config_exception( $url ) {
		throw new Exception(
			sprintf(
				__( '%s URL \'%s\' invalid, please check your %s configuration settings', 'lct-social-media' ),
				$this->name,
				$url,
				$this->name ) );
	}

	/**
	 * Assigns name and key (common for all social networks)
	 *
	 * @param $key
	 */
	protected function assign_key_and_name( $key ) {
		$this->key  = $key;
		$this->name = LCT_Social_Stats_Factory::get_all_supported_social_networks_key_and_name()[ $key ];
	}

	/**
	 * Gets file contents without throwing standard exceptions. Instead funnels failures through our own exception types
	 * making them handleable in a standard way
	 *
	 * @param string $url
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function safe_file_get_contents( $url ) {

		$file_contents = @file_get_contents( $url );
		if ( ! $file_contents ) {
			$this->throw_config_exception( $url );
		}

		return $file_contents;
	}

	/**
	 * Gets url meta tags without throwing standard exceptions. Instead funnels failures through our own exception types
	 * making them handleable in a standard way
	 * @param string $url
	 *
	 * @return array
	 * @throws Exception
	 */
	protected function safe_get_meta_tags( $url ) {

		$meta_tags = @get_meta_tags( $url );
		if ( ! $meta_tags ) {
			$this->throw_config_exception( $url );
		}

		return $meta_tags;
	}

}