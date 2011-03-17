<?php
function getValue($object, $field)
{
    if (isset($object->$field)) {
        if ($object instanceof Savvy_ObjectProxy) {
            $value = $object->getRaw($field);
        } else {
            $value = $object->$field;
        }
        return htmlentities($value, ENT_QUOTES);
    }

    if (isset($_POST[$field])) {
        return htmlentities($_POST[$field], ENT_QUOTES);
    }

    return '';
}
?>
<script type="text/javascript">
var ENEWS_DEFAULT_PRESENTATIONID = {<?php foreach ($types = array('news', 'event', 'ad') as $i => $type): ?>
    "<?php echo $type; ?>":"<?php echo UNL_ENews_Story_Presentation::getDefault($type)->id; ?>"<?php echo ($i < count($types) - 1) ? ',' : ''; ?>
<?php endforeach; ?>
};
<?php $id = getValue($context,'id'); //Set up the form for editing if an id is specified ?>
<?php if (!empty($id)) : ?>
	var storyID = <?php echo $id;?>;
	<?php switch(UNL_ENews_Story_Presentation::getByID($context->presentation_id)->type) :
		case 'news' : ?>
	var editType = 'news';
		<?php break; ?>
		<?php case 'event' : ?>
	var editType = 'event';
		<?php break; ?>
		<?php case 'ad' : ?>
	var editType = 'ad';
		<?php break; ?>
	<?php endswitch; ?>
<?php else :?>
	var editType = false;
<?php endif; ?>
</script>
<script type="text/javascript" src="/wdn/templates_3.0/scripts/plugins/ui/jQuery.ui.js"></script>
<script type="text/javascript" src="<?php echo UNL_ENews_Controller::getURL();?>js/submission.js"></script>
<script type="text/javascript" src="<?php echo UNL_ENews_Controller::getURL();?>js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo UNL_ENews_Controller::getURL();?>js/jquery.textarearesizer.compressed.js"></script>
<script type="text/javascript">
    WDN.loadCSS("<?php echo UNL_ENews_Controller::getURL();?>css/imgareaselect-default.css");
    WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css");
</script>

<div id="enewsForm">

<?php
$showAdClass = '';
if (UNL_ENews_Controller::getUser()->hasNewsroomPermission()) {
    $showAdClass = 'showAd';
}
?>

<h3 class="highlighted"><span>1</span>Select News Type</h3>
<form id="enewsStep1" name="enewsStep1" class="enews energetic <?php echo $showAdClass;?>" action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=submit" method="post" enctype="multipart/form-data">
<fieldset id="wdn_process_step1">
    <legend>Select News Type</legend>
    <ol class="option_step">
        <li><a href="#" id="newsAnnouncement">Is this a News announcement?</a></li>
        <li><a href="#" id="eventAnnouncement">Is this an Event announcement?</a></li>
        <li><a href="#" id="adAnnouncement">Is this an Advertisement?</a></li>
    </ol>
</fieldset>
</form>




<h3><span>2</span>Enter Date Details for Event</h3>
<form id="enewsStep2" name="enewsStep2" class="enews energetic" action="#" method="post" enctype="multipart/form-data">
<fieldset id="wdn_process_step2" style="display:none;">
    <legend><span>Enter Date Details for Event</span></legend>
        <ol>
            <li>
                <label for="date">Date of Event<span class="required">*</span></label>
                <input class="datepicker" id="date" name="date" type="text" value="<?php echo getValue($context, 'request_publish_end'); ?>" />
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
</form>




<h3><span>3</span>Announcement Submission</h3>
<form id="enewsSubmission" name="enewsSubmission" class="enews energetic" action="#" method="post" enctype="multipart/form-data">
<fieldset id="wdn_process_step3" style="display:none;">
    <legend><span>News Announcement Submission</span></legend>
    <?php //Story id if we are editing ?>
    <input type="hidden" id="storyid" name="storyid" value="<?php echo getValue($context, 'id'); ?>" />
    <input type="hidden" name="_type" value="story" />
    <input type="hidden" id="presentation_id" name="presentation_id" value="<?php echo getValue($context, 'presentation_id'); ?>" />
    
    <?php
        $originalImage = null;
        if (!empty($id)) { 
            $originalImage = UNL_ENews_Story::getByID($id)->getFileByUse('originalimage');
        }
     ?>
    <input type="hidden" id="fileID" name="fileID" value="<?php echo getValue($originalImage, 'id'); ?>" />
    <input type="hidden" id="fileDescription" name="fileDescription" value="<?php echo getValue($originalImage, 'description'); ?>" />
    <input type="hidden" id="thumbX1" name="thumbX1" value="-1" />
    <input type="hidden" id="thumbX2" name="thumbX2" value="-1" />
    <input type="hidden" id="thumbY1" name="thumbY1" value="-1" />
    <input type="hidden" id="thumbY2" name="thumbY2" value="-1" />
    
    <ol>
        <li><label for="title">Headline or Title<span class="required">*</span></label><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" class="required" /></li>
        <li><label for="description">Summary<span class="required">*</span><span class="helper">You have <strong>300</strong> characters remaining.</span></label><textarea id="description" name="description" class="resizable" cols="60" rows="5" class="required"><?php echo getValue($context, 'description'); ?></textarea></li>
        <li><label for="full_article">Full Article<span class="helper">For news releases, departmental news feeds, etc...</span></label><textarea id="full_article" name="full_article" class="resizable" cols="60" rows="5"><?php echo getValue($context, 'full_article'); ?></textarea></li>
        <li><label for="request_publish_start">What date would like this to run?<span class="required">*</span></label><input class="datepicker required" id="request_publish_start" name="request_publish_start" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_start')); ?>" /></li>
        <li><label for="request_publish_end">Last date this could run<span class="required">*</span></label><input class="datepicker required" id="request_publish_end" name="request_publish_end" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_end')); ?>" /></li>
        <li><label for="website">Supporting Website</label><input id="website" name="website" type="text" value="<?php echo getValue($context, 'website'); ?>" /></li>
        <li><label for="sponsor">Sponsoring Unit<span class="required">*</span></label><input id="sponsor" name="sponsor" type="text" value="<?php if(getValue($context, 'title') == ''){echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment;}else{echo getValue($context, 'sponsor');}?>" class="required" /></li>
        <li>
        <fieldset id="newsroom_id">
         <legend>Please consider for</legend>
            <?php
                $id = getValue($context, 'id');
                $newsroom_id = getValue($context->newsroom, 'id');
                $newsroom_name = getValue($context->newsroom, 'name');
            ?>
            <?php if (!empty($id)) : ?>
                    <?php foreach (UNL_ENews_Story::getByID($id)->getNewsrooms() as $item) : ?>
                        <select name="newsroom_id[]" disabled="disabled">
                        <option value=""></option>
                        <option selected="selected" value="<?php echo $item->id;?>"><?php echo $item->name;?></option>
                        <?php foreach (UNL_ENews_Submission::getOpenNewsrooms() as $item2): ?>
                            <?php if ($item2->id != $item->id) : ?>
                            <option value="<?php echo $item2->id;?>"><?php echo $item2->name;?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                        </select>
                    <?php endforeach ?>
                <div id="newsroom_id_dropdown" style="display:none">
                    <select name="newsroom_id[]">
                        <option value="1">Today@UNL and other UComm publications (Scarlet, UNL Today, etc)</option>
                        <?php foreach (UNL_ENews_Submission::getOpenNewsrooms() as $item): ?>
                            <?php if ($item->id != 1) : ?>
                            <option value="<?php echo $item->id;?>"><?php echo $item->name;?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </div>
            <?php else : ?>
                <div id="newsroom_id_dropdown">
                    <select name="newsroom_id[]">
                        <?php if ($newsroom_id == 1) : ?>
                        <option value="1">Today@UNL and other UComm publications (Scarlet, UNL Today, etc)</option>
                        <?php  else : ?>
                        <option value="<?php echo $newsroom_id; ?>"><?php echo $newsroom_name;?></option>
                        <?php endif ?>
                        <?php foreach (UNL_ENews_Submission::getOpenNewsrooms() as $item): ?>
                            <?php if ($item->id != $newsroom_id) : ?>
                            <option value="<?php echo $item->id;?>"><?php echo $item->name;?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </div>
            <?php endif ?>
         <span id="addAnotherNewsroom"><span>+</span> Add another newsroom for submission</span>
        </fieldset>
        </li>
    </ol>
</fieldset>
<fieldset id="wdn_process_step3b" style="display:none;">
    <legend>Event Announcement Submission</legend>
    <p>Pull in the event form.</p>
</fieldset>

<div id="enewsSubmissionButton" class="submissionButton" style="display:none;"><input type="submit" name="submit" value="Submit" /></div>
<?php if (false && !empty($id)): ?>
<div id="enewsSaveCopyButton" class="submissionButton" style="display:none;"><input type="submit" name="copy" value="Save As A COPY" /></div>
<?php endif; ?>
</form>







<div id="sampleLayout" style="display:none;">
    <h5>Preview of Your Submission</h5>
    <div id="sampleLayoutInner">
    <h4>&lt;Enter Your Title&gt;</h4>
    <div id="sampleLayoutImage">
        <?php if ($id = getValue($context,"id")) {
                if ($thumbnail = UNL_ENews_Story::getByID($id)->getFileByUse('thumbnail')) { ?>
                    <img src="<?php echo $thumbnail->getURL(); ?>" alt="Image to accompany story submission" />
        <?php   }
              } else { ?>
                &lt;Upload Image&gt;
        <?php }  ?>
    </div>
    <p>&lt;Enter Your Article Text&gt;</p>
    <a href="#"></a>
    <div class="clear"></div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/Submission/ImageForm.tpl.php'); ?>


<?php //ending div for #enewsForm ?>
<div class="clear"></div>
</div>
