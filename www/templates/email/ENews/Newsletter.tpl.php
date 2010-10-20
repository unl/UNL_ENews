<style type="text/css">
 <!--td {color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;}-->
 <!--.newsColumn h4 {font-size: 15px; font-weight:bold; padding:2px; text-decoration:none;}-->
 <!--.newsColumn h4 a{text-decoration:none;}-->
 <!--.newsColumn .story {padding-bottom:10px;}-->
 <!--#newsColumnIntro h4 {font-size:20px; margin-bottom:10px;}-->
 <!--#newsColumnIntro {border-bottom:1px dashed #e0e0e0;padding-bottom:10px;}-->
</style>
<div>
<table cellspacing="0" cellpadding="0" border="0" width="98%" style="margin-top:10px; margin-bottom:10px;">
<tr>
    <td align="center">
        <a href="<?php echo $context->getURL(); ?>">Problem viewing? Click here to read online.</a>
    </td>
</tr>
<tr>
    <td align="center" valign="top">
        <!-- [ header starts here] -->
        <table cellspacing="0" cellpadding="0" border="0" width="600">
            <tr>
                <td><img src="http://www.unl.edu/wdn/templates_3.0/images/email/header.jpg" alt="The University of Nebraska-Lincoln" width="600" height="126" border="0" valign="bottom" /></td>
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
                                <p style="margin:0;font-size:12px;color:#909090;"><?php echo date('l, F j, Y', strtotime($context->release_date)); ?></p>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
                            </td>
                        </tr>
                            <?php
                            $columnIntro = '';
                            $column1 = '';
                            $column2 = '';
                            foreach ($context->getStories() as $key=>$story) {

                                switch($story->sort_order % 3) {
                                    case 1:
                                        $column = 'columnIntro';
                                        break;
                                    case 2:
                                        $column = 'column1';
                                        break;
                                    case 0:
                                        $column = 'column2';
                                        break;
                                }

                                $$column .= '
                                    <div class="story" id="story_'.$key.'" valign="top">'
                                        . $savvy->render($story);
                                $$column .= '<img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />';
                                $$column .= '</div>';
                            }
                            ?>
                        <tr>
                            <td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
                                <!-- This is the main content -->
                                <div id="newsColumnIntro" class="newsColumn">
                                <?php echo $columnIntro; ?>
                                </div>
                            </td>
                         </tr>
                         <tr id="newsStories">
                             <td valign="top" width="273">
                                <div id="newsColumn1" class="newsColumn">
                                <?php echo $column1; ?>
                                </div>
                            </td>
                            <td width="10">&nbsp;</td>
                            <td valign="top" width="273">
                                <div id="newsColumn2" class="newsColumn">
                                <?php echo $column2; ?>
                                </div>
                            </td>
                         </tr>
                        <tr valign="top" background="http://www.unl.edu/wdn/templates_3.0/images/email/insideFooter.jpg" bgcolor="white" style="background-image: url(http://www.unl.edu/wdn/templates_3.0/images/email/insideFooter.jpg)">
                            <td colspan="3" style="color:#606060;font-size:10px;line-height:1.4em;padding:12px;font-family:'Lucida Grande',Verdana,Arial;min-height:42px;" valign="top">
                                <!--  This the footer -->
                                <p style="margin-top:95px;width:530px;"><img src="http://www.unl.edu/wdn/templates_3.0/images/email/wordmark.png" alt="" width="90" height="37" align="right" />
                                &copy; 2010 University of Nebraska&ndash;Lincoln | Lincoln, NE 68588 | 402-472-8515 <br />
                                This email produced and distributed by <a href="http://ucomm.unl.edu/" title="go to the University Communications">University Communications</a>
                                <br /> <a href="<?php echo UNL_ENews_Controller::getURL().$context->newsroom->shortname."/submit"; ?>" style="outline: none;color: #ba0000;text-decoration: none;">Submit Your News</a>
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