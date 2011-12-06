--TEST--
Add a tag
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';
$controller = new UNL_ENews_Controller();

$mysql = $controller::getDB();
$mysql->query('DELETE FROM tags');

$tag = new UNL_ENews_Tag();
$tag->name = 'fish';
if (!$tag->save()) {
	echo 'Failed';
}

?>
===DONE===
--EXPECT--
===DONE===