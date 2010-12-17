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
		<?php elseif ($context->type == 'sendnews'): ?>
		    <h4>Congratulations!</h4>
		    <p>You have been unsubscribed from the newsletter!</p>
		<?php else: ?>
		    <h4>Thanks for your submission!</h4>
		    <p>Your article is now in our queue. We will review, adapt and incorporate to the best of our abilities. If we have any questions, we'll contact you. If you have any questions, please contact us.</p>
		    <p>Have a great day!</p>
		<?php endif;?>
	</div>
</div>