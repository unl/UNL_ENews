<?php
abstract class UNL_ENews_LoginRequired
{
    public $options = array();
    
    final function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $user = UNL_ENews_Controller::authenticate();
        $this->options['newsroom'] = $user->newsroom_id;
        $this->__postConstruct();
    }
}