<!DOCTYPE html>
<head>
	<title>UNL | Announce <?php if (isset(UNL_ENews_Controller::$pagetitle[$context->options['view']])) echo '| '.UNL_ENews_Controller::$pagetitle[$context->options['view']]; ?></title>
</head>
<body>
<?php
echo $savvy->render($context->actionable);
?>
</body>