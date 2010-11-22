<?php $storylink = $context->getURL();?>
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
         . '" style="margin-left:15px; float:right;" class="frame" alt="'.$file->name.'" /></a>';
}

echo nl2br($context->description);
if (!empty($context->full_article)) {
    echo ' <a href="'.$storylink.'">Continue reading&hellip;</a>';
}
?>
</p>
<?php
if (($context->website)) { ?>

More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage"><?php echo $context->website; ?></a>
<?php
}
?>
<div class="clear"></div>