<?php echo $savvy->render($context, 'ENews/Newsletter/Styles.tpl.php'); ?>
<?php 
	if (!empty($context->options['preview'])) : //For the preview building tool
?>
<div id="previewWrapper">
	<a href="<?php echo $context->getURL(); ?>">Problem viewing? Click here to read online.</a>
	<div id="preview">
		<header>
			<hgroup>
				<h1><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?></h1>
				<?php if(UNL_ENews_Newsroom::getByID($context->newsroom_id)->subtitle) :?>
				<h2><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->subtitle; ?></h2>
				<?php endif;?>
			</hgroup>
		</header>
		<div id="previewContent">
			<time class="newsletterDate"><?php echo date('l, F j, Y', strtotime($context->release_date)); ?></time>
			<?php
				$stories = $context->getStories();
				$stories->setIsPreview(true);
				echo $savvy->render($stories, 'templates/default/ENews/Newsletter/Stories.tpl.php');
			?>
		</div>
		<footer>
		<p>
			<img src="http://www.unl.edu/wdn/templates_3.0/images/email/wordmark.png" alt="" width="90" height="37" />
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
		        <table width="600" border="0" cellpadding="0" cellspacing="0">
		                    <tr>
		                        <td rowspan="3" background="http://www.unl.edu/wdn/templates_3.0/images/email/email2_07.gif" valign="top">
		                            <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email2_01.gif" width="22" height="95" alt="Left header slice" /></td>
		                        <td rowspan="2" valign="top">
		                            <a href="http://www.unl.edu/" style="outline:none;display:block;">
		                                <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email2_02.gif" width="67" height="70" alt="Red N" />
		                            </a>
		                        </td>
		                        <td valign="top">
		                            <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email2_03.gif" width="489" height="31" alt="University of Nebraska-Lincoln" />
		                        </td>
		                        <td rowspan="3" background="http://www.unl.edu/wdn/templates_3.0/images/email/email2_09.gif" valign="top">
		                            <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email2_04.gif" width="22" height="95" alt="Right header slice" /></td>
		                    </tr>
		                    <tr>
		                        <td valign="top">
		                            <h1 style="color:#565656;font-family:arial;text-decoration:none;font-size:28px;line-height:1;font-weight:bold;margin:0;margin-top:3px;"><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?></h1>
		                            <?php if(UNL_ENews_Newsroom::getByID($context->newsroom_id)->subtitle) :?>
		                            	<h2 style="color:#565656;font-family:arial;text-decoration:none;font-size:10px;line-height:1;font-weight:normal;margin:0;padding-top:2px;padding-bottom:0;"><?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->subtitle; ?></h2>
		                            <?php endif;?>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2">
		                            <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email2_06.gif" width="556" height="25" alt="Red bar seperator" />
		                        </td>
		                    </tr>
		                    <tr>
		                        <td background="http://www.unl.edu/wdn/templates_3.0/images/email/email2_07.gif">
		                            <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email2_07.gif" width="22" height="100%" alt="Left maincontent slice" />
		                        </td>
		                        <td style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;" colspan="2" valign="top">
		                              <!-- This is the main content -->
		                              <p style="margin:0 0 10px 0;font-size:12px;color:#909090;" class="newsletterDate"><?php echo date('l, F j, Y', strtotime($context->release_date)); ?></p>
		                              <table>
		                              <?php
				                        $stories = $context->getStories();
				                        if (!empty($context->options['preview'])) {
				                            $stories->setIsPreview(true);
				                        }
				                        echo $savvy->render($stories, 'templates/default/ENews/Newsletter/Stories.tpl.php');
				                      ?>
		                              </table>
		                        </td>
		                        <td background="http://www.unl.edu/wdn/templates_3.0/images/email/email2_09.gif">
		                            <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email2_09.gif" width="22" height="100%" alt="Right main content slice">
		                        </td>
		                    </tr>
		                    <tr background="EEEEEE">
			                    <td colspan="4" valign="top" style="line-height:0;">
			                        <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email3_10.gif" width="600" height="51" alt="N Seal" />
			                    </td>
			                </tr>
			                <tr>
			                    <td colspan="4" valign="top">
				                    <table bgcolor="EEEEEE" style="background:#eeeeee;" width="100%">
		                        <tr>
		                            <td width="22">
		                            
		                            </td>
		                            <td style="color:#606060;font-size:10px;line-height:1.4em;padding:12px;font-family:'Lucida Grande',Verdana,Arial;min-height:42px;">
		                            <p style="width:556px;"><img src="http://www.unl.edu/wdn/templates_3.0/images/email/wordmark.png" alt="" width="90" height="37" align="right" />
		                                &copy; <?php echo date('Y'); ?> University of Nebraska&ndash;Lincoln <br />
		                                <?php echo $context->newsroom->getRaw('footer_text'); ?>
			                            </p>
			                            <p>
		                                	<a href="<?php echo $context->newsroom->getSubmitURL(); ?>" style="outline: none;color: #ba0000;text-decoration: none;">Submit Your News</a>
				                                </p>
				                            </td>
				                            <td width="22">
				                            
				                            </td>
				                        </tr>
			                    	</table>
			                    </td>
			                </tr>
			                <tr>
		                    <td colspan="4">
		                        <img src="http://www.unl.edu/wdn/templates_3.0/images/email/email3_14.gif" width="600" height="12" alt="Very bottom of table" />
		                    </td>
		                </tr>
		            </table>
		    </td>
		</tr>
	</table>
</div>
<?php 
	endif; //end conditional for preview building tool
?>
