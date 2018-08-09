<form action="#" method="post" style="display:none;" id="deleteImages">
    <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
    <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
    <input type="hidden" name="storyid" value="<?php echo getValue($context, 'id'); ?>" />
    <input type="hidden" name="_type" value="deletestoryimages" />
    <input type="submit" value="Delete Images" />
</form>