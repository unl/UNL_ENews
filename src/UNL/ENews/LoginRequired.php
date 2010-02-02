<?php
abstract class UNL_ENews_LoginRequired
{
    public $options = array();
    
    final function __construct($options = array())
    {
        UNL_ENews_Controller::authenticate();
        $this->__postConstruct();
    }
}