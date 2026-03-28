<?php

/**
 * Fired when the plugin is uninstalled (deleted, not just deactivated).
 *
 * @link       https://murmlo.com
 * @since      1.0.0
 *
 * @package    Murmlo_Global_Comments
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Clean up plugin data.
 *
 * Only delete the main options. Transients will expire naturally
 * (attempting mass transient deletion is unreliable and can cause
 * performance issues on large sites).
 */
delete_option( 'murmlo_global_comments_options' );

// For multisite installations, clean up each site's options.
if ( is_multisite() ) {
	$sites = get_sites( array( 'fields' => 'ids' ) );
	foreach ( $sites as $site_id ) {
		switch_to_blog( $site_id );
		delete_option( 'murmlo_global_comments_options' );
		restore_current_blog();
	}
}
