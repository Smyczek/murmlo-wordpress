<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://murmlo.com
 * @since      1.0.0
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Handles frontend rendering of the Murmlo comments link/button,
 * shortcode support, and WordPress comments suppression.
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/public
 * @author     Murmlo <support@murmlo.com>
 */
class Murmlo_Global_Comments_Public {

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
	 * @param    string    $plugin_name    The name of the plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/murmlo-global-comments-public.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/murmlo-global-comments-public.js',
			array(), // No dependencies - vanilla JS
			$this->version,
			true // Load in footer for better performance
		);

		// Pass config to JS for API calls and deep linking
		wp_localize_script(
			$this->plugin_name,
			'murmloEmbedConfig',
			array(
				'webBase' => Murmlo_Global_Comments_Api::get_web_base_url(),
				'apiBase' => Murmlo_Global_Comments_Api::get_api_base_url(),
			)
		);
	}

	/**
	 * Get the canonical URL for a post.
	 *
	 * Priority:
	 * 1. Yoast SEO canonical (if set)
	 * 2. RankMath canonical (if set)
	 * 3. Default WordPress permalink
	 *
	 * @since    1.0.0
	 * @param    int|null    $post_id    Optional post ID. Defaults to current post.
	 * @return   string                  The canonical URL.
	 */
	private function get_canonical_url( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$url = null;

		// 1. Try Yoast SEO canonical
		$yoast_canonical = get_post_meta( $post_id, '_yoast_wpseo_canonical', true );
		if ( ! empty( $yoast_canonical ) ) {
			$url = $yoast_canonical;
		}

		// 2. Try RankMath canonical
		if ( ! $url ) {
			$rm_canonical = get_post_meta( $post_id, 'rank_math_canonical_url', true );
			if ( ! empty( $rm_canonical ) ) {
				$url = $rm_canonical;
			}
		}

		// 3. Fallback to permalink
		if ( ! $url ) {
			$url = get_permalink( $post_id );
		}

		// Normalize URL
		return $this->normalize_url( $url );
	}

	/**
	 * Normalize URL by removing fragments and tracking parameters.
	 *
	 * @since    1.0.0
	 * @param    string    $url    The URL to normalize.
	 * @return   string            Normalized URL.
	 */
	private function normalize_url( $url ) {
		if ( empty( $url ) ) {
			return '';
		}

		// Remove fragment
		$url = preg_replace( '/#.*$/', '', $url );

		// Parse URL
		$parsed = wp_parse_url( $url );
		if ( ! $parsed || ! isset( $parsed['host'] ) ) {
			return $url;
		}

		// If no query string, return as-is
		if ( ! isset( $parsed['query'] ) || empty( $parsed['query'] ) ) {
			return $url;
		}

		// Parse query string and remove tracking params
		parse_str( $parsed['query'], $query_params );

		$tracking_params = array(
			'utm_source',
			'utm_medium',
			'utm_campaign',
			'utm_term',
			'utm_content',
			'fbclid',
			'gclid',
			'msclkid',
		);

		foreach ( $tracking_params as $param ) {
			unset( $query_params[ $param ] );
		}

		// Rebuild URL
		$base = $parsed['scheme'] . '://' . $parsed['host'];
		if ( isset( $parsed['port'] ) ) {
			$base .= ':' . $parsed['port'];
		}
		if ( isset( $parsed['path'] ) ) {
			$base .= $parsed['path'];
		}
		if ( ! empty( $query_params ) ) {
			$base .= '?' . http_build_query( $query_params );
		}

		return $base;
	}

	/**
	 * Build the initial label for the comments element.
	 *
	 * Returns either a custom label or a placeholder that JS will replace
	 * with the actual count after fetching from the API client-side.
	 *
	 * @since    1.0.0
	 * @param    array     $options     Plugin options.
	 * @param    array     $overrides   Shortcode overrides.
	 * @return   string                 The label text.
	 */
	private function build_label( $options, $overrides = array() ) {
		// Check for custom label override (shortcode or settings)
		$custom_label = isset( $overrides['label'] ) && ! empty( $overrides['label'] )
			? $overrides['label']
			: ( isset( $options['label'] ) && ! empty( $options['label'] ) ? $options['label'] : '' );

		if ( ! empty( $custom_label ) ) {
			return esc_html( $custom_label );
		}

		// Placeholder label - JS will replace with actual count
		return esc_html__( 'Comments', 'murmlo-global-comments' );
	}

	/**
	 * Generate the HTML for the comments element.
	 *
	 * @since    1.0.0
	 * @param    string    $url         The canonical URL.
	 * @param    array     $options     Plugin options.
	 * @param    array     $overrides   Shortcode attribute overrides.
	 * @return   string                 HTML output.
	 */
	private function render_element( $url, $options, $overrides = array() ) {
		// Get Murmlo room URL
		$room_url = Murmlo_Global_Comments_Api::get_room_url( $url );

		// Build label (placeholder — JS fetches actual count client-side)
		$label = $this->build_label( $options, $overrides );

		// Determine variant
		$variant = isset( $overrides['variant'] ) && ! empty( $overrides['variant'] )
			? $overrides['variant']
			: ( isset( $options['variant'] ) ? $options['variant'] : 'button' );

		// Determine theme
		$theme = isset( $overrides['theme'] ) && ! empty( $overrides['theme'] )
			? $overrides['theme']
			: ( isset( $options['theme'] ) ? $options['theme'] : '' );

		// Build HTML
		$class = 'murmlo-comments-' . esc_attr( $variant );

		$html  = '<div class="murmlo-comments-wrapper">';
		$html .= '<a href="' . esc_url( $room_url ) . '" ';
		$html .= 'class="' . esc_attr( $class ) . '" ';
		if ( ! empty( $theme ) ) {
			$html .= 'data-murmlo-theme="' . esc_attr( $theme ) . '" ';
		}
		$html .= 'target="_blank" ';
		$html .= 'rel="nofollow noopener noreferrer">';
		$html .= $label;
		$html .= '</a>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Check if current post should display the comments element.
	 *
	 * @since    1.0.0
	 * @param    array    $options    Plugin options.
	 * @return   bool
	 */
	private function should_display( $options ) {
		// Skip non-content contexts
		if ( is_feed() || is_admin() || wp_doing_ajax() || is_preview() ) {
			return false;
		}

		// Check if enabled
		if ( empty( $options['enable'] ) ) {
			return false;
		}

		// Check post type
		$post_type     = get_post_type();
		$allowed_types = isset( $options['post_types'] ) ? (array) $options['post_types'] : array( 'post', 'page' );

		return in_array( $post_type, $allowed_types, true );
	}

	/**
	 * Filter the_content to inject comments element.
	 *
	 * @since    1.0.0
	 * @param    string    $content    Post content.
	 * @return   string                Modified content.
	 */
	public function inject_comments_element( $content ) {
		$options = get_option( MURMLO_OPTIONS_KEY, array() );

		if ( ! $this->should_display( $options ) ) {
			return $content;
		}

		// Skip if already injected in this content
		if ( false !== strpos( $content, 'murmlo-comments-wrapper' ) ) {
			return $content;
		}

		$url     = $this->get_canonical_url();
		$element = $this->render_element( $url, $options );

		$position = isset( $options['position'] ) ? $options['position'] : 'after';

		switch ( $position ) {
			case 'before':
				return $element . $content;
			case 'after':
				return $content . $element;
			case 'both':
				return $element . $content . $element;
			default:
				return $content . $element;
		}
	}

	/**
	 * Inject comments element into post-content block (block themes).
	 *
	 * Block themes render content via blocks, not the_content filter.
	 * This hooks into render_block to append the button after core/post-content.
	 *
	 * @since    1.0.0
	 * @param    string    $block_content    Rendered block HTML.
	 * @param    array     $block            Block data.
	 * @return   string                      Modified block HTML.
	 */
	public function inject_into_post_content_block( $block_content, $block ) {
		// Target post-content and post-excerpt blocks
		$target_blocks = array( 'core/post-content', 'core/post-excerpt' );
		if ( ! in_array( $block['blockName'], $target_blocks, true ) ) {
			return $block_content;
		}

		$options = get_option( MURMLO_OPTIONS_KEY, array() );

		if ( ! $this->should_display( $options ) ) {
			return $block_content;
		}

		// Skip if already injected
		if ( false !== strpos( $block_content, 'murmlo-comments-wrapper' ) ) {
			return $block_content;
		}

		$url     = $this->get_canonical_url();
		$element = $this->render_element( $url, $options );

		$position = isset( $options['position'] ) ? $options['position'] : 'after';

		switch ( $position ) {
			case 'before':
				return $element . $block_content;
			case 'after':
				return $block_content . $element;
			case 'both':
				return $element . $block_content . $element;
			default:
				return $block_content . $element;
		}
	}

	/**
	 * Fallback: inject via wp_footer if no other hook managed to.
	 *
	 * This handles edge cases where neither the_content nor render_block
	 * fires (e.g. block themes without a post-content block).
	 *
	 * @since    1.0.0
	 */
	/**
	 * Register shortcode.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcode() {
		add_shortcode( 'murmlo_comments', array( $this, 'shortcode_handler' ) );
	}

	/**
	 * Handle shortcode rendering.
	 *
	 * Shortcode works even when global enable=false (explicit placement override).
	 * It also ignores post_types restriction - renders wherever placed.
	 *
	 * @since    1.0.0
	 * @param    array    $atts    Shortcode attributes.
	 * @return   string            Shortcode output.
	 */
	public function shortcode_handler( $atts ) {
		$atts = shortcode_atts(
			array(
				'label'   => '',
				'variant' => '',
				'theme'   => '',
			),
			$atts,
			'murmlo_comments'
		);

		$options = get_option( MURMLO_OPTIONS_KEY, array() );

		// Build overrides array (only set if shortcode provided value)
		$overrides = array();

		if ( ! empty( $atts['label'] ) ) {
			$overrides['label'] = $atts['label'];
		}

		if ( '' !== $atts['variant'] && in_array( $atts['variant'], array( 'link', 'button' ), true ) ) {
			$overrides['variant'] = $atts['variant'];
		}

		$valid_themes = array( 'brand', 'light', 'dark', 'light-mono', 'dark-mono' );
		if ( '' !== $atts['theme'] && in_array( $atts['theme'], $valid_themes, true ) ) {
			$overrides['theme'] = $atts['theme'];
		}

		$url = $this->get_canonical_url();

		return $this->render_element( $url, $options, $overrides );
	}

	/**
	 * Disable WordPress comments on selected post types.
	 *
	 * @since    1.0.0
	 * @param    bool    $open       Whether comments are open.
	 * @param    int     $post_id    Post ID.
	 * @return   bool
	 */
	public function disable_comments( $open, $post_id ) {
		// Only disable on singular views
		if ( ! is_singular() ) {
			return $open;
		}

		$options       = get_option( MURMLO_OPTIONS_KEY, array() );
		$allowed_types = isset( $options['post_types'] ) ? (array) $options['post_types'] : array( 'post', 'page' );

		$post_type = get_post_type( $post_id );

		if ( in_array( $post_type, $allowed_types, true ) ) {
			return false;
		}

		return $open;
	}

	/**
	 * Disable pingbacks on selected post types.
	 *
	 * @since    1.0.0
	 * @param    bool    $open       Whether pings are open.
	 * @param    int     $post_id    Post ID.
	 * @return   bool
	 */
	public function disable_pings( $open, $post_id ) {
		return $this->disable_comments( $open, $post_id );
	}

}
