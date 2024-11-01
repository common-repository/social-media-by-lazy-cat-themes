<?php
/**
 * Lazy Cat Themes Social Media Plugin : Widget Loading
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

require_once( LCT_SOCIAL_MEDIA_WIDGETS_DIR . 'class-lct-social-media-widget.php' );

add_action( 'widgets_init', array( 'LCT_Widget_Loader', 'register_widgets' ) );

class LCT_Widget_Loader {

	static function register_widgets() {
		register_widget( 'LCT_Social_Media_Widget' );
	}

}



