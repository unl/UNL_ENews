<?php
$url = UNL_ENews_Controller::getURL().'?view=story&amp;id='.$context->id;
?>
<h4><a style="color:#666;" href="<?php echo $url ?>"><?php echo $context->title; ?></a></h4>
<p>
<?php 
if ($file = $context->getThumbnail()) {
    echo '<a href="'.$url.'" style="outline:none;border:none;"><img src="'.UNL_ENews_Controller::getURL().'file'
         . $file->id
         . '.jpg" style="max-width:65px; margin-right:15px;" border="0" align="left" /></a>';
}

echo nl2br($context->description);
if (!empty($context->full_article)) {
    echo ' <a href="'.$url.'" style="color:#BA0000;">More&hellip;</a>';
}
?>
</p>
<?php
if ($parent->context->options['view'] == 'preview'
    || (isset($parent->parent) && $parent->parent->context->options['view'] == 'preview')):
    if (isset($parent->context->id)) {
        $newsletter_id = $parent->context->id;
    } elseif (isset($parent->context->newsletter)) {
        $newsletter_id = $parent->context->newsletter->id;
    }
    ?>
    <form method="post" action="?view=preview&amp;id=<?php echo $newsletter_id; ?>">
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
