<?php
if (file_exists(dirname(__FILE__).'/www/config.inc.php')) {
    require_once dirname(__FILE__).'/www/config.inc.php';
} else {
    require dirname(__FILE__).'/www/config.sample.php';
}

echo 'Connecting to the database&hellip;';
$mysqli = UNL_ENews_Controller::getDB();
echo 'connected successfully!<br />';

echo 'Initializing database structure&hellip;';
$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/enews.sql'));
if (!$result) {
    echo 'There was an error initializing the database.<br />';
    echo $mysqli->error;
    exit();
}

do {
    if ($result = $mysqli->use_result()) {
        $result->close();
    }
} while ($mysqli->next_result()); 

echo 'initialization complete!<br />';

if (UNL_ENews_Newsroom::getByID(1) === false ) {
    echo 'Inserting sample data&hellip;';
    $result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/enews_sample_data.sql'));
    if (!$result) {
        echo 'There was an error inserting the sample data!<br />';
        echo $mysqli->error;
        exit();
    }
    $mysqli->close();
}

// @todo add a newsroom for all the others here, unltoday, scarlet, etc?
if (UNL_ENews_Newsroom::getByID(2) === false) {
    $newsroom            = new UNL_ENews_Newsroom();
    $newsroom->name      = 'UNL Today';
    $newsroom->shortname = 'unltoday';
    $newsroom->save();
}

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

echo 'Adding newsroom administrators&hellip;<br />';
// Now let's set up some newsroom admins
foreach (array(
    'bbieber2',
    's-mjuhl2',
    'smeranda2',
    'rcrisler1',
    'acoleman1',
    'kbartling2',
    'erasmussen2',
    'tfedderson2',
	's-mfairch4',
    ) as $uid) {

    echo '* adding '.$uid.'&hellip;';
    UNL_ENews_Newsroom::getByID(1)->addUser(UNL_ENews_User::getByUID($uid));
    UNL_ENews_Newsroom::getByID(2)->addUser(UNL_ENews_User::getByUID($uid));
    UNL_ENews_Newsroom::getByID(3)->addUser(UNL_ENews_User::getByUID($uid));
    UNL_ENews_Newsroom::getByID(4)->addUser(UNL_ENews_User::getByUID($uid));
    echo 'done.<br />';

}

echo 'Upgrade complete!';