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
    WDN.loadJS("/wdn/templates_3.0/scripts/plugins/ui/jQuery.ui.js");
    WDN.loadJS("<?php echo UNL_ENews_Controller::getURL();?>js/functions.js");
    WDN.loadJS("<?php echo UNL_ENews_Controller::getURL();?>js/submission.js");
    WDN.loadJS("<?php echo UNL_ENews_Controller::getURL();?>js/ajaxfileupload.js");
    WDN.loadJS("<?php echo UNL_ENews_Controller::getURL();?>js/jquery.textarearesizer.compressed.js");
    WDN.loadCSS("<?php echo UNL_ENews_Controller::getURL();?>css/imgareaselect-default.css");
    WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css");

    <?php $id = getValue($context,'id'); //Set up the form for editing if an id is specified ?>
    WDN.jQuery(document).ready(function($){
        $('textarea.resizable:not(.processed)').TextAreaResizer();
        <?php if (!empty($id)) : ?>
        (function(){
            setTimeout(function(){
                $('#newsAnnouncement').click();
                $('#enewsSubmissionButton').show();
                document.enewsSubmission.storyid.value = document.enewsImage.storyid.value = <?php echo $id;?>;
            },100);
        })();
        <?php endif; ?>
    });
</script>

<div id="enewsForm">



<h3 class="highlighted"><span>1</span>Select News Type</h3>
<form id="enewsStep1" name="enewsStep1" class="enews energetic" action="#" method="post" enctype="multipart/form-data">
<fieldset id="wdn_process_step1">
    <legend>Select News Type</legend>
    <ol class="option_step">
        <li><a href="#" id="newsAnnouncement">Is this a News announcement?</a></li>
        <li><a href="#" id="eventAnnouncement">Is this an Event announcement?</a></li>
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
<form id="enewsSubmission" name="enewsSubmission" class="enews energetic" action="?view=submit" method="post" enctype="multipart/form-data">
<fieldset id="wdn_process_step3" style="display:none;">
    <legend><span>News Announcement Submission</span></legend>
    <?php //Story id if we are editing ?>
    <input type="hidden" id="storyid" name="storyid" value="<?php echo getValue($context, 'id'); ?>" />
    <input type="hidden" name="_type" value="story" />
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
                    $original_image = null;
                ?>
                <?php if (!empty($id)) :
                            $original_image = UNL_ENews_Story::getByID($id)->getFileByUse('originalimage');
                            ?>
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
             <span id="addAnotherNewsroom"><span>+</span> Add another newsroom to submit to</span>
            </fieldset>
            </li>
            <li>
            <label for="file_description">Image Caption<span class="helper">The Description of the image</span></label>
            <?php
            $disabled = 'disabled="disabled"';
            
            if (isset($original_image)) {
                $disabled = '';
            }
            ?>
            <input id="file_description" name="originalimage_description" <?php echo $disabled; ?> type="text" value="<?php echo getValue($original_image, 'description'); ?>" />
            </li>
        </ol>
</fieldset>
<fieldset id="wdn_process_step3b" style="display:none;">
    <legend>Event Announcement Submission</legend>
    <p>Pull in the event form.</p>
</fieldset>

<div id="enewsSubmissionButton" style="display:none;margin:20px 0;padding-bottom:20px;clear:both;"><input type="submit" name="submit" value="Submit" /></div>
</form>







<div id="sampleLayout" style="display:none;">
    <h5>Preview of Your Submission</h5>
    <div id="sampleLayoutInner">
    <h4>&lt;Enter Your Title&gt;</h4>
    <div id="sampleLayoutImage" style="float:left;margin-right:5px;background:#f7f7f7;padding:5px;border:1px solid #ededed;font-size:.8em;line-height:1em;text-align:center;">
        <?php if ($id = getValue($context,"id")) {
                if ($image = UNL_ENews_Story::getByID($id)->getFileByUse('thumbnail')) { ?>
                    <img src="<?php echo UNL_ENews_Controller::getURL().'?view=file&id='.$image->id; ?>" alt="Image to accompany story submission" />                 
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
