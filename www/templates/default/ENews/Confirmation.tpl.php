<script type="text/javascript">
// This plugin is only needed for the demo.
WDN.initializePlugin('notice');
</script>
<div class="wdn_notice affirm">
	<div class="close">
		<a href="#" title="Close this notice">Close this notice</a>
	</div>
	<div class="message">
		<?php if ($context->type == 'sendnews'): ?>
		    <h4>Distribution Successful.</h4>
		    <p>Your newsletter has been submitted to the distribution queue.</p>
		<?php else: ?>
		    <h4>Thank you for submitting your newstip.</h4>
		    <p>If you have any questions, please contact us.</p>
		<?php endif;?>
	</div>
</div>