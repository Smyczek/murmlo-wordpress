=== Murmlo – Global Comments ===
Contributors: murmlo
Tags: comments, discussion, external comments, murmlo, community
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace or supplement WordPress comments with Murmlo — a global, URL-based discussion layer.

== Description ==

**Murmlo – Global Comments** adds a link or button to your posts that takes readers to a discussion on [Murmlo](https://murmlo.com/). Murmlo is a threaded discussion platform where conversations happen around URLs — so your readers can join a broader conversation that extends beyond your site.

This is a lightweight "gateway" plugin. It does **not** embed comments on your page. Instead, it provides a customizable link/button that opens the Murmlo discussion room for each post's URL.

**Features:**

* Automatic link/button injection after (or before) post content
* Live murmur count badge fetched from the Murmlo API
* `[murmlo_comments]` shortcode for manual placement in page builders
* Option to suppress native WordPress comments
* Works with Yoast SEO and RankMath canonical URLs
* URL normalization (strips UTM params, fragments)
* Theme-neutral styling — adapts to your theme's colors
* Browser extension detection — opens sidepanel if installed
* Lightweight — no JavaScript frameworks, no iframes

== Installation ==

1. Upload the `murmo-global-comments` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to **Settings > Murmlo Comments** to configure

== Frequently Asked Questions ==

= Does this replace my WordPress comments? =

Only if you want it to. There is an option to disable native WordPress comments on selected post types. Otherwise, both systems can coexist.

= Do my readers need a Murmlo account? =

No. Murmlo supports anonymous commenting. Readers can also create a free account for additional features.

= Can I customize the link text? =

Yes. You can set a custom label in the settings, or leave it empty for dynamic labels that show the murmur count (e.g. "Comments (5) on Murmlo").

= Can I place the link manually instead of auto-injecting? =

Yes. Use the `[murmlo_comments]` shortcode anywhere in your content. The shortcode works even when auto-injection is disabled.

= Does it work with caching plugins? =

Yes. Murmur counts are cached server-side using WordPress transients (5-minute TTL), so it works well with page caching plugins.

== Screenshots ==

1. Settings page in WordPress admin
2. Link displayed below a post
3. Button variant below a post

== Changelog ==

= 1.0.0 =
* Initial release
* Link/button injection with configurable position
* Murmur count badge with API caching
* Shortcode support
* WordPress comments suppression option
* Canonical URL detection (Yoast SEO, RankMath, fallback)
* Browser extension sidepanel integration

== Upgrade Notice ==

= 1.0.0 =
Initial release.
