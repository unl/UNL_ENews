<!DOCTYPE html>
<head>
	<title><?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?> | Announce | University of Nebraska-Lincoln</title>
</head>
<body>
<?php
echo $savvy->render($context->actionable);
?>
</body>