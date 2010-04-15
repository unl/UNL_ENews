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
	WDN.loadJS("/wdn/templates_3.0/scripts/plugins/ui/jQuery.ui.js");
	WDN.loadJS("js/functions.js");
	WDN.loadJS("js/jquery.imgareaselect.pack.js");
	WDN.loadJS("js/jquery.jfeedUNL.js");
	WDN.loadJS("js/submission.js");
	WDN.loadJS("js/ajaxfileupload.js");
	WDN.loadCSS("css/imgareaselect-default.css");
	WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
	WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css");
	WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css");
</script>
 

<form id="enewsSubmission" name="enewsSubmission" class="enews energetic" action="?view=submit" method="post" enctype="multipart/form-data">
	
<?php //Story id if we are editing ?>
<input type="hidden" id="storyid" name="storyid" value="<?php echo getValue($context, 'id'); ?>" />

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
            <li><label for="description">Summary<span class="required">*</span><span class="helper">You have <strong>300</strong> characters remaining.</span></label><textarea id="description" name="description" cols="60" rows="5"><?php echo getValue($context, 'description'); ?></textarea></li>
            <li><label for="full_article">Full Article<span class="helper">For news releases, departmental news feeds, etc...</span></label><textarea id="full_article" name="full_article" cols="60" rows="5"><?php /*echo getValue($context, 'description');*/ ?></textarea></li>
            <li><label for="request_publish_start">What date would like this to run?<span class="required">*</span></label><input id="request_publish_start" name="request_publish_start" type="text" size="10"  value="<?php echo getValue($context, 'request_publish_start'); ?>" /></li>
            <li><label for="request_publish_end">Last date this could run<span class="required">*</span></label><input id="request_publish_end" name="request_publish_end" type="text" size="10"  value="<?php echo getValue($context, 'request_publish_end'); ?>" /></li>
            <li><label for="website">Supporting Website</label><input id="website" name="website" type="text"  value="<?php echo getValue($context, 'website'); ?>" /></li>
            <li><label for="sponsor">Sponsoring Unit<span class="required">*</span></label><input id="sponsor" name="sponsor" type="text" value="<?php echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment; ?>" /></li>
            <?php if ($context->newsroom->id != 1) : ?>
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
                    	<label for="newsroom_id[]">UComm Publications (E-News, UNL Today, Scarlet, etc.)</label>
                    </li>
            		</ol>
            	</fieldset>
            </li>
            <?php else : ?>
            <li>
            	<input type="hidden" name="newsroom_id[]" value="1" />
            </li>
            <?php endif; ?>
        </ol>
	<div id="sampleLayout">
		<h4>&lt;Enter Your Title&gt;</h4>
		<p>&lt;Enter Your Article Text&gt;</p>
		<a href="#"></a>
	</div>
	<div class="clear"></div>
	<p class="nextStep"><a href="#" id="next_step4">Save Story and Continue to Image Upload</a></p>
</fieldset>
<fieldset id="wdn_process_step3b" style="display:none;">
	<legend>Event Announcement Submission</legend>
    <p>Pull in the event form.</p>
</fieldset> 
</form>






<form id="enewsImage" name="enewsImage" class="enews energetic" action="?view=submit" method="post" enctype="multipart/form-data">
<input type="hidden" name="_type" value="file" />
<h3><span>4</span>Image Upload</h3>

<?php //Story id that gets attached when the story is submitted above ?>
<input type="hidden" id="storyid" name="storyid" value="" /> 

<fieldset id="wdn_process_step4" style="display:none;">
            <ol><li>
            <label for="image">Image<span class="helper">This is the image that will be displayed with your announcement.</span></label>
            <input id="image" name="image" type="file" />
            </li></ol>
            
			<div id="upload_area"><div style="border:1px dashed #888;background:white;text-align:center;margin:0 auto;width:100px;min-height:90px;">Upload An Image To Accompany Your Submission</div></div>
</fieldset>
</form>




<form id="enewsSubmit" name="enewsSubmit" class="enews energetic" action="?view=submit" method="post" enctype="multipart/form-data">
<input type="hidden" name="_type" value="savecropped" />
<input type="hidden" name="x1" value="" />
<input type="hidden" name="y1" value="" />
<input type="hidden" name="x2" value="" />
<input type="hidden" name="y2" value="" /> 
<input type="hidden" id="storyid" name="storyid" value="" />
<?php //Submit button removed when an image is uplaoded but then is (re)appended dynamically when image is clicked on to crop in order to force users to make a thumbnail ?>
<div id="enewssubmitbutton" style="display:none;margin:20px 0;"><input type="submit" name="submit" value="Finish Your Submission" /></div>
</form>