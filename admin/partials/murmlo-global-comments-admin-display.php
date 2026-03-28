<?php

/**
 * Provide admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://murmlo.com
 * @since      1.0.0
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/admin/partials
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options        = get_option( MURMLO_OPTIONS_KEY, array() );
$post_types     = get_post_types( array( 'public' => true ), 'objects' );
$selected_types = isset( $options['post_types'] ) ? (array) $options['post_types'] : array( 'post', 'page' );
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php settings_fields( 'murmlo_global_comments_settings_group' ); ?>

		<table class="form-table" role="presentation">
			<!-- Enable Plugin -->
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable', 'murmlo-global-comments' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
							   name="<?php echo esc_attr( MURMLO_OPTIONS_KEY ); ?>[enable]"
							   value="1"
							   <?php checked( ! empty( $options['enable'] ) ); ?>>
						<?php esc_html_e( 'Enable Murmlo comments link on posts', 'murmlo-global-comments' ); ?>
					</label>
				</td>
			</tr>

			<!-- Post Types -->
			<tr>
				<th scope="row"><?php esc_html_e( 'Post Types', 'murmlo-global-comments' ); ?></th>
				<td>
					<fieldset>
						<?php foreach ( $post_types as $pt_slug => $pt_obj ) : ?>
							<label style="display: block; margin-bottom: 5px;">
								<input type="checkbox"
									   name="<?php echo esc_attr( MURMLO_OPTIONS_KEY ); ?>[post_types][]"
									   value="<?php echo esc_attr( $pt_slug ); ?>"
									   <?php checked( in_array( $pt_slug, $selected_types, true ) ); ?>>
								<?php echo esc_html( $pt_obj->labels->name ); ?>
								<code><?php echo esc_html( $pt_slug ); ?></code>
							</label>
						<?php endforeach; ?>
					</fieldset>
					<p class="description">
						<?php esc_html_e( 'Select which post types should display the Murmlo comments link.', 'murmlo-global-comments' ); ?>
					</p>
				</td>
			</tr>

			<!-- Position -->
			<tr>
				<th scope="row"><?php esc_html_e( 'Position', 'murmlo-global-comments' ); ?></th>
				<td>
					<?php $position = isset( $options['position'] ) ? $options['position'] : 'after'; ?>
					<select name="<?php echo esc_attr( MURMLO_OPTIONS_KEY ); ?>[position]">
						<option value="before" <?php selected( $position, 'before' ); ?>>
							<?php esc_html_e( 'Before content', 'murmlo-global-comments' ); ?>
						</option>
						<option value="after" <?php selected( $position, 'after' ); ?>>
							<?php esc_html_e( 'After content', 'murmlo-global-comments' ); ?>
						</option>
						<option value="both" <?php selected( $position, 'both' ); ?>>
							<?php esc_html_e( 'Before and after content', 'murmlo-global-comments' ); ?>
						</option>
					</select>
				</td>
			</tr>

			<!-- Variant -->
			<tr>
				<th scope="row"><?php esc_html_e( 'Display Style', 'murmlo-global-comments' ); ?></th>
				<td>
					<?php $variant = isset( $options['variant'] ) ? $options['variant'] : 'link'; ?>
					<select name="<?php echo esc_attr( MURMLO_OPTIONS_KEY ); ?>[variant]">
						<option value="link" <?php selected( $variant, 'link' ); ?>>
							<?php esc_html_e( 'Link', 'murmlo-global-comments' ); ?>
						</option>
						<option value="button" <?php selected( $variant, 'button' ); ?>>
							<?php esc_html_e( 'Button', 'murmlo-global-comments' ); ?>
						</option>
					</select>
				</td>
			</tr>

			<!-- Theme -->
			<tr>
				<th scope="row"><?php esc_html_e( 'Color Theme', 'murmlo-global-comments' ); ?></th>
				<td>
					<?php $theme = isset( $options['theme'] ) ? $options['theme'] : ''; ?>
					<select name="<?php echo esc_attr( MURMLO_OPTIONS_KEY ); ?>[theme]">
						<option value="" <?php selected( $theme, '' ); ?>>
							<?php esc_html_e( 'Default (inherits from your theme)', 'murmlo-global-comments' ); ?>
						</option>
						<option value="brand" <?php selected( $theme, 'brand' ); ?>>
							<?php esc_html_e( 'Brand (green)', 'murmlo-global-comments' ); ?>
						</option>
						<option value="light" <?php selected( $theme, 'light' ); ?>>
							<?php esc_html_e( 'Light', 'murmlo-global-comments' ); ?>
						</option>
						<option value="dark" <?php selected( $theme, 'dark' ); ?>>
							<?php esc_html_e( 'Dark', 'murmlo-global-comments' ); ?>
						</option>
						<option value="light-mono" <?php selected( $theme, 'light-mono' ); ?>>
							<?php esc_html_e( 'Light Mono (no green)', 'murmlo-global-comments' ); ?>
						</option>
						<option value="dark-mono" <?php selected( $theme, 'dark-mono' ); ?>>
							<?php esc_html_e( 'Dark Mono (no green)', 'murmlo-global-comments' ); ?>
						</option>
					</select>
				</td>
			</tr>

			<!-- Custom Label -->
			<tr>
				<th scope="row"><?php esc_html_e( 'Custom Label', 'murmlo-global-comments' ); ?></th>
				<td>
					<input type="text"
						   name="<?php echo esc_attr( MURMLO_OPTIONS_KEY ); ?>[label]"
						   value="<?php echo esc_attr( isset( $options['label'] ) ? $options['label'] : '' ); ?>"
						   class="regular-text"
						   placeholder="<?php esc_attr_e( 'Leave empty for dynamic labels', 'murmlo-global-comments' ); ?>">
					<p class="description">
						<?php esc_html_e( 'Default: "Comments". The count badge appears automatically when there are murmurs.', 'murmlo-global-comments' ); ?>
					</p>
				</td>
			</tr>

			<!-- Disable WP Comments -->
			<tr>
				<th scope="row"><?php esc_html_e( 'Disable WordPress Comments', 'murmlo-global-comments' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
							   name="<?php echo esc_attr( MURMLO_OPTIONS_KEY ); ?>[disable_wp_comments]"
							   value="1"
							   <?php checked( ! empty( $options['disable_wp_comments'] ) ); ?>>
						<?php esc_html_e( 'Disable native WordPress comments on selected post types', 'murmlo-global-comments' ); ?>
					</label>
					<p class="description">
						<?php esc_html_e( 'This will close comments and pingbacks. Existing comments will not be deleted.', 'murmlo-global-comments' ); ?>
					</p>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>

	<hr>

	<h2><?php esc_html_e( 'Shortcode', 'murmlo-global-comments' ); ?></h2>
	<p><?php esc_html_e( 'Use the shortcode to manually place the Murmlo link anywhere in your content or page builders.', 'murmlo-global-comments' ); ?></p>

	<h3><?php esc_html_e( 'Basic Usage', 'murmlo-global-comments' ); ?></h3>
	<p><code>[murmlo_comments]</code></p>

	<h3><?php esc_html_e( 'Available Attributes', 'murmlo-global-comments' ); ?></h3>
	<table class="widefat striped" style="max-width: 600px;">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Attribute', 'murmlo-global-comments' ); ?></th>
				<th><?php esc_html_e( 'Values', 'murmlo-global-comments' ); ?></th>
				<th><?php esc_html_e( 'Description', 'murmlo-global-comments' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><code>label</code></td>
				<td><?php esc_html_e( 'Any text', 'murmlo-global-comments' ); ?></td>
				<td><?php esc_html_e( 'Custom label text (overrides dynamic labels)', 'murmlo-global-comments' ); ?></td>
			</tr>
			<tr>
				<td><code>variant</code></td>
				<td><code>link</code> | <code>button</code></td>
				<td><?php esc_html_e( 'Display style', 'murmlo-global-comments' ); ?></td>
			</tr>
			<tr>
				<td><code>theme</code></td>
				<td><code>brand</code> | <code>light</code> | <code>dark</code> | <code>light-mono</code> | <code>dark-mono</code></td>
				<td><?php esc_html_e( 'Color theme (overrides global setting)', 'murmlo-global-comments' ); ?></td>
			</tr>
			</tbody>
	</table>

	<h3><?php esc_html_e( 'Examples', 'murmlo-global-comments' ); ?></h3>
	<ul>
		<li><code>[murmlo_comments variant="button"]</code> — <?php esc_html_e( 'Display as button', 'murmlo-global-comments' ); ?></li>
		<li><code>[murmlo_comments label="Join the discussion"]</code> — <?php esc_html_e( 'Custom label', 'murmlo-global-comments' ); ?></li>
	</ul>

	<p class="description">
		<strong><?php esc_html_e( 'Note:', 'murmlo-global-comments' ); ?></strong>
		<?php esc_html_e( 'The shortcode works even when "Enable" is unchecked above. This allows manual placement via page builders while keeping automatic injection disabled.', 'murmlo-global-comments' ); ?>
	</p>

	<hr>

	<h2><?php esc_html_e( 'Custom Styling', 'murmlo-global-comments' ); ?></h2>
	<p><?php esc_html_e( 'Override the button appearance by adding custom CSS to your theme. The button uses CSS custom properties that you can change:', 'murmlo-global-comments' ); ?></p>

	<pre style="background: #f0f0f1; padding: 12px 16px; border-radius: 4px; max-width: 600px; overflow-x: auto; font-size: 13px;"><code>.murmlo-comments-wrapper {
  --murmlo-bg: transparent;      /* <?php esc_html_e( 'Button background', 'murmlo-global-comments' ); ?> */
  --murmlo-text: currentColor;   /* <?php esc_html_e( 'Text color', 'murmlo-global-comments' ); ?> */
  --murmlo-border: currentColor; /* <?php esc_html_e( 'Border color', 'murmlo-global-comments' ); ?> */
  --murmlo-logo: #2cb7a3;        /* <?php esc_html_e( 'Logo color', 'murmlo-global-comments' ); ?> */
  --murmlo-badge-bg: #2cb7a3;    /* <?php esc_html_e( 'Count badge background', 'murmlo-global-comments' ); ?> */
  --murmlo-badge-text: #fff;     /* <?php esc_html_e( 'Count badge text', 'murmlo-global-comments' ); ?> */
  --murmlo-radius: 6px;          /* <?php esc_html_e( 'Border radius', 'murmlo-global-comments' ); ?> */
  --murmlo-logo-size: 22px;      /* <?php esc_html_e( 'Logo size', 'murmlo-global-comments' ); ?> */
}</code></pre>

	<p class="description">
		<?php esc_html_e( 'Add this to Appearance → Customize → Additional CSS, or to your child theme\'s style.css file.', 'murmlo-global-comments' ); ?>
	</p>

</div>
