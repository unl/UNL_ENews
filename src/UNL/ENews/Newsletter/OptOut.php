<?php
class UNL_ENews_Newsletter_OptOut
{
    public $url;
    public $list_name;
    public $list_domain;

    function __construct($options = array())
    {
        if (!isset($options['email'])) {
            throw new Exception('You must pass a newsroom email object.', 400);
        }

        $this->list_name = substr($options['email']->email, 0, strpos($options['email']->email, '@'));
        $this->list_domain = explode('@', $options['email']->email)[1];

        if ($this->list_domain == 'listserv.unl.edu') {
          $this->url = 'https://listserv.unl.edu/signup-anon/?UNSUB=1&LISTNAME=' . urlencode($list_name) . '&LOCKTYPE=LIST&SUCCESS_URL=';
          $this->url .= urlencode(UNL_ENews_Controller::getURL() . '?view=thanks&_type=unsubscribe');
        } elseif ($this->list_domain == 'lists.unl.edu' || $this->list_domain == 'lists.nebraska.edu') {
          $this->url = 'https://mailman.nebraska.edu/';
        } else {
          //throw new Exception('The email '.$options['email']->email.' cannot be set to "Can Unsubscribe". The list domain is not supported.', 400);
        }
    }
}
