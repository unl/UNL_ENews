<?php
class UNL_ENews_StoryList_Latest extends UNL_ENews_StoryList
{
    function __construct($options = array())
    {
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        //$sql = 'SELECT id FROM stories;';
        $sql = 'SELECT story_id FROM newsroom_stories WHERE newsroom_id = '.(int)$options['newsroom'];
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories);
    }
    
}