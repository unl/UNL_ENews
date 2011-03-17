<?php
class UNL_ENews_Story_File extends UNL_ENews_Record
{
    public $story_id;
    
    public $file_id;
    
    function getTable()
    {
        return 'story_files';
    }

    public static function getById($story_id, $file_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM story_files WHERE story_id = ".intval($story_id)." AND file_id = ".intval($file_id);
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            $object->synchronizeWithArray($result->fetch_assoc());
            return $object;
        }
        return false;
    }
    
    function keys()
    {
        return array('story_id', 'file_id');
    }
}