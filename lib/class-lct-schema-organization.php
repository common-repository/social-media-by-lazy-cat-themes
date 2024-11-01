<?php
/**
 * Lazy Cat Themes Social Media Plugin : Create Schema.Org Organisation JSON-LD
 * For details check out: https://schema.org/Organization
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */

add_action( 'wp_head', array( 'LCT_Schema_Organization', 'display_schema_organization' ) );

class LCT_Schema_Organization {

	/**
	 * Display the Schema.org data for organistaion with a hook out to the theme in case the theme would like to
	 * provide a logo URL ( supported in all Lazy Cat Themes - http://lazycatthemes.com )
	 */
	public static function display_schema_organization() {

		//* This is only shown on the homepage
		if ( ! is_home() ) {
			return;
		}

		$json_payload['@context'] = 'http://schema.org';
		$json_payload['@type']    = 'Organization';
		$json_payload['name']     = get_bloginfo();
		$json_payload['url']      = get_home_url();

		//* Can be overriden to provide a logo URL
		$logo = apply_filters( 'lct_get_organization_logo_url', false );
		if ( $logo ) {
			$json_payload['logo'] = $logo;
		}
		$same_as_details = self::get_same_as_details();
		if ( $same_as_details ) {
			$json_payload['sameAs'] = $same_as_details;
		}

		echo '<script type="application/ld+json">' . json_encode( $json_payload ) . '</script>';

	}

	/**
	 * Get the sameAS details for all the configured social networks. Except for the email, which is not included
	 * as it is a mailto tag and not appropriate
	 *
	 * @return array|null
	 */
	private static function get_same_as_details() {
		$sameAsArray = array();
		$social_data = LCT_Social_Stats_Api::get_social_data();

		if ( $social_data ) {
			foreach ( $social_data as $social_item ) {
				if ( false === strpos( $social_item['url'], 'mailto:' ) ) {
					$sameAsArray[] = $social_item['url'];
				}
			}

			return $sameAsArray;
		}

		return null;
	}

}