<?php
ini_set('display_errors', true);
error_reporting(E_ALL|E_STRICT);
set_include_path(dirname(__FILE__).'/../src/'.PATH_SEPARATOR.get_include_path());
require_once 'UNL/Autoload.php';

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

UNL_Peoplefinder_Driver_LDAP::$bindDN = 'uid=giggidy,ou=service,dc=unl,dc=edu';
UNL_Peoplefinder_Driver_LDAP::$bindPW = 'flibbertygibberty';

define('UNL_PEOPLEFINDER_URI', 'http://peoplefinder.unl.edu/');
set_time_limit(5);
// If you have LDAP access credentials, best to use this driver
$driver = new UNL_Peoplefinder_Driver_LDAP();

// Otherwise, use the webservice driver
$driver = new UNL_Peoplefinder_Driver_WebService();
