<form action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=newsroom" method="post" id="addUser" class="dcf-form-controls-inline addData">
    <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
    <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
    <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
    <input type="hidden" name="_type" value="adduser" />
    <label for="dcf-input-group-button-input">My.UNL username</label>
    <div class="dcf-input-group">
        <input type="text" class="dcf-input-group-button-input" name="user_uid" />
        <input type="submit" class="dcf-btn dcf-btn-primary" value="Add User" />
    </div>
</form>
