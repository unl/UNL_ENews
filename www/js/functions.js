WDN.jQuery(document).ready(function() {
	WDN.jQuery('ol.option_step a').click(function() {
		WDN.jQuery('#wdn_process_step1').slideToggle();
		if(WDN.jQuery(this).attr('id') == 'newsAnnouncement') { //the user has selected news, so hide the event date panel and show the news form
			WDN.jQuery('#enews h3').eq(1).hide();
			WDN.jQuery('#wdn_process_step3').slideToggle(function() {
				WDN.jQuery('#enews h3').eq(0).removeClass('highlighted');
				WDN.jQuery('#enews h3').eq(2).addClass('highlighted');
			});
		} else { //we have an event request
			WDN.jQuery('#wdn_process_step2').slideToggle(function() {
				WDN.jQuery('#enews h3').eq(0).removeClass('highlighted');
				WDN.jQuery('#enews h3').eq(1).addClass('highlighted');
			});
		}
		return false;
	});
	WDN.jQuery('#next_step3').click(function() {
		WDN.jQuery('#wdn_process_step2').slideToggle();
		WDN.jQuery('#wdn_process_step3').slideToggle(function() {
			WDN.jQuery('#enews h3').eq(1).removeClass('highlighted');
			WDN.jQuery('#enews h3').eq(2).addClass('highlighted');
		});
		return false;
	});
	//Update the sample layout
	WDN.jQuery('#title').keyup(function() {
		var demoTitle = WDN.jQuery(this).val();
		WDN.jQuery('#sampleLayout h4').text(function(index){
			return demoTitle;
		});
	});
	var characterLimit = 300;
	WDN.jQuery('#description').keyup(function() {
		var demoText = WDN.jQuery(this).val();
		WDN.jQuery(this).prev('label').children('span').children('strong').text(characterLimit - demoText.length);
		if ((characterLimit - demoText.length) < (characterLimit * .08)) {
			WDN.jQuery(this).prev('label').children('span').children('strong').addClass('warning');
		} else {
			WDN.jQuery(this).prev('label').children('span').children('strong').removeClass('warning');
		}
		if (demoText.length > characterLimit) {
			WDN.jQuery(this).val(demoText.substr(0,characterLimit));
		}
		WDN.jQuery('#sampleLayout p').text(function(index){
			return demoText;
		});
	});
});