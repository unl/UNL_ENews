<?php
if (file_exists(dirname(__FILE__).'/www/config.inc.php')) {
    require_once dirname(__FILE__).'/www/config.inc.php';
} else {
    require dirname(__FILE__).'/www/config.sample.php';
}

echo 'Connecting to the database&hellip;';
$mysqli = UNL_ENews_Controller::getDB();
echo 'connected successfully!<br />'.PHP_EOL;

echo 'Initializing database structure&hellip;';

foreach (array('enews.sql', 'story_tags.sql') as $sql_file) {

	$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/'.$sql_file));
	if (!$result) {
	    echo 'There was an error initializing the database.<br />'.PHP_EOL;
	    echo $mysqli->error;
	    exit();
	}
	
	do {
	    if ($result = $mysqli->use_result()) {
	        $result->close();
	    }
	} while ($mysqli->next_result());

}

echo 'initialization complete!<br />'.PHP_EOL;

if (UNL_ENews_Newsroom::getByID(1) === false ) {
    echo 'Inserting sample data&hellip;';
    $result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/enews_sample_data.sql'));
    if (!$result) {
        echo 'There was an error inserting the sample data!<br />'.PHP_EOL;
        echo $mysqli->error;
        exit();
    }
}

$result = $mysqli->multi_query(file_get_contents(dirname(__FILE__).'/data/story_presentations.sql'));
if (!$result) {
    echo 'There was an error updating the story presentation types!<br />'.PHP_EOL;
    echo $mysqli->error;
    exit();
}

echo 'Adding subtitle field to newsrooms...<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `newsrooms` ADD `subtitle` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `name`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />'.PHP_EOL;
    }
}

echo 'Adding submit_url field to newsrooms...<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `newsrooms` ADD `submit_url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `allow_submissions`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />'.PHP_EOL;
    }
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

echo 'Adding from_address field to newsroom table<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `newsrooms` ADD `from_address` VARCHAR( 255 ) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `website`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already exists but that\'s ok!<br />'.PHP_EOL;
    } else {
        echo $mysqli->error;
        exit();
    }
}

echo 'Adding footer_text field to newsrooms&hellip;<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `newsrooms` ADD `footer_text` VARCHAR( 300 ) NOT NULL;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />'.PHP_EOL;
    }
}

echo 'Adding presentation_id field to stories table<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `stories` ADD `presentation_id` INT( 10 ) NOT NULL AFTER `website`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already exists but that\'s ok!<br />'.PHP_EOL;
    } else {
        echo $mysqli->error;
        exit();
    }
} else {
    echo 'Setting existing stories to presentation_id of 1<br />'.PHP_EOL;
    $result = $mysqli->query("UPDATE stories SET presentation_id = 1;");
    if (!$result) {
        echo 'Error setting default presentations on existing stories: ';
        echo $mysqli->error;
        exit();
    }
}
echo 'Adding description field to files table<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `files` ADD `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `use_for`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already exists but that\'s ok!<br />'.PHP_EOL;
    }
}

echo 'Correcting default presentation type field&hellip;<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `story_presentations` CHANGE `default` `isdefault` TINYINT( 1 ) NOT NULL ;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been corrected<br />'.PHP_EOL;
    }
}

echo 'Adding presentation_id field to newsletter_stories&hellip;<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `newsletter_stories` ADD `presentation_id` INT( 10 ) NULL AFTER `story_id`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />'.PHP_EOL;
    }
}

echo 'Adding active field to story_presentations&hellip;<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `story_presentations` ADD `active` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `template`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1060) {
        echo 'Field already has been added<br />'.PHP_EOL;
    }
}

echo 'Removing invalid column from story_presentations&hellip;<br />'.PHP_EOL;
$result = $mysqli->query("ALTER TABLE `story_presentations` DROP COLUMN `dependent_selector`;");
if (!$result) {
    if (mysqli_errno($mysqli) == 1091) {
        echo 'Field does not exist but that\'s ok!<br />'.PHP_EOL;
    } else {
        echo $mysqli->error;
        exit();
    }
}

echo 'Updating existing stories with invalid presentation...<br />'.PHP_EOL;
$result = $mysqli->query("UPDATE stories SET presentation_id = 6 WHERE presentation_id = 7;");
if (!$result) {
    echo 'Error updating stories with invalid presentation: ';
    echo $mysqli->error;
    exit();
}

echo 'Removing invalid story_presentations...<br />'.PHP_EOL;
$result = $mysqli->query("DELETE FROM story_presentations WHERE id = 7;");
if (!$result) {
    echo 'Error removing invalid story_presentations: ';
    echo $mysqli->error;
    exit();
}

echo 'Upgrade complete!';
