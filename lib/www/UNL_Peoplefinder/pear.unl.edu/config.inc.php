<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
set_include_path(dirname(__FILE__).'/../src/'.PATH_SEPARATOR.get_include_path());
require_once 'UNL/Autoload.php';

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

UNL_Peoplefinder_Driver_LDAP::$bindDN = 'uid=unlwebsearch,ou=service,dc=unl,dc=edu';
UNL_Peoplefinder_Driver_LDAP::$bindPW = 'gagfawki';

define('UNL_PEOPLEFINDER_URI', 'http://ucommbieber.unl.edu/workspace/peoplefinder/www/');
set_time_limit(5);
//$driver = new UNL_Peoplefinder_Driver_WebService();
$driver = new UNL_Peoplefinder_Driver_LDAP();