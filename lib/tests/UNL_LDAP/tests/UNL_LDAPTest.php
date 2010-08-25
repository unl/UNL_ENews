<?php
/**
 * Test the LDAP class
 * 
 * PHP version 5
 * 
 * $Id$
 * 
 * @category  Default 
 * @package   UNL_LDAP
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_LDAP
 */
ini_set('display_errors', true);
error_reporting(E_ALL);
// Call UNL_LDAPTest::main() if this source file is executed directly.
if (!defined('PHPUNIT_MAIN_METHOD')) {
    define('PHPUNIT_MAIN_METHOD', 'UNL_LDAPTest::main');
}

require_once 'PHPUnit/Framework.php';

chdir(dirname(__FILE__).'/../');

require_once 'UNL/LDAP.php';
require_once 'config.inc.php';

/**
 * Test class for UNL_LDAP.
 * 
 * $Id$
 * 
 * @category  Default 
 * @package   UNL_LDAP
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_LDAP
 */
class UNL_LDAPTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var    UNL_LDAP
     * @access protected
     */
    protected $object;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     * @return void
     */
    public static function main()
    {
        include_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('UNL_LDAPTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     * @return void
     */
    protected function setUp()
    {
        global $options;
        $this->object = UNL_LDAP::getConnection($options);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     * @return void
     */
    protected function tearDown()
    {
    }

    /**
     * Just tests that you can get a connection to the server.
     * 
     * @return void
     */
    public function testGetConnection()
    {
        $this->assertEquals('UNL_LDAP', get_class($this->object));
    }

    /**
     * Test get attribute function
     * 
     * @return void
     */
    public function testGetAttribute()
    {
        $this->assertEquals(array('count'=>1, 'Bieber'), $this->object->getAttribute('bbieber2', 'sn'));
    }

    /**
     * test getFirstAttribute
     * 
     * @return void
     */
    public function testGetFirstAttribute()
    {
        $this->assertEquals('Bieber', $this->object->getFirstAttribute('bbieber2', 'sn'));
    }
}

// Call UNL_LDAPTest::main() if this source file is executed directly.
if (PHPUNIT_MAIN_METHOD == 'UNL_LDAPTest::main') {
    UNL_LDAPTest::main();
}
?>
