<?php
if ($parent->context instanceof UNL_ENews_Newsletter) {
    $newsletter_id = $parent->context->id;
} elseif ($parent->context instanceof UNL_ENews_Newsletter_Preview) {
    $newsletter_id = $parent->context->newsletter->id;
}
?>
<h4 style=""><?php echo $context->title; ?></h4>
<p>
<?php 
foreach ($context->getFiles() as $file) {
    if (preg_match('/^image/', $file->type)) {
        echo '<img src="?view=file&amp;id='
             . $file->id
             . '" style="max-width:65px; margin-right:15px;" align="left" />';
    }
}

echo $context->description; ?>
<form method="post" action="?view=newsletter&amp;newsletter_id=<?php echo $newsletter_id; ?>">
    <input type="hidden" name="_type" value="addstory" />
    <input type="hidden" name="story_id" value="<?php echo $context->id; ?>" />
    <input type="hidden" name="sort_order" value="0" />
    <input type="hidden" name="intro" value="" />
    <input type="submit" value="add story" />
</form>
</p>