<?php
abstract class UNL_ENews_Admin_LoginRequired
{
    public $options = array();

    final function __construct($options = array())
    {
        $user = UNL_ENews_Controller::authenticate();

        if (!UNL_ENews_Controller::isAdmin($user->uid)) {
            throw new Exception('You are not an administrator!', 403);
        }

        $this->options = $options + $this->options;

        $this->__postConstruct();
    }
}