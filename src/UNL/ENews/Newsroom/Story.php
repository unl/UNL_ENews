<?php
class UNL_ENews_Newsroom_Story extends UNL_ENews_Record
{
    public $newsroom_id;
    public $story_id;
    public $uid_created;
    public $date_created;
    public $status;
    
    function keys()
    {
        return array('newsroom_id', 'story_id');
    }
    
    function getTable()
    {
        return 'newsroom_stories';
    }
    
    public static function getById($newsroom_id, $story_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsroom_stories WHERE newsroom_id = ".intval($newsroom_id)." AND story_id = ".intval($story_id);
        if ($result = $mysqli->query($sql)) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
            return $object;
        }
        return false;
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
        return $result;
    }
}