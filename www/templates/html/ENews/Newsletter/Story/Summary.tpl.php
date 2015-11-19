<?php $storylink = $context->getURL();?>
<div class="article">
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
        echo   '<a href="'.$storylink.'" title="Read more about '.$context->title.'">'
             . '<img src="'.$file->getURL(). '" alt="'.$description.'" />'
             . '</a>';
    }
    ?>
	<h4><a href="<?php echo $storylink; ?>" title="Read more about <?php echo $context->title; ?>"><?php echo $context->title; ?></a></h4>

	<p><?php echo $savvy->render($context, 'ENews/Story/field-description.tpl.php'); ?></p>
</div>
