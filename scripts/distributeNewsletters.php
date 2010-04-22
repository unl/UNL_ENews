<?php

if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require dirname(__FILE__).'/../config.sample.php';
}

$enews = new UNL_ENews_Controller();

foreach (UNL_ENews_NewsletterList::getUndistributed() as $newsletter) {
    $newsletter->distribute();
}