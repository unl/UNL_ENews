var submission = function($) {
	return {
		utm_campaign : 'UNL_ENews',

		utm_medium : 'email',

		utm_content : '',

		utm_source : 'eNews',

		urlPreview : false,

		characterLimit : 300,

		editing : false,

		announcementType : false,

		initialize : function() {
			$('.hasDatepicker').each(function() {
				$(this).attr({'autocomplete' : 'off'});
			});
			$('#enewsForm h3').eq(0).css('cursor','pointer');
			$('#enewsForm h3').eq(1).css('cursor','pointer');
			submission.bindActions();
			
			$('textarea.resizable:not(.processed)').TextAreaResizer();
			if (submission.editing) {
				$('#enewsForm h3').eq(0).hide();
				$('#enewsForm h3').eq(1).hide();
				$('#enewsForm h3').eq(2).html('Edit Submission');
				$('#enewsSubmissionButton').show();
				$('#enewsSaveCopyButton').show();
				submission.determinePresentation(submission.announcementType);
				submission.updatePreview();
			};
		},

		determinePresentation : function(announcementType) {
			submission.announcementType = announcementType;
			switch (announcementType) {
			case 'news' :
				submission.prepareNewsSubmission();
				submission.setPresentationId('news');
				break;
			case 'event' :
				submission.prepareEventSubmission();
				submission.setPresentationId('event');
				break;
			case 'ad' :
				submission.prepareAdSubmission();
				submission.setPresentationId('ad');
				break;
			}
		},

		bindActions : function() {
			$("#date").bind('change', function() {// Set up the 'Event' story type date select box
				// Update the story end publish date to match the event date
				$('#request_publish_end').attr('value', $(this).val());
				submission.findEvents($(this).val());
			});

			$('select#event').bind('change', function() {
				$('#website').attr('value', $(this).val());
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

			$('ol.option_step a').bind('click', function() {// Sliding action for the three part form
				var a = $(this).attr('id');
				announcementType = a.replace('Announcement', '');
				submission.determinePresentation(announcementType);
				return false;
			});

			// Make a GoURL with campaign tagging for the Supporting Website
			$('#website').bind('change', function() {
				var website = $.trim($(this).val());
				if (website.substring(0, 7) !== 'http://' && website.substring(0, 8) !== 'https://' && website.substring(0, 7) !== 'mailto:') {
					website = 'http://' + website;
				}
				var goURLPrefix = RegExp('http://go.unl.edu');
				var eventsPrefix = RegExp('http://events.unl.edu');
				if (!goURLPrefix.test(website) && !eventsPrefix.test(website)) {
					submission.createGoURL(website);
				} else {
					if (!submission.urlPreview) {
						submission.updatePreview();
						submission.urlPreview = true;
					}
				}
			});

			$('#addAnotherNewsroom').bind('click', function() {
				var dropdown = $('#newsroom_id_dropdown').html();
				$(this).before(dropdown);
				$('#newsroom_id select:last').prepend('<option selected="selected" value=""></option>');
				return false;
			});

			// When a file is selected from users local machine, save the story first to get the story's id then...
			$('#enewsImage #image').bind('change', function() {
				$('#upload_area').html('<img src="/wdn/templates_3.0/css/header/images/colorbox/loading.gif" alt="Loading..." />');
				ajaxUpload.upload(document.getElementById("enewsImage"));
				return false;
			});

			// When the file upload returns with the file ID and the iframe updates the hidden fileID input, populate the image previews, then load cropping when img is done loading
			$('#enewsSubmission #fileID').bind('change', function() {
				var imgString = '<img onload="if(submission.announcementType != \'ad\')submission.loadImageCrop(\'4:3\');" src="'+ENEWS_HOME+'?view=file&id='+$(this).val()+'" alt="Uploaded Image" />';
				$('#upload_area').html(imgString);
				$('#sampleLayoutImage').html('Select Thumbnail Below');
				ajaxUpload.removeIframe();
			});

			// When the file description filed is edited, copy over to the hidden field in the story form that will be submitted
			$('#file_description').bind('change', function() {
				$('#fileDescription').val($(this).val());
			});

			$('#cropRatio').click(
				function() {
					$('#sampleLayoutImage').html('Select Thumbnail Below');
					$('input[name=thumbX1]').val('-1');
					$('input[name=thumbX2]').val('-1');
					$('input[name=thumbY1]').val('-1');
					$('input[name=thumbY2]').val('-1');
					
					if ($(this).hasClass('r34')) {
						submission.loadImageCrop('4:3');
						$(this).removeClass('r34');
						$(this).addClass('r43');
					} else {
						submission.loadImageCrop('3:4');
						$(this).removeClass('r43');
						$(this).addClass('r34');
					}
					
				}
			);

			// When the submission button is pressed, save whatever changes were made to the story first
			$('form#enewsSubmission').bind('submit', function() {
				if (validationErrorMessage = submission.submitStory(true, false)) {
					$('#maincontent').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="wdn_notice negate"><div class="close"><a href="#" title="Close this notice">Close this notice</a></div><div class="message"><h4>Submit Failed!</h4><p>'+validationErrorMessage+'</p></div></div>');
					return false;
				}
			});

			// Update the sample layout
			$('#description').bind('keyup', function() {
				submission.updatePreview();
			});
			$('#title').bind('keyup', function() {
				submission.updatePreview();
			});

			$('#next_step3').bind('click', function() {
				$('#wdn_process_step2').slideToggle();
				$('#wdn_process_step3').slideToggle(function() {
					$('#enewsForm h3').eq(1).removeClass('highlighted');
					$('#enewsForm h3').eq(2).addClass('highlighted').append('<span class="announceType">Event Announcement</span>'); 
				});
				$('#sampleLayout,#enewsImage,#enewsSubmissionButton').show();
				return false;
			});
			$('#enewsForm h3').eq(0).bind('click', function() {
				submission.goToStep(1);
			});
			$('#enewsForm h3').eq(1).bind('click', function() {
				submission.goToStep(2);
			});
		},

		goToStep : function(step) {
			$('#enewsSubmissionButton,#sampleLayout,#enewsImage').hide();
			switch(step){
			case 1:
				oppStep = 2;
				break;
			case 2: 
				oppStep = 1;
				break;
			}
			$('#wdn_process_step'+oppStep).slideUp();
			$('#wdn_process_step'+step).slideDown();
			$('#enewsForm h3').eq(oppStep-1).removeClass("highlighted");
			$('#enewsForm h3').eq(step-1).addClass("highlighted");
			$('#wdn_process_step3').slideUp();
			$('#enewsForm h3').eq(2).removeClass("highlighted");
			$('#enewsForm h3 span.announceType').remove();
			$('#enewsForm h3').show();
		},

		prepareNewsSubmission : function() {
			$('#wdn_process_step1').slideToggle();
			$('#enewsForm h3').eq(1).hide();
			$('#wdn_process_step3').slideToggle(function() {
				$('#enewsForm h3').eq(0).removeClass('highlighted');
				$('#enewsForm h3').eq(2).addClass('highlighted').append(' <span class="announceType">News Announcement</span>');
				$('#sampleLayout,#enewsImage,#enewsSubmissionButton').show();
			});
		},

		prepareEventSubmission : function() {
			$('#wdn_process_step1').slideToggle();
			submission.goToStep(2);
		},

		prepareAdSubmission : function() {
			$('#wdn_process_step1').slideToggle();
			$('#enewsForm h3').eq(1).hide();
			$('#wdn_process_step3').slideToggle(function() {
				$('#enewsForm h3').eq(0).removeClass('highlighted');
				$('#enewsForm h3').eq(2).addClass('highlighted').append(' <span class="announceType">Advertisement</span>');
				$('#enewsImage,#enewsSubmissionButton').show();
			});
			submission.setupAd();
			
		},
		
		setupAd : function(){ //let's tweak the main form to streamline when we're doing a simple ad
			$('#sampleLayout').hide();
			$('#upload_area span').remove();
			$('#full_article').parents('li').hide();
			$('label[for="title"]').html('Advertisement Name<span class="required">*</span>');
			$('label[for="image"]').html('Image advertisement <span class="helper">Must be 273px X 104px</span>')
		},

		setPresentationId : function(value){
			$('#presentation_id').attr('value', ENEWS_DEFAULT_PRESENTATIONID[value]);
		},

		findEvents : function(selectedDate) {
			var date = selectedDate.split(/-/);
			// Grab the latest events for this date and populate select box
			WDN.get('http://events.unl.edu/'+date[0]+'/'+date[1]+'/'+date[2]+'/?format=xml', null,
				function(eventsXML){
					$("#event").html('<option value="NewEvent">New Event</option>');
					$(eventsXML).find('Event').each(function(){
						var url = $(this).find('WebPage URL:contains(events.unl.edu)').first();
						$("#event").append('<option value="'+url.text()+'">' + $(this).find('EventTitle').text() + '</option>');
					});
				},
				'xml'
			);
		},

		updatePreview : function() {
			$('#sampleLayout p').text(function(index){
				if ($('#description').val().length) {
					return $('#description').val().substring(0,300);
				}
			});
			$('#sampleLayout h4').text(function(index){
				if ($('#title').val().length) {
					return $('#title').val();
				}
			});
			$('#sampleLayout a').text(function(index){
				return $('#website').val();
			});
			var demoText = $('#description').val();
			if ((submission.characterLimit - demoText.length) < (submission.characterLimit * .08)) {
				$('label[for="description"] span.helper strong').addClass('warning');
			} else {
				$('label[for="description"] span.helper strong').removeClass('warning');
			}
			if (demoText.length > submission.characterLimit) {
				demoText = demoText.substr(0,submission.characterLimit);
				$('#description').val(demoText);
			}
			$('label[for="description"] span.helper strong').text(submission.characterLimit - demoText.length);
		},

		createGoURL : function(url) {
			$('#website').siblings('label').html('Supporting Website <span class="helper">Building a GoURL...</span>');
			submission.utm_content = $('#title').val();
			submission.utm_source = submission.announcementType;

			gaTagging = "utm_campaign="+submission.utm_campaign+"&utm_medium="+submission.utm_medium+"&utm_source="+submission.utm_source+"&utm_content="+submission.utm_content;

			WDN.socialmediashare.createURL(
				WDN.socialmediashare.buildGAURL(url, gaTagging),
				function(data) {
					$('#website').attr('value', data).siblings('label').children('span.helper').html('URL converted to a <a href="http://go.unl.edu/" target="_blank">GoURL</a>');
					submission.updatePreview(data);
				},
				function(){
					$('#website').attr('value', website).siblings('label').children('span.helper').html('URL can\'t be converted to a GoURL.');
					submission.updatePreview(website);
				}
			);
		},

		loadImageCrop : function(ratio) {
			WDN.loadJS(ENEWS_HOME+'/js/jquery.imgareaselect.dev.js',function() {
				submission.clearImageCrop();
				submission.setUpImageCrop(ratio);
			},true,true);
		},

		clearImageCrop : function() {
			if (submission.ias) {
				submission.ias.setOptions({disable:true,hide:true,remove:true});
				submission.ias.update();
			}
			$('#imageControls').hide();
			$('#file_description').attr('disabled','disabled');
		},

		setUpImageCrop : function(ratio) {
			if (ratio == '3:4') {
				xWidth = 72;
				yWidth = 96;
			} else {
				xWidth = 96;
				yWidth = 72;
			}

			var preview = function(img, selection) {
				$('#sampleLayoutImage').css({
					width: xWidth + 'px',
					height: yWidth + 'px'
				});
				
				var imgString = '<img src="'+ENEWS_HOME+'?view=file&id='+$('#enewsSubmission #fileID').val()+'" alt="Uploaded Image" />';
				$('#sampleLayoutImage').html(imgString);
				
				var scaleX = xWidth / (selection.width || 1);
				var scaleY = yWidth / (selection.height || 1);
				
				$('#sampleLayoutImage > img').css({
					width: Math.round(scaleX * imgWidth) + 'px',
					height: Math.round(scaleY * imgHeight) + 'px',
					marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
					marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
				}); 
			}

			submission.ias = $('#upload_area img').imgAreaSelect({
				instance: true,
				enable : true,
				hide : false,
				aspectRatio : ratio,
				handles : true,
				onSelectChange : preview,
				onSelectEnd : function(img, selection) {
					$('input[name=thumbX1]').val(selection.x1);
					$('input[name=thumbX2]').val(selection.x2);
					$('input[name=thumbY1]').val(selection.y1);
					$('input[name=thumbY2]').val(selection.y2);
				}
			});

			$('#imageControls').show();
			$('#file_description').removeAttr('disabled');

			/* Get the width/height of image and set the coords according to the given ratio.
			 * Using a setTimeout because .width() and .height() of the image return 0 immediately after the image onload event,
			 * could use window.load but don't want to be dependent on other servers (alert,planetred)
			 */
			var imgWidth = 0;
			var imgHeight = 0;
			var calcSize = function(){
				imgWidth = $('#upload_area > img').width();
				imgHeight = $('#upload_area > img').height();
				if (imgWidth == 0) {
					setTimeout(function(){calcSize();},25);
				} else {
					if (ratio == '3:4') {
						if (imgWidth/imgHeight < 3/4) {
							submission.ias.setOptions({
								maxWidth : imgWidth,
								x1: imgWidth*(1/4),
								y1: (imgHeight/2)-((imgWidth/2)*(4/3)/2),
								x2: imgWidth*(3/4),
								y2: (imgHeight/2)+((imgWidth/2)*(4/3)/2)
							});
						} else {
							submission.ias.setOptions({
								maxHeight : imgHeight,
								x1 : (imgWidth/2)-((imgHeight/2)*(3/4)/2),
								y1 : imgHeight*(1/4),
								x2 : (imgWidth/2)+((imgHeight/2)*(3/4)/2),
								y2 : imgHeight*(3/4)
							});
						}
					} else {
						if (imgWidth/imgHeight > 4/3) {
							submission.ias.setOptions({
								maxHeight : imgHeight,
								x1 : (imgWidth/2)-((imgHeight/2)*(4/3)/2),
								y1 : imgHeight*(1/4),
								x2 : (imgWidth/2)+((imgHeight/2)*(4/3)/2),
								y2 : imgHeight*(3/4)
							});
						} else {
							submission.ias.setOptions({
								maxWidth : imgWidth,
								x1: imgWidth*(1/4),
								y1: (imgHeight/2)-((imgWidth/2)*(3/4)/2),
								x2: imgWidth*(3/4),
								y2: (imgHeight/2)+((imgWidth/2)*(3/4)/2)
							});
						}
					}
					submission.ias.update();
					return false;
				}
			}
			calcSize();
		},

		submitStory : function(validate, ajax) {
			if (validate) {
				var message = '';
				$('input.required,textarea.required').each(function() {
					if (this.value == '') {
						message = 'Required fields cannot be left blank';
					}
				});
				if ($("input#request_publish_start").val() > $("input#request_publish_end").val()) {
					message = '"Last date this could run" must be after or equal to "What date would like this to run?"';
				}
				if (message != '') {
					return message;
				}
			}

			// Create the data string to POST
			var dataString = $('#enewsSubmission').serialize();
			dataString += '&ajaxupload=yes';

			if (ajax) {
				$.post(
					window.location.href,
					dataString,
					function(data,status) {
						// We get back the id of the newly saved story
						$('#enewsSubmission #storyid').val(data);
						$('#enewsImage #storyid').val(data).change();
					},
					function (data, status, e) {
						$('#maincontent').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="wdn_notice negate"><div class="close"><a href="#" title="Close this notice">Close this notice</a></div><div class="message"><h4>Error</h4><p>Problem uploading image</p></div></div>');
						return e;
					}
				);
			}
			return false;
		}
	};
}(WDN.jQuery);

WDN.jQuery(document).ready(function($) {
	// Set up date pickers on all inputs with datepicker class
	$("input.datepicker").datepicker({
		showOn: 'both',
		buttonImage: '/wdn/templates_3.0/css/content/images/mimetypes/x-office-calendar.png',
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		defaultDate: this.value
	});
	if (editType) { // Editing, so update where needed
		submission.editing = true;
		submission.announcementType = editType;
	}
	submission.initialize();
});