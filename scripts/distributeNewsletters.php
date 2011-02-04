#!/usr/bin/env php
<?php

if (file_exists(dirname(__FILE__).'/../www/config.inc.php')) {
    require_once dirname(__FILE__).'/../www/config.inc.php';
} else {
    require dirname(__FILE__).'/../www/config.sample.php';
}

chdir(dirname(__FILE__).'/../www');

foreach (UNL_ENews_NewsletterList::getUndistributed() as $newsletter) {
    $newsletter->distribute();
    $newsletter->distributed = 1;
    $newsletter->save();
}
