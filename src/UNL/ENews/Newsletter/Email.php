<?php
class UNL_ENews_Newsletter_Email extends UNL_ENews_Record
{
    public $newsletter_id;
    public $newsroom_email_id;

    function getTable()
    {
        return 'newsletter_emails';
    }

    function keys()
    {
        return array('newsletter_id', 'newsroom_email_id');
    }
}