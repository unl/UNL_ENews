<form action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=newsroom" method="post" id="addEmail" class="addData">
    <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
    <input type="hidden" name="_type" value="addemail" />
    <input type="text" name="email" />
    <label for="optout">Optout?</label><input type="checkbox" name="optout" value="1" />
    <label for="newsletter_default">Use by default?</label><input type="checkbox" name="newsletter_default" value="1" />
    <input type="submit" value="Add Email" />
</form>