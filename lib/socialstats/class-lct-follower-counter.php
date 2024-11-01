<?php
/**
 * Lazy Cat Themes Social Media Plugin : Follower Counter
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

//* Action for counting and saving followers, this action means it can be scheduled and run in the background
add_action( 'calc_followers', array( 'LCT_Follower_Counter', 'get_follower_count_and_save' ) );

/**
 * Main entry point got managing the calculations (which is just counting of followers)
 */
class LCT_Follower_Counter {

	/**
	 *
	 *  Get follower count (using a different mechanism) for every social network and save in config
	 *  so we only perform this calc whenever we refresh the cache (typically every hour)
	 *
	 */
	public static function get_follower_count_and_save() {

		$options = get_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME );

		//* Keep track of whether a social network has failed
		$social_network_failed = false;
		$social_stats_list = null;

		foreach ( self::get_all_enabled_social_networks() as $key ) {
			//* Use a class factory to populate the data for each social network
			$social_stats = LCT_Social_Stats_Factory::create( $key );
			if ( $social_stats != null ) {
				try {
					//* Populate the followers and other info (if applicable)
					$social_stats->populate_all_data( $options );
					$social_stats_list[ $social_stats->key ] = array(
						'url'       => $social_stats->url,
						'followers' => $social_stats->followers,
						'tooltip'   => $social_stats->tooltip,
						'width'     => $social_stats->width,
						'height'    => $social_stats->height,
						'body'      => $social_stats->body
					);
				} catch ( Exception $e ) {

					//* Show message to admin user if there was a problem connecting to the network
					printf(
						'<div class="admin-notice notice-red" ><strong>%s%s</strong></div>',
						$e->getMessage(),
						__( '. Follower counts have not been updated.', 'lct-social-media' )
					);
					$social_network_failed = true;
				}
			}
		}

		if ( ( ! is_null( $social_stats_list ) ) && ( ! $social_network_failed ) ) {
			//* If anything fails, then don't save. This stops an error in, say, the Twitter API, wiping the Twitter icon
			//* off the social console until the error is resolved.
			self::save_social_stats( $social_stats_list );
		}

	}

	/**
	 *
	 * Get a list of all the enabled social networks
	 *
	 * @return array
	 */
	private static function get_all_enabled_social_networks() {

		$enabled_keys           = array();
		$supported_network_keys = LCT_Social_Stats_Factory::get_all_supported_social_networks();
		$social_stats_options   = get_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME );


		if ( ! $social_stats_options ) {
			//No options configured, default to all enabled
			return $supported_network_keys;
		}

		//Go through and filter down to all enabled keys
		foreach ( $supported_network_keys as $key ) {
			$is_enabled_key = 'is_' . $key . '_enabled';
			if ( array_key_exists( $is_enabled_key, $social_stats_options ) ) {
				if ( $social_stats_options[ $is_enabled_key ] ) {
					$enabled_keys[] = $key;
				}
			}
		}

		return $enabled_keys;
	}

	/**
	 *
	 * Save to the options table
	 *
	 * @param $social_stats_list
	 */
	private static function save_social_stats( $social_stats_list ) {
		update_option( LCT_SOCIAL_MEDIA_CACHED_STATS_OPTIONS_NAME, new LCT_Social_Stat_Holder( $social_stats_list ) );
	}

}

