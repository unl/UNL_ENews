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


$enewsDefaultPresentationID = '';
foreach ($types = array('news', 'event', 'ad') as $i => $type) {
  $enewsDefaultPresentationID .=  '"' . $type . '":"' . UNL_ENews_Story_Presentation::getDefault($type)->id . '"';
  $enewsDefaultPresentationID .= ($i < count($types) - 1) ? ',' : '';
}
$savvy->loadScriptDeclaration('
        var ENEWS_ALLOWED_TAGS_DESCRIPTION = '. json_encode(UNL_ENews_Controller::$js_allowed_tags_description) . ';
        var ENEWS_ALLOWED_ATTR_DESCRIPTION = '. json_encode(UNL_ENews_Controller::$js_allowed_attr_description) . ';
        var ENEWS_DEFAULT_PRESENTATIONID = { ' . $enewsDefaultPresentationID . '};
     ');

$savvy->loadScript(UNL_ENews_Controller::getURL() . "js/ajaxfileupload.js");

$id = getValue($context,'id'); //Set up the form for editing if an id is specified
$submissionEditType = "";
if (!empty($id)) {
  switch (UNL_ENews_Story_Presentation::getByID($context->presentation_id)->type) {
    case 'news':
      $submissionEditType = "submission.editType = 'news';";
      break;

    case 'event':
      $submissionEditType = "submission.editType = 'event';";
      break;

    case 'ad':
      $submissionEditType = "submission.editType = 'ad';";
      break;
  }
}
$cacheBust = uniqid();
$savvy->loadScriptDeclaration('
    require(["'. UNL_ENews_Controller::getURL() . 'js/purify.js?ver=' . $cacheBust . '", "' . UNL_ENews_Controller::getURL() . 'js/submission.js?ver=' . $cacheBust . '"],
        function(DOMPurify, submission){
            ' . $submissionEditType . '
            submission.initialize();
    });
    WDN.loadCSS("' . UNL_ENews_Controller::getURL(). 'css/imgareaselect-default.css?ver=' . $cacheBust . '");
');

?>

<div class="dcf-grid-full dcf-grid-halves@md dcf-col-gap-vw">
    <div>
        <form id="enewsSubmission" name="enewsSubmission" class="dcf-form enews energetic" action="#" method="post" enctype="multipart/form-data">
            <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
            <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
            <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">

            <?php //Story id if we are editing ?>
            <input type="hidden" id="storyid" name="storyid" value="<?php echo getValue($context, 'id'); ?>" />
            <input type="hidden" name="_type" value="story" />
            <input type="hidden" id="presentation_id" name="presentation_id" value="<?php echo !empty(getValue($context, 'presentation_id')) ? getValue($context, 'presentation_id') : UNL_ENews_Story_Presentation::getDefault('news')->id; ?>" />

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
                <li>
                  <label class="dcf-label" for="title"><span class="dcf-required">*</span> Headline or Title</label>
                  <input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" class="dcf-input-text required" />
                </li>
                <li>
                  <label class="dcf-label" for="description"><span class="dcf-required">*</span> Summary <span class="helper">You have <strong>300</strong> characters remaining:</span></label>
                  <textarea id="description" name="description" cols="60" rows="5" class="dcf-input-text required"><?php echo getValue($context, 'description'); ?></textarea>
                </li>
                <li>
                  <label class="dcf-label" for="full_article">Full Article <span class="helper">For news releases, departmental news feeds, etc...</span></label>
                  <textarea id="full_article" name="full_article" cols="60" rows="<?php echo $full_article_rows; ?>"><?php echo getValue($context, 'full_article'); ?></textarea>
                </li>
                <li>
                  <label class="dcf-label" for="request_publish_start"><span class="dcf-required">*</span> What date would like this to run?</label>
                  <input class="datepicker required" id="request_publish_start" name="request_publish_start" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_start')); ?>" />
                </li>
                <li>
                  <label class="dcf-label" for="request_publish_end"><span class="dcf-required">*</span> Last date this could run</label>
                  <input class="datepicker required" id="request_publish_end" name="request_publish_end" type="text" size="10"  value="<?php echo str_replace(' 00:00:00', '', getValue($context, 'request_publish_end')); ?>" />
                </li>
                <li>
                  <label class="dcf-label" for="website">Supporting Website</label>
                  <input class="dcf-input-text" id="website" name="website" type="url" value="<?php echo getValue($context, 'website'); ?>" />
                </li>
                <li>
                  <label class="dcf-label" for="sponsor"><span class="dcf-required">*</span> Sponsoring Unit</label>
                  <input id="sponsor" name="sponsor" type="text" value="<?php if(getValue($context, 'title') == ''){echo UNL_ENews_Controller::getUser()->unlHRPrimaryDepartment;}else{echo getValue($context, 'sponsor');}?>" class="dcf-input-text required" />
                </li>
                <li>
                <fieldset id="newsroom_id">
                    <legend class="dcf-legend">Please consider for</legend>
                    <?php
                       $id = getValue($context, 'id');
                       $newsroom_id = getValue($context->newsroom, 'id');
                       $newsroom_name = getValue($context->newsroom, 'name');
                    ?>
                    <?php if (!empty($id)) : ?>
                        <?php foreach (UNL_ENews_Story::getByID($id)->getNewsrooms() as $item) : ?>
                            <select class="dcf-input-select" name="newsroom_id[]" disabled="disabled">
                                <option value=""></option>
                                <option selected="selected" value="<?php echo $item->id;?>"><?php echo $item->name;?></option>
                                <?php foreach (UNL_ENews_Submission::getOpenNewsroomsStatic() as $item2): ?>
                                <?php if ($item2->id != $item->id) : ?>
                                <option value="<?php echo $item2->id;?>"><?php echo $item2->name;?></option>
                                <?php endif ?>
                                <?php endforeach ?>
                            </select>
                        <?php endforeach ?>
                        <div id="newsroom_id_dropdown" style="display:none">
                            <select class="dcf-input-select" name="newsroom_id[]">
                                <option selected="selected" value=""></option>
                                <?php foreach (UNL_ENews_Submission::getOpenNewsroomsStatic() as $item): ?>
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
                            <select class="dcf-input-select" name="newsroom_id[]">
                                <?php if ($newsroom_id == 1) : ?>
                                <option value="1">Nebraska Today and other UComm publications</option>
                                <?php  else : ?>
                                <option value="<?php echo $newsroom_id; ?>"><?php echo $newsroom_name;?></option>
                                <?php endif ?>
                                <?php foreach (UNL_ENews_Submission::getOpenNewsroomsStatic() as $item): ?>
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

            <div id="enewsSubmissionButton" class="submissionButton"><input class="dcf-btn dcf-btn-primary" type="submit" name="submit" value="Submit" /></div>
        </form>

    </div>
    <div>

        <?php include(dirname(__FILE__) . '/Submission/ImageForm.tpl.php'); ?>
        <?php include(dirname(__FILE__) . '/Submission/DeleteImagesForm.tpl.php'); ?>

    </div>
</div>
