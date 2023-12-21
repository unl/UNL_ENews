<?php
$cacheBust = uniqid();
$savvy->loadScriptDeclaration("
    require(['jquery', 'jqueryui'], function($) {
        require([\"" . UNL_ENews_Controller::getURL() . "js/preview.js?ver=" . $cacheBust . "\"], function(preview){
            preview.initialize();
        });
    });");

$savvy->loadScriptDeclaration("
// This plugin is only needed for the demo.
WDN.initializePlugin('notice');");
?>

<div id="newsletterDetails" class="dcf-grid dcf-col-gap-vw dcf-pt-4">
   <form class="dcf-form dcf-col-100% dcf-col-67%-start@md unl-bg-lightest-gray dcf-rounded dcf-p-4" method="post" action="<?php echo $context->getURL(); ?>" id="detailsForm">
        <h5 class="dcf-txt-base">Newsletter Details:</h5>
    <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
       <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
       <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
       <input type="hidden" name="_type" value="newsletter" />
       <input type="hidden" name="id" id="id" value="<?php echo $context->newsletter->id; ?>" />
        <div class="dcf-form-group">
            <label class="dcf-label" for="emailSubject">Email Subject <span class="dcf-form-help">(Include story keywords!)</span><small class="dcf-required">Required</small></label>
            <input class="dcf-input-text" name="subject" type="text" value="<?php echo $context->newsletter->subject; ?>" id="emailSubject" />
        </div>
        <fieldset style="width: fit-content;" aria-describedby="central-time-help">
            <legend>Release Date &amp; Time (Central Time)</legend>
            <div class="dcf-form-group dcf-datepicker" style="width: 30ch;">
                <label class="dcf-label" for="releaseDate">Date</label>
                <input name="release_date" type="text" size="10" value="<?php if (!empty($context->newsletter->release_date)) { echo date('n/j/Y', strtotime($context->newsletter->release_date)); } ?>" id="releaseDate" autocomplete="off" />
            </div>
            <div>
                <div class="dcf-d-flex dcf-ai-center dcf-flex-grow-1">
                    <div class="dcf-form-group">
                        <?php
                            $release_hour = '07';
                            if (!empty($context->newsletter->release_date)) {
                                $release_hour = date('h', strtotime($context->newsletter->release_date));
                            }
                        ?>
                        <label for="release_date_hour">Hour</label>
                        <select
                            class="dcf-flex-grow-1"
                            id="release_date_hour"
                            name="release_date_hour"
                        >
                            <?php for ($i = 1; $i <= 12; $i++) { ?>
                                <option
                                    <?php if ($release_hour == $i) { echo 'selected="selected"'; } ?>
                                    value="<?php echo $i; ?>"
                                >
                                    <?php echo $i; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <span class="dcf-pr-1 dcf-pl-1">:</span>
                    <div class="dcf-form-group">
                        <?php
                            $release_minute = '00';
                            if (!empty($context->newsletter->release_date)) {
                                $release_minute = date('i', strtotime($context->newsletter->release_date));
                            }
                        ?>
                        <label for="release_date_minute">Minute</label>
                        <select
                            class="dcf-flex-grow-1"
                            id="release_date_minute"
                            name="release_date_minute"
                        >
                        <option
                            value="00"
                            <?php if ($release_minute == '00') { echo 'selected="selected"'; } ?>
                        >00</option>
                        <option
                            value="30"
                            <?php if ($release_minute == '30') { echo 'selected="selected"'; } ?>
                        >30</option>
                        </select>
                    </div>
                    <fieldset
                        class="dcf-d-flex
                            dcf-flex-col
                            dcf-row-gap-3
                            dcf-ai-center
                            dcf-col-gap-3
                            dcf-mb-0
                            dcf-ml-4
                            dcf-p-0
                            dcf-b-0
                            dcf-txt-sm"
                        id="start-time-am-pm"
                    >
                        <?php
                            $release_am_pm = 'am';
                            if (!empty($context->newsletter->release_date)) {
                                $release_am_pm = date('a', strtotime($context->newsletter->release_date));
                            }
                        ?>
                        <legend class="dcf-sr-only">AM/PM</legend>
                        <div class="dcf-input-radio dcf-mb-0">
                            <input
                                id="release_date_am_pm_am"
                                name="release_date_am_pm"
                                type="radio"
                                value="am"
                                <?php if ($release_am_pm == 'am') { echo 'checked="checked"'; } ?>
                            >
                            <label class="dcf-mb-0" for="release_date_am_pm_am">AM</label>
                        </div>
                        <div class="dcf-input-radio dcf-mb-0">
                            <input
                                id="release_date_am_pm_pm"
                                name="release_date_am_pm"
                                type="radio"
                                value="pm"
                                <?php if ($release_am_pm == 'pm') { echo 'checked="checked"'; } ?>
                            >
                            <label class="dcf-mb-0" for="release_date_am_pm_pm">PM</label>
                        </div>
                    </fieldset>
                </div>
                
            </div>
        </fieldset>
        <div class="dcf-input-checkbox dcf-mb-4">
            <input
                class="dcf-checkbox"
                type="checkbox"
                name="ready_to_release"
                id="ready_to_release"
                value="1"
                aria-describedby="ready_to_release_help"
                <?php if (isset($context->newsletter->ready_to_release) && $context->newsletter->ready_to_release === '1') { echo 'checked="checked"'; }?>
            >
            <label for="ready_to_release">Ready to distribute</label>
            <span id="ready_to_release_help" class="dcf-form-help dcf-d-block">
                The newsletter will not be distributed unless it is marked ready to distribute.
            </span>
        </div>
       <input class="dcf-btn dcf-btn-primary" type="submit" name="submit" value="Save" />
       <a class="dcf-btn dcf-btn-secondary action preview" href="#" onclick="WDN.initializePlugin('modal', [function() {WDN.jQuery(this).colorbox({inline:true, href:'#sendPreview<?php echo $context->newsletter->id; ?>', open:true, width:'30%', title:'Send Newsletter Preview'}); return false;}]);">Send Preview</a>
   </form>
   <?php echo $savvy->render($context->newsletter, 'ENews/Newsletter/SendPreviewForm.tpl.php'); ?>
   <div class="dcf-txt-sm dcf-col-100% dcf-col-33%-end@md unl-bg-lightest-gray dcf-rounded dcf-p-4">
       <h5 class="dcf-txt-base">Distribute this newsletter to:</h5>
       <ul class="dcf-list-bare">
           <?php
           $existing_emails = $context->newsletter->getEmails()->getArrayCopy();
           foreach ($context->newsletter->newsroom->getEmails() as $email):
               $checked = false;
               if (in_array($email->id, $existing_emails)) {
                   $checked = true;
               }
           ?>
           <li>
               <form class="dcf-form emailIndicator">
                   <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
                   <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
                   <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
                   <div class="dcf-input-checkbox">
                       <input type="checkbox" id="email_<?php echo $email->id; ?>" <?php if ($checked) echo 'checked="checked"'; ?> />
                       <label for="email_<?php echo $email->id; ?>" >
                           <?php echo $email->email; ?>
                       </label>
                   </div>
               </form>
               <form action="<?php echo $context->getURL(); ?>" method="post" class="dcf-form remove email">
                   <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
                   <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
                   <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
                   <input type="hidden" name="newsletter_id" value="<?php echo $context->newsletter->id; ?>" />
                   <input type="hidden" name="newsroom_email_id" value="<?php echo $email->id; ?>" />
                   <input type="hidden" name="_type" value="removenewsletteremail" />
                   <input class="dcf-btn dcf-btn-primary" type="submit" value="Remove" />
               </form>
               <form action="<?php echo $context->getURL(); ?>" method="post" class="dcf-form add email">
                   <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
                   <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
                   <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
                   <input type="hidden" name="newsletter_id" value="<?php echo $context->newsletter->id; ?>" />
                   <input type="hidden" name="newsroom_email_id" value="<?php echo $email->id; ?>" />
                   <input type="hidden" name="_type" value="addnewsletteremail" />
                   <input class="dcf-btn dcf-btn-primary" type="submit" value="Add" />
               </form>
           </li>
           <?php endforeach; ?>
       </ul>
    </div>
</div>
<div class="dcf-grid dcf-col-gap-vw">
    <div class="dcf-col-100% dcf-col-33%-start@md" id="drag_story_list">
        <div id="againAvailable">
            <div class="storyItemWrapper">
            </div>
        </div>
        <h3 class="dcf-txt-sm dcf-mt-0">New items</h3>
        <div id="drag_story_list_unpublished">
            <?php $stories = $context->getRaw('unpublished_stories'); ?>
            <?php foreach (array('news', 'event', 'ad') as $type): ?>
                <div class="<?php echo $type; ?>Available">
                    <div class="storyItemWrapper">
                        <?php echo $savvy->render($stories->setType($type)); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <h3 class="dcf-txt-sm">Previously used items</h3>
        <div id="drag_story_list_reusable">
            <?php $stories = $context->getRaw('reusable_stories'); ?>
            <?php foreach (array('news', 'event', 'ad') as $type): ?>
                <div class="<?php echo $type; ?>Available">
                    <div class="storyItemWrapper">
                        <?php echo $savvy->render($stories->setType($type)); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="dcf-col-100% dcf-col-67%-end@md">
        <?php $context->newsletter->options = array('preview' => true); ?>
        <?php echo $context->newsletter->getRawObject()->getEmailHTML(); ?>
    </div>
</div>
