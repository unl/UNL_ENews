<a class="wdn-button action preview" href="#" onclick="WDN.initializePlugin('modal', [function() {WDN.jQuery(this).colorbox({inline:true, href:'#sendPreview<?php echo $context->id; ?>', open:true, width:'30%', title:'Send Newsletter Preview'}); return false;}]);">Send Preview</a>
<div class="hidden">
    <form id="sendPreview<?php echo $context->id; ?>" action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=sendnews&amp;id=<?php echo $context->id; ?>" method="post">
        <input type="hidden" name="_type" value="previewnewsletter" />
        Email Address: <input type="text" name="to" value="<?php echo UNL_ENews_Controller::getUser(true)->mail; ?>" />
        <input type="submit" value="Send" />
    </form>
    <script type="text/javascript">
	WDN.jQuery('#sendPreview<?php echo $context->id; ?>').submit(function(event) {
		// Disable submit button
		WDN.jQuery(this).children('input[type=submit]').attr('disabled', 'disabled');
		var data = WDN.jQuery(this).serialize();
		var url  = WDN.jQuery(this).attr('action') + '&format=partial';
		// Handle post operation
		WDN.post(url, data, function(response, textStatus) {
			// Re-enable submit button
			WDN.jQuery('#sendPreview<?php echo $context->id; ?> input[type=submit]').removeAttr('disabled');

			// Close colorbox
			WDN.jQuery.colorbox.close();

			// Display response
			WDN.jQuery('#maincontent').prepend(response);
		});
		event.preventDefault();
	});
	</script>
</div>
