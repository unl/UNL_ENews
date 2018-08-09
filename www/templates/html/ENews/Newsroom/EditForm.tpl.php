<?php

// Set defaults for the edit form
$action = UNL_ENews_Controller::getURL() . '?view=manager';

if (!$context->id) {
    // Must be adding a newsroom
    $action = UNL_ENews_Controller::getURL() . '?view=addnewsroom';
}

?>
<script>
    WDN.loadCSS('<?php echo UNL_ENews_Controller::getURL();?>css/newsroom.css');
</script>

<form action="<?php echo $action; ?>" method="post">
    <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
    <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
    <input type="hidden" name="_type" value="newsroom" />
    <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
    <ol>
        <li>
            <label for="name">
                <span class="required">*</span>
                Name
                <span class="helper">Title for this newsroom</span>
            </label>
            <input type="text" id="name" name="name" value="<?php echo $context->name; ?>" />
        </li>
        <li>
            <label for="subtitle">
                Subtitle
                <span class="helper">A short string used as a subheading or subtitle for your newsletters</span>
            </label>
            <input type="text" id="subtitle" name="subtitle" value="<?php echo $context->subtitle; ?>" />
        </li>
        <li>
            <label for="shortname">
                <span class="required">*</span>
                Short Name
                <span class="helper">A short string used in the web address</span>
            </label>
            <?php
            $disabled = '';
            if (!UNL_ENews_Controller::isAdmin(UNL_ENews_Controller::getUser())) {
                // Prevent "regular" users from modifying this field
                $disabled = 'disabled="disabled"';
            }
            ?>
            <input type="text" id="shortname" name="shortname" <?php echo $disabled; ?> value="<?php echo $context->shortname; ?>" />
        </li>
        <li>
            <label for="website">
                Website
                <span class="helper">Website associated with this newsroom</span>
            </label>
            <input type="text" id="website" name="website" value="<?php echo $context->website; ?>" />
        </li>
        <li>
            <label for="allow_submissions">Allow Submissions
            <span class="helper">Can users send news items for review?</span>
            </label>
            <input type="checkbox" id="allow_submissions" name="allow_submissions" <?php echo ($context->allow_submissions)? 'checked="checked"': ''; ?> /> Yes
        </li>
        <?php if ($context->id) : ?>
        <li>
            <label for="submit_url">Submit News URL
            <span class="helper">Where should users submit their news items?</span>
            </label>
            <input type="text" id="submit_url" name="submit_url" value="<?php echo $context->getSubmitURL(); ?>" />
        </li>
        <?php endif; ?>
        <li>
            <label for="footer_text">
                Footer Text
                <span class="helper">Text displayed at the footer of emails (some HTML allowed)</span>
            </label>
            <textarea id="footer_text" name="footer_text"><?php echo $context->footer_text; ?></textarea>
        </li>
    </ol>
    <input type="submit" name="submit" value="Submit" />
</form>
