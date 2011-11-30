#!/usr/bin/env php
<?php
if (file_exists(dirname(__FILE__).'/../www/config.inc.php')) {
    require_once dirname(__FILE__).'/../www/config.inc.php';
} else {
    require dirname(__FILE__).'/../www/config.sample.php';
}

if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 2) {
    echo "This program requires 1 argument.\n";
    echo "addTag.php {tagname}\n\n";
    echo "Example: addTag.php 'College of Arts & Sciences'\n";
    exit(1);
}

if ($tag = UNL_ENews_Tag::getByName($_SERVER['argv'][1])) {
    // Already exists!
    exit(0);
}

$tag = new UNL_ENews_Tag();
$tag->name = $_SERVER['argv'][1];

if (!$tag->save()) {
    echo 'An error occurred';
    exit(1);
}

exit(0);
