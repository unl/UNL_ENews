<?php
class UNL_ENews_Newsroom_Story extends UNL_ENews_Record
{
    public $newsroom_id;
    public $story_id;
    public $uid_created;
    public $date_created;
    public $status;
    
    function getTable()
    {
        return 'newsroom_stories';
    }
}