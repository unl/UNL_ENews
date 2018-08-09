<form action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=newsroom" method="post" id="addEmail" class="addData">
    <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
    <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
    <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
    <input type="hidden" name="_type" value="addemail" />
    <input type="text" name="email" />
    <input type="checkbox" name="newsletter_default" value="1" /><label for="newsletter_default">Use as default</label>
    <br>
    <input type="checkbox" name="optout" value="1" /><label for="optout">Recipients can opt-out <span class="helper">Requires <a href="https://listserv.unl.edu/">listserv</a> list be properly configured</span></label>
    <br>
    <input type="submit" value="Add Email" />
</form>
