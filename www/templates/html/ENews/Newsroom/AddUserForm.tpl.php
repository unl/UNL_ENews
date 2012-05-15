<form action="<?php echo UNL_ENews_Controller::getURL(); ?>?view=newsroom" method="post" id="addUser" class="addData">
    <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
    <input type="hidden" name="_type" value="adduser" />
    <input type="text" name="user_uid" />
    <input type="submit" value="Add User" />
</form>