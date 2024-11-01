<?php
/**
 * Lazy Cat Themes Social Media Plugin : Email implementation
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

class LCT_Email_Social_Stat extends LCT_Social_Stat {

	/**
     * @param $key
     */
    public function __construct( $key ) {
        $this->assign_key_and_name( $key );
    }

	/**
     * Email has no counter, just populate the URL and the body
     *
     * @param array $options
	 * @param bool $is_share
     */
    public function populate_data( $options, $is_share = false ) {
        $this->body = $options['email_body_message'];
        $this->url = 'mailto:' . esc_attr( $options['email_mailto_address'] );
    }

	/**
     * @param string $post_title
     * @param string $post_url
     * @param string $title
     *
     * @return string
     */
    public function get_share_link( $post_title, $post_url, $title ) {

        return sprintf(
            '<a title="%s" href="javascript: void(0);" onclick="window.location.href=\'mailto:?Subject=%s&Body=%s\';">%s</a>',
            sprintf( __( 'Share on %s', 'lct-social-media' ),$title ),
            esc_attr( $post_title ),
            esc_attr( sprintf( $this->body, $post_url ) ),
	        LCT_SOCIAL_MEDIA_PLACEHOLDER );
    }

	/**
     * @return string
     */
    public function get_font_awesome_name()
    {
        return "envelope";
    }

	/**
     * @return bool
     */
    public function is_shareable()
    {
        return true;
    }

}