<?php 
$storylink = $context->getURL();

if ($file = $context->getFileByUse(UNL_ENews_File_Image::MAX_WIDTH.'_wide', true)) {
    echo '<a href="'.$storylink.'">'
         . '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
         . $file->id
         . '" style="margin-bottom:5px;width:96%;" class="frame" alt="'.$file->name.'" /></a>';
}
?>
<h4>
    <a href="<?php echo $storylink; ?>">
      <?php echo $context->title; ?>
    </a>
</h4>
<p>
<?php
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