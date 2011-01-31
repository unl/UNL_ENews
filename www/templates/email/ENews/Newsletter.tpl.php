<?php echo $savvy->render($context, 'ENews/Newsletter/Styles.tpl.php'); ?>
<div>
<table cellspacing="0" cellpadding="0" border="0" width="98%" style="margin-top:10px; margin-bottom:10px;">
<tr>
    <td align="center">
    	<span style="font-size:9px;color:#ffffff;">Campus Activities for <span class="newsletterDate"><?php echo date('l, F j, Y', strtotime($context->release_date)); ?></span><br/>
        <a href="<?php echo $context->getURL(); ?>">Problem viewing? Click here to read online.</a></span>
    </td>
</tr>
<tr>
    <td align="center" valign="top">
        <!-- [ header starts here] -->
        <table cellspacing="0" cellpadding="0" border="0" width="600">
            <tr>
                <td><img src="http://www.unl.edu/wdn/templates_3.0/images/email/header.jpg" alt="UNL" width="600" height="126" border="0" valign="bottom" /></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="600" valign="top">
            <tr>
                <td width="12" bgcolor="#E0E0E0">&nbsp;</td>
                <td width="10">&nbsp;</td>
                <td align="left" valign="top" width="556">
                    <table cellspacing="0" cellpadding="0" border="0" valign="top">
                        <tr>
                            <td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
                                <!-- This is the main content -->
                                <h1 style="color:#BA0000;font-family:arial;text-decoration:none;font-size:24px;line-height:1;font-weight:bold;"><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?></h1>
                                <p style="margin:0 0 10px 0;font-size:12px;color:#909090;" class="newsletterDate"><?php echo date('l, F j, Y', strtotime($context->release_date)); ?></p>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
                            </td>
                        </tr>
                        <?php
                        $stories = $context->getStories();
                        if (!empty($context->options['preview'])) {
                            $stories->setIsPreview(true);
                        }
                        ?>
                        <?php echo $savvy->render($stories, 'templates/default/ENews/Newsletter/Stories.tpl.php'); ?>
                        <tr valign="top" background="http://www.unl.edu/wdn/templates_3.0/images/email/insideFooter.jpg" bgcolor="white" style="background-image: url(http://www.unl.edu/wdn/templates_3.0/images/email/insideFooter.jpg)">
                            <td colspan="3" style="color:#606060;font-size:10px;line-height:1.4em;padding:12px;font-family:'Lucida Grande',Verdana,Arial;min-height:42px;" valign="top">
                                <!--  This the footer -->
                                <p style="margin-top:95px;width:530px;"><img src="http://www.unl.edu/wdn/templates_3.0/images/email/wordmark.png" alt="" width="90" height="37" align="right" />
                                &copy; <?php echo date('Y'); ?> University of Nebraska&ndash;Lincoln <br />
                                <?php echo $context->newsroom->getRaw('footer_text'); ?>
                                <!-- optout -->
                                </p>
                                <p style="border-top:1px solid #ffffff;">
                                 <a href="<?php echo UNL_ENews_Controller::getURL().$context->newsroom->shortname."/submit"; ?>" style="outline: none;color: #ba0000;text-decoration: none;">Submit Your News</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="10"></td>
                <td width="12" bgcolor="#E0E0E0"></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="600" height="22">
            <tr>
                <td valign="top"><img src="http://www.unl.edu/wdn/templates_3.0/images/email/footer.jpg" alt="The University of Nebraska-Lincoln" width="600" height="22" /></td>
            </tr>
        </table>
    </td>
</tr>
</table>
</div>