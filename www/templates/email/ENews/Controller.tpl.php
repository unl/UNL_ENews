<!DOCTYPE html>
<head>
	<title>UNL | Announce <?php echo $savvy->render($context, 'ENews/PageTitle.tpl.php'); ?></title>
</head>
<body>
<?php
echo $savvy->render($context->actionable);
?>
</body>