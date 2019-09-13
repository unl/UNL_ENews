<?php
$use = UNL_ENews_File_Image::MAX_WIDTH.'_wide';
if ($context->getColFromSort() == 'onecol') {
    $use = UNL_ENews_File_Image::HALF_WIDTH.'_wide';
}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:normal">
    <tr>
        <td bgcolor="#f6f6f5" style="padding:10px 10px 10px 10px" class="unltoday-padding" align="center">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="responsive-table" style="max-width: 620px;">
                <tbody>
                <tr>
                    <td valign="middle">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tbody>
                            <tr>
                                <td align="left" valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>

                                        <?php if ($file = $context->getFileByUse($use, true)): ?>
                                            <tr>
                                                <td>
                                                    <?php if (($context->website)): ?>
                                                        <a href="<?php echo $context->website; ?>">
                                                    <?php endif; ?>
                                                    <img src="<?php echo $file->getURL(); ?>" width="100%" style="margin-bottom:5px;" />
                                                    <?php if (($context->website)): ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="unltoday-body" align="top" style="font-size:0px;line-height:1px;color:#545350;padding:0 0 0 0;">
                                                <p>
                                                    <?php if (($context->website)): ?>
                                                        <a href="<?php echo $context->website; ?>" style="color:#D00000;word-wrap: break-word;">>
                                                    <?php endif; ?>
                                                    <?php echo $savvy->render($context, 'ENews/Story/field-description.tpl.php'); ?>
                                                    <?php if (($context->website)): ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </p>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
