<?php
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Allowed', UNL_ENews_Controller::$allowed_html_field_description);
$purifier = new HTMLPurifier($config);


$description = nl2br($context->getRawObject()->description);
$description = $purifier->purify($description);

echo $description;
