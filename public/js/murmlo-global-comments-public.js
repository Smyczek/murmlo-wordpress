/**
 * Murmlo Embed - Comments link with animated logo, count badge, and deep linking
 *
 * Works standalone (single <script> tag) or as WordPress plugin JS.
 * Styles are injected automatically if not already present.
 *
 * === Full button (logo + label + count badge) ===
 *
 *   <a class="murmlo-comments-button" data-murmlo-url="https://example.com/page">
 *     Comments
 *   </a>
 *   <script src="https://cdn.murmlo.com/embed.js"></script>
 *
 * === Count only (inject number into any element) ===
 *
 *   <span class="murmlo-count" data-murmlo-url="https://example.com/page">-</span>
 *   <script src="https://cdn.murmlo.com/embed.js"></script>
 *
 * @package    Murmlo_Global_Comments
 * @subpackage Murmlo_Global_Comments/public/js
 */

(function() {
	'use strict';

	// ===== Embedded Styles =====
	// Injected once if no external CSS is loaded (standalone mode).
	// WP plugin loads CSS separately, so this is skipped.

	var STYLES = '.murmlo-comments-wrapper{' +
		'--murmlo-brand:#2cb7a3;--murmlo-bg:transparent;--murmlo-text:currentColor;' +
		'--murmlo-border:currentColor;--murmlo-logo:#2cb7a3;--murmlo-badge-bg:#2cb7a3;' +
		'--murmlo-badge-text:#fff;--murmlo-radius:6px;--murmlo-logo-size:22px;margin:1.5em 0}' +
		'.murmlo-theme-brand{--murmlo-bg:#2cb7a3;--murmlo-text:#fff;--murmlo-border:#2cb7a3;' +
		'--murmlo-logo:#fff;--murmlo-badge-bg:rgba(255,255,255,0.25);--murmlo-badge-text:#fff}' +
		'.murmlo-theme-dark{--murmlo-bg:#1a1a1a;--murmlo-text:#fff;--murmlo-border:#333}' +
		'.murmlo-theme-light{--murmlo-bg:#fff;--murmlo-text:#1a1a1a;--murmlo-border:#e0e0e0}' +
		'.murmlo-theme-dark-mono{--murmlo-bg:#1a1a1a;--murmlo-text:#fff;--murmlo-border:#333;' +
		'--murmlo-logo:#fff;--murmlo-badge-bg:rgba(255,255,255,0.2);--murmlo-badge-text:#fff}' +
		'.murmlo-theme-light-mono{--murmlo-bg:#fff;--murmlo-text:#1a1a1a;--murmlo-border:#e0e0e0;' +
		'--murmlo-logo:#1a1a1a;--murmlo-badge-bg:#1a1a1a;--murmlo-badge-text:#fff}' +
		'.murmlo-comments-link,.murmlo-comments-button{display:inline-flex;align-items:center;' +
		'gap:8px;color:var(--murmlo-text);text-decoration:none;' +
		'font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;' +
		'font-size:14px;line-height:1;cursor:pointer;transition:opacity .15s ease}' +
		'.murmlo-comments-link:hover,.murmlo-comments-link:focus,' +
		'.murmlo-comments-button:hover,.murmlo-comments-button:focus{opacity:.8;text-decoration:none}' +
		'.murmlo-comments-button{padding:8px 14px;background:var(--murmlo-bg);' +
		'border:1px solid var(--murmlo-border);border-radius:var(--murmlo-radius)}' +
		'.murmlo-logo-wrap{display:inline-flex;align-items:center;justify-content:center;' +
		'width:var(--murmlo-logo-size);height:var(--murmlo-logo-size);flex-shrink:0}' +
		'.murmlo-logo-wrap svg{width:100%;height:100%;overflow:visible}' +
		'.murmlo-logo-wrap svg path{stroke:var(--murmlo-logo);fill:none;' +
		'stroke-dasharray:100;stroke-dashoffset:0;transition:stroke-dashoffset .4s ease}' +
		'.murmlo-loading .murmlo-logo-wrap svg path{animation:murmlo-draw 1.6s ease-in-out infinite}' +
		'@keyframes murmlo-draw{0%{stroke-dashoffset:100;stroke-dasharray:100}' +
		'50%{stroke-dashoffset:0;stroke-dasharray:100}100%{stroke-dashoffset:-100;stroke-dasharray:100}}' +
		'.murmlo-loaded .murmlo-logo-wrap svg path{stroke-dasharray:none;stroke-dashoffset:0;animation:none}' +
		'.murmlo-count-badge{display:inline-flex;align-items:center;justify-content:center;' +
		'min-width:20px;height:20px;padding:0 6px;background:var(--murmlo-badge-bg);' +
		'color:var(--murmlo-badge-text);font-size:11px;font-weight:700;' +
		'font-variant-numeric:tabular-nums;line-height:1;border-radius:10px}' +
		'.murmlo-count{font-variant-numeric:tabular-nums;font-weight:600}';

	function injectStyles() {
		// Skip if styles already loaded (WP plugin or previous injection)
		if (document.getElementById('murmlo-embed-styles')) return;
		var style = document.createElement('style');
		style.id = 'murmlo-embed-styles';
		style.textContent = STYLES;
		(document.head || document.documentElement).appendChild(style);
	}

	// ===== Configuration =====

	var config = {
		webBase: 'https://murmlo.com',
		apiBase: 'https://api.murmlo.com',
		appScheme: 'murmlo://',
		fallbackDelay: 1500
	};

	if (typeof window.murmloEmbedConfig === 'object') {
		if (window.murmloEmbedConfig.webBase) config.webBase = window.murmloEmbedConfig.webBase;
		if (window.murmloEmbedConfig.apiBase) config.apiBase = window.murmloEmbedConfig.apiBase;
	}

	var currentScript = document.currentScript;
	if (currentScript) {
		if (currentScript.dataset.murmloBase) config.webBase = currentScript.dataset.murmloBase;
		if (currentScript.dataset.murmloApi) config.apiBase = currentScript.dataset.murmloApi;
	}

	// ===== M Logo SVG =====

	var LOGO_SVG = '<svg viewBox="0 0 445.2 445.2" xmlns="http://www.w3.org/2000/svg">' +
		'<path pathLength="100" fill="none" stroke="currentColor" stroke-width="45.2" stroke-miterlimit="10" stroke-linecap="round" ' +
		'd="M185.13,212.9L101.93,45.8c-7.08-14.22-21.59-23.2-37.47-23.2h0c-23.12,0-41.86,18.74-41.86,41.86v287.59c0,38.97,31.59,70.56,70.56,70.56h258.89c38.97,0,70.56-31.59,70.56-70.56V64.46c0-23.12-18.74-41.86-41.86-41.86h0c-15.88,0-30.39,8.99-37.47,23.2l-83.2,167.1c-15.4,30.94-59.54,30.94-74.94,0Z"/>' +
		'</svg>';

	// ===== Platform & Extension Detection =====

	function getPlatform() {
		var ua = navigator.userAgent || '';
		if (/iPhone|iPad|iPod/.test(ua)) return 'ios';
		if (/Android/.test(ua)) return 'android';
		return 'desktop';
	}

	var extension = { installed: false, version: null };
	document.addEventListener('murmlo-extension-installed', function(event) {
		extension.installed = true;
		extension.version = event.detail ? event.detail.version : null;
	});

	// ===== URL Helpers =====

	function extractPageUrl(webUrl) {
		try {
			var parsed = new URL(webUrl);
			return parsed.searchParams.get('url') || webUrl;
		} catch (e) {
			return webUrl;
		}
	}

	function buildWebUrl(pageUrl) {
		return config.webBase + '/murmurs?url=' + encodeURIComponent(pageUrl);
	}

	/**
	 * Get current page URL (stripped of hash and query params).
	 * All elements always show count for the current page.
	 */
	function getCurrentPageUrl() {
		return window.location.href.split('#')[0].split('?')[0];
	}

	// ===== Render Branded Elements =====

	function renderBrandedElement(el) {
		// Auto-wrap in .murmlo-comments-wrapper if not already wrapped
		var parent = el.parentElement;
		if (!parent || !parent.classList.contains('murmlo-comments-wrapper')) {
			var wrapper = document.createElement('div');
			wrapper.className = 'murmlo-comments-wrapper';
			el.parentNode.insertBefore(wrapper, el);
			wrapper.appendChild(el);
			parent = wrapper;
		}

		// Apply theme from data attribute
		var theme = el.dataset.murmloTheme;
		if (theme) {
			parent.classList.add('murmlo-theme-' + theme);
		}

		// Prepend logo before existing text
		var logoWrap = document.createElement('span');
		logoWrap.className = 'murmlo-logo-wrap';
		logoWrap.innerHTML = LOGO_SVG;
		el.insertBefore(logoWrap, el.firstChild);

		el.classList.add('murmlo-loading');
	}

	function updateBrandedElement(el, count) {
		el.classList.remove('murmlo-loading');
		el.classList.add('murmlo-loaded');

		// Append badge only when there are murmurs
		if (count && count > 0) {
			var badge = document.createElement('span');
			badge.className = 'murmlo-count-badge';
			badge.textContent = count;
			el.appendChild(badge);
		}
	}

	// ===== Fetch Counts =====

	function fetchCounts(elements, pageUrlFn) {
		var urlMap = {};

		elements.forEach(function(el) {
			var pageUrl = pageUrlFn(el);
			if (!pageUrl) return;
			if (!urlMap[pageUrl]) urlMap[pageUrl] = [];
			urlMap[pageUrl].push(el);
		});

		Object.keys(urlMap).forEach(function(pageUrl) {
			var apiUrl = config.apiBase + '/api/murmurs/embed/stats/?url=' + encodeURIComponent(pageUrl);

			fetch(apiUrl, {
				method: 'GET',
				headers: { 'Accept': 'application/json' }
			})
				.then(function(response) {
					if (!response.ok) return null;
					return response.json();
				})
				.then(function(data) {
					var count = (data && typeof data.count === 'number') ? data.count : null;

					urlMap[pageUrl].forEach(function(el) {
						if (el.classList.contains('murmlo-count')) {
							el.textContent = (count !== null) ? count : '';
						} else {
							updateBrandedElement(el, count);
						}
					});
				})
				.catch(function() {
					urlMap[pageUrl].forEach(function(el) {
						if (el.classList.contains('murmlo-count')) {
							el.textContent = '';
						} else {
							updateBrandedElement(el, null);
						}
					});
				});
		});
	}

	// ===== Deep Linking (mobile) =====

	function tryDeepLink(pageUrl, webUrl) {
		var deepLink = config.appScheme + 'share?url=' + encodeURIComponent(pageUrl);
		var start = Date.now();
		var fallbackTimer;

		function onVisibilityChange() {
			if (document.hidden) {
				clearTimeout(fallbackTimer);
				document.removeEventListener('visibilitychange', onVisibilityChange);
			}
		}

		document.addEventListener('visibilitychange', onVisibilityChange);
		window.location.href = deepLink;

		fallbackTimer = setTimeout(function() {
			document.removeEventListener('visibilitychange', onVisibilityChange);
			if (!document.hidden && Date.now() - start >= config.fallbackDelay - 100) {
				window.open(webUrl, '_blank', 'noopener,noreferrer');
			}
		}, config.fallbackDelay);
	}

	// ===== Click Handler =====

	function handleClick(event) {
		var link = event.currentTarget;
		var pageUrl = getCurrentPageUrl();
		var webUrl = link.getAttribute('href') || buildWebUrl(pageUrl);

		var platform = getPlatform();

		if (platform === 'desktop') {
			if (extension.installed) {
				event.preventDefault();
				document.dispatchEvent(new CustomEvent('murmlo:open-sidepanel', {
					detail: { url: webUrl, source: 'murmlo-embed' }
				}));
			}
			return;
		}

		event.preventDefault();
		tryDeepLink(pageUrl, webUrl);
	}

	// ===== Init =====

	function init() {
		// Inject styles if not already present
		injectStyles();

		// Branded elements (logo + label + badge + click handler)
		var branded = document.querySelectorAll(
			'.murmlo-comments-link, .murmlo-comments-button, .murmlo-embed'
		);

		var currentPageUrl = getCurrentPageUrl();

		branded.forEach(function(el) {
			// Always link to current page's murmroom
			if (!el.getAttribute('href')) {
				el.setAttribute('href', buildWebUrl(currentPageUrl));
				if (!el.getAttribute('target')) el.setAttribute('target', '_blank');
				if (!el.getAttribute('rel')) el.setAttribute('rel', 'nofollow noopener noreferrer');
			}

			renderBrandedElement(el);
			el.addEventListener('click', handleClick);
		});

		// Count-only elements (support data-murmlo-url for different pages)
		var countOnly = document.querySelectorAll('.murmlo-count');

		// Fetch count for current page (one request for all elements)
		var allElements = [];
		branded.forEach(function(el) { allElements.push(el); });
		countOnly.forEach(function(el) { allElements.push(el); });

		if (allElements.length > 0) {
			fetchCounts(allElements, function() { return currentPageUrl; });
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

})();
