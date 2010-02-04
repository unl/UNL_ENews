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
<<<<<<< .mine
<form action="?view=submit" method="post">
<h3><span>1</span>Select E-News Type</h3>
<fieldset id="wdn_process_step1" style="display:none;">
=======
<script type="text/javascript">
//WDN.loadCSS('http://www.unl.edu/wdn/templates_3.0/css/content/forms.css');
</script>
<form class="cool" action="?view=submit" method="post" enctype="multipart/form-data">
<fieldset id="wdn_process_step1">
>>>>>>> .r36
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
        	<li><label for="title" class="element"><span class="required">*</span>Date of Event</label><div class="element"><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" /></div></li>
        </ol>
</fieldset>
<h3 class="highlighted"><span>3</span>Announcement Submission</h3>
<fieldset id="wdn_process_step3">
	<legend><span>News Announcement Submission</span></legend>
    <input type="hidden" name="_type" value="story" />
        <ol>
            <li><label for="title" class="element">Headline or Title<span class="required">*</span></label><div class="element"><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" /></div></li>
            <li><label for="description" class="element">Description</label><div class="element"><textarea id="description" name="description" cols="60" rows="5"><?php echo getValue($context, 'description'); ?></textarea></div></li>
            <li><label for="event_date" class="element">Date and Time<span class="required">*</span></label><div class="element"><input id="event_date" name="event_date" type="text" size="10"  value="<?php echo getValue($context, 'event_date'); ?>" /></div></li>
            <li><label for="website" class="element">Website</label><div class="element"><input id="website" name="website" type="text"  value="<?php echo getValue($context, 'website'); ?>" /></div></li>
            <li><label for="sponsor" class="element">Sponsoring Unit<span class="required">*</span></label><div class="element"><input id="sponsor" name="sponsor" type="text" value="<?php echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment; ?>" /></div></li>
            <li><label for="image" class="element">Image</label><div class="element"><input id="image" name="image" type="file" /></div></li>
            <li>
                <label class="element">Please consider for</label>
                <div class="element">
                    <?php foreach (array('enews'    => 'E-News',
                                         'unltoday' => 'UNL Today',
                                         'scarlet'  => 'Scarlet',
                                         'release'  => 'News Release',
                                         'promo'    => 'Web Promo',
                                         'nemag'    => 'NebraskaMag') as $type=>$title) :?>
                    <input type="checkbox" name="<?php echo $type; ?>" />
                    <label for="<?php echo $type; ?>"><?php echo $title; ?></label><br />
                    <?php endforeach; ?>
                </div>
            </li>
        </ol>
</fieldset>
<fieldset>
	<legend>Event Announcement Submission</legend>
    <p>Pull in the event form.</p>
</fieldset>
<p class="submit"><input type="submit" name="submit" value="Submit" /></p>
</form>

<script type="text/javascript">
//WDN.loadCSS('http://www.unl.edu/wdn/templates_3.0/css/content/forms.css');
WDN.jQuery(document).ready(function() { 
	alert("yeah");
	WDN.jQuery('legend span').width(
			WDN.jQuery(this).parent('form').width();
	);
});
</script>