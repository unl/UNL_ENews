<?php

require_once 'Testing/Selenium.php';
require_once 'PHPUnit/Framework/TestCase.php';

class BrowserTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->verificationErrors = array();
        $this->selenium = new Testing_Selenium("*firefox", "http://peoplefinder.unl.edu/");
        $result = $this->selenium->start();
    }

    function tearDown()
    {
        $this->selenium->stop();
    }

    function testBasicQuery()
    {
        $this->selenium->open("/");
        $this->selenium->type("q", "Harvey Perlman");
        $this->selenium->click("submitbutton");
        $this->selenium->waitForPageToLoad("30000");
        try {
            $this->assertTrue($this->selenium->isTextPresent("Perlman, Harvey"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->selenium->click("link=Perlman, Harvey");
        $this->selenium->waitForPageToLoad("30000");
        try {
            $this->assertTrue($this->selenium->isTextPresent("Chancellor"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertTrue($this->selenium->isElementPresent("link=hperlman1@unl.edu"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }

    }
}
?>