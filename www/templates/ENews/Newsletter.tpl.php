<style>
 td {color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;}
</style>

<div>
<table cellspacing="0" cellpadding="0" border="0" width="98%" style="margin-top:10px; margin-bottom:10px;">
<tr>
    <td align="center" valign="top">
        <!-- [ header starts here] -->
        <table cellspacing="0" cellpadding="0" border="0" width="600">
            <tr>
                <td valign="top"><a href="{{store url=''}}" style="outline:none;display:block;height:126px;"><img src="http://www.unl.edu/wdn/templates_3.0/images/email/header.png" alt="The University of Nebraska-Lincoln" width="600" height="126" border="0"/></a></td>
            </tr>
        </table>
        <!-- [ middle starts here] -->
        <table cellspacing="0" cellpadding="0" border="0" width="600">
            <tr>
                <td width="12" bgcolor="#E0E0E0">&nbsp;</td>
                <td width="10">&nbsp;</td>
                <td align="left" valign="top" width="556">
                    <table cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
                                <!-- This is the main content -->
                                <h1 style="color:#BA0000;font-family:arial;text-decoration:none;font-size:1.6em;line-height:1;font-weight:bold;">UNL Today</h1>
                                <p style="margin:0;font-size:.9em;color:#909090;">Tuesday, March 3, 2010</p>
                                <?php foreach ($context->stories as $story): ?>
                                <p style="margin:1.1em 0;">Title: <?php echo $story->title; ?><br /><?php echo $story->description; ?></p>
                                <?php endforeach; ?>
                          </td>
                        </tr>
                        <tr background="http://www.unl.edu/wdn/templates_3.0/images/email/insideFooter.png" style="background-image: url(http://www.unl.edu/wdn/templates_3.0/images/email/insideFooter.png)">
                            <td style="color:#606060;font-size:10px;line-height:1.4em;padding:12px;font-family:'Lucida Grande',Verdana,Arial;min-height:42px;">
                                <!--  This the footer -->
                                <p style="margin-top:95px;width:530px;"><img src="http://www.unl.edu/wdn/templates_3.0/images/email/wordmark.png" alt="" width="90" height="37" align="right" />
                                &copy; 2010 University of Nebraska&ndash;Lincoln | Lincoln, NE 68588 | 402-472-7211 <br />
                                This email produced and distributed by <a href="http://ucomm.unl.edu/" title="go to the University Communications">University Communications</a>
                                <br /> <a href="#" style="outline: none;color: #ba0000;text-decoration: none;">Submit Your News</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
              <td width="10"></td>
                <td width="12" bgcolor="#E0E0E0"></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="600">
            <tr>
                <td><img src="http://www.unl.edu/wdn/templates_3.0/images/email/footer.png" alt="The University of Nebraska-Lincoln" width="600" height="22" /></td>
            </tr>
        </table>
    </td>
</tr>
</table>
</div>