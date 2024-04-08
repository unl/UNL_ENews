<?php

// Set defaults for the edit form
$action = UNL_ENews_Controller::getURL() . '?view=manager';

if (!$context->id) {
    // Must be adding a newsroom
    $action = UNL_ENews_Controller::getURL() . '?view=addnewsroom';
}
$savvy->loadScriptDeclaration("
WDN.loadCSS('" . UNL_ENews_Controller::getURL(). "css/newsroom.css');
");
?>

<form action="<?php echo $action; ?>" method="post" class="dcf-form">
    <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
    <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
    <input type="hidden" name="_type" value="newsroom" />
    <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
    <ol class="dcf-list-bare">
        <li>
            <label class="dcf-label" for="name">
                <span class="dcf-required">*</span>
                Name
                <span class="dcf-form-help">Title for this newsroom</span>
            </label>
            <input class="dcf-input-text" type="text" id="name" name="name" value="<?php echo $context->name; ?>" />
        </li>
        <li>
            <label class="dcf-label" for="subtitle">
                Subtitle
                <span class="dcf-form-help">A short string used as a subheading or subtitle for your newsletters</span>
            </label>
            <input class="dcf-input-text" type="text" id="subtitle" name="subtitle" value="<?php echo $context->subtitle; ?>" />
        </li>
        <li>
            <label class="dcf-label" for="shortname">
                <span class="required">*</span>
                Short Name
                <span class="dcf-form-help">A short string used in the web address</span>
            </label>
            <?php
            $disabled = '';
            if (!UNL_ENews_Controller::isAdmin(UNL_ENews_Controller::getUser())) {
                // Prevent "regular" users from modifying this field
                $disabled = 'disabled="disabled"';
            }
            ?>
            <input class="dcf-input-text" type="text" id="shortname" name="shortname" <?php echo $disabled; ?> value="<?php echo $context->shortname; ?>" />
        </li>
        <li>
            <label class="dcf-label" for="website">
                Website
                <span class="dcf-form-help">Website associated with this newsroom</span>
            </label>
            <input class="dcf-input-text" type="text" id="website" name="website" value="<?php echo $context->website; ?>" />
        </li>
        <li class="dcf-input-checkbox">
            <input class="dcf-input-control" type="checkbox" id="allow_submissions" name="allow_submissions" <?php echo ($context->allow_submissions)? 'checked="checked"': ''; ?> />
            <label class="dcf-label" for="allow_submissions">
                Allow Submissions <span class="dcf-form-help">Can users send news items for review?</span>
            </label>
        </li>
        <li class="dcf-input-checkbox">
            <input class="dcf-input-control" type="checkbox" id="private_web_view" name="private_web_view" <?php echo ($context->private_web_view)? 'checked="checked"': ''; ?> />
            <label class="dcf-label" for="private_web_view">
                Private Web View <span class="dcf-form-help">Newsletter webpages will only be viewable to UNL-authenticated users</span>
            </label>
        </li>
        <?php if ($context->id) : ?>
        <li>
            <label class="dcf-label" for="submit_url">Submit News URL
            <span class="dcf-form-help">Where should users submit their news items?</span>
            </label>
            <input class="dcf-input-text" type="text" id="submit_url" name="submit_url" value="<?php echo $context->getSubmitURL(); ?>" />
        </li>
        <?php endif; ?>
        <li>
            <label class="dcf-label" for="footer_text">
                Footer Text
                <span class="dcf-form-help">Text displayed at the footer of emails (some HTML allowed)</span>
            </label>
            <textarea class="dcf-input-text" id="footer_text" name="footer_text"><?php echo $context->footer_text; ?></textarea>
        </li>
    </ol>
    <input class="dcf-btn dcf-btn-primary" type="submit" name="submit" value="Submit" />
</form>
