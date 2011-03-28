<?php
$use = UNL_ENews_File_Image::MAX_WIDTH.'_wide';
if ($context->getColFromSort() == 'onecol') {
    $use = UNL_ENews_File_Image::HALF_WIDTH.'_wide';
}
?>

<?php if ($file = $context->getFileByUse($use, true)): ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td valign="top" align="left">
<img src="<?php echo $file->getURL(); ?>" style="margin-bottom:5px;width:100%;" />
</td>
</tr>
</table>
<?php endif; ?>
<h4><a style="color:#666; text-decoration:none;" href="<?php echo $context->getURL(); ?>"><?php echo $context->title; ?></a></h4>
<p style="margin-bottom:5px">
<?php
echo nl2br($context->description);
if (!empty($context->full_article)) {
    echo ' <a href="'.$context->getURL().'" style="color:#BA0000;">Continue reading&hellip;</a>';
}
?>
<?php if ($context->ics): ?>
	<a href="<?php echo $context->ics ?>" class="icsformat">Add to my calendar (.ics)</a>
<?php endif; ?>
</p>
<?php if (($context->website)): ?>
<table cellspacing="0" cellpadding="3" border="0" valign="top" bgcolor="#f7f6f6" width="100%">
<tr>
<td>
More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage" style="color:#BA0000;"><?php echo $context->website; ?></a>
</td>
</tr>
</table>
<?php endif;?>