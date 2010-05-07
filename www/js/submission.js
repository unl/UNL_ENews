WDN.jQuery(function($){
	$("input.datepicker").datepicker({showOn: 'both', buttonImage: '/wdn/templates_3.0/css/content/images/mimetypes/x-office-calendar.png', buttonImageOnly: true});
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
		$('form.enews input[name=website]').val($(this).val());
		$.getFeed({
	        url: $(this).val()+'/?format=rss',
	        success: function(feed) {
				for(var i = 0, l = feed.items.length; i < l; i++) {
					var item = feed.items[i];
					$('form.enews input[name=title]').val(item.title);
					$('#sampleLayout h4').text(item.title);
					// The description here is actually HTML, we should grab ?format=xml
					//$('form.enews input[name=description]').val(escape(item.description));
				}
			}
		});
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
				$('#enewssubmitbutton').show();
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
		$('#enewssubmitbutton').show();
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
		var goURLPrefix = RegExp('http://go.unl.edu');
		if (!goURLPrefix.test(website)) {
			submission.createGoURL(website);
		} else {
			if (!submission.urlPreview){
				submission.addURLtoPreview(website);
			}
		}
		//Now, let's add the URL to the preview
		
	});

	//When a file is selected from users local machine, do the ajax image upload
	$('#enewsImage #image').change(function() {
		$('#upload_area').html('<img src="http://www.unl.edu/wdn/templates_3.0/css/header/images/colorbox/loading.gif" />')
		if (submitStory()) {
			//Hide submit button until user selects a crop area
			$('#enewssubmitbutton').hide();
			//Remove the previous crop selection area if it exists
			$('#upload_area img').imgAreaSelect({
				disable:true,
				hide:true
			});
			//need stupid closure here and timeout because storyid from the submitted story is not available immediately
			(function(){
				var myform = document.getElementById("enewsImage");
				setTimeout(function(){ajaxUpload(myform);},500);
			})();
		} else {
			alert('Error');
		}
		
		return false;
	});
	
 

	
	function backToStep1() {
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
	
	function submitStory() {
	  	var storyid = $("input#storyid").val();
	  	var title = $("input#title").val();
	  	var description = $("textarea#description").val();
	  	var full_article = $("textarea#full_article").val();
		var request_publish_start = $("input#request_publish_start").val();
		var request_publish_end = $("input#request_publish_end").val();
		var sponsor = $("input#sponsor").val();
		
		//Use placeholder text if user uploads an image first
		if (title == "")
			title = 'PH';
		if (description == "")
			description = 'PH';
		if (request_publish_start == "")
			request_publish_start = '2020-01-01';
		if (request_publish_end == "")
			request_publish_end = '2020-01-01';
		if (sponsor == "")
			sponsor = 'PH';
	    		
	    var newsroom_id = new Array();
	    $("input[name=newsroom_id\\[\\]]").each( function(index) {
			newsroom_id.push($(this).val());
	    }); 

		//Create the data string to POST
		var dataString = '_type=story&storyid=' + storyid + '&title='+ title + '&description=' + description + '&full_article=' + full_article + '&request_publish_start=' + request_publish_start;
		dataString += '&request_publish_end=' + request_publish_end + '&sponsor=' + sponsor;
		$.each(newsroom_id, function(key, value) { 
			  dataString += '&newsroom_id[]=';
			  dataString += value; 
		});

		//alert(dataString);//return false;
			
		$.ajax({
	      type: "POST",
	      url: $('#enewsSubmission').attr('action'),
	      data: dataString,
	      success: function(data,status) {
			//We get back the id of the newly saved story
	        document.enewsImage.storyid.value = data; 
	        document.enewsSubmit.storyid.value = data; 
	      },
	      error: function (data, status, e) {
			alert(e);
	  	  }
	    });
	 
	 	return true;
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
			if((selection.x1 + 40) < selection.x2) { //make sure we actually have a real selection
				WDN.jQuery('#enewssubmitbutton').show();
			}
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
			WDN.jQuery('#website').siblings('label').append('<span class="helper">Building a GoURL...</span>');
			WDN.jQuery('#website').attr('disabled','disabled');
			submission.utm_content = WDN.jQuery('#title').val();
			
			gaTagging = "utm_campaign="+submission.utm_campaign+"&utm_medium="+submission.utm_medium+"&utm_source="+submission.utm_source+"&utm_content="+submission.utm_content;
			
			WDN.socialmediashare.createURL(
				WDN.socialmediashare.buildGAURL(url, gaTagging),
				function(data) {
					WDN.jQuery('#website').attr('value', data).siblings('label').children('span.helper').html('URL converted to a GoURL');
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

