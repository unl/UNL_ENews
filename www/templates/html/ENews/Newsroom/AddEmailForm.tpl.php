<form action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=newsroom" method="post" id="addEmail" class="unl-bg-light-gray addData">
    <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
    <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
    <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
    <input type="hidden" name="_type" value="addemail" />
    <input type="text" name="email" />
    <div class="dcf-form-group">
        <input type="checkbox" class="dcf-input-control" name="newsletter_default" value="1" /><label for="newsletter_default">Use as default when creating new newsletter</label>
    </div>
    <div class="dcf-form-group">
        <input type="checkbox" class="dcf-input-control" name="optout" value="1" /><label for="optout">Recipients can unsubscribe (Requires <a href="https://listserv.unl.edu/">listserv</a> list be properly configured)</label>
    </div>
    <input type="submit" value="Add Email" />
</form>
