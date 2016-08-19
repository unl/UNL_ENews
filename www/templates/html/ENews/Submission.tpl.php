<?php
function getValue($object, $field) {
    if (isset($object->$field)) {
        if ($object instanceof Savvy_ObjectProxy) {
            $value = $object->getRaw($field);
        } else {
            $value = $object->$field;
        }
        return htmlentities($value, ENT_QUOTES, 'UTF-8');
    }

    if (isset($_POST[$field])) {
        return htmlentities($_POST[$field], ENT_QUOTES, 'UTF-8');
    }

    return '';
}
?>
<script>
    var ENEWS_ALLOWED_TAGS_DESCRIPTION = <?php echo json_encode(UNL_ENews_Controller::$js_allowed_tags_description); ?>;
    var ENEWS_ALLOWED_ATTR_DESCRIPTION = <?php echo json_encode(UNL_ENews_Controller::$js_allowed_attr_description); ?>;
    var ENEWS_DEFAULT_PRESENTATIONID = {
        <?php foreach ($types = array('news', 'event', 'ad') as $i => $type): ?>
        "<?php echo $type; ?>":"<?php echo UNL_ENews_Story_Presentation::getDefault($type)->id; ?>"<?php echo ($i < count($types) - 1) ? ',' : ''; ?>
        <?php endforeach; ?>
    };
</script>
<script src="<?php echo UNL_ENews_Controller::getURL();?>js/ajaxfileupload.js"></script>
<script>
    require(["<?php echo UNL_ENews_Controller::getURL();?>js/purify.js", "<?php echo UNL_ENews_Controller::getURL();?>js/submission.js"],
        function(DOMPurify, submission){

            <?php $id = getValue($context,'id'); //Set up the form for editing if an id is specified ?>
            <?php if (!empty($id)) : ?>
            <?php switch(UNL_ENews_Story_Presentation::getByID($context->presentation_id)->type) :
            case 'news' : ?>
            submission.editType = 'news';
            <?php break; ?>
            <?php case 'event' : ?>
            submission.editType = 'event';
            <?php break; ?>
            <?php case 'ad' : ?>
            submission.editType = 'ad';
            <?php break; ?>
            <?php endswitch; ?>
            <?php endif; ?>

            submission.initialize();
    });
    WDN.loadCSS("<?php echo UNL_ENews_Controller::getURL();?>css/imgareaselect-default.css");
</script>

<div id="enewsForm">

    <?php
    $showAdClass = '';
    if (UNL_ENews_Controller::getUser()->hasNewsroomPermission()) {
        $showAdClass = 'showAd';
    }
    ?>

    <h3 class="highlighted wdn-brand"><span>1</span>Select News Type</h3>
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


    <h3 class="wdn-brand"><span>2</span>Enter Date Details for Event</h3>
    <form id="enewsStep2" name="enewsStep2" class="enews energetic" action="#" method="post" enctype="multipart/form-data">
    <fieldset id="wdn_process_step2" style="display:none;">
        <legend><span>Enter Date Details for Event</span></legend>
            <ol>
                <li>
                    <label for="date"><span class="required">*</span>Date of Event</label>
                    <input class="datepicker" id="date" name="date" type="text" value="<?php echo getValue($context, 'request_publish_end'); ?>" />
                </li>
                <li>
                    <label for="event"><span class="required">*</span>Which Event?<span class="helper">These are your events, as found at http://events.unl.edu/</span></label>
                    <select id="event">
                        <option value="NewEvent">New Event</option>

                    </select>
                </li>
            </ol>
            <p class="nextStep"><a href="#" id="next_step3">Continue</a></p>
    </fieldset>
    </form>

    <h3 class="wdn-brand"><span>3</span>Announcement Submission</h3>

    <div class="wdn-grid-set">
        <div class="wdn-col-one-half">
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

                    <?php
                    $full_article_rows = 5;
                    if (isset($context->full_article)) {
                        $full_article_rows = count(explode("\n", $context->full_article));
                        if ($full_article_rows < 5) {
                            // Go back to default size
                            $full_article_rows = 5;
                        }
                        if ($full_article_rows > 15) {
                            // Too many, just show 15
                            $full_article_rows = 15;
                        }
                    }
                    ?>
                    <ol>
                        <li><label for="title"><span class="required">*</span> Headline or Title</label><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" class="required" /></li>
                        <li><label for="description"><span class="required">*</span> Summary<span class="helper">You have <strong>300</strong> characters remaining.</span></label><textarea id="description" name="description" cols="60" rows="5" class="required"><?php echo getValue($context, 'description'); ?></textarea></li>
                        <li><label for="full_article">Full Article<span class="helper">For news releases, departmental news feeds, etc...</span></label><textarea id="full_article" name="full_article" cols="60" rows="<?php echo $full_article_rows; ?>"><?php echo getValue($context, 'full_article'); ?></textarea></li>
                        <li><label for="request_publish_start"><span class="required">*</span> What date would like this to run?</label><input class="datepicker required" id="request_publish_start" name="request_publish_start" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_start')); ?>" /></li>
                        <li><label for="request_publish_end"><span class="required">*</span> Last date this could run</label><input class="datepicker required" id="request_publish_end" name="request_publish_end" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_end')); ?>" /></li>
                        <li><label for="website">Supporting Website</label><input id="website" name="website" type="url" value="<?php echo getValue($context, 'website'); ?>" /></li>
                        <li><label for="sponsor"><span class="required">*</span> Sponsoring Unit</label><input id="sponsor" name="sponsor" type="text" value="<?php if(getValue($context, 'title') == ''){echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment;}else{echo getValue($context, 'sponsor');}?>" class="required" /></li>
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
                                        <option selected="selected" value=""></option>
                                        <?php foreach (UNL_ENews_Submission::getOpenNewsrooms() as $item): ?>
                                            <?php
                                            /* @var $item UNL_ENews_Newsroom */
                                            if (!$item->hasStory($context->getRawObject()->getStory())) :
                                            ?>
                                            <option value="<?php echo $item->id;?>"><?php echo $item->name;?></option>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                     </select>
                                </div>
                            <?php else : ?>
                                <div id="newsroom_id_dropdown">
                                    <select name="newsroom_id[]">
                                        <?php if ($newsroom_id == 1) : ?>
                                        <option value="1">Nebraska Today and other UComm publications</option>
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

        </div>
        <div class="wdn-col-one-half">

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

                <div id="supporting_website"></div>

                <div class="clear"></div>
                </div>
            </div>

            <?php include(dirname(__FILE__) . '/Submission/ImageForm.tpl.php'); ?>
            <?php include(dirname(__FILE__) . '/Submission/DeleteImagesForm.tpl.php'); ?>

        </div>
    </div>
    <div class="clear"></div>
    <?php //ending div for #enewsForm ?>
</div>
