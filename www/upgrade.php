<?php
if (file_exists('config.inc.php')) {
    require_once 'config.inc.php';
} else {
    require 'config.sample.php';
}

$mysqli = UNL_ENews_Controller::getDB();
$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/../data/enews.sql'));
if (!$result) {
    echo $mysqli->error;
}

do {
  if ($result = $mysqli->use_result()) {
      $result->close();
  }
} while ($mysqli->next_result()); 



$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/../data/enews_sample_data.sql'));
if (!$result) {
    echo $mysqli->error;
}
$mysqli->close();

// @todo add a newsroom for all the others here, unltoday, scarlet, etc?
//if (UNL_ENews_Newsroom::getByID(2) === false) {
//    $newsroom            = new UNL_ENews_Newsroom();
//    $newsroom->name      = 'UNL E-News';
//    $newsroom->shortname = 'enews';
//    $newsroom->save();
//}

echo 'Upgrade complete!';