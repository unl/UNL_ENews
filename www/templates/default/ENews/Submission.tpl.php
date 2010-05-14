<?php
function getValue($object, $field)
{
    if (isset($object->$field)) {
        return htmlentities($object->getRaw($field), ENT_QUOTES);
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
    WDN.loadJS("js/submission.js");
    WDN.loadJS("js/ajaxfileupload.js");
    WDN.loadCSS("css/imgareaselect-default.css");
    WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css");

    <?php //Set up the form for editing if an id is specified
          $id = getValue($context,'id'); ?>
    <?php if (!empty($id)) : ?>
    WDN.jQuery(document).ready(function($){
        (function(){
            setTimeout(function(){
                $('#newsAnnouncement').click();
                $('#enewsSubmissionButton').show();
                document.enewsSubmission.storyid.value = document.enewsImage.storyid.value = <?php echo $id;?>;
            },100);
        })();
    });
    <?php endif; ?>
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
            <li><label for="title">Headline or Title<span class="required">*</span></label><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" /></li>
            <li><label for="description">Summary<span class="required">*</span><span class="helper">You have <strong>300</strong> characters remaining.</span></label><textarea id="description" name="description" cols="60" rows="5"><?php echo getValue($context, 'description'); ?></textarea></li>
            <li><label for="full_article">Full Article<span class="helper">For news releases, departmental news feeds, etc...</span></label><textarea id="full_article" name="full_article" cols="60" rows="5"><?php echo getValue($context, 'full_article'); ?></textarea></li>
            <li><label for="request_publish_start">What date would like this to run?<span class="required">*</span></label><input class="datepicker" id="request_publish_start" name="request_publish_start" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_start')); ?>" /></li>
            <li><label for="request_publish_end">Last date this could run<span class="required">*</span></label><input class="datepicker" id="request_publish_end" name="request_publish_end" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_end')); ?>" /></li>
            <li><label for="website">Supporting Website</label><input id="website" name="website" type="text" value="<?php echo getValue($context, 'website'); ?>" /></li>
            <li><label for="sponsor">Sponsoring Unit<span class="required">*</span></label><input id="sponsor" name="sponsor" type="text" value="<?php echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment; ?>" /></li>
            <?php if ($context->newsroom->id != 1) : ?>
            <li>
                <fieldset>
                    <legend>Please consider for</legend>
                    <ol>
                    <li> 
                        <input type="checkbox" name="newsroom_id[]" value="<?php echo (int)$context->newsroom->id; ?>" checked="checked" />
                        <label for="newsroom_id[]"><?php echo $context->newsroom->name; ?></label>
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





<form id="enewsImage" name="enewsImage" class="enews energetic" action="?view=submit" method="post" enctype="multipart/form-data" style="display:none;">
<input type="hidden" name="_type" value="file" />

<?php //Story id that gets attached when the story is submitted above ?>
<input type="hidden" id="storyid" name="storyid" value="" /> 

<fieldset>
            <ol style="padding:0;margin-top:0;"><li>
            <label for="image">Image<span class="helper">To be displayed with your announcement</span></label>
            <input id="image" name="image" type="file" />
            </li></ol>
            
            <div id="upload_area">
            <?php if ($id = getValue($context,"id")) { ?>
                    <?php if ($image = UNL_ENews_Story::getByID($id)->getFileByUse('originalimage')) { ?>
                            <img src="<?php echo UNL_ENews_Controller::getURL().'?view=file&id='.$image->id; ?>" alt="Image to accompany story submission" />
                            <span><script type="text/javascript">document.write(ajaxUpload.message);</script></span>
                            <script type="text/javascript">WDN.loadJS("js/jquery.imgareaselect.pack.js",function(){setImageCrop();},true,true);</script>
                    <?php } ?>
            <?php } else { ?>
                    <div>Image preview</div>
            <?php }  ?>
            </div>
</fieldset>
</form>



<?php //ending div for #enewsForm ?>
<div class="clear"></div>
</div>