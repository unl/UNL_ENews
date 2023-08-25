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

// Set the theme you wish to use
$theme = 'MockU';

// Site Notice
$siteNotice = new stdClass();
$siteNotice->display = false;
$siteNotice->noticePath = 'dcf-notice';
$siteNotice->containerID = 'dcf-main';
$siteNotice->type = 'dcf-notice-info';
$siteNotice->title = 'Maintenance Notice';
$siteNotice->message = 'We will be performing site maintenance on February 3rd from 4:30 to 5:00 pm CST.  This site may not be available during this time.';
UNL_ENews_Controller::$siteNotice = $siteNotice;
