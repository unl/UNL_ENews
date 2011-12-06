--TEST--
Search for tags
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';
$controller = new UNL_ENews_Controller();

$mysql = $controller::getDB();
$mysql->query('DELETE FROM tags');

foreach (array('Fish', 'Stick') as $name) {
    $tag = new UNL_ENews_Tag();
    $tag->name = $name;
    $tag->save();
}

$search_results = new UNL_ENews_TagList_NameSearch(array('q'=>'tic'));

foreach ($search_results as $tag) {
    echo $tag.PHP_EOL;
}

?>
===DONE===
--EXPECT--
Stick
===DONE===