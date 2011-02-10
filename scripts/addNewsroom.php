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
    echo "addNewsroom.php newsroom_name username\n\n";
    echo "Example: addNewsroom.php mynewsroom jdoe\n";
    exit(1);
}

UNL_ENews_Controller::setUser(UNL_ENews_User::getByUID($_SERVER['argv'][2]));

if ($newsroom = UNL_ENews_Newsroom::getByShortName($_SERVER['argv'][1])) {
    echo "That newsroom already exists!\n";
    exit(1);
}

echo "Adding newsroom...\n";

$newsroom                    = new UNL_ENews_Newsroom();
$newsroom->name              = $_SERVER['argv'][1];
$newsroom->shortname         = $_SERVER['argv'][1];
$newsroom->footer_text       = ' ';
$newsroom->allow_submissions = 1;

if (!$newsroom->insert()) {
    echo 'Error creating the newsroom!'.PHP_EOL;
    exit(1);
}

if (!$newsroom->addUser(UNL_ENews_Controller::getUser())) {
    echo 'Error adding the user to the newsroom!'.PHP_EOL;
    exit(1);
}

echo "The newsroom {$newsroom->name} has been created, and the user has been added!".PHP_EOL;

exit(0);

//@TODO Add script to add a newsroom
// eg, php addNewsroom.php userid "Newsroom Title"
