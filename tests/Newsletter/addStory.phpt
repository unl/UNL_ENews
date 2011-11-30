--TEST--
Add story to newsletter test
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';

$newsletter               = new UNL_ENews_Newsletter();
$newsletter->newsroom_id  = 1;
$newsletter->release_date = date('Y-m-d H:i:s', strtotime('tomorrow'));
$newsletter->subject      = 'My big newsletter';

if (!$newsletter->save()) {
    echo 'Should have saved and it did not';
}

// Now let's get a story
include __DIR__ . '/../Stories/insertStory.inc.php';

if (!$newsletter->addStory($story)) {
    echo 'Should have added and it did not';
}

if (!$newsletter->hasStory($story)) {
    echo 'Newsletter should have this story and it does not';
}

?>
===DONE===
--CLEAN--
<?php
require dirname(__FILE__) . '/../setup.inc.php';
$mysql = UNL_ENews_Controller::getDB();
$mysql->query('DELETE FROM newsletters WHERE subject="My big newsletter";');
?>
--EXPECT--
===DONE===