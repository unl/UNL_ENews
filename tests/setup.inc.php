<?php
define('CLI', true);
require_once dirname(__FILE__).'/../www/config.inc.php';
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