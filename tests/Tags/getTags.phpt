--TEST--
Get list of all tags
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';
$controller = new UNL_ENews_Controller();

$mysql = $controller::getDB();
$mysql->query('DELETE FROM tags');
$mysql->query('INSERT INTO tags VALUES (1,"fish")');

$tags = new UNL_ENews_Tags();
foreach ($tags as $tag) {
	echo $tag.PHP_EOL;
}

?>
===DONE===
--EXPECT--
fish
===DONE===