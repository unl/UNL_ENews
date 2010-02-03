<?php
class UNL_ENews_Newsroom_Stories extends UNL_ENews_StoryList
{
    function __construct($options = array())
    {
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT story_id FROM newsroom_stories ';
        if (isset($options['type'])) {
            $sql .= 'WHERE newsroom_id = '.(int)$options['newsroom_id'] .
                    ' AND status = `'.$options['type'].'`';
        }
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories);
    }
}