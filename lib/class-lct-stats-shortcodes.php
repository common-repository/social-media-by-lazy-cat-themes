<?php
/**
 * Lazy Cat Themes Social Media Plugin : Shortcodes
 *
 * @author   Lazy Cat Themes
 * @license  GPL-2.0+
 */

add_shortcode( 'lct-social-stats', array( 'LCT_Stats_Shortcodes', 'get_social_stats' ) );

/**
 * Handle all the shortcodes (currently only one)
 */
class LCT_Stats_Shortcodes
{

	/**
     *
     * Implements the 'lct-social-stats' shortcode using the API
     *
     * @param array $attributes
     */
    static public function get_social_stats( $attributes ) {

        $args = array();

        $size = 'small';
        $post = null;

        if ( ! empty( $attributes ) ) {

            if ( in_array( 'sharing', $attributes ) ) {
                //* Size defaults to large if sharing
                $size = 'large';
                $post = get_post();
            }

            if ( array_key_exists( 'alignment', $attributes ) ) {
                $args['alignment'] = $attributes['alignment'];
            }

            if ( array_key_exists( 'title', $attributes ) ) {
                $args['title'] = $attributes['title'];
            }

            if ( array_key_exists( 'size', $attributes ) ) {
                $size = $attributes['size'];
            }
            $args['size'] = $size;

            //* The default of show_count
            if ( in_array( 'show_count', $attributes ) ) {
                $args['show_count'] = true;
            }

        }

        return LCT_Social_Stats_Api::get_social_data_html(
            $post,
            $args );

    }


}