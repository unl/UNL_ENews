<?php
function getValue($object, $field)
{
    if (isset($object->$field)) {
        return htmlentities($object->$field, ENT_QUOTES);
    }
    
    if (isset($_POST[$field])) {
        return htmlentities($_POST[$field], ENT_QUOTES);
    }
    
    return '';
}
?>
<script type="text/javascript">
WDN.loadJS("js/jquery.imgareaselect.pack.js");
WDN.loadJS("js/jquery.jfeedUNL.js");
WDN.loadCSS("css/imgareaselect-default.css");
WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css");
WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css");
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


	
});
</script>
<form id="enews" class="energetic" action="?view=submit" method="post" enctype="multipart/form-data">
<h3 class="highlighted"><span>1</span>Select News Type</h3>
<fieldset id="wdn_process_step1">
	<legend>Select News Type</legend>
	<ol class="option_step">
		<li><a href="#" id="newsAnnouncement">Is this a News announcement?</a></li>
		<li><a href="#" id="eventAnnouncement">Is this an Event announcement?</a></li>
	</ol>
</fieldset>
<h3><span>2</span>Enter Date Details for Event</h3>
<fieldset id="wdn_process_step2" style="display:none;">
	<legend><span>Enter Date Details for Event</span></legend>
        <ol>
        	<li>
        		<label for="date">Date of Event<span class="required">*</span></label>
				<input id="date" name="date" type="text" value="<?php echo getValue($context, 'title'); ?>" />
			</li>
        	<li>
        		<label for="event">Which Event?<span class="required">*</span><span class="helper">These are your events, as found at http://events.unl.edu/</span></label>
				<select id="event">
					<option value="NewEvent">New Event</option>
					
				</select>
			</li>
        </ol>
        <p class="nextStep"><a href="#" id="next_step3">Continue</a></p>
</fieldset>
<h3><span>3</span>Announcement Submission</h3>
<fieldset id="wdn_process_step3" style="display:none;">
	<legend><span>News Announcement Submission</span></legend>
    <input type="hidden" name="_type" value="story" />
        <ol>
            <li><label for="title">Headline or Title<span class="required">*</span></label><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" /></li>
            <li><label for="description">Description<span class="helper">You have <strong>300</strong> characters remaining.</span></label><textarea id="description" name="description" cols="60" rows="5"><?php echo getValue($context, 'description'); ?></textarea></li>
            <li><label for="request_publish_start">What date would like this to run?<span class="required">*</span></label><input id="request_publish_start" name="request_publish_start" type="text" size="10"  value="<?php echo getValue($context, 'request_publish_start'); ?>" /></li>
            <li><label for="request_publish_end">Last date this could run<span class="required">*</span></label><input id="request_publish_end" name="request_publish_end" type="text" size="10"  value="<?php echo getValue($context, 'request_publish_end'); ?>" /></li>
            <li><label for="website">Supporting Website</label><input id="website" name="website" type="text"  value="<?php echo getValue($context, 'website'); ?>" /></li>
            <li><label for="sponsor">Sponsoring Unit<span class="required">*</span></label><input id="sponsor" name="sponsor" type="text" value="<?php echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment; ?>" /></li>
            <li><label for="image">Image<span class="helper">This is the image that will be displayed with your announcement.</span></label><input id="image" name="image" type="file" /></li>
            <?php if ($context->newsroom->id != 1 && $context->newsroom->id != 2) : ?>
            <li>
            	<fieldset>
            		<legend>Please consider for</legend>
            		<ol>
            		<li> 
            			<input type="checkbox" name="newsroom_id[]" value="<?php echo (int)$context->newsroom->id; ?>" checked="checked" />
                    	<label for="newsroom_id[]"><?php echo htmlentities($context->newsroom->name); ?></label>
            		</li>
            		<li>
            			<input type="checkbox" name="newsroom_id[]" value="1" />
                    	<label for="newsroom_id[]">E-News</label>
                    </li>
            		<li>
            			<input type="checkbox" name="newsroom_id[]" value="2" />
                    	<label for="newsroom_id[]">UNL Today</label>
                    </li>
            		</ol>
            	</fieldset>	
      <!--      <fieldset>
      				<legend>Please consider for </legend> 
					<ol>              
                    <?php foreach (array(1 => 'E-News',
                                         2 => 'UNL Today',
                                         3 => 'Scarlet',
                                         4 => 'News Release',
                                         5 => 'Web Promo',
                                         6 => 'NebraskaMag') as $id=>$title) :?>
                    <li>
                    	<input type="checkbox" name="newsroom_id[]<?php echo $type; ?>" value="<?php echo $id; ?>" />
                    	<label for="newsroom_id[]<?php echo $type; ?>"><?php echo $title; ?></label>
                    </li>
                    <?php endforeach; ?>
                    </ol>
                </fieldset>  -->
            </li>
            <?php else : ?>
            <li>
            	<input type="hidden" name="newsroom_id[]" value="1" />
            	<input type="hidden" name="newsroom_id[]" value="2" />
            </li>
            <?php endif; ?>
        </ol>
	<div id="sampleLayout">
		<h4>&lt;Enter Your Title&gt;</h4>
		<p>&lt;Enter Your Article Text&gt;</p>
	</div>
</fieldset>
<fieldset id="wdn_process_step3b" style="display:none;">
	<legend>Event Announcement Submission</legend>
    <p>Pull in the event form.</p>
</fieldset>
<p class="submit"><input type="submit" name="submit" value="Submit" /></p>
</form>