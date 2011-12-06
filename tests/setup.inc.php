<?php
define('CLI', true);
if (file_exists(dirname(__FILE__).'/../www/config.inc.php')) {
    require_once dirname(__FILE__).'/../www/config.inc.php';
} else {
    require dirname(__FILE__).'/../www/config.sample.php';
}

class UNL_Auth
{
    function factory($type)
    {
        return new MockAuth();
    }
}
class MockAuth
{
    function login()
    {
        
    }
    
    function isLoggedIn()
    {
        return true;
    }
    
    function getUser()
    {
        return 'testuser';
    }
}

?>