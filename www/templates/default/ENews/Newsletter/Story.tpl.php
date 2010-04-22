<?php $storylink = UNL_ENews_Controller::getURL().'?view=story&id='.$context->id; ?> 
<h4>
    <a href="<?php echo $storylink; ?>">
      <?php echo $context->title; ?>
    </a>
</h4>
<p>
<?php 

if ($file = $context->getThumbnail()) {
    echo '<a href="'.$storylink.'">'
         . '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
         . $file->id
         . '" style="margin-right:15px; float:left;" class="frame" alt="'.$file->name.'" /></a>';
}

echo $context->description;
if ($parent->context instanceof UNL_ENews_Newsletter_Preview
    || (isset($parent->parent) && $parent->parent->context instanceof UNL_ENews_Newsletter_Preview) ):
?>
<form method="post" action="?view=newsletter&amp;newsletter_id=<?php echo $newsletter_id; ?>">
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