<?php
	if (!empty($context->options['preview'])) : //For the preview building tool
?>
<div id="previewWrapper" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
	<a href="<?php echo $context->getURL(); ?>">Problem viewing? Click here to read online.</a>
	<div id="preview">
		<header>
            <img src="http://www.unl.edu/wdn/templates_4.0/images/email/header_01.gif">
            <img src="http://www.unl.edu/wdn/templates_4.0/images/email/header_02.gif">
            <h1><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?></h1>
            <?php if(UNL_ENews_Newsroom::getByID($context->newsroom_id)->subtitle) :?>
            <h2><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->subtitle; ?></h2>
            <?php endif;?>
		</header>
		<div id="previewContent">
			<time class="newsletterDate" datetime="<?php echo $context->release_date; ?>"><?php echo date('F j, Y', strtotime($context->release_date)); ?></time>
				<table>
				<?php
					$stories = $context->getStories();
					$stories->setIsPreview(true);
					echo $savvy->render($stories, 'templates/html/ENews/Newsletter/Stories.tpl.php');
				?>
				</table>
		</div>
		<footer>
		<p>
			<img src="http://www.unl.edu/wdn/templates_4.0/images/email/wordmark.gif" alt="" width="111" height="43" />
		    &copy; <?php echo date('Y'); ?> University of Nebraska&ndash;Lincoln <br />
		    <?php echo $context->newsroom->getRaw('footer_text'); ?>
		</p>
		<p>
			<a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit Your News</a>
		</p>
		</footer>
	</div>
</div>
<?php 
	else: //everything else
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height: normal;">
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td bgcolor="#F2EEE4" class="mobile-hide">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="650" class="responsive-table">
                            <tr>
                                <td style="line-height:0"><a href="http://www.unl.edu/" target="_blank"><img src="http://www.unl.edu/wdn/templates_4.0/images/email/header_01.gif" alt="" height="41" width="650" border="0" align="left" style="display: block;"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#F8F5EC" class="mobile-hide">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="650" class="responsive-table">
                            <tr>
                                <td><a href="http://www.unl.edu/" target="_blank"><img src="http://www.unl.edu/wdn/templates_4.0/images/email/header_02.gif" alt="" height="30" width="650" border="0" align="left" style="display: block;"></a></td>
                            </tr>
                            <tr>
                                <td align="center" style="font-size: 30px; line-height: 1.5; font-family: Georgia, serif;"><a href="<?php echo $context->getURL(); ?>" target="_blank" style="text-decoration: none; color:#444341"><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#F8F5EC" style="display: none;" class="mobile-show">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="650" class="responsive-table">
                            <tr>
                                <td class="mobile-logo" rowspan="2" width="54"><a href="http://www.unl.edu/" target="_blank"><img src="http://www.unl.edu/wdn/templates_4.0/images/email/header_05.gif" width="1" height="1" alt="" border="0" align="left" style="display: block; width: 0px; height: 0px;"></a></td>
                                <td class="mobile-unl"><img src="http://www.unl.edu/wdn/templates_4.0/images/email/header_06.gif" width="1" height="1" alt="" border="0" align="left" style="display: block; width: 0px; height: 0px;"></td>
                            </tr>
                            <tr>
                                <td class="mobile-header" style="font-size: 0px; line-height: 0; font-family: Georgia, serif;"><a href="<?php echo $context->getURL(); ?>" target="_blank" style="text-decoration: none; color: #444341;"><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#C80E13" style="padding: 10px 20px 10px 20px;">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="610" class="responsive-table" style="font-size:14px;font-family:Helvetica,Arial,sans-serif;color:#FFFFFF;">
                            <tr>
                                <td class="unltoday-mast" align="left"><?php echo date('F j, Y', strtotime($context->release_date)); ?></td>
                                <td class="unltoday-mast" align="right"><i><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->subtitle; ?></i></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Start main content -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:normal">
    <tr bgcolor="#F8F5EC">
        <td></td>
        <td style="padding:30px 15px 30px 15px" width="620" class="unltoday-padding">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="responsive-table" style="max-width: 620px;">
                <?php
                    $stories = $context->getStories();
                    if (!empty($context->options['preview'])) {
                        $stories->setIsPreview(true);
                    }
                    echo $savvy->render($stories, 'templates/html/ENews/Newsletter/Stories.tpl.php');
                ?>
            </table>
        </td>
        <td></td>
    </tr>
</table>
<!-- End main content -->

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height: normal;">
    <tr>
        <td bgcolor="#525151" align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td style="padding: 20px 20px 20px 20px;">
                        <table width="610" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">
                            <tr>
                                <td align="left" valign="middle" style="font-size: 10px; line-height: 16px; font-family: Helvetica, Arial, sans-serif; color:#cfcfcf; -webkit-text-size-adjust: none">
                                <span class="appleFooter" style="color:#cfcfcf; -webkit-text-size-adjust: none"><a style="color:#cfcfcf" href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit Your News</a> | <a style="color:#cfcfcf" href="<?php echo $context->getURL(); ?>">Read this newsletter on the web</a><br />
                                    &copy; <?php print date("Y")?> University of Nebraska&ndash;Lincoln<br />
                                    <?php echo $context->newsroom->getRaw('footer_text'); ?>
                                 </span></td>
                                <td width="111" valign="top" class="mobile-hide" style="padding-left:20px"><a href="http://www.unl.edu/" target="_blank" style="font-family: Arial; color: #ffffff; font-size: 14px;"><img alt="University of Nebraska-Lincoln" src="http://www.unl.edu/wdn/templates_4.0/images/email/wordmark.gif" width="111" height="43" style="display: block;" border="0"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php 
	endif; //end conditional for preview building tool
?>
