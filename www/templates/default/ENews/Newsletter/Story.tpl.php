<?php $storylink = UNL_ENews_Controller::getURL().'?view=story&id='.$context->id; ?> 
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
         . '" style="margin-right:15px; float:left;" class="frame" alt="'.$file->name.'" /></a>';
}

echo nl2br($context->description);
?>
</p>
<div class="clear"></div>