--TEST--
Add a story
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';

include __DIR__ . '/insertStory.inc.php';

?>
===DONE===
--CLEAN--
<?php
require dirname(__FILE__) . '/../setup.inc.php';
$mysql = UNL_ENews_Controller::getDB();
$mysql->query('DELETE FROM stories WHERE title="My big news story";');
?>
--EXPECT--
===DONE===