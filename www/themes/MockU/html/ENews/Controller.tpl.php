<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo UNL_ENews_Controller::getURL();?>css/all.css" />

<title>MockU | E-News <?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?></title>
<script type="text/javascript" src="/wdn/templates_3.0/scripts/all.js"></script>
<script type="text/javascript">
var ENEWS_HOME = '<?php echo UNL_ENews_Controller::getURL(); ?>';
</script>
</head>
<body>
    <div id="breadcrumbs">
        <ul>
        	<li>MockU</li>
            <li><a href="<?php echo UNL_ENews_Controller::getURL();?>">E-News</a></li>
            <li><?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?></li>
        </ul>
    </div>
    <div id="navigation">
        <?php echo $savvy->render($context, 'ENews/Navigation.tpl.php'); ?>
    </div>
    <div id="titlegraphic">
        <h1>MockU E-News</h1>
    </div>
    <div id="pagetitle">
    	<h2><?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?></h2>
    </div>
    <div id="maincontent">
        <?php echo $savvy->render($context->actionable); ?>
    </div>
    <div id="footer">
        &copy; <?php echo date('Y'); ?> MockU
    </div>
</body>
</html>
