<?php
/**
 * Lazy Cat Themes Social Media Plugin : Queuing of styles
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */


add_action( 'wp_enqueue_scripts', array( 'LCT_Social_Media_Plugin_Styles', 'enqueue_stylesheets' ) );

class LCT_Social_Media_Plugin_Styles {

	/**
	 * Queue font awesome and custom styles
	 */
	public static function enqueue_stylesheets() {

		$plugin_styles_url = plugin_dir_url( __FILE__ ) . 'style.css';
		$font_awesome_url  = plugin_dir_url( dirname( __FILE__ ) ) . 'vendor';

		//* Standard stylesheet
		wp_enqueue_style(
			'lct-social-media-styles-css',
			$plugin_styles_url,
			array(),
			'1.0.0' );

		//* Font awesome for social icons
		wp_enqueue_style(
			'font-awesome-css',
			$font_awesome_url . '/font-awesome/css/font-awesome.min.css',
			array(),
			'4.5.0' );
	}

}