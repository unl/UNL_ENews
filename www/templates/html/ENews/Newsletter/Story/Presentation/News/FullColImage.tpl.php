<?php
$storylink = $context->getURL();

if ($file = $context->getFileByUse('originalimage')) {
    $description = $file->name;
    $width = $context->getColFromSort();
    if (!empty($file->description)) {
        $description = $file->description;
    }
    echo '<figure class="'. $width .'">'
         .'<a href="'.$storylink.'">'
    	 .'<img class="announcefullwidth" src="'.$file->getURL()
         . '" alt="'.$description.'" />'
         .'</a>'
         .'<figcaption>'.$description.'</figcaption>'
         .'</figure>';
}
?>
<h4>
    <a href="<?php echo $storylink; ?>">
      <?php echo $context->title; ?>
    </a>
</h4>
<p>
<?php
echo $savvy->render($context, 'ENews/Story/field-description.tpl.php');
if (!empty($context->full_article)) {
    echo ' <a href="'.$storylink.'">Continue reading&hellip;</a>';
}
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
