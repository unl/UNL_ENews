WDN.jQuery(document).ready(function($){
	
	$("input.datepicker").datepicker({
		showOn: 'both',
		buttonImage: '/wdn/templates_3.0/css/content/images/mimetypes/x-office-calendar.png',
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		defaultDate: this.value});
	$("#date").change(function(){
		var date = $(this).val().split(/-/);

		$('#request_publish_end').attr('value', $(this).val());
		
		WDN.get('http://events.unl.edu/'+date[0]+'/'+date[1]+'/'+date[2]+'/?format=xml', null,
			function(eventsXML){
				$("#event").html('<option value="NewEvent">New Event</option>');
				$(eventsXML).find('Event').each(function(){
					$("#event").append('<option value="'+$(this).find('WebPage URL').text()+'">' + $(this).find('EventTitle').text() + '</option>');
				});
			}, 'xml');
	});
	$('.hasDatepicker').each(function() {
		$(this).attr({'autocomplete' : 'off'});
	});
	$('select#event').change(function(){
		$('form.enews input[name=website]').val($(this).val());
		WDN.get($(this).val()+'?format=xml', null, function(data) {

			$('form.enews input[name=title]').val($(data).find('EventTitle').text());
			$('form.enews textarea[name=description]').val($(data).find('Description').text());

			$('#title').keyup();
			$('#description').keyup();

		}, 'xml');

	});
	$('ol.option_step a').click(function() {
		$('#wdn_process_step1').slideToggle();
		if($(this).attr('id') == 'newsAnnouncement') { //the user has selected news, so hide the event date panel and show the news form
			$('#enewsForm h3').eq(1).hide();
			$('#wdn_process_step3').slideToggle(function() {
				$('#enewsForm h3').eq(0).removeClass('highlighted');
				$('#enewsForm h3').eq(2).addClass('highlighted').append(' <span class="announceType">News Announcement</span>');
				$('#sampleLayout').show();
				$('#enewsImage').show();
				$('#enewsSubmissionButton').show();
			});
		} else { //we have an event request
			$('#wdn_process_step2').slideToggle(function() {
				$('#enewsForm h3').eq(0).removeClass('highlighted');
				$('#enewsForm h3').eq(1).addClass('highlighted');
			});
		}
		return false;
	});
	
	$('#next_step3').click(function() {
		$('#wdn_process_step2').slideToggle();
		$('#wdn_process_step3').slideToggle(function() {
			$('#enewsForm h3').eq(1).removeClass('highlighted');
			$('#enewsForm h3').eq(2).addClass('highlighted').append('<span class="announceType">Event Announcement</span>'); 
		});
		$('#sampleLayout').show();
		$('#enewsImage').show();
		$('#enewsSubmissionButton').show();
		return false;
	}); 
	
	$('#enewsForm h3').eq(0).css('cursor','pointer').click(backToStep1);
	$('#enewsForm h3').eq(1).css('cursor','pointer').click(backToStep2);
	
	
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
		if (website.substring(0, 7) !== 'http://' && website.substring(0, 8) !== 'https://' && website.substring(0, 7) !== 'mailto:') {
			website = "http://" + website;
		}
		var goURLPrefix = RegExp('http://go.unl.edu');
		var eventsPrefix = RegExp('http://events.unl.edu');
		if (!goURLPrefix.test(website) && !eventsPrefix.test(website)) {
			submission.createGoURL(website);
		} else {
			if (!submission.urlPreview) {
				submission.addURLtoPreview(website);
			}
		}
	});

	//When a file is selected from users local machine, do the ajax image upload
	$('#enewsImage #image').change(function() {
		$('#upload_area').html('<img src="http://www.unl.edu/wdn/templates_3.0/css/header/images/colorbox/loading.gif" alt="Loading..." />');
		if (!submitStory()) {
			//Need stupid closure here and timeout because storyid from the submitted story is not available immediately
			(function(){
				//Remove the previous crop selection area if it exists
				WDN.loadJS("js/jquery.imgareaselect.pack.js",function(){
					$('#upload_area img').imgAreaSelect({
						disable:true,
						hide:true
					});
				},true,true);
				//Ajax up the image
				var myform = document.getElementById("enewsImage");
				setTimeout(function(){ajaxUpload.upload(myform);},1000);
			})();
			$('#enewsSubmissionButton').remove();
			$('#enewsSubmitButton').show();
		} else {
			alert('Error');
		}
		
		return false;
	});
	
	//When the final submission button is pressed, save whatever changes were made to the story first
	$('form#enewsSubmission').submit(function(){
		if (message = submitStory(true)) {
			$('#maincontent').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="wdn_notice negate"><div class="close"><a href="#" title="Close this notice">Close this notice</a></div><div class="message"><h4>Submit Failed!</h4><p>'+message+'</p></div></div>');
			return false;
		}
	});
	
	//When the final submission button is pressed, save whatever changes were made to the story first
	$('form#enewsSubmit').submit(function(){
		if (message = submitStory(true)) {
			$('#maincontent').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="wdn_notice negate"><div class="close"><a href="#" title="Close this notice">Close this notice</a></div><div class="message"><h4>Submit Failed!</h4><p>'+message+'</p></div></div>');
			return false;
		}
	});

	
	function backToStep1() {
		$('#enewsSubmissionButton').hide();
		$('#sampleLayout').hide();
		$('#enewsImage').hide();
		$('#enewssubmitbutton').hide();
		$('#wdn_process_step2').slideUp();
		$('#wdn_process_step3').slideUp();
		$('#wdn_process_step1').slideDown();
		$('#enewsForm h3').eq(2).removeClass("highlighted");
		$('#enewsForm h3').eq(1).removeClass("highlighted");
		$('#enewsForm h3').eq(0).addClass("highlighted");
		$('#enewsForm h3 span.announceType').remove();
		$('#enewsForm h3').show();
	};
	function backToStep2() {
		$('#enewsSubmissionButton').hide();
		$('#sampleLayout').hide();
		$('#enewsImage').hide();
		$('#enewssubmitbutton').hide();
		$('#wdn_process_step1').slideUp();
		$('#wdn_process_step3').slideUp();
		$('#wdn_process_step2').slideDown();
		$('#enewsForm h3').eq(2).removeClass("highlighted");
		$('#enewsForm h3').eq(0).removeClass("highlighted");
		$('#enewsForm h3').eq(1).addClass("highlighted");
		$('#enewsForm h3 span.announceType').remove();
		$('#enewsForm h3').show();
	};
	
	function submitStory(validate) {
	  	var storyid = $("input#storyid").val();
	  	var title = $("input#title").val();
	  	var description = $("textarea#description").val();
	  	var full_article = $("textarea#full_article").val();
		var request_publish_start = $("input#request_publish_start").val();
		var request_publish_end = $("input#request_publish_end").val();
		var website = $("input#website").val();
		var sponsor = $("input#sponsor").val();
		
		if (validate && (title == "" || description == "" || request_publish_start == "" || request_publish_end == "" || sponsor == "")) {
			return 'Required fields cannot be left blank';
		}
		if (validate && (request_publish_start > request_publish_end)) {
			return '"Last date this could run" must be later then "What date would like this to run?"';
		}
		
		//Use placeholder text if user uploads an image first
		if (title == "")
			title = '?';
		if (description == "")
			description = '?';
		if (request_publish_start == "")
			request_publish_start = '1999-01-01';
		if (request_publish_end == "")
			request_publish_end = '1999-01-01';
		if (sponsor == "")
			sponsor = '?';
	    		
	    var newsroom_id = new Array();
	    $("input[name=newsroom_id\\[\\]]").each( function(index) {
			newsroom_id.push($(this).val());
	    }); 

		//Create the data string to POST
		var dataString = '_type=story&ajaxupload=yes&storyid=' + storyid + '&title='+ title + '&description=' + description;
		dataString += '&full_article=' + full_article + '&request_publish_start=' + request_publish_start;
		dataString += '&request_publish_end=' + request_publish_end + '&website=' + website + '&sponsor=' + sponsor;
		$.each(newsroom_id, function(key, value) { 
			dataString += '&newsroom_id[]=';
			dataString += value; 
		});
			
		$.ajax({
			type: "POST",
			//xhr needed to make ie8 work, jQuery 1.4.2 has a bug: http://forum.jquery.com/topic/jquery-ajax-ie8-problem	
			xhr: (window.ActiveXObject) ?
				function() {
					try {
						return new window.ActiveXObject("Microsoft.XMLHTTP");
					} catch(e) {}
				} :
				function() {
					return new window.XMLHttpRequest();
				},
			url: $('#enewsSubmission').attr('action'),
			data: dataString,
			success: function(data,status) {
				//We get back the id of the newly saved story
				document.enewsSubmission.storyid.value = data; 
				document.enewsImage.storyid.value = data; 
				document.enewsSubmit.storyid.value = data;
			},
			error: function (data, status, e) {
				alert(e);
			}
		});
		
		return false;
	};
});




function setImageCrop() {
	WDN.jQuery('#upload_area img').imgAreaSelect({
		enable:true,
		hide:false,
		aspectRatio: "4:3",
		onSelectEnd: function (img, selection) {
			WDN.jQuery('#enewsSubmit input[name=x1]').val(selection.x1);
			WDN.jQuery('#enewsSubmit input[name=y1]').val(selection.y1);
			WDN.jQuery('#enewsSubmit input[name=x2]').val(selection.x2);
			WDN.jQuery('#enewsSubmit input[name=y2]').val(selection.y2);
		}
	});
};



/**
 * Namespace for submission javascript.
 */
var submission = function() {
	return {
		utm_campaign : 'UNL_ENews',
		
		utm_medium : 'email',
		
		utm_content : '',
		
		utm_source : 'eNews',
		
		urlPreview : false,
		
		createGoURL : function(url) {
			WDN.jQuery('#website').siblings('label').html('Supporting Website <span class="helper">Building a GoURL...</span>');
			//WDN.jQuery('#website').attr('disabled','disabled');
			submission.utm_content = WDN.jQuery('#title').val();
			
			gaTagging = "utm_campaign="+submission.utm_campaign+"&utm_medium="+submission.utm_medium+"&utm_source="+submission.utm_source+"&utm_content="+submission.utm_content;
			
			WDN.socialmediashare.createURL(
				WDN.socialmediashare.buildGAURL(url, gaTagging),
				function(data) {
					WDN.jQuery('#website').attr('value', data).siblings('label').children('span.helper').html('URL converted to a <a href="http://go.unl.edu/" target="_blank">GoURL</a>');
					submission.addURLtoPreview(data);
				}
			);
			
		},
		addURLtoPreview : function(url) {
			WDN.jQuery('#sampleLayout a').text(function(index){
				return url;
				submission.urlPreview = true;
			});
		}
	};
}();

