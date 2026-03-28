<?php

/**
 * Fired during plugin activation
 *
 * @link       https://murmlo.com
 * @since      1.0.0
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/includes
 * @author     Murmlo <support@murmlo.com>
 */
class Murmlo_Global_Comments_Activator {

	/**
	 * Set up default plugin options on activation.
	 *
	 * Only sets defaults if the option doesn't already exist,
	 * preserving any existing configuration.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Only set defaults if options don't exist yet
		if ( false === get_option( MURMLO_OPTIONS_KEY ) ) {
			$defaults = array(
				'enable'                      => false,
				'post_types'                  => array( 'post', 'page' ),
				'position'                    => 'after',
				'variant'                     => 'link',
				'label'                       => '',
				'disable_wp_comments'         => false,
			);
			add_option( MURMLO_OPTIONS_KEY, $defaults );
		}
	}
}
