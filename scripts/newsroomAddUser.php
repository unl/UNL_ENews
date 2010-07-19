#!/usr/bin/env php
<?php
if (file_exists(dirname(__FILE__).'/../www/config.inc.php')) {
    require_once dirname(__FILE__).'/../www/config.inc.php';
} else {
    require dirname(__FILE__).'/../www/config.sample.php';
}

if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 3) {
    echo "This program requires 2 arguments.\n";
    echo "newsroomAddUser.php newsroom_name username\n\n";
    echo "Example: newsroomAddUser.php mynewsroom jdoe\n";
    exit(1);
}

UNL_ENews_Controller::setUser(UNL_ENews_User::getByUID($_SERVER['argv'][2]));

$newsroom = UNL_ENews_Newsroom::getByShortName($_SERVER['argv'][1]);

if (false === $newsroom) {
    echo "Could not find that newsroom!\n";
    exit(1);
}

if (!$newsroom->addUser(UNL_ENews_Controller::getUser())) {
    echo "Could not add that user to the newsroom!\n";
    exit(1);
}

echo 'The user has been added to the newsroom.'.PHP_EOL;
exit(0);

// @TODO script to add a user to a newsroom