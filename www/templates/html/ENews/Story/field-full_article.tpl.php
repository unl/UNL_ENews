<?php
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Allowed', "a[href],strong,p,ul,ol,li,em");
$purifier = new HTMLPurifier($config);


$full_article = nl2br(UNL_ENews_Controller::makeClickableLinks($context->getRawObject()->full_article));
$full_article = $purifier->purify($full_article);

echo $full_article;
