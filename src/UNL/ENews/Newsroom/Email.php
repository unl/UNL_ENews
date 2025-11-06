<?php
class UNL_ENews_Newsroom_Email extends UNL_ENews_Record
{
    public $id;
    public $newsroom_id;
    public $email;
    public $optout;
    public $newsletter_default;
    public $use_subscribe_link;

    function getTable()
    {
        return 'newsroom_emails';
    }
}
