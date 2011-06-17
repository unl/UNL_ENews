<?php $storylink = $context->getURL();?>
<div class="article">
	<h4><a href="<?php echo $storylink; ?>" title="Read more about this story"><?php echo $context->title; ?></a></h4>
	<?php
	/* @var $context UNL_ENews_Story_Summary */
	$image_type = 'thumbnail';
	if (isset($parent->context->options['image'])) {
	    $image_type = $parent->context->options['image'];
	}
    if ($file = $context->getFileByUse($image_type, true)) {
        $description = $file->name;
        if (!empty($file->description)) {
            $description = $file->description;
        }
        echo   '<a href="'.$storylink.'">'
             . '<img src="'.$file->getURL(). '" alt="'.$description.'" />'
             . '</a>';
    }
    ?>

	<p><?php echo nl2br($context->description); ?></p>
</div>
