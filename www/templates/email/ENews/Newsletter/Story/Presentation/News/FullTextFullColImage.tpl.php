<?php
$use = UNL_ENews_File_Image::MAX_WIDTH.'_wide';
if ($context->getColFromSort() == 'onecol') {
    $use = UNL_ENews_File_Image::HALF_WIDTH.'_wide';
}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:normal">
    <tr>
        <td bgcolor="#f6f6f5" style="padding:10px 10px 10px 10px" class="unltoday-padding" align="center">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="responsive-table" style="max-width: 650px;">
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
                                            <?php
                                                $description = $file->name;
                                                if (!empty($file->description)) {
                                                    $description = $file->description;
                                                }
                                            ?>
                                            <tr>
                                                <td>
                                                    <img src="<?php echo $file->getURL(); ?>" alt="<?php echo $description; ?>" width="100%" style="margin-bottom:5px;" />
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td colspan="2" class="unltoday-head" align="left" style="font-size:28px;font-family:Verdana,sans-serif;color:#d00000;padding:10px 0 10px 0">
                                                <a href="<?php echo $context->getURL(); ?>" style="color:#d00000;text-decoration:none;"><?php echo $context->title; ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="unltoday-body" align="top" style="font-size:18px;line-height:26px;font-family:Georgia,serif;color:#545350;padding:0 0 6px 0;">
                                                <p>
                                                    <?php echo $savvy->render($context, 'ENews/Story/field-full_article.tpl.php'); ?>
                                                    <?php if (isset($context->ics)): ?>
                                                        <a href="<?php echo $context->ics ?>" class="icsformat">Add to my calendar (.ics)</a>
                                                    <?php endif; ?>
                                                </p>

                                                <?php if (($context->website)): ?>
                                                    <table cellspacing="0" cellpadding="3" border="0" valign="top" bgcolor="#ebebea" style="background-color:#ebebea;" width="100%">
                                                        <tr>
                                                            <td align="right" style="margin:10px 0 10px 0;padding:10px 10px 10px 10px;font-family:Montserrat,Verdana,sans-serif;font-size:14px;line-height:20px;">
                                                                More details at <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage" style="color:#D00000;word-wrap: break-word;"><?php echo $context->website; ?></a>
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
