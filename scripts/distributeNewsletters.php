#!/usr/bin/env php
<?php
/**
 * Distribute newsletters that are both undistributed and past their send date
 */

if (file_exists(dirname(__FILE__).'/../www/config.inc.php')) {
    require_once dirname(__FILE__).'/../www/config.inc.php';
} else {
    require dirname(__FILE__).'/../www/config.sample.php';
}

chdir(dirname(__FILE__).'/../www');

foreach (UNL_ENews_NewsletterList::getUndistributed() as $newsletter) {
    /* @var $newsletter UNL_ENews_Newsletter */
    $newsletter->distribute();
    $newsletter->distributed = 1;
    $newsletter->save();
}
