<?php
class UNL_ENews_Newsroom_Email extends UNL_ENews_Record
{
    public $id;
    public $newsroom_id;
    public $email;
    public $optout;
    public $newsletter_default;

    function getTable()
    {
        return 'newsroom_emails';
    }
}