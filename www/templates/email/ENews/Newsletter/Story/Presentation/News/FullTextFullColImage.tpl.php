<?php
$use = UNL_ENews_File_Image::MAX_WIDTH.'_wide';
if ($context->getColFromSort() == 'onecol') {
    $use = UNL_ENews_File_Image::HALF_WIDTH.'_wide';
}
?>

<?php if ($file = $context->getFileByUse($use, true)):
/* @var $file UNL_ENews_File_Image */
$size = $file->getSize();
?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td align="left">
<img src="<?php echo $file->getURL(); ?>"  width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>" style="margin-bottom:5px;width:100%;" />
</td>
</tr>
</table>
<?php endif; ?>
<h4 style="color:#666; text-decoration:none;"><?php echo $context->title; ?></h4>
<p style="margin-bottom:5px">
<?php
echo nl2br($context->full_article);
?>
<?php if (isset($context->ics)): ?>
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