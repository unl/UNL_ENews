<?php
function autoload($class)
{
    $class = str_replace('_', '/', $class);
    include $class . '.php';
}

spl_autoload_register("autoload");

set_include_path(dirname(dirname(__FILE__)).'/src'.PATH_SEPARATOR.dirname(dirname(__FILE__)).'/lib/php');

ini_set('display_errors', true);
error_reporting(E_ALL);