<a class="dcf-btn action preview" href="#" onclick="WDN.initializePlugin('modal', [function() {WDN.jQuery(this).colorbox({inline:true, href:'#sendPreview<?php echo $context->id; ?>', open:true, width:'30%', title:'Send Newsletter Preview'}); return false;}]);">Send Preview</a>
<div class="hidden">
    <form id="sendPreview<?php echo $context->id; ?>" action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=sendnews&amp;id=<?php echo $context->id; ?>" method="post">
        <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
        <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
        <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>"> 
        <input type="hidden" name="_type" value="previewnewsletter" />
        Email Address: <input class="dcf-input-text" type="text" name="to" value="<?php echo UNL_ENews_Controller::getUser(true)->mail; ?>" />
        <input class="dcf-btn" type="submit" value="Send" />
    </form>
<?php
$savvy->loadScriptDeclaration("
	WDN.jQuery('#sendPreview<?php echo $context->id; ?>').submit(function(event) {
		// Disable submit button
		WDN.jQuery(this).children('input[type=submit]').attr('disabled', 'disabled');
		var data = WDN.jQuery(this).serialize();
		var url  = WDN.jQuery(this).attr('action') + '&format=partial';
		// Handle post operation
		WDN.post(url, data, function(response, textStatus) {
			// Re-enable submit button
			WDN.jQuery('#sendPreview" . $context->id . " input[type=submit]').removeAttr('disabled');

			// Close colorbox
			WDN.jQuery.colorbox.close();

			// Display response
			WDN.jQuery('#dcf-main').prepend(response);
		});
		event.preventDefault();
	});");
?>
</div>
