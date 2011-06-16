<?php
class UNL_ENews_Newsletter_OptOut
{
    public $url;

    function __construct($options = array())
    {
        if (!isset($options['email'])) {
            throw new Exception('You must pass a newsroom email object!', 400);
        }
        $list_name = substr($options['email']->email, 0, strpos($options['email']->email, '@'));
        $this->url = 'http://listserv.unl.edu/signup-anon/?UNSUB=1&LISTNAME='.urlencode($list_name).'&LOCKTYPE=LIST&SUCCESS_URL=';

        $this->url .= urlencode(UNL_ENews_Controller::getURL().'?view=thanks&_type=unsubscribe');
    }
}