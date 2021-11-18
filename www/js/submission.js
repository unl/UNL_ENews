define([
  'jquery',
  'wdn',
  'require',
  ['js/socialmediashare'],
], function($, WDN, require, social) {

	var plugin = {
		utm_campaign : 'UNL_ENews',

		utm_medium : 'email',

		utm_content : '',

		utm_source : 'eNews',

		urlPreview : false,

		characterLimit : 300,

		editing : false,

		announcementType : false,

		editType : false,

		initialize : function() {
			WDN.initializePlugin('jqueryui', [function() {
				$(document).ready(function(){
					// Set up date pickers on all inputs with datepicker class
					$("input.datepicker").datepicker({
						showOn: 'both',
						buttonImage: ENEWS_HOME + 'css/images/x-office-calendar.png',
						buttonImageOnly: true,
						dateFormat: 'yy-mm-dd',
						defaultDate: this.value
					});
					$('.hasDatepicker').each(function () {
						$(this).attr({'autocomplete': 'off'});
					});
				});

				if (plugin.editType) { // Editing, so update where needed
					plugin.editing = true;
					plugin.announcementType = plugin.editType;
				}
				plugin.bindActions();

				if (plugin.editing) {
					plugin.determinePresentation(plugin.announcementType);
				}
			}]);
		},

		determinePresentation : function(announcementType) {
			plugin.announcementType = announcementType;
			switch (announcementType) {
			case 'news' :
				plugin.setPresentationId('news');
				break;
			case 'event' :
				plugin.setPresentationId('event');
				break;
			case 'ad' :
				plugin.setPresentationId('ad');
				break;
			}
		},

		bindActions : function() {
			$("#date").bind('change', function() {// Set up the 'Event' story type date select box
				// Update the story end publish date to match the event date
				$('#request_publish_end').attr('value', $(this).val());
				plugin.findEvents($(this).val());
			});

			// Make a GoURL with campaign tagging for the Supporting Website
			$('#website').bind('blur', function() {
				var website = $.trim($(this).val());

				if (website.substring(0, 7) !== 'http://' && website.substring(0, 8) !== 'https://' && website.substring(0, 7) !== 'mailto:') {
					website = 'http://' + website;
					$(this).val(website);
				}
				var goURLPrefix = RegExp('//go.unl.edu');
				if (!goURLPrefix.test(website)) {
					plugin.createGoURL(website);
				} else {
					if (!plugin.urlPreview) {
						plugin.urlPreview = true;
					}
				}
			});

			$('#addAnotherNewsroom').bind('click', function() {
				var dropdown = $('#newsroom_id_dropdown').html();
				$(this).before(dropdown);
				if ($('#newsroom_id select:last option[value=""]').size() == 0) {
					$('#newsroom_id select:last').prepend('<option selected="selected" value=""></option>');
				}
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
				var imgString = '<img onload="require([ENEWS_HOME+\'js/submission.js\'],function(submission){if(submission.announcementType != \'ad\')submission.loadImageCrop(\'4:3\');})" src="'+ENEWS_HOME+'?view=file&id='+$(this).val()+'" alt="Uploaded Image" />';
				$('#upload_area').html(imgString);
				$('#sampleLayoutImage').html('Select Thumbnail Below');
				$('#img_description_label .required').remove();
				$('#img_description_label').prepend('<span class="required">*</span> ');
				$('#file_description').addClass('required').removeAttr('disabled');
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
						plugin.loadImageCrop('4:3');
						$(this).removeClass('r34');
						$(this).addClass('r43');
					} else {
						plugin.loadImageCrop('3:4');
						$(this).removeClass('r43');
						$(this).addClass('r34');
					}

				}
			);

			// When the submission button is pressed, save whatever changes were made to the story first
			$('form#enewsSubmission').bind('submit', function() {
				if (validationErrorMessage = plugin.submitStory(true, false)) {
					$('#dcf-main').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="dcf-notice dcf-notice-danger" id="submission-error" hidden data-overlay="dcf-main"><h2>Submit Failed!</h2><div><p>'+validationErrorMessage+'</p></div></div>');
					window.scrollTo(0, 0);
					return false;
				}
			});

			$('form#deleteImages').bind('submit', function() {
				var dataString = $('form#deleteImages').serialize();

				$.post(
					window.location.href,
					dataString,
					function(data,status) {
						$('#sampleLayoutImage img').attr('src', '');
						$('#upload_area img').attr('src', '');
						$('#img_description_label span.required').remove();
						$('#file_description').removeClass('required').attr('disabled','disabled');
						plugin.loadImageCrop();
					}
				);
				return false;
			});

			// Summary 'Characters remaining' helper
			$('#description').bind('keyup', function() {
				var demoText = $('#description').val();
				if ((plugin.characterLimit - demoText.length) < (plugin.characterLimit * .08)) {
					$('label[for="description"] span.helper strong').addClass('warning');
				} else {
					$('label[for="description"] span.helper strong').removeClass('warning');
				}
				if (demoText.length > plugin.characterLimit) {
					demoText = demoText.substr(0,plugin.characterLimit);
					$('#description').val(demoText);
				}
				$('label[for="description"] span.helper strong').text(plugin.characterLimit - demoText.length);
			});

		},

		setPresentationId : function(value){
			$('#presentation_id').attr('value', ENEWS_DEFAULT_PRESENTATIONID[value]);
		},

		createGoURL : function(url) {
			if (url == 'http://') {
				return;
			}
			$('#website').siblings('label').html('Supporting Website <span class="helper">Building a GoURL...</span>');
			plugin.utm_content = $('#title').val();
			plugin.utm_source = plugin.announcementType;

			gaTagging = "utm_campaign="+plugin.utm_campaign+"&utm_medium="+plugin.utm_medium+"&utm_source="+plugin.utm_source+"&utm_content="+plugin.utm_content;

			if (url.indexOf('?') != -1) { //check to see if has a ?, if not then go ahead with the ?. Otherwise add with &
				url = url+"&"+gaTagging;
			} else {
				url = url+"?"+gaTagging;
			}

			social.createURL(
				url,
				function(data) {
					$('#website').val(data).siblings('label').children('span.helper').html('URL converted to a <a href="http://go.unl.edu/" target="_blank">GoURL</a>');
				},
				function(){
					$('#website').val(url).siblings('label').children('span.helper').html('URL can\'t be converted to a GoURL.');
				}
			);
		},

		loadImageCrop : function(ratio) {
			WDN.loadJS(ENEWS_HOME+'/js/jquery.imgareaselect.dev.js',function() {
				plugin.clearImageCrop();
				plugin.setUpImageCrop(ratio);
			});
		},

		clearImageCrop : function() {
			if (plugin.ias) {
				plugin.ias.setOptions({disable:true,hide:true,remove:true});
				plugin.ias.update();
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

			plugin.ias = $('#upload_area img').imgAreaSelect({
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
							plugin.ias.setOptions({
								maxWidth : imgWidth,
								x1: imgWidth*(1/4),
								y1: (imgHeight/2)-((imgWidth/2)*(4/3)/2),
								x2: imgWidth*(3/4),
								y2: (imgHeight/2)+((imgWidth/2)*(4/3)/2)
							});
						} else {
							plugin.ias.setOptions({
								maxHeight : imgHeight,
								x1 : (imgWidth/2)-((imgHeight/2)*(3/4)/2),
								y1 : imgHeight*(1/4),
								x2 : (imgWidth/2)+((imgHeight/2)*(3/4)/2),
								y2 : imgHeight*(3/4)
							});
						}
					} else {
						if (imgWidth/imgHeight > 4/3) {
							plugin.ias.setOptions({
								maxHeight : imgHeight,
								x1 : (imgWidth/2)-((imgHeight/2)*(4/3)/2),
								y1 : imgHeight*(1/4),
								x2 : (imgWidth/2)+((imgHeight/2)*(4/3)/2),
								y2 : imgHeight*(3/4)
							});
						} else {
							plugin.ias.setOptions({
								maxWidth : imgWidth,
								x1: imgWidth*(1/4),
								y1: (imgHeight/2)-((imgWidth/2)*(3/4)/2),
								x2: imgWidth*(3/4),
								y2: (imgHeight/2)+((imgWidth/2)*(3/4)/2)
							});
						}
					}
					plugin.ias.update();
					return false;
				}
			}
			calcSize();
		},

		submitStory : function(validate, ajax) {
			if (validate) {
				var message = '';
				$('input.required, textarea.required').each(function() {
					if ($(this).attr('disabled') !== undefined) {
						// Field is disabled, user couldn't enter text
						return;
					}

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
						$('#deleteImages input[name=storyid]').val(data);
					},
					function (data, status, e) {
						$('#dcf-main').prepend('<script type="text/javascript">WDN.initializePlugin("notice");</script><div class="dcf-notice dcf-notice-danger" hidden><h2>Error</h2><div><p>Problem uploading image</p></div></div>');
						return e;
					}
				);
			}
			return false;
		}
	};

	return plugin;
});
