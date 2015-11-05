<?php
function autoload($class)
{
    $class = str_replace(array('_', '\\'), '/', $class);
    include $class . '.php';
}

spl_autoload_register("autoload");

set_include_path(dirname(dirname(__FILE__)).'/src'.PATH_SEPARATOR.dirname(dirname(__FILE__)).'/lib/php');

ini_set('display_errors', true);
error_reporting(E_ALL);

UNL_ENews_Controller::setAdmins(array('bbieber2', 'erasmussen2', 's-mfairch4', 'smeranda2'));

UNL_ENews_Controller::$url = 'http://localhost/workspace/UNL_ENews/www/';

//Database config
UNL_ENews_Controller::setDbSettings(array(
    'host'     => 'localhost',
    'user'     => 'enews',
    'password' => 'enews',
    'dbname'   => 'enews'
));

UNL_ENews_GAStats::$ga_client_id = '';
UNL_ENews_GAStats::$ga_profile_id = 111; //integer

// Set the theme you wish to use
$theme = 'MockU';