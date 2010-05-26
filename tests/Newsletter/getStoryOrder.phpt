--TEST--
Get story sort order
--SKIPIF--
skip
--POST--
foo=&
--FILE--
<?php
require dirname(__FILE__) . '/../../www/config.inc.php';
$controller = new UNL_ENews_Controller();

?>
===DONE===
--EXPECT--
===DONE===