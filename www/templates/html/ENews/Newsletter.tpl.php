<?php
UNL_ENews_PostRunFilter::setReplacementData('pagetitle', $context->subject);
UNL_ENews_PostRunFilter::setReplacementData('sitetitle', $context->newsroom->name);
if (isset($_GET['_type']) && $_GET['_type'] == 'subscribed') : ?>
<?php
$savvy->loadScriptDeclaration("
// This plugin is only needed for the demo.
WDN.initializePlugin('notice');");
?>
<div class="dcf-notice dcf-notice-success" hidden>
    <h2>Almost there!</h2>
    <div>
        <p>We have received your subscription request. An email has been sent to your address asking you to confirm. Simply click the confimation link in
        that email, and you'll be set.</p>
    </div>
</div>
<?php
endif;
?>
<?php if (strtotime($context->release_date) < strtotime('-1 year')): ?>
<div class="unl-bg-scarlet unl-cream dcf-rounded dcf-p-3 dcf-mb-3">
    <strong>Archived Newsletter:</strong> This newsletter is part of our newsletter archives. It has
    been preserved for reference, but the information may no longer be current.
</div>
<?php endif ?>
<section class="dcf-grid dcf-col-gap-vw">
    <div class="dcf-col-100% dcf-col-67%-start@md">
        <div id="newsletterWeb">
            <?php echo $savvy->render($context->getStories(), 'ENews/Newsletter/StoriesWeb.tpl.php'); ?>

            <div style="clear:both;display:block;text-align:center;font-size:.8em;border-top:1px solid #E0E0E0;margin-top:5px;padding-top:5px">
                Originally published <?php echo date('F j, Y', strtotime($context->release_date)); ?>
                -
                <a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit an Item</a>
            </div>
        </div>
    </div>
    <div class="dcf-col-100% dcf-col-33%-end@md">
        <?php echo $savvy->render($context, 'ENews/Newsletter/SidebarNav.tpl.php'); ?>
    </div>
</section>
