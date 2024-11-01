<?php
/**
 * Lazy Cat Themes Social Media Plugin : Social Stat Wrapper
 *
 * @author  Lazy Cat Themes
 * @license GPL-2.0+
 */


/**
 * Wrapper for social stats and timestamp
 */
class LCT_Social_Stat_Holder {

	/**
	 * DateTime as unix Epoch seconds. This is used for questioning how long since these stats were last refreshed.
	 *
	 * @var string
	 */
	public $timestamp;

	/**
	 * Full social stats object
	 * @var
	 */
	public $social_stat_list;

	/**
	 * Create social stat holder wrapper to save to options tables
	 *
	 * @param $social_stat_list
	 */
	public function __construct( $social_stat_list ) {
		$this->timestamp        = ( new DateTime() )->format( 'U' );
		$this->social_stat_list = $social_stat_list;
	}
}