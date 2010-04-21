<?php
if ($parent->context instanceof UNL_ENews_Newsletter) {
    $newsletter_id = $parent->context->id;
} elseif ($parent->context instanceof UNL_ENews_Newsletter_Preview) {
    $newsletter_id = $parent->context->newsletter->id;
}
?>
<h4><a style="color:#666;" href="<?php echo UNL_ENews_Controller::getURL(); ?>?view=story&id=<?php echo $context->id; ?>"><?php echo $context->title; ?></a></h4>
<p>
<?php 
foreach ($context->getFiles() as $file) {
    if ($file->use_for == 'thumbnail') {
        echo '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
             . $file->id
             . '" style="max-width:65px; margin-right:15px;" align="left" />';
    }
}

echo $context->description;
if ($parent->context instanceof UNL_ENews_Newsletter_Preview
    || (isset($parent->parent) && $parent->parent->context instanceof UNL_ENews_Newsletter_Preview) ):
?>
<form method="post" action="?view=preview&amp;newsletter_id=<?php echo $newsletter_id; ?>">
    <input type="hidden" name="_type" value="addstory" />
    <input type="hidden" name="story_id" value="<?php echo $context->id; ?>" />
    <input type="hidden" name="sort_order" value="0" />
    <input type="hidden" name="intro" value="" />
    <input type="submit" value="add story" />
</form>
<span class="requestedDates">
<?php
echo date('F j', strtotime($context->request_publish_start));
if (isset($context->request_publish_end)) {
        echo ' - '.date('F j', strtotime($context->request_publish_end));
} ?>
</span>
<?php endif; ?>
</p>