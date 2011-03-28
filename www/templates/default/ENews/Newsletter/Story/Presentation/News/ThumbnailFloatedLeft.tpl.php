<?php $storylink = $context->getURL();?>
<h4>
    <a href="<?php echo $storylink; ?>">
      <?php echo $context->title; ?>
    </a>
</h4>
<p>
<?php

if ($file = $context->getThumbnail()) {
    $description = $file->name;
    if (!empty($file->description)) {
        $description = $file->description;
    }
    echo '<a href="'.$storylink.'">'
         . '<img src="'.$file->getURL()
         . '" style="margin-right:15px; float:left;" class="frame" alt="'.$description.'" /></a>';
}

echo nl2br($context->description);
if (!empty($context->full_article)) {
    echo ' <a href="'.$storylink.'">Continue reading&hellip;</a>';
}
?>
<?php if ($context->ics): ?>
	<a href="<?php echo $context->ics ?>" class="icsformat">Add to my calendar (.ics)</a>
<?php endif; ?>
</p>
<?php
if (($context->website)) { ?>

More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage"><?php echo $context->website; ?></a>
<?php
}
?>