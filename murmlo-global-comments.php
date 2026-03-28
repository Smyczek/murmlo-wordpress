<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://murmlo.com
 * @since             1.0.0
 * @package           Murmlo_Global_Comments
 *
 * @wordpress-plugin
 * Plugin Name:       Murmlo – Global Comments
 * Plugin URI:        https://murmlo.com/wordpress
 * Description:       Replace WordPress comments with an external, global comment layer powered by Murmlo.
 * Version:           1.0.0
 * Author:            Murmlo
 * Author URI:        https://murmlo.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       murmlo-global-comments
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'MURMLO_GLOBAL_COMMENTS_VERSION', '1.0.0' );

/**
 * Base URLs - override via filters or wp-config.php constants
 * Filters: murmlo_web_base_url, murmlo_api_base_url
 */
if ( ! defined( 'MURMLO_WEB_BASE_URL' ) ) {
	define( 'MURMLO_WEB_BASE_URL', 'https://murmlo.com' );
}
if ( ! defined( 'MURMLO_API_BASE_URL' ) ) {
	define( 'MURMLO_API_BASE_URL', 'https://api.murmlo.com' );
}

/**
 * Single option key for all plugin settings
 */
define( 'MURMLO_OPTIONS_KEY', 'murmlo_global_comments_options' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-murmlo-global-comments-activator.php
 */
function murmlo_gc_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-murmlo-global-comments-activator.php';
	Murmlo_Global_Comments_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-murmlo-global-comments-deactivator.php
 */
function murmlo_gc_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-murmlo-global-comments-deactivator.php';
	Murmlo_Global_Comments_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'murmlo_gc_activate' );
register_deactivation_hook( __FILE__, 'murmlo_gc_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-murmlo-global-comments.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function murmlo_gc_run() {
	$plugin = new Murmlo_Global_Comments();
	$plugin->run();
}
murmlo_gc_run();
