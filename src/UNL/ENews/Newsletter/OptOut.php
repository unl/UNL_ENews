<?php
class UNL_ENews_Newsletter_OptOut
{
    public $url;

    function __construct($options = array())
    {
        $this->url = 'http://listserv.unl.edu/signup-anon/?UNSUB=1&LISTNAME='..'&LOCKTYPE=LIST&SUCCESS_URL=';
        
        $this->url .= UNL_ENews_Controller::getURL().'/?view=thanks&_type=unsubscribe';
    }
}