<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><!-- InstanceBegin template="/Templates/php.fixed.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!--
    Membership and regular participation in the UNL Web Developer Network
    is required to use the UNL templates. Visit the WDN site at 
    http://wdn.unl.edu/. Click the WDN Registry link to log in and
    register your unl.edu site.
    All UNL template code is the property of the UNL Web Developer Network.
    The code seen in a source code view is not, and may not be used as, a 
    template. You may not use this code, a reverse-engineered version of 
    this code, or its associated visual presentation in whole or in part to
    create a derivative work.
    This message may not be removed from any pages based on the UNL site template.
    
    $Id: php.fixed.dwt.php 536 2009-07-23 15:47:30Z bbieber2 $
-->
<link rel="stylesheet" type="text/css" media="screen" href="/wdn/templates_3.0/css/all.css" />
<link rel="stylesheet" type="text/css" media="print" href="/wdn/templates_3.0/css/print.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/all.css" />
<script type="text/javascript" src="/wdn/templates_3.0/scripts/all.js"></script>
<?php virtual('/wdn/templates_3.0/includes/browserspecifics.html'); ?>
<?php virtual('/wdn/templates_3.0/includes/metanfavico.html'); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>UNL | E-Newsroom <?php if (isset(UNL_ENews_Controller::$pagetitle[$context->options['view']])) echo '| '.UNL_ENews_Controller::$pagetitle[$context->options['view']]; ?></title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<link rel="home" href="<?php echo UNL_ENews_Controller::getURL();?>" title="E-Newsroom" />
<?php
if ($user = UNL_ENews_Controller::getUser()) {
    echo '<script type="text/javascript">
            try {
                WDN.idm.logoutURL = "'.UNL_ENews_Controller::getURL().'?logout";
                WDN.idm.displayNotice("'.$user->uid.'");
            } catch(e) {}
          </script>';
}
?>
<!-- InstanceEndEditable -->
</head>
<body class="fixed">
<p class="skipnav"> <a class="skipnav" href="#maincontent">Skip Navigation</a> </p>
<div id="wdn_wrapper">
    <div id="header"> <a href="http://www.unl.edu/" title="UNL website"><img src="/wdn/templates_3.0/images/logo.png" alt="UNL graphic identifier" id="logo" /></a>
        <h1>University of Nebraska&ndash;Lincoln</h1>
        <?php virtual('/wdn/templates_3.0/includes/wdnTools.html'); ?>
    </div>
    <div id="wdn_navigation_bar">
        <div id="breadcrumbs">
            <!-- WDN: see glossary item 'breadcrumbs' -->
            <!-- InstanceBeginEditable name="breadcrumbs" -->
            <ul>
                <li><a href="http://www.unl.edu/" title="University of Nebraska-Lincoln Home">UNL</a></li>
                <li><a href="http://ucomm.unl.edu/" title="Office of University Communications">UComm</a></li>
                <li><a href="http://newsroom.unl.edu/" title="UNL Newsroom">Newsroom</a></li>
                <li><a href="<?php echo UNL_ENews_Controller::getURL();?>">E-Newsroom</a></li>
                <li><?php if (isset(UNL_ENews_Controller::$pagetitle[$context->options['view']])) echo UNL_ENews_Controller::$pagetitle[$context->options['view']]; ?></li>
            </ul>
            <!-- InstanceEndEditable --></div>
        <div id="wdn_navigation_wrapper">
            <div id="navigation"><!-- InstanceBeginEditable name="navlinks" -->
                <ul>
                    <li><a href="?view=newsletter">E-News</a>
                        <ul>
                            <li><a href="?view=help">Help</a></li>
                        </ul>
                    </li>
                    <li><a href="?view=submit">Submit A News Item</a></li>
                    <?php if (UNL_ENews_Controller::isAdmin(UNL_ENews_Controller::getUser())) : ?>
                    <li><a href="?view=manager">Manage News</a></li>
                    <li><a href="?view=preview">Build Newsletter</a>
                        <?php
                        if (($user = UNL_ENews_Controller::getUser())
                            && $newsletters = $user->newsroom->getNewsletters()) {
                            if (count($newsletters)) {
                                echo '<ul>';
                                // There is a user logged in
                                foreach($newsletters as $newsletter) {
                                    if (isset($newsletter->release_date)) {
                                        echo '<li><a href="?view=preview&amp;id='.$newsletter->id.'">'.$newsletter->release_date.'</a></li>';
                                    } 
                                }
                                echo '<li><a href="?view=newsletters">All newsletters</a></li>';
                                echo '</ul>';
                            }
                        }
                        ?>
                    </li>
                    <?php endif; ?>
                </ul>
                <!-- InstanceEndEditable --></div>
        </div>
    </div>
    <div id="wdn_content_wrapper">
        <div id="titlegraphic"><!-- InstanceBeginEditable name="titlegraphic" -->
            <h1>E-Newsroom</h1>
            <!-- InstanceEndEditable --></div>
        <div id="pagetitle"><!-- InstanceBeginEditable name="pagetitle" -->
        	<h2><?php if (isset(UNL_ENews_Controller::$pagetitle[$context->options['view']])) echo UNL_ENews_Controller::$pagetitle[$context->options['view']]; ?></h2>
        	<!-- InstanceEndEditable --></div>
        <div id="maincontent">
            <!--THIS IS THE MAIN CONTENT AREA; WDN: see glossary item 'main content area' -->
            <!-- InstanceBeginEditable name="maincontentarea" -->
            <?php echo $savvy->render($context->actionable); ?>
            <!-- InstanceEndEditable -->
            <div class="clear"></div>
            <?php virtual('/wdn/templates_3.0/includes/noscript.html'); ?>
            <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
        </div>
        <div id="footer">
            <div id="footer_floater"></div>
            <div class="footer_col">
                <?php virtual('/wdn/templates_3.0/includes/feedback.html'); ?>
            </div>
            <div class="footer_col"><!-- InstanceBeginEditable name="leftcollinks" -->
                <h3>Related Links</h3>
                <ul>
                    <li><a href="http://www.unl.edu/ucomm/chancllr/" title="Welcome, statements, initiatives of the Office of the Chancellor">Office of the Chancellor</a></li>
                    <li><a href="http://www.unl.edu/ucomm/ucomm/speakers/" title="UNL Speakers Bureau">Speakers Bureau</a></li>
                    <li><a href="http://www.unl.edu/is/" title="Computing and telecommunications services for the university community">Information Services</a></li>
                    <li><a href="http://iris.unl.edu/" title="Catalogs, resources, services and information from University Libraries">Libraries</a></li>
                    <li><a href="http://www.unl.edu/regrec/calendar/calendar_main.shtml" title="Academic Calender">Academic Calendar</a></li>
                    <li><a href="http://events.unl.edu/" title="Upcoming UNL events in a searchable database">UNL Calendar</a></li>
                </ul>
                
                <!-- InstanceEndEditable --></div>
            <div class="footer_col"><!-- InstanceBeginEditable name="contactinfo" -->
                <h3>Contacting Us</h3>
                <p><strong>University of Nebraska-Lincoln</strong><br />
                1400 R Street<br />
                Lincoln, NE 68588 <br />
                402-472-7211</p>
                
                <!-- InstanceEndEditable --></div>
            <div class="footer_col">
                <?php virtual('/wdn/templates_3.0/includes/socialmediashare.html'); ?>
            </div>
            <!-- InstanceBeginEditable name="optionalfooter" --> <!-- InstanceEndEditable -->
            <div id="wdn_copyright"><!-- InstanceBeginEditable name="footercontent" -->
                <?php echo file_get_contents('http://www.unl.edu/ucomm/sharedcode/footer.html'); ?>
                <!-- InstanceEndEditable -->
                <?php virtual('/wdn/templates_3.0/includes/wdn.html'); ?>
                | <a href="http://validator.unl.edu/check/referer">W3C</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS</a> <a href="http://www.unl.edu/" title="UNL Home" id="wdn_unl_wordmark"><img src="/wdn/templates_3.0/css/footer/images/wordmark.png" alt="UNL's wordmark" /></a> </div>
        </div>
    </div>
    <div id="wdn_wrapper_footer"> </div>
</div>
</body>
<!-- InstanceEnd --></html>
