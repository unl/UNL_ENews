<?php
/**
 * model for a story associated with a newsroom, ties back to records in the
 * newsroom_stories table
 *
 */
class UNL_ENews_Newsroom_Story extends UNL_ENews_Record
{
    public $newsroom_id;
    public $story_id;
    public $uid_created;
    public $date_created;
    public $status;
    public $source;

    function keys()
    {
        return array('newsroom_id', 'story_id');
    }
    
    function getTable()
    {
        return 'newsroom_stories';
    }

    /**
     * Static function to retrieve an instance
     * 
     * @param $newsroom_id The newsroom id
     * @param $story_id    The story id
     * 
     * @return UNL_ENews_Newsroom_Story
     */
    public static function getById($newsroom_id, $story_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsroom_stories WHERE newsroom_id = ".intval($newsroom_id)." AND story_id = ".intval($story_id);
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            $object->synchronizeWithArray($result->fetch_assoc());
            return $object;
        }
        return false;
    }
    
    function save()
    {
        $this->setDefaults();
        $result = parent::save();
        
        if (!$result) {
            throw new Exception('Error adding the story to the newsroom.', 500);
        }
        return $result;
    }
    
    function insert()
    {
        $this->setDefaults();
        $result = parent::insert();
        
        if (!$result) {
            throw new Exception('Error adding the story to the newsroom.', 500);
        }
        return $result;
    }
    
    function setDefaults()
    {
        if (!isset($this->uid_created)) {
            $this->uid_created = strtolower(UNL_ENews_Controller::getUser(true)->uid);
        }
        $this->date_created = date('Y-m-d H:i:s');
    }
}