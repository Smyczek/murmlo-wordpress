=== Murmlo – Global Comments ===
Contributors: murmlo
Tags: comments, discussion, external comments, murmlo, community
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a Murmlo comments button to your posts — animated logo, comment count, mobile deep linking.

== Description ==

**Murmlo – Global Comments** adds a button to your posts that opens a discussion on [Murmlo](https://murmlo.com/). Murmlo is a threaded discussion platform where conversations happen around URLs — your readers can join a broader conversation that extends beyond your site.

This is a lightweight plugin. It does **not** embed comments on your page. Instead, it provides a button with an animated Murmlo logo and a live comment count badge.

**Features:**

* Animated M logo with loading animation
* Live comment count badge fetched client-side from the Murmlo API
* 6 color themes: Default, Brand, Light, Dark, Light Mono, Dark Mono
* `[murmlo_comments]` shortcode for manual placement
* Option to suppress native WordPress comments
* Works with classic themes and block themes (Full Site Editing)
* Mobile deep linking — opens the Murmlo app on iOS/Android
* Chrome extension integration — opens sidepanel if installed
* Custom CSS variables for full style control
* Lightweight — vanilla JavaScript, no frameworks, no iframes

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to **Settings > Murmlo Comments** to configure

The button appears automatically on posts and pages after activation.

== Frequently Asked Questions ==

= Does this replace my WordPress comments? =

Only if you want it to. There is an option to disable native WordPress comments on selected post types. Otherwise, both systems can coexist.

= Do my readers need a Murmlo account? =

No. Murmlo supports anonymous commenting. Readers can also create a free account for additional features.

= Can I customize the button text? =

Yes. Set a custom label in the settings, or leave it empty for the default "Comments" text. You can write anything in any language.

= Can I change the button colors? =

Yes. Choose from 6 built-in color themes in the settings, or override any style using CSS custom properties. See the Custom Styling section in the settings page.

= Can I place the button manually? =

Yes. Use the `[murmlo_comments]` shortcode anywhere in your content. Supports `variant`, `label`, and `theme` attributes. Works even when auto-injection is disabled.

= Does it work with block themes (Full Site Editing)? =

Yes. The plugin hooks into both classic `the_content` filter and block theme `render_block` for full compatibility.

= What happens on mobile? =

On iOS and Android, tapping the button opens the Murmlo app directly. If the app is not installed, it falls back to the Murmlo website.

== Screenshots ==

1. Settings page in WordPress admin
2. Button with animated M logo and comment count
3. Color theme variants

== Changelog ==

= 1.0.0 =
* Initial release
* Animated M logo with stroke drawing animation
* Live comment count badge (client-side API fetch)
* 6 color themes with CSS custom properties
* Block theme support (render_block hook)
* Shortcode with variant, label, and theme attributes
* Mobile deep linking (iOS/Android)
* Chrome extension sidepanel integration
* WordPress comments suppression option

== Upgrade Notice ==

= 1.0.0 =
Initial release.
