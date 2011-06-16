<?php $storylink = $context->getURL();?>
<div class="article">
	<h4><a href="<?=echo $storylink ?>" title="Read more about this story"><?php echo $context->title; ?></a></h4>
	<?php
    if ($file = $context->getThumbnail()) {
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
