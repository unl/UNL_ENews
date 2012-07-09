<!DOCTYPE html>
<html lang="en">
<!-- InstanceBegin template="/Templates/fixed_html5.dwt" codeOutsideHTMLIsLocked="false" -->
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
    
    $Id: unlaffiliate.dwt 1390 2010-11-18 15:24:33Z bbieber2 $
-->
<link rel="stylesheet" type="text/css" media="screen" href="/wdn/templates_3.0/css/all.css" />
<link rel="stylesheet" type="text/css" media="print" href="/wdn/templates_3.0/css/print.css" />
<link rel="stylesheet" type="text/css" media="screen" href="http://ternandplover.unl.edu/sharedcode/affiliate.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo UNL_ENews_Controller::getURL();?>css/all.css" />
<script type="text/javascript" src="/wdn/templates_3.0/scripts/all.js"></script>
<!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" media="screen" href="/wdn/templates_3.0/css/ie.css" />
<![endif]-->
<meta name="author" content="University of Nebraska-Lincoln | Tern &amp; Plover Conservation Partnership" />
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="content-language" content="en" />
<meta name="language" content="en" />
<link rel="shortcut icon" href="/sharedcode/affiliate_imgs/favicon.ico" />

<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?> | Announce | University of Nebraska-Lincoln</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<link rel="home" href="<?php echo UNL_ENews_Controller::getURL();?>" title="UNL Announce" />
<link rel="logout" href="<?php echo UNL_ENews_Controller::getURL();?>?logout" title="Log out" />
<script type="text/javascript">
var ENEWS_HOME = '<?php echo UNL_ENews_Controller::getURL(); ?>';
</script>
<!-- InstanceEndEditable -->
</head>
<?php
$body_class = 'html5 fixed';
if ($context->options['model'] == 'UNL_ENews_PublishedStory'
    && true == isset($_SERVER['HTTP_USER_AGENT'])
    && false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko/2008')) {
    // Firefox 2.0.0 series, or Lotus Notes web browser
    $body_class = 'document';
}
?>
<body class="<?php echo $body_class; ?>">
<p class="skipnav"> <a class="skipnav" href="#maincontent">Skip Navigation</a> </p>
<div id="wdn_wrapper">
    <div id="header"> 	
		<!-- InstanceBeginEditable name="sitebranding" -->
		<div id="affiliate_note"><a href="http://www.unl.edu" title="University of Nebraska&ndash;Lincoln">An affiliate of the University of Nebraska&ndash;Lincoln</a></div>
		<a href="http://ternandplover.unl.edu/" title="Tern &amp; Plover Conservation Partnership"><img src="http://ternandplover.unl.edu/sharedcode/affiliate_imgs/affiliate_logo.png" alt="Tern &amp; Plover Conservation Partnership" id="logo" /></a>
    	<div id='tag_line'>Conservation Partnership</div>
		<!-- InstanceEndEditable -->
		<?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/wdnTools.html'; ?>

    </div>
    <div id="wdn_navigation_bar">
        <div id="breadcrumbs">
            <!-- WDN: see glossary item 'breadcrumbs' -->
            <!-- InstanceBeginEditable name="breadcrumbs" -->
            <ul>
                <li><a href="http://www.unl.edu/" title="University of Nebraska&ndash;Lincoln">UNL</a></li>
                <li><a href="http://snr.unl.edu/" title="School of Natural Resources">SNR</a></li>
                <li><a href="http://ternandplover.unl.edu/index.asp" title="Tern and Plover Conservation Partnership">Tern and Plover</a></li>
                <li><a href="<?php echo UNL_ENews_Controller::getURL();?>">Announce</a></li>
                <li><?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?></li>
            </ul>
            <!-- InstanceEndEditable --></div>
        <div id="wdn_navigation_wrapper">
            <div id="navigation"><!-- InstanceBeginEditable name="navlinks" -->
                <?php echo $savvy->render($context, 'ENews/Navigation.tpl.php'); ?>
                <!-- InstanceEndEditable --></div>
        </div>
    </div>
    <div id="wdn_content_wrapper">
        <div id="titlegraphic"><!-- InstanceBeginEditable name="titlegraphic" -->
            <h1>Tern and Plover</h1>
            <!-- InstanceEndEditable --></div>
        <div id="pagetitle"><!-- InstanceBeginEditable name="pagetitle" -->
        	<h2><?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?></h2>
        	<!-- InstanceEndEditable --></div>
        <div id="maincontent">
            <!--THIS IS THE MAIN CONTENT AREA; WDN: see glossary item 'main content area' -->
            <!-- InstanceBeginEditable name="maincontentarea" -->
            <?php echo $savvy->render($context->actionable); ?>
            <!-- InstanceEndEditable -->
            <div class="clear"></div>
            <?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/noscript.html'; ?>
            <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
        </div>
        <div id="footer">
            <div id="footer_floater"></div>
            <div class="footer_col">
                <?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/feedback.html'; ?>
            </div>
            <div class="footer_col"><!-- InstanceBeginEditable name="leftcollinks" -->
                <h3>Related Links</h3>
<ul>
  <li><a href="http://snr.unl.edu/" title="Welcome, statements, initiatives of the Office of the Chancellor">School of Natural Resources</a></li>
  <li><a href="http://www.environmentaltrust.org/">Nebraska Environmental Trust</a></li>
  <li><a href="http://www.fws.gov/" title="United State Fish and Wildlife Service" target="_blank">United States Fish and Wildlife Service</a></li>
  <li><a href="http://outdoornebraska.ne.gov/" title="Nebraska Game &amp; Parks Commission">Nebraska Game and Parks Commission</a></li>
</ul>
<p>&nbsp;</p>

                <!-- InstanceEndEditable --></div>
            <div class="footer_col"><!-- InstanceBeginEditable name="contactinfo" -->
                <h3>Contact Us</h3>
<p><strong>Tern &amp; Plover Conservation Partnership</strong><br />
153 Hardin Hall<br />
3310 Holdrege Street<br />
Lincoln, NE 68583-0931<br />
<strong>Phone:</strong> 402-472-8878<br />
<strong>E-mail:</strong> <a href="mailto:ternsandplovers@unl.edu">ternsandplovers@unl.edu</a></p>

                <!-- InstanceEndEditable --></div>
            <div class="footer_col">
                <?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/socialmediashare.html'; ?>
            </div>
            <!-- InstanceBeginEditable name="optionalfooter" --> <!-- InstanceEndEditable -->
            <div id="wdn_copyright"><!-- InstanceBeginEditable name="footercontent" -->
                

&copy; 2011 Tern &mp; Plover Conservation Partnership -  University of Nebraska-Lincoln | 153 Hardin Hal<a href="/tools/index-webtools.asp">l</a>, 3310 Holdrege Street, Lincoln, NE 68583-0931 <br />Telephone: 402-472-8878 | <a href="mailto:ternsandplovers@unl.edu">comments?</a>
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-25971741-1']); //replace with your unique tracker id
_gaq.push(['_setDomainName', '.unl.edu']);
_gaq.push(['_setAllowLinker', true]);
_gaq.push(['_setAllowHash', false]);
_gaq.push(['_trackPageview']);
</script>

                <!-- InstanceEndEditable -->
                <?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/wdn.html'; ?>
                | <a href="http://validator.unl.edu/check/referer">W3C</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS</a> <a href="http://www.unl.edu/" title="UNL Home" id="wdn_unl_wordmark"><img src="/wdn/templates_3.0/css/footer/images/wordmark.png" alt="UNL's wordmark" /></a> </div>
        </div>
    </div>
    <div id="wdn_wrapper_footer"> </div>
</div>
</body>
<!-- InstanceEnd --></html>
