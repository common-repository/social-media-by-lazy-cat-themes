<?php
/**
 * Lazy Cat Themes Social Media Plugin : API methods
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

const LCT_SOCIAL_MEDIA_PLACEHOLDER = '[SocialMediaPlaceholder]';
require_once( __DIR__ . '/socialstats/class-lct-social-stat-holder.php' );

/**
 * Class that holds all the API methods
 */
class LCT_Social_Stats_Api {

	/**
	 * 	 Gets all the share enabled social networks. Returns a key and a network name
	 *
	 * @param $social_stats_options_data
	 *
	 * @return array
	 */
	private static function get_all_share_enabled_social_networks( $social_stats_options_data ) {

		$share_enabled_keys           = array();
		$supported_network_keys       = LCT_Social_Stats_Factory::get_all_supported_social_networks();

		//Go through and filter down to all enabled keys
		foreach ( $supported_network_keys as $key ) {
			$is_share_enabled_key = 'is_' . $key . '_share_enabled';
			if ( array_key_exists( $is_share_enabled_key, $social_stats_options_data ) ) {
				if ( $social_stats_options_data[ $is_share_enabled_key ] ) {
					$share_enabled_keys[] = array(
						'key' => $key,
						'network_name' =>$social_stats_options_data[$key . '_tooltip'] );
				}
			}
		}

		return $share_enabled_keys;
	}


	/**
	 *
	 * Returns the number of enabled networks
	 *
	 * @return int Number of active networks
	 */
	public static function get_active_network_count() {

		$data = get_option( LCT_SOCIAL_MEDIA_CACHED_STATS_OPTIONS_NAME );

		if ( ( ! $data ) || ( is_null( $data->social_stat_list ) ) ) {
			return 0;
		}

		return count( $data->social_stat_list );
	}

	/**
	 *
	 * Outputs get_social_data_html to the output buffer
	 *
	 * @param null|WP_Post $post
	 * @param null|array $args
	 */
	public static function show_social_data_html( $post, $args ) {
		echo self::get_social_data_html( $post, $args );
	}

	/**
	 *
	 * Return the social media stats or share links as HTML
	 *
	 * @param null|WP_Post $post Included if wanting share links
	 * @param null|array $args
	 *
	 * @return string
	 */
	public static function get_social_data_html( $post, $args ) {

		//* Provide defaults for args
		$defaults = array(
			'alignment'  => 'left',
			'size'       => 'small',
			'show_count' => false,
			'hide_bar'   => false,
			'title'      => ''
		);
		$final_args = wp_parse_args( $args, $defaults );

		//* Obtain parameters
		$alignment  = $final_args['alignment'];
		$size       = $final_args['size'];
		$show_count = $final_args['show_count'];
		$hide_bar   = $final_args['hide_bar'];
		$title      = $final_args['title'];

		if ( 'small' === $size ) {
			//We just don't have room to display the numbers with the small icon version
			$show_count = false;
		}

		$html = '';

		if ( $post ) {
			//Get the data for sharing
			$social_data = self::get_social_sharing_data( $post );
		} else {
			//get the data for non-sharing of social networks
			$social_data = self::get_social_data( $post );
		}

		if ( empty( $social_data )) {
			return $html;
		}

		//* Put a social header bar class around to provide the top and bottom delimiting lines
		if ( ! $hide_bar ) {
			$html .= sprintf( '<div class="social-header-bar social-header-bar-%s">', esc_attr( $alignment ) );
		}
		$html .= sprintf( '<div class="%s-social-media-banner">', esc_attr( $size ) );

		//* Add in a the title if configured
		if ( $title ) {
			$html .= '<div class="social-title">' . esc_html( $title ) .'</div>';
		}

		$html .= '<ul>';

		if ( ! $post ) {
			//* If there is at least one follower count, then we can show the followers div
			$networks_with_followers_count =
				count(
					array_filter(
						$social_data,
						function ( $var ) {
							return ! is_null( $var['follower_count'] );
						}
					)
				);
		}

		//* Loop through each enabled social network
		foreach ( $social_data as $single_social_data ) {
			if ( is_null( $post ) ) {
				//* There is no post, this gets the overall icon which has links to the different social networks
				$html .= self::get_social_list_item(
					$single_social_data['network_name'],
					$single_social_data['url'],
					$single_social_data['network_key'],
					$single_social_data['follower_count'],
					( $show_count && $networks_with_followers_count ) );
			} else {
				//* There is a post, these are the sharing links for that post
				$html .= '<li>' . str_replace(
						LCT_SOCIAL_MEDIA_PLACEHOLDER,
						sprintf( '<span class="fa fa-%s"></span>', $single_social_data['font_awesome_name'] ),
						$single_social_data['share_link'] ) . '</li>';
			}
		}

		return $html . '</ul>' . self::get_close_social_bar( $hide_bar );
	}

	/**
	 * Get the sharing data (either post level or modal for overall)
	 *
	 * @param null $post
	 *
	 * @return array
	 */
	public static function get_social_sharing_data( $post = null ) {
		//Get all option settings
		$social_options_data = get_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME );
		$social_data = array();

		//Loop through all share-enabled social networks
		foreach ( self::get_all_share_enabled_social_networks( $social_options_data ) as $social_network ) {
			$social_stat = LCT_Social_Stats_Factory::create( $social_network['key'], true );
			$social_stat->populate_all_data( $social_options_data, true );

			if ( $post != null ) {
				$share_link = $social_stat->get_share_link(
					$post->post_title,
					urlencode( get_permalink( $post->ID ) ),
					$social_network['network_name'] );
			} else {
				$share_link = $social_stat->get_share_link(
					'{0}',
					'{1}',
					$social_network['network_name'] );
			}

			$social_data[] = array(
				'share_link'        => $share_link,
				'font_awesome_name' => $social_stat->get_font_awesome_name(),
				'network_name'      => $social_network['network_name'],
				'network_key'       => $social_network['key']
			);
		}
		return $social_data;
	}

	/**
	 *
	 * Gets the raw social data
	 *
	 * @param WP_Post|null $post The post to create a sahre link for
	 *
	 * @return array|null One item per social network
	 */
	public static function get_social_data( $post = null, $use_placeholders = false ) {

		if ( $use_placeholders ) {
			// This is for backwards compatibility with existing Kaleidoscope implementations
			return self::get_social_sharing_data( $post );
		}

		$data = get_option( LCT_SOCIAL_MEDIA_CACHED_STATS_OPTIONS_NAME );

		if ( empty( $data ) ) {
			//* If there is nothing saved, try and get stats
			do_action( 'calc_followers' );
			$data = get_option( LCT_SOCIAL_MEDIA_CACHED_STATS_OPTIONS_NAME );

			//* Okay, there is nothing to show, then exit with null
			if ( empty( $data ) ) {
				return null;
			}
		}

		//* Check if the stats have expired (they expire after one hour
		$seconds_difference = ( new DateTime )->format( 'U' ) - $data->timestamp;
		if ( $seconds_difference > HOUR_IN_SECONDS ) {
			//* Stats have expired, run the refreshing script in the background
			wp_schedule_single_event( time(), 'calc_followers' );
		}

		//* Get the social stat list
		$social_stat_list = self::get_sorted_social_stat_list( $data->social_stat_list );

		//* Get the social data as internal structure
		return self::get_social_data_structures( $social_stat_list, $post );

	}

	/**
	 *
	 * Gets the social data as an array
	 *
	 * @param array $social_stat_list The raw config imformation from the options table
	 * @param $post
	 *
	 * @return array
	 */
	private static function get_social_data_structures( $social_stat_list, $post ) {

		$overall_social_network_item = array();

		//* Loop through social data
		foreach ( $social_stat_list as $key => $value ) {
			$social_stats = LCT_Social_Stats_Factory::create( $key );

			//* Occasionally this is in a state where it can be null
			if ( ! is_null( $social_stats ) ) {

				//* Create item that can be used for the overall HTML (not a share link)
				$social_item_info = array(
					'network_key'       => $key,
					'font_awesome_name' => $social_stats->get_font_awesome_name(),
					'url'               => $social_stat_list[ $key ]['url'],
					'follower_count'    => $social_stat_list[ $key ]['followers'],
					'network_name'      => $social_stat_list[ $key ]['tooltip'],
				);

				if ( ! is_null( $post ) ) {
					//* Create share link to facilitate post sharing for this social network
					$social_item_info['share_link'] = self::get_share_url(
						$social_stats,
						$post->post_title,
						urlencode( get_permalink( $post->ID ) ),
						$social_stat_list[ $key ]['width'],
						$social_stat_list[ $key ]['height'],
						$social_stat_list[ $key ]['body'],
						$social_stat_list[ $key ]['tooltip'],
						$social_stat_list[ $key ]['url'] );
				}
				$overall_social_network_item[] = $social_item_info;

			}

		}

		return $overall_social_network_item;
	}

	/**
	 *
	 * Get the share URL based on the input data
	 *
	 * @param LCT_Social_Stat $social_stats
	 * @param string $post_title
	 * @param string $post_url
	 * @param $width
	 * @param $height
	 * @param $body
	 * @param $network_name
	 * @param $url
	 *
	 * @return string The sharing <a> tag
	 */
	private static function get_share_url(
		$social_stats,
		$post_title,
		$post_url,
		$width,
		$height,
		$body,
		$network_name,
		$url
	) {

		$social_stats->width  = $width;
		$social_stats->height = $height;
		$social_stats->body   = $body;
		$social_stats->url    = $url;
		$share_link           = $social_stats->get_share_link(
			$post_title,
			$post_url,
			$network_name );

		return $share_link;
	}

	/**
	 * @param $hide_bar
	 *
	 * @return string
	 */
	private static function get_close_social_bar( $hide_bar ) {
		if ( ! $hide_bar ) {
			return '</div></div>';
		}

		return '</div>';
	}

	/**
	 *
	 * Gets the overall social network list item. This is not the one they use to share, this is the one that takes
	 * the user to the social network website (except email which just sends an email)
	 *
	 * @param $title
	 * @param $url
	 * @param $key
	 * @param $follower_count
	 * @param $show_follower_count
	 *
	 * @return string The list item html tag
	 */
	private static function get_social_list_item( $title, $url, $key, $follower_count, $show_follower_count ) {

		$social_stats = LCT_Social_Stats_Factory::create( $key );

		if ( ( ! is_null( $social_stats ) ) && ( ! empty( $title ) ) ) {

			$html = "<li>";
			$html .= sprintf(
				'<a %s title="%s" href="%s" class="%s fa fa-%s"></a>',
				( 'email' === $key ) ? '' : "target='_blank'",
				esc_attr( $title ),
				esc_attr( $url ),
				esc_attr( $key ),
				esc_attr( $social_stats->get_font_awesome_name() ) );

			//* Add in follower count if configured to show
			if ( $show_follower_count ) {
				if ( ! is_null( $follower_count ) ) {
					$html .= '<div class="follower-count">' . esc_html( self::format_follower_count_number( $follower_count ) ) . '</div>';
				}
			}
			$html .= '</li>';

			return $html;
		}

		return '';
	}

	/**
	 *
	 * Gets the social stat list in the order it is configured to be in
	 *
	 * @param $social_stat_list
	 *
	 * @return array
	 */
	private static function get_sorted_social_stat_list( $social_stat_list ) {

		$sorted_social_stat_list = array();

		$options_data       = get_option( LCT_SOCIAL_MEDIA_OPTIONS_NAME );
		$display_order_list = $options_data['social_network_order']['display order'];

		//* Use the display order list as the drive (as it is in the right order, then build the return list
		foreach ( $display_order_list as $key => $value ) {
			if ( array_key_exists( $key, $social_stat_list ) ) {
				$sorted_social_stat_list[ $key ] = $social_stat_list[ $key ];
			}
		}

		return $sorted_social_stat_list;
	}


	/**
	 *
	 * Formats number so that it has maximum of 3 digits and one decimal place
	 *  4,123,312   = 4.1M
	 *  3,138       = 3.1K
	 *  323         = 323
	 *
	 * @param int $fan_count raw fan count
	 *
	 * @return string Formatted number
*/
	private static function format_follower_count_number( $fan_count ) {

		if ( $fan_count >= 1000000 ) {
			$fan_count_thousands = round( ( $fan_count / 1000000 ), 1 );

			return self::format_thousand_or_million( $fan_count_thousands, "M" );
		}

		if ( $fan_count >= 1000 ) {
			$fan_count_thousands = round( ( $fan_count / 1000 ), 1 );

			return self::format_thousand_or_million( $fan_count_thousands, "K" );
		}

		return $fan_count;
	}

	/**
	 *
	 * For thousands or millions, only show decimal place if it isn't zero. Zero decimal place just shows the number
	 * number on its own
	 *
	 * @param $fan_count
	 * @param $suffix
	 *
	 * @return string
	 */
	private static function format_thousand_or_million( $fan_count, $suffix ) {

		if ( is_int( $fan_count ) ) {
			return number_format( $fan_count, 0 ) . $suffix;
		}

		return $fan_count . $suffix;
	}


}




