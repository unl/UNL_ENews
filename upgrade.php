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
        $mysqli->close();
        exit();
    }
}

$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/story_presentations.sql'));
if (!$result) {
    echo 'There was an error updating the story presentation types!<br />';
    echo $mysqli->error;
    exit();
}

/*
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
*/
echo 'Adding newsroom administrators&hellip;<br />';
// Now let's set up some newsroom admins
foreach (array(
    'bbieber2',
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

$result = $mysqli->query("SELECT id, email_lists FROM newsrooms;");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['email_lists'])) {
            UNL_ENews_Newsroom::getByID($row['id'])->addEmail($row['email_lists']);
        }
    }
    // Assume we did a good job, and drop the email_lists field?
}

echo 'Adding from_address field to newsroom table<br />';
$result = $mysqli->query("ALTER TABLE `newsrooms` ADD `from_address` VARCHAR( 255 ) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `website`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already exists but that\'s ok!<br />';
    } else {
        echo $mysqli->error;
        exit();
    }
}

echo 'Adding footer_text field to newsrooms&hellip;<br />';
$result = $mysqli->query("ALTER TABLE `newsrooms` ADD `footer_text` VARCHAR( 300 ) NOT NULL;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />';
    }
}

$today = UNL_ENews_Newsroom::getByID(1);
$today->from_address = 'today@unl.edu';
$today->save();

if ($next = UNL_ENews_Newsroom::getByID(5)) {
    $next->from_address = 'nextnebraska@unl.edu';
    $next->save();
}

echo 'Adding presentation_id field to stories table<br />';
$result = $mysqli->query("ALTER TABLE `stories` ADD `presentation_id` INT( 10 ) NOT NULL AFTER `website`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already exists but that\'s ok!<br />';
    } else {
        echo $mysqli->error;
        exit();
    }
} else {
    echo 'Setting existing stories to presentation_id of 1<br />';
    $result = $mysqli->query("UPDATE stories SET presentation_id = 1;");
    if (!$result) {
        echo 'Error setting default presentations on existing stories: ';
        echo $mysqli->error;
        exit();
    }
}
echo 'Adding description field to files table<br />';
$result = $mysqli->query("ALTER TABLE `files` ADD `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `use_for`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already exists but that\'s ok!<br />';
    }
}

echo 'Correcting default presentation type field&hellip;<br />';
$result = $mysqli->query("ALTER TABLE `story_presentations` CHANGE `default` `isdefault` TINYINT( 1 ) NOT NULL ;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been corrected<br />';
    }
}

echo 'Adding presentation_id field to newsletter_stories&hellip;<br />';
$result = $mysqli->query("ALTER TABLE `newsletter_stories` ADD `presentation_id` INT( 10 ) NULL AFTER `story_id`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />';
    }
}

echo 'Adding active field to story_presentations&hellip;<br />';
$result = $mysqli->query("ALTER TABLE `story_presentations` ADD `active` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `template`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />';
    }
}

echo 'Removing invalid column from story_presentations&hellip;<br />';
$result = $mysqli->query("ALTER TABLE `story_presentations` DROP COLUMN `dependent_selector`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1091) {
        echo 'Field does not exist but that\'s ok!<br />';
    } else {
        echo $mysqli->error;
        exit();
    }
}

echo 'Updating existing stories with invalid presentation...<br />';
$result = $mysqli->query("UPDATE stories SET presentation_id = 6 WHERE presentation_id = 7;");
if (!$result) {
    echo 'Error updating stories with invalid presentation: ';
    echo $mysqli->error;
    exit();
}

echo 'Removing invalid story_presentations...<br />';
$result = $mysqli->query("DELETE FROM story_presentations WHERE id = 7;");
if (!$result) {
    echo 'Error removing invalid story_presentations: ';
    echo $mysqli->error;
    exit();
}

$mysqli->close();

echo 'Upgrade complete!';