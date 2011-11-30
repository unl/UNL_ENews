<?php
class UNL_ENews_Story_Tag extends UNL_ENews_Record
{
    public $story_id;
    
    public $tag_id;
    
    function getTable()
    {
        return 'story_tags';
    }

    public static function getById($story_id, $tag_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM story_tags WHERE story_id = ".intval($story_id)." AND tag_id = ".intval($tag_id);
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
        return array('story_id', 'tag_id');
    }
}