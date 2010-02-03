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
    
    function save()
    {
        if (!isset($this->uid_created)) {
            $this->uid_created = strtolower(UNL_ENews_Controller::getUser(true)->uid);
        }
        $this->date_created = date('Y-m-d H:i:s');
        $result = parent::save();
        
        if (!$result) {
            throw new Exception('Error adding the story to the newsroom.');
        }
    }
}