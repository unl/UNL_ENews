WDN.jQuery(document).ready(function() {
	WDN.jQuery('legend span').width(
			WDN.jQuery(this).parent('form').width();
	);
})