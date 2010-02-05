WDN.jQuery(document).ready(function() {
	WDN.jQuery('ol.option_step a').click(function() {
		if(WDN.jQuery(this).attr('id') == 'newsAnnouncement') { //the user has selected news, so hide the event date panel and show the news form
			WDN.jQuery('#wdn_process_step1').slideToggle();
			WDN.jQuery('#enews h3').eq(1).hide();
			WDN.jQuery('#wdn_process_step3').slideToggle(function() {
				WDN.jQuery('#enews h3').eq(0).removeClass('highlighted');
				WDN.jQuery('#enews h3').eq(2).addClass('highlighted');
			});
		}
		return false;
	});
	//Update the sample layout
	WDN.jQuery('#title').keyup(function() {
		var demoTitle = WDN.jQuery(this).val();
		WDN.jQuery('#sampleLayout h4').text(function(index){
			return demoTitle;
		});
	})
	WDN.jQuery('#description').keyup(function() {
		var demoText = WDN.jQuery(this).val();
		WDN.jQuery('#sampleLayout p').text(function(index){
			return demoText;
		});
	})
});