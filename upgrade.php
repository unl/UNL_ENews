<?php
if (file_exists(dirname(__FILE__).'/www/config.inc.php')) {
    require_once dirname(__FILE__).'/www/config.inc.php';
} else {
    require dirname(__FILE__).'/www/config.sample.php';
}

$mysqli = UNL_ENews_Controller::getDB();
$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/enews.sql'));
if (!$result) {
    echo $mysqli->error;
}

do {
  if ($result = $mysqli->use_result()) {
      $result->close();
  }
} while ($mysqli->next_result()); 



$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/enews_sample_data.sql'));
if (!$result) {
    echo $mysqli->error;
}
$mysqli->close();
/*
// @todo add a newsroom for all the others here, unltoday, scarlet, etc?
if (UNL_ENews_Newsroom::getByID(2) === false) {
    $newsroom            = new UNL_ENews_Newsroom();
    $newsroom->name      = 'UNL Today';
    $newsroom->shortname = 'unltoday';
    $newsroom->save();
}

exit();
if (UNL_ENews_Newsroom::getByID(3) === false) {
    $newsroom            = new UNL_ENews_Newsroom();
    $newsroom->name      = 'Scarlet';
    $newsroom->shortname = 'scarlet';
    $newsroom->save();
}

if (UNL_ENews_Newsroom::getByID(4) === false) {
    $newsroom            = new UNL_ENews_Newsroom();
    $newsroom->name      = 'News Release';
    $newsroom->shortname = 'newsrelease';
    $newsroom->save();
}
*/

// Now let's set up some newsroom admins
foreach (array(
    'bbieber2',
    's-mjuhl2',
    'smeranda2',
    'rcrisler1',
    'acoleman1',
    'kbartling2',
    'erasmussen2'
    ) as $uid) {

    UNL_ENews_Newsroom::getByID(1)->addUser(UNL_ENews_User::getByUID($uid));
    UNL_ENews_Newsroom::getByID(2)->addUser(UNL_ENews_User::getByUID($uid));
    UNL_ENews_Newsroom::getByID(3)->addUser(UNL_ENews_User::getByUID($uid));
    UNL_ENews_Newsroom::getByID(4)->addUser(UNL_ENews_User::getByUID($uid));

}

// Make sure data field in files table in longblob
$mysqli = UNL_ENews_Controller::getDB();
$result = $mysqli->query("ALTER TABLE `files` CHANGE `data` `data` LONGBLOB NOT NULL;");
if (!$result) {
    echo $mysqli->error;
}


echo 'Upgrade complete!';