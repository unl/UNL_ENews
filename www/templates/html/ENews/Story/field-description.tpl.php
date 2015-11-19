<?php
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Allowed', "a[href],strong,p,ul,ol,li,em");
$purifier = new HTMLPurifier($config);


$description = nl2br($context->getRawObject()->description);
$description = $purifier->purify($description);

echo $description;
