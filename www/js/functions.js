
WDN.jQuery(document).ready(function() {
	WDN.jQuery('ol.option_step a').click(function() {
		WDN.jQuery('#wdn_process_step1').slideToggle();
		if(WDN.jQuery(this).attr('id') == 'newsAnnouncement') { //the user has selected news, so hide the event date panel and show the news form
			WDN.jQuery('#enews h3').eq(1).hide();
			WDN.jQuery('#wdn_process_step3').slideToggle(function() {
				WDN.jQuery('#enews h3').eq(0).removeClass('highlighted');
				WDN.jQuery('#enews h3').eq(2).addClass('highlighted').append(' <span class="announceType">News Announcement</span>');
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
			WDN.jQuery('#enews h3').eq(2).addClass('highlighted').append(' <span class="announceType">Event Announcement</span>');
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
	
	//the newsletter creation page <- to be moved to it's own file/plugin
	WDN.jQuery('.dragItem').draggable({ 
		revert: 'invalid',
		snap: '.newsColumn',
		snapMode : 'inner',
		connectToSortable: '.newsColumn',
		//helper: 'clone',
		opacity: 0.45
	});
	WDN.jQuery('#newsColumn1, #newsColumn2').sortable({
		//revert: true,
		connectWith: '.newsColumn',
		scroll: true,
		delay: 250,
		opacity: 0.45,
		tolerance: 'pointer',
		stop: function(event, ui){
			saveStoryOrder();
		}
	});
	WDN.jQuery('#newsColumn1, #newsColumn2').disableSelection();
	WDN.jQuery('.newsColumn').droppable({
		drop: function(event, ui) {
			ui.draggable.addClass('story').removeAttr('style').removeClass('dragItem');
			WDN.jQuery.post(WDN.jQuery(this).find('form').attr('action'), WDN.jQuery(this).find('form').serialize());
		}
	});
});
function saveStoryOrder() {
	WDN.jQuery('#newsColumn1, #newsColumn2').sortable('refresh');
	var result1 = WDN.jQuery('#newsColumn1').sortable('toArray');
	var result2 = WDN.jQuery('#newsColumn2').sortable('toArray');
	var sort_order;
	for(var i = 0; i<result1.length; i++) {
		WDN.jQuery('#'+result1[i]+' form input[name=sort_order]').attr('value', i*2+1);
		WDN.jQuery.post(WDN.jQuery('#'+result1[i]+' form').attr('action'), WDN.jQuery('#'+result1[i]+' form').serialize());
	}
	for(i = 0; i<result2.length; i++) {
		WDN.jQuery('#'+result2[i]+' form input[name=sort_order]').attr('value', i*2+2);
		WDN.jQuery.post(WDN.jQuery('#'+result2[i]+' form').attr('action'), WDN.jQuery('#'+result2[i]+' form').serialize());
	}
}
