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
<script type="text/javascript" src="js/functions.js"></script>
<form id="enews" action="?view=submit" method="post" enctype="multipart/form-data">
<h3 class="highlighted"><span>1</span>Select E-News Type</h3>
<fieldset id="wdn_process_step1">
	<legend>Select E-News Type</legend>
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
        		<label for="event">Which Event?<span class="required">*</span><span class="helper">These are your events, as found at http://events.unl.edu</label>
				<select id="event">
					<option value="NewEvent">New Event</option>
					<option value="EventID1">Red Letter Day (Friday, March 3, 2009)</option>
				</select>
			</li>
        </ol>
        <a href="#" id="next_step3">Continue</a>
</fieldset>
<h3><span>3</span>Announcement Submission</h3>
<fieldset id="wdn_process_step3" style="display:none;">
	<legend><span>News Announcement Submission</span></legend>
    <input type="hidden" name="_type" value="story" />
        <ol>
            <li><label for="title">Headline or Title<span class="required">*</span></label><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" /></li>
            <li><label for="description">Description<span class="helper">You have <strong>300</strong> characters remaining.</label><textarea id="description" name="description" cols="60" rows="5"><?php echo getValue($context, 'description'); ?></textarea></li>
            <li><label for="request_publish_start">What date would like this to run?<span class="required">*</span></label><input id="request_publish_start" name="request_publish_start" type="text" size="10"  value="<?php echo getValue($context, 'request_publish_start'); ?>" /></li>
            <li><label for="request_publish_end">Last date this could run<span class="required">*</span></label><input id="request_publish_end" name="request_publish_end" type="text" size="10"  value="<?php echo getValue($context, 'request_publish_end'); ?>" /></li>
            <li><label for="website">Supporting Website</label><input id="website" name="website" type="text"  value="<?php echo getValue($context, 'website'); ?>" /></li>
            <li><label for="sponsor">Sponsoring Unit<span class="required">*</span></label><input id="sponsor" name="sponsor" type="text" value="<?php echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment; ?>" /></li>
            <li><label for="image">Image<span class="helper">This is the image that will be displayed with your announcement.</label><input id="image" name="image" type="file" /></li>
            <li>
            	<fieldset>
                <legend>Please consider for </legend> 
					<ol>              
                    <?php foreach (array('enews'    => 'E-News',
                                         'unltoday' => 'UNL Today',
                                         'scarlet'  => 'Scarlet',
                                         'release'  => 'News Release',
                                         'promo'    => 'Web Promo',
                                         'nemag'    => 'NebraskaMag') as $type=>$title) :?>
                    <li>
                    	<input type="checkbox" name="<?php echo $type; ?>" />
                    	<label for="<?php echo $type; ?>"><?php echo $title; ?></label>
                    </li>
                    <?php endforeach; ?>
                    </ol>
                    </fieldset>
            </li>
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