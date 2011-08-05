<h4><a style="color:#666; text-decoration:none;" href="<?php echo $context->getURL(); ?>"><?php echo $context->title; ?></a></h4>

<?php
/* @var $context UNL_ENews_Story */
if ($file = $context->getThumbnail()) {
    /* @var $file UNL_ENews_File_Image */
    $size = $file->getSize();
    echo '<table cellspacing="0" cellpadding="0" border="0" align="left"><tr><td valign="top" align="left">'
       . '<img src="'.$file->getURL().'" width="'.$size[0].'" height="'.$size[1].'" style="margin-right:15px;margin-bottom:5px;" align="left" />'
       . '</td></tr></table>';
}
?>
<p style="margin-bottom:5px">
<?php
echo nl2br($context->description);
if (!empty($context->full_article)) {
    echo ' <a href="'.$context->getURL().'" style="color:#BA0000;">Continue reading&hellip;</a>';
}
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
<?php endif; ?>