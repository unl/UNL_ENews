<?php

if ($file = $context->getFileByUse('originalimage')) {
    $description = $file->name;
    $width = $context->getColFromSort();
    if (!empty($file->description)) {
        $description = $file->description;
    }
    echo '<figure class="'. $width .'">'
    	 .'<img src="'.$file->getURL()
         . '" alt="'.$description.'" />'
         .'<figcaption>'.$description.'</figcaption>'
         .'</figure>';
}
?>
<h4>
    <?php echo $context->title; ?>
</h4>
<p>
<?php
echo $savvy->render($context, 'ENews/Story/field-full_article.tpl.php');
?>
<?php if (isset($context->ics)): ?>
	<a href="<?php echo $context->ics ?>" class="icsformat">Add to my calendar (.ics)</a>
<?php endif; ?>
</p>
<?php
if (($context->website)) { ?>

More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage"><?php echo $context->website; ?></a>
<?php
}
?>