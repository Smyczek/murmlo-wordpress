<?php

/**
 * API client for communicating with Murmlo backend
 *
 * @link       https://murmlo.com
 * @since      1.0.0
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/includes
 */

/**
 * API client class.
 *
 * Provides URL helpers for the Murmlo API and web app.
 * Murmur counts are fetched client-side by the JS embed script.
 *
 * @since      1.0.0
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/includes
 * @author     Murmlo <support@murmlo.com>
 */
class Murmlo_Global_Comments_Api {

	/**
	 * Get the API base URL (filterable)
	 *
	 * @since    1.0.0
	 * @return   string    The API base URL
	 */
	public static function get_api_base_url() {
		return apply_filters( 'murmlo_api_base_url', MURMLO_API_BASE_URL );
	}

	/**
	 * Get the Web base URL for comments links (filterable)
	 *
	 * @since    1.0.0
	 * @return   string    The web base URL
	 */
	public static function get_web_base_url() {
		return apply_filters( 'murmlo_web_base_url', MURMLO_WEB_BASE_URL );
	}

	/**
	 * Get the Murmlo room URL for a given canonical URL
	 *
	 * @since    1.0.0
	 * @param    string    $url    The canonical URL
	 * @return   string            The Murmlo room URL
	 */
	public static function get_room_url( $url ) {
		$web_base = self::get_web_base_url();
		return $web_base . '/murmurs?url=' . rawurlencode( $url );
	}

}
