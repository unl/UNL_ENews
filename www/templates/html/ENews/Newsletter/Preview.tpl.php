<?php
$cacheBust = uniqid();
$savvy->loadScriptDeclaration("
    require(['jquery', 'jqueryui'], function($) {
        $(\"#releaseDate\").datepicker({
            showOn: 'both',
            buttonImage: '" . UNL_ENews_Controller::getURL(). "css/images/x-office-calendar.png',
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true
        });
        require([\"" . UNL_ENews_Controller::getURL() . "js/preview.js?ver=" . $cacheBust . "\"], function(preview){
            preview.initialize();
        });
    });");
?>

<div id="newsletterDetails" class="dcf-grid dcf-col-gap-vw dcf-pt-4 unl-bg-lighter-gray">
   <form class="dcf-col-100% dcf-col-67%-start@md" method="post" action="<?php echo $context->getURL(); ?>" id="detailsForm">
       <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
       <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
       <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
       <ol style="margin-top:0">
           <li>
               <input type="hidden" name="_type" value="newsletter" />
               <input type="hidden" name="id" id="id" value="<?php echo $context->newsletter->id; ?>" />
               <label class="dcf-label" for="emailSubject">Email Subject <span class="helper">(Include story keywords!)</span><small class="dcf-required">Required</small></label>
               <input class="dcf-input-text" name="subject" type="text" value="<?php echo $context->newsletter->subject; ?>" id="emailSubject" />
           </li>
           <li>
               <label class="dcf-label" for="releaseDate">Release Date <span class="helper">(Will be sent at 7:00 am)</span></label>
               <input class="datepicker" name="release_date" type="text" size="10" value="<?php echo str_replace(' 00:00:00', '', $context->newsletter->release_date); ?>" id="releaseDate" />
           </li>
       </ol>
       <input class="dcf-btn dcf-btn-primary" type="submit" name="submit" value="Save" />
       <a class="dcf-btn dcf-btn-secondary action preview" href="#" onclick="WDN.initializePlugin('modal', [function() {WDN.jQuery(this).colorbox({inline:true, href:'#sendPreview<?php echo $context->newsletter->id; ?>', open:true, width:'30%', title:'Send Newsletter Preview'}); return false;}]);">Send Preview</a>
   </form>
   <?php echo $savvy->render($context->newsletter, 'ENews/Newsletter/SendPreviewForm.tpl.php'); ?>
   <div class="email_addresses dcf-col-100% dcf-col-33%-end@md">
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
               <form class="emailIndicator">
                   <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
                   <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
                   <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
                   <input class="dcf-input-control" type="checkbox" id="email_<?php echo $email->id; ?>" <?php if ($checked) echo 'checked="checked"'; ?> />
                   <label class="dcf-label" for="email_<?php echo $email->id; ?>" >
                       <?php echo $email->email; ?>
                   </label>
               </form>
               <form action="<?php echo $context->getURL(); ?>" method="post" class="remove email">
                   <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
                   <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
                   <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
                   <input type="hidden" name="newsletter_id" value="<?php echo $context->newsletter->id; ?>" />
                   <input type="hidden" name="newsroom_email_id" value="<?php echo $email->id; ?>" />
                   <input type="hidden" name="_type" value="removenewsletteremail" />
                   <input class="dcf-btn" type="submit" value="Remove" />
               </form>
               <form action="<?php echo $context->getURL(); ?>" method="post" class="add email">
                   <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
                   <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
                   <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
                   <input type="hidden" name="newsletter_id" value="<?php echo $context->newsletter->id; ?>" />
                   <input type="hidden" name="newsroom_email_id" value="<?php echo $email->id; ?>" />
                   <input type="hidden" name="_type" value="addnewsletteremail" />
                   <input class="dcf-btn" type="submit" value="Add" />
               </form>
           </li>
           <?php endforeach; ?>
       </ul>
    </div>
</div>
<div class="dcf-grid dcf-col-gap-vw">
    <div class="dcf-col-100% dcf-col-33%-start@md" id="drag_story_list">
    <?php $stories = $context->getRaw('available_stories'); ?>
    <?php foreach (array('news', 'event', 'ad') as $type): ?>
        <div id="<?php echo $type; ?>Available">
            <h3><?php echo ucfirst($type); ?> <span>Submissions</span><a href="#" class="showHide">Hide</a></h3>
            <div class="storyItemWrapper">
                <?php echo $savvy->render($stories->setType($type)); ?>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <div class="dcf-col-100% dcf-col-67%-end@md">
        <?php $context->newsletter->options = array('preview' => true); ?>
        <?php echo $context->newsletter->getRawObject()->getEmailHTML(); ?>
    </div>
</div>
