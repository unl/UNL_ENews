<?php
if (isset($parent->context->id)) {
    $url = $parent->context->getURL().'/'.$context->id;
} elseif (isset($parent->context->newsletter)) {
    $url = $parent->context->newsletter->getURL().'/'.$context->id;
}
?>
<h4><a style="color:#666; text-decoration:none;" href="<?php echo $url ?>"><?php echo $context->title; ?></a></h4>

<?php 
if ($file = $context->getThumbnail()) {
    echo '<table cellspacing="0" cellpadding="0" border="0" width="106" align="left"><tr><td valign="top" algin="left"><img src="'.UNL_ENews_Controller::getURL().'file'
         . $file->id
         . '.jpg" style="margin-right:15px;margin-bottom:5px;" align="left" /></td></tr></table>';
}
?>
<p style="margin-bottom:5px">
<?php 
echo nl2br($context->description);
if (!empty($context->full_article)) {
    echo ' <a href="'.$url.'" style="color:#BA0000;">Continue reading&hellip;</a>';
}
?>
</p>
<?php if (($context->website)) {?>
<table cellspacing="0" cellpadding="3" border="0" valign="top" bgcolor="#f7f6f6" width="100%">
<tr>
<td>
More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage" style="color:#BA0000;"><?php echo $context->website; ?></a>
</td>
</tr>
</table>
<?php }?>

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
