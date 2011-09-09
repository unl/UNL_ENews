<?php
// Should we show a preview of the submission
$preview = false;
?>
<script type="text/javascript">
// This plugin is only needed for the demo.
WDN.initializePlugin('notice');
</script>
<div class="wdn_notice affirm">
	<div class="close">
		<a href="#" title="Close this notice">Close this notice</a>
	</div>
	<div class="message">
		<?php if ($context->options['_type'] == 'sendnews'): ?>
		    <h4>Oh yeah! Distribution Successful!</h4>
		    <p>Your newsletter has been submitted to the distribution queue.</p>
		<?php elseif ($context->options['_type'] == 'unsubscribe'): ?>
		    <h4>Congratulations!</h4>
		    <p>You have been unsubscribed from the newsletter and will therefore no longer receive it in your inbox.</p>
		<?php elseif ($context->options['_type'] == 'subscribed'): ?>
			<h4>Congratulations!</h4>
		    <p>You have been subscribed to the newsletter! Keep a look out for our next newsletter to arrive in your inbox.</p>
		<?php else:
		    $preview = true; ?>
    		<h4>Thanks for your submission!</h4>
    		<p>Your article is now in our queue. We will review, adapt and incorporate to the best of our abilities. If we have any questions, we'll contact you. If you have any questions, please contact us.</p>
    		<?php if (!empty($context->options['newsroom'])): ?>
		    <h4>Have more news you'd like to share?</h4>
    		<p><a href="<?php echo UNL_ENews_Newsroom::getById($context->options['newsroom'])->getSubmitURL(); ?>">Submit another story&hellip;</a></p>
    		<?php endif; ?>
		<?php endif;?>
	</div>
</div>
<?php
if ($preview) {
    echo $savvy->render($context, 'ENews/Confirmation/Submission.tpl.php');
}
?>