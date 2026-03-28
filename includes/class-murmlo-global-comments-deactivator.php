<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://murmlo.com
 * @since      1.0.0
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/includes
 * @author     Murmlo <support@murmlo.com>
 */
class Murmlo_Global_Comments_Deactivator {

	/**
	 * Plugin deactivation handler.
	 *
	 * Intentionally does nothing - we preserve settings on deactivation.
	 * Options are only deleted on uninstall (deletion) of the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Do nothing - preserve settings for potential reactivation
	}
}
