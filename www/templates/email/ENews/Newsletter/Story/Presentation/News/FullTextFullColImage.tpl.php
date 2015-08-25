<?php
$use = UNL_ENews_File_Image::MAX_WIDTH.'_wide';
if ($context->getColFromSort() == 'onecol') {
    $use = UNL_ENews_File_Image::HALF_WIDTH.'_wide';
}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:normal">
    <tr>
        <td bgcolor="#F8F5EC" style="padding:30px 15px 30px 15px" class="unltoday-padding">
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
                                                    <img src="<?php echo $file->getURL(); ?>" width="100%" style="margin-bottom:5px;" />
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td colspan="2" class="unltoday-head" align="left" style="font-size:24px;font-family:Helvetica,Arial,sans-serif;color:#cc0000;padding-bottom:18px">
                                                <a href="<?php echo $context->getURL(); ?>" style="color:#cc0000;text-decoration:none;"><?php echo $context->title; ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="unltoday-body" align="top" style="padding:0 20px 0 0;font-size:16px;line-height:25px;font-family:Georgia,serif;color:#545350">
                                                <p>
                                                    <?php echo nl2br($context->full_article); ?>
                                                    <?php if (isset($context->ics)): ?>
                                                        <a href="<?php echo $context->ics ?>" class="icsformat">Add to my calendar (.ics)</a>
                                                    <?php endif; ?>
                                                </p>

                                                <?php if (($context->website)): ?>
                                                    <table cellspacing="0" cellpadding="3" border="0" valign="top" bgcolor="#E7E2D6" width="100%">
                                                        <tr>
                                                            <td>
                                                                More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage" style="color:#BA0000;"><?php echo $context->website; ?></a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php endif; ?>
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
