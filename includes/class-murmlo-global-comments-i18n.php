<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the internationalization functionality.
 *
 * Since WordPress 4.6, translations are loaded automatically from
 * translate.wordpress.org when the plugin is hosted on WordPress.org.
 *
 * @since      1.0.0
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/includes
 * @author     Murmlo <support@murmlo.com>
 */
class Murmlo_Global_Comments_i18n {

	/**
	 * No-op. WordPress automatically loads translations since 4.6.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		// Intentionally empty — WordPress handles this automatically.
	}
}
