<?php
if (file_exists('config.inc.php')) {
    require_once 'config.inc.php';
} else {
    require 'config.sample.php';
}

$mysqli = UNL_ENews_Controller::getDB();
$mysqli->query(file_get_contents(dirname(__FILE__).'/../data/enews.sql'));

if (UNL_ENews_Newsroom::getByID(1) === false) {
    $mysqli->query(file_get_contents(dirname(__FILE__).'/../data/enews_sample_data.sql'));
    $newsroom            = new UNL_ENews_Newsroom();
    $newsroom->name      = 'UNL E-News';
    $newsroom->shortname = 'enews';
    $newsroom->save();
}

// @todo add a newsroom for all the others here, unltoday, scarlet, etc?
//if (UNL_ENews_Newsroom::getByID(2) === false) {
//    $newsroom            = new UNL_ENews_Newsroom();
//    $newsroom->name      = 'UNL E-News';
//    $newsroom->shortname = 'enews';
//    $newsroom->save();
//}

echo 'Upgrade complete!';