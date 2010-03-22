WDN.jQuery(function($){
	$("#date,#request_publish_start,#request_publish_end").datepicker({showOn: 'both', buttonImage: '/wdn/templates_3.0/css/content/images/mimetypes/x-office-calendar.png', buttonImageOnly: true});
	$("#date").change(function(){
		var date = $(this).val().split(/\//);

		$('#request_publish_end').attr('value', $(this).val());
		
	    $.getFeed({
	        url: 'http://events.unl.edu/'+date[2]+'/'+date[0]+'/'+date[1]+'/?format=rss',
	        success: function(feed) {
	        	window.whatisfeed = feed;
	        	$("#event").html('<option value="NewEvent">New Event</option>');
	            for(var i = 0, l = feed.items.length; i < l; i++) {
		            
	                var item = feed.items[i];
	               $("#event").append('<option value="'+item.link+'">' + item.title + '</option>');
	            }
	            
	        }    
	    });
	    
	});
	$('.hasDatepicker').each(function() {
		$(this).attr({'autocomplete' : 'off'});
	});
	$('select#event').change(function(){
		$('form#enews input[name=website]').val($(this).val());
	});
	$('ol.option_step a').click(function() {
		$('#wdn_process_step1').slideToggle();
		if($(this).attr('id') == 'newsAnnouncement') { //the user has selected news, so hide the event date panel and show the news form
			$('#enews h3').eq(1).hide();
			$('#wdn_process_step3').slideToggle(function() {
				$('#enews h3').eq(0).removeClass('highlighted');
				$('#enews h3').eq(2).addClass('highlighted').append(' <span class="announceType">News Announcement</span>');
			});
		} else { //we have an event request
			$('#wdn_process_step2').slideToggle(function() {
				$('#enews h3').eq(0).removeClass('highlighted');
				$('#enews h3').eq(1).addClass('highlighted');
			});
		}
		return false;
	});
	
	$('#next_step3').click(function() {
		$('#wdn_process_step2').slideToggle();
		$('#wdn_process_step3').slideToggle(function() {
			$('#enews h3').eq(1).removeClass('highlighted');
			$('#enews h3').eq(2).addClass('highlighted').append('<span class="announceType">Event Announcement</span>');
		});
		return false;
	});
	$('#enews h3').eq(0).css('cursor','pointer').click(backToStep1);
	$('#enews h3').eq(1).css('cursor','pointer').click(backToStep2);
	//Update the sample layout
	$('#title').keyup(function() {
		var demoTitle = $(this).val();
		$('#sampleLayout h4').text(function(index){
			return demoTitle;
		});
	});
	var characterLimit = 300;
	$('#description').keyup(function() {
		var demoText = $(this).val();
		$(this).prev('label').children('span').children('strong').text(characterLimit - demoText.length);
		if ((characterLimit - demoText.length) < (characterLimit * .08)) {
			$(this).prev('label').children('span').children('strong').addClass('warning');
		} else {
			$(this).prev('label').children('span').children('strong').removeClass('warning');
		}
		if (demoText.length > characterLimit) {
			$(this).val(demoText.substr(0,characterLimit));
		}
		$('#sampleLayout p').text(function(index){
			return demoText;
		});
	});
	
	//Make a GoURL with campaign tagging for the Supporting Website
	$('#website').change(function() {
		var website = $(this).val();
		var goURLPrefix = RegExp('http://go.unl.edu');
		if (!goURLPrefix.test(website)) {
			submission.createGoURL(
				website,
				function(data) {
					submission.addURLtoPreview(data);
				}
			);
		}
		//Now, let's add the URL to the preview
		if (!submission.urlPreview){
			submission.addURLtoPreview(website);
		}
	});

	
});
/**
 * Namespace for submission javascript.
 */

var submission = function() {
	return {
		utm_campaign : 'UNL_ENews',
		
		utm_medium : 'email',
		
		utm_content : WDN.jQuery('#website').attr('value'),
		
		utm_source : 'eNews',
		
		urlPreview : false,
		
		createGoURL : function(url) {
			WDN.jQuery('#website').siblings('label').append('<span class="helper">Building a GoURL...</span>');
			WDN.jQuery('#website').attr('disabled','disabled');
			
			gaTagging = "utm_campaign="+submission.utm_campaign+"&utm_medium="+submission.utm_medium+"&utm_source="+submission.utm_source+"&utm_cotent="+submission.utm_content;
			
			WDN.socialmediashare.createURL(
				WDN.socialmediashare.buildGAURL(url, gaTagging),
				function(data) {
					$('#website').attr('value', data).siblings('label').children('span.helper').html('URL converted to a GoURL');
				}
			);
		},
	
		addURLtoPreview : function(url) {
			WDN.jQuery('#sampleLayout p a').text(function(index){
				return url;
				submission.urlPreview = true;
			});
		}
	};
}();

function backToStep1() {
	$('#wdn_process_step2').slideUp();
	$('#wdn_process_step3').slideUp();
	$('#wdn_process_step1').slideDown();
	$('#enews h3').eq(2).removeClass("highlighted");
	$('#enews h3').eq(1).removeClass("highlighted");
	$('#enews h3').eq(0).addClass("highlighted");
	$('#enews h3 span.announceType').remove();
	$('#enews h3').show();
};
function backToStep2() {
	$('#wdn_process_step1').slideUp();
	$('#wdn_process_step3').slideUp();
	$('#wdn_process_step2').slideDown();
	$('#enews h3').eq(2).removeClass("highlighted");
	$('#enews h3').eq(0).removeClass("highlighted");
	$('#enews h3').eq(1).addClass("highlighted");
	$('#enews h3 span.announceType').remove();
	$('#enews h3').show();
};