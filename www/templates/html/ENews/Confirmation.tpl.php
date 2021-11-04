<?php
// Should we show a preview of the submission
$preview = false;
$savvy->loadScriptDeclaration("
// This plugin is only needed for the demo.
WDN.initializePlugin('notice');
");
?>
<div class="dcf-notice dcf-notice-success" hidden>
    <?php if ($context->options['_type'] == 'sendnews'): ?>
        <h2>Oh yeah! Distribution Successful!</h2>
        <div>
            <p>Your newsletter has been submitted to the distribution queue.</p>
        </div>
    <?php elseif ($context->options['_type'] == 'unsubscribe'): ?>
        <h2>Congratulations!</h2>
        <div>
            <p>You have been unsubscribed from the newsletter and will therefore no longer receive it in your inbox.</p>
        </div>
    <?php elseif ($context->options['_type'] == 'subscribed'): ?>
        <h2>Congratulations!</h2>
        <div>
            <p>You have been subscribed to the newsletter! Keep a look out for our next newsletter to arrive in your inbox.</p>
        </div>
    <?php else:
        $preview = true; ?>
        <h2>Thanks for your submission!</h2>
        <div>
            <p>Your article is now in our queue. We will review, adapt and incorporate to the best of our abilities. If we have any questions, we'll contact you. If you have any questions, please contact us.</p>
            <?php if (!empty($context->options['newsroom'])): ?>
            <h2 class="dcf-mt-4 dcf-mb-2 dcf-txt-h6" style="color: var(--bg-white);">Have more news you'd like to share?</h2>
            <p><a href="<?php echo UNL_ENews_Newsroom::getById($context->options['newsroom'])->getSubmitURL(); ?>">Submit another story&hellip;</a></p>
            <?php endif; ?>
        </div>
    <?php endif;?>
</div>
<?php
if ($preview) {
    echo $savvy->render($context, 'ENews/Confirmation/Submission.tpl.php');
}
?>