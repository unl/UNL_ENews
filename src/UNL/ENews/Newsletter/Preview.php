<?php
class UNL_ENews_Newsletter_Preview
{
    public $newsletter;
    
    function __construct($options = array())
    {
        $this->newsletter = new UNL_ENews_Newsletter();
    }
}