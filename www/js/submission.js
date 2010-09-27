WDN.jQuery(document).ready(function($){
	// Set up date pickers on all inputs with datepicker class
	$("input.datepicker").datepicker({
		showOn: 'both',
		buttonImage: '/wdn/templates_3.0/css/content/images/mimetypes/x-office-calendar.png',
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		defaultDate: this.value});
	$('.hasDatepicker').each(function() {
		$(this).attr({'autocomplete' : 'off'});
		});

	// Set up the 'Event' story type date select box
	$("#date").change(function(){

		// Update the story end publish date to match the event date
		$('#request_publish_end').attr('value', $(this).val());

		var date = $(this).val().split(/-/);

		// Grab the latest events for this date and populate select box
		WDN.get('http://events.unl.edu/'+date[0]+'/'+date[1]+'/'+date[2]+'/?format=xml', null,
			function(eventsXML){
				$("#event").html('<option value="NewEvent">New Event</option>');
				$(eventsXML).find('Event').each(function(){
					$("#event").append('<option value="'+$(this).find('WebPage URL').text()+'">' + $(this).find('EventTitle').text() + '</option>');
				});
			}, 'xml');

	});

	// When an event has been selected from the drop down
	$('select#event').change(function(){
		$('form.enews input[name=website]').val($(this).val());

		// Get the details for this specific event
		WDN.get($(this).val()+'?format=xml', null, function(data) {

			// Set the title and description from data we know
			$('form.enews input[name=title]').val($(data).find('EventTitle').text());
			$('form.enews textarea[name=description]').val($(data).find('Description').text());

			// Trigger the keyup on these fields so the preview is updated
			$('#title').keyup();
			$('#description').keyup();

		}, 'xml');

	});

	// Sliding action for the three part form
	$('ol.option_step a').click(function() {
		$('#wdn_process_step1').slideToggle();
		if($(this).attr('id') == 'newsAnnouncement') { //the user has selected news, so hide the event date panel and show the news form
			$('#enewsForm h3').eq(1).hide();
			$('#wdn_process_step3').slideToggle(function() {
				$('#enewsForm h3').eq(0).removeClass('highlighted');
				$('#enewsForm h3').eq(2).addClass('highlighted').append(' <span class="announceType">News Announcement</span>');
				$('#sampleLayout,#enewsImage,#enewsImageDescription,#enewsSubmissionButton').show();
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
		$('#sampleLayout,#enewsImage,#enewsImageDescription,#enewsSubmissionButton').show();
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
		if ((characterLimit - demoText.length) < (characterLimit * .08)) {
			$(this).parents('.resizable-textarea').prev('label').children('span').children('strong').addClass('warning');
		} else {
			$(this).parents('.resizable-textarea').prev('label').children('span').children('strong').removeClass('warning');
		}
		if (demoText.length > characterLimit) {
			demoText = demoText.substr(0,characterLimit);
			$(this).val(demoText);
		}
		$('#sampleLayout p').text(function(index){
			return demoText.substring(0,300);
		});
		$(this).parents('.resizable-textarea').prev('label').children('span').children('strong').text(characterLimit - demoText.length);
	});

	// Make a GoURL with campaign tagging for the Supporting Website
	$('#website').change(function() {
		website = $.trim($(this).val());
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

	// When a file is selected from users local machine, do the ajax image upload
	$('#enewsImage #image').change(function() {
		$('#upload_area').html('<img src="/wdn/templates_3.0/css/header/images/colorbox/loading.gif" alt="Loading..." />');
		if (!submitStory()) {
			// Need stupid closure here and timeout because storyid from the submitted story is not available immediately
			(function(){
				// Load the ImageAreaSelect plugin and remove the previous crop selection area if it exists
				WDN.loadJS("js/jquery.imgareaselect.pack.js",function(){
					$('#upload_area img').imgAreaSelect({
						disable:true,
						hide:true
					});
				},true,true);
				// Ajax up the image
				var myform = document.getElementById("enewsImage");
				setTimeout(function(){ajaxUpload.upload(myform);},1000);
			})();
		} else {
			$('#maincontent').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="wdn_notice negate"><div class="close"><a href="#" title="Close this notice">Close this notice</a></div><div class="message"><h4>Error</h4><p>Problem uploading image</p></div></div>');
		}

		return false;
	});

	$('#addAnotherNewsroom').click(function(){
		var dropdown = $('#newsroom_id_dropdown').html();
		$(this).before(dropdown);
		$('#newsroom_id select:last').prepend('<option selected="selected" value=""></option>');
		return false;
	});

	//When the submission button is pressed, save whatever changes were made to the story first
	$('form#enewsSubmission').submit(function(){
		if (message = submitStory(true)) {
			$('#maincontent').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="wdn_notice negate"><div class="close"><a href="#" title="Close this notice">Close this notice</a></div><div class="message"><h4>Submit Failed!</h4><p>'+message+'</p></div></div>');
			return false;
		}
	});

	function backToStep1() {
		$('#enewsSubmissionButton,#sampleLayout,#enewsImage,#enewsImageDescription').hide();
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
		$('#enewsSubmissionButton,#sampleLayout,#enewsImage,#enewsImageDescription').hide();
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
		var request_publish_start = $("input#request_publish_start").val();
		var request_publish_end = $("input#request_publish_end").val();
		
		var message = '';
		$('input.required,textarea.required').each(function(){
			if (this.value == '') {
				message = 'Required fields cannot be left blank';
			}
		});
		if (request_publish_start > request_publish_end) {
			message = '"Last date this could run" must be later then "What date would like this to run?"';
		}
		if (validate && message != '') {
			return message;
		}
		
		//Use placeholder text if user uploads an image first
		if ($('input#title').val() == '')
			$('input#title').val('Title');
		if ($('textarea#description').val() == '')
			$('textarea#description').val('Story');
		if ($('input#request_publish_start').val() == '')
			$('input#request_publish_start').val('1999-01-01');
		if ($('input#request_publish_end').val() == '')
			$('input#request_publish_end').val('1999-01-01');
		if ($('input#sponsor').val() == '')
			$('input#sponsor').val('Sponsoring Unit');
		
		//Create the data string to POST
		var dataString = $('#enewsSubmission').serialize();
		dataString += '&ajaxupload=yes';
		
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
				//Clear out the placeholder text
				if ($('input#title').val() == 'Title')
					$('input#title').val('');
				if ($('textarea#description').val() == 'Story')
					$('textarea#description').val('');
				if ($('input#request_publish_start').val() == '1999-01-01')
					$('input#request_publish_start').val('');
				if ($('input#request_publish_end').val() == '1999-01-01')
					$('input#request_publish_end').val('');
				if ($('input#sponsor').val() == 'Sponsoring Unit')
					$('input#sponsor').val('');
			},
			error: function (data, status, e) {
				return e;
			}
		});
		
		return false;
	};
});








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
				},
				function(){
					WDN.jQuery('#website').attr('value', website).siblings('label').children('span.helper').html('URL can\'t be converted to a GoURL.');
					submission.addURLtoPreview(website);
				}
			);
		},
		addURLtoPreview : function(url) {
			WDN.jQuery('#sampleLayout a').text(function(index){
				return url;
				submission.urlPreview = true;
			});
		},
		setImageCrop : function() {
			WDN.jQuery('#upload_area img').imgAreaSelect({
				enable:true,
				hide:false,
				aspectRatio: "4:3",
				onSelectEnd: function (img, selection) {
					var dataString = '_type=thumbnail&ajaxupload&storyid=' + WDN.jQuery('#enewsSubmission input[name=storyid]').val();
					dataString += '&x1=' + selection.x1 + '&x2=' + selection.x2 + '&y1=' + selection.y1 + '&y2=' + selection.y2;
					
					WDN.jQuery.ajax({
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
						url: WDN.jQuery('#enewsSubmission').attr('action'),
						data: dataString,
						success: function(data,status) {
							return false;
						},
						error: function (data, status, e) {
							return e;
						}
					});
				}
			});
		}
	};
}();

