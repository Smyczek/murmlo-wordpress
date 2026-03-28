(function( $ ) {
	'use strict';

	$(function() {
		var $variant = $('select[name="murmlo_global_comments_options[variant]"]');
		var $themeRow = $('select[name="murmlo_global_comments_options[theme]"]').closest('tr');

		function toggleTheme() {
			if ($variant.val() === 'link') {
				$themeRow.hide();
			} else {
				$themeRow.show();
			}
		}

		$variant.on('change', toggleTheme);
		toggleTheme();
	});

})( jQuery );
