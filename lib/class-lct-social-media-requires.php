<?php
/**
 * Lazy Cat Themes Social Media Plugin : Required Plugins Configuration
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

add_action( 'tgmpa_register', array( 'LCT_Social_Media_Requires', 'required_plugins' ) );

class LCT_Social_Media_Requires {

	/**
	 * Make it clear that without the social media plugin there is no social media!
	 */
	public static function required_plugins() {

		$plugins = array(
			array(
				'name'     => 'Redux Options Framework',
				'slug'     => 'redux-framework',
				'required' => true,
			)
		);

		$config = array(
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'id'           => 'lazy-cat-themes-social-media',
			// Default absolute path to bundled plugins.
			'default_path' => '',
			// Menu slug.
			'menu'         => 'tgmpa-install-plugins',
			// Parent menu slug.
			'parent_slug'  => 'themes.php',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'capability'   => 'edit_theme_options',
			// Show admin notices or not.
			'has_notices'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismissable'  => false,
			// If 'dismissable' is false, this message will be output at top of nag.
			'dismiss_msg'  => '',
			// Automatically activate plugins after installation or not.
			'is_automatic' => true,
			// Message to output right before the plugins table.
			'message'      => '',
			'strings'      => array(
				'notice_can_install_required'     => _n_noop(
					'This Social Media Plugin requires the following plugin: %1$s.',
					'This Social Media Plugin requires the following plugins: %1$s.',
					'tgmpa'	),
				'notice_can_install_recommended'  => _n_noop(
					'This Social Media Plugin recommends the following plugin: %1$s.',
					'This Social Media Plugin recommends the following plugins: %1$s.',
					'tgmpa'	),
				'notice_ask_to_update'            => _n_noop(
					'The following plugin needs to be updated to its latest version to ensure maximum compatibility with the Social Media Plugin: %1$s.',
					'The following plugins need to be updated to their latest version to ensure maximum compatibility with this Social Media Plugin: %1$s.',
					'tgmpa'	) ),
		);

		tgmpa( $plugins, $config );

	}

}