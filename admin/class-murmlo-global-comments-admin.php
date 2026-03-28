<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://murmlo.com
 * @since      1.0.0
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the admin area
 * including the settings page.
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/admin
 * @author     Murmlo <support@murmlo.com>
 */
class Murmlo_Global_Comments_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/murmlo-global-comments-admin.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/murmlo-global-comments-admin.js',
			array( 'jquery' ),
			$this->version,
			false
		);
	}

	/**
	 * Add settings page under Settings menu.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Murmlo Global Comments', 'murmlo-global-comments' ),
			__( 'Murmlo Comments', 'murmlo-global-comments' ),
			'manage_options',
			'murmlo-global-comments',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {
		register_setting(
			'murmlo_global_comments_settings_group',
			MURMLO_OPTIONS_KEY,
			array( $this, 'sanitize_options' )
		);
	}

	/**
	 * Sanitize options on save.
	 *
	 * @since    1.0.0
	 * @param    array    $input    The submitted options.
	 * @return   array              Sanitized options.
	 */
	public function sanitize_options( $input ) {
		$sanitized = array();

		// enable (bool)
		$sanitized['enable'] = ! empty( $input['enable'] );

		// post_types (array of strings) - whitelist against public post types
		$sanitized['post_types'] = array();
		if ( isset( $input['post_types'] ) && is_array( $input['post_types'] ) ) {
			$all_post_types = get_post_types( array( 'public' => true ), 'names' );
			foreach ( $input['post_types'] as $pt ) {
				$pt = sanitize_key( $pt );
				if ( isset( $all_post_types[ $pt ] ) ) {
					$sanitized['post_types'][] = $pt;
				}
			}
		}

		// position (before | after | both)
		$valid_positions       = array( 'before', 'after', 'both' );
		$sanitized['position'] = isset( $input['position'] ) && in_array( $input['position'], $valid_positions, true )
			? $input['position']
			: 'after';

		// variant (link | button)
		$valid_variants       = array( 'link', 'button' );
		$sanitized['variant'] = isset( $input['variant'] ) && in_array( $input['variant'], $valid_variants, true )
			? $input['variant']
			: 'link';

		// label (string, can be empty)
		$sanitized['label'] = isset( $input['label'] )
			? sanitize_text_field( $input['label'] )
			: '';

		// disable_wp_comments (bool)
		$sanitized['disable_wp_comments'] = ! empty( $input['disable_wp_comments'] );

		return $sanitized;
	}

	/**
	 * Render settings page.
	 *
	 * @since    1.0.0
	 */
	public function render_settings_page() {
		include plugin_dir_path( __FILE__ ) . 'partials/murmlo-global-comments-admin-display.php';
	}

}
