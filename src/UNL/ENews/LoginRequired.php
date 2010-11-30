<?php
abstract class UNL_ENews_LoginRequired
{
    public $options = array();

    final function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $user = UNL_ENews_Controller::authenticate();

        // If newsroom isn't set in $_GET, insert user's default newsroom into options
        if (!isset($this->options['newsroom'])) {
            $this->options['newsroom'] = $user->newsroom_id;
        }

        $this->__postConstruct();
    }
}