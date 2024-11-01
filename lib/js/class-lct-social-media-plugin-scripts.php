<?php
/**
 * Lazy Cat Themes Social Media Plugin : Queuing of scripts
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */


add_action( 'wp_enqueue_scripts', array( 'LCT_Social_Media_Plugin_Scripts', 'enqueue_scripts' ) );

class LCT_Social_Media_Plugin_Scripts {

	/**
	 * Queue centering popup javascript
	 */
	public static function enqueue_scripts() {

		//* Script for centering popups
		wp_enqueue_script(
			'lct-popup-center',
			plugin_dir_url( __FILE__ ) . 'popup_center.js',
			array(),
			'1.0.0' );

	}

}