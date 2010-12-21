WDN.jQuery('document').ready(function() {
	WDN.jQuery("#subscribe").submit(function() {
		WDN.log('form submitted');
		WDN.get(
			WDN.jQuery(this).attr('action'),
			WDN.jQuery(this).serialize(),
			function(){
				WDN.log('Posted!');
			}
		);
	return false;
	});
});