<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:normal">
    <tr>
        <td bgcolor="#F8F5EC" style="padding:10px 10px 10px 10px" class="unltoday-padding">
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

                                        <tr>
                                            <td colspan="2" class="unltoday-head" align="left" style="font-size:24px;font-family:Helvetica,Arial,sans-serif;color:#cc0000;padding-bottom:18px">
                                                <a href="<?php echo $context->getURL(); ?>" style="color:#cc0000;text-decoration:none;"><?php echo $context->title; ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="unltoday-body" align="top" style="font-size:16px;line-height:25px;font-family:Georgia,serif;color:#545350;border-bottom:2px solid #E7E2D6;padding:0 0 6px 0;">
                                                <?php if ($file = $context->getThumbnail()) : ?>
                                                    <img src="<?php echo $file->getURL(); ?>" width="30%" style="max-width:35%; margin:0 0 5px 8px; padding-top: 5px;" align="right" />
                                                <?php endif; ?>
                                                <p>
                                                    <?php
                                                    echo $savvy->render($context, 'ENews/Story/field-description.tpl.php');
                                                    if (!empty($context->full_article)) {
                                                        echo ' <a href="'.$context->getURL().'" style="color:#BA0000;">Continue reading&hellip;</a>';
                                                    }
                                                    ?>
                                                    <?php if (isset($context->ics)): ?>
                                                        <a href="<?php echo $context->ics ?>" class="icsformat">Add to my calendar (.ics)</a>
                                                    <?php endif; ?>
                                                </p>

                                                <?php if (($context->website)): ?>
                                                    <table cellspacing="0" cellpadding="3" border="0" valign="top" bgcolor="#E7E2D6" width="100%">
                                                        <tr>
                                                            <td align="right" style="padding:0 8px 0 0;font-size:14px;line-height:20px;">
                                                                More details at <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage" style="color:#BA0000;"><?php echo $context->website; ?></a>
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
