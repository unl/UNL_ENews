

WDN.jQuery(document).ready(function() {
	self.focus();
	document.getElementById("form1").elements[0].focus();
	WDN.loadJS('wdn/templates_3.0/scripts/toolbar_peoplefinder.js');
	WDN.jQuery('#form1').submit(function(eventObject) {
		WDN.toolbar_peoplefinder.queuePFRequest(WDN.jQuery('#q').val(), 'results');
		eventObject.preventDefault();
		eventObject.stopPropagation();
		return false;
	});
});
