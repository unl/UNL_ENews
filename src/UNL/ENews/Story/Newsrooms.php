<?php
class UNL_ENews_Story_Newsrooms extends UNL_ENews_NewsroomList
{
    function __construct($options = array())
    {
        $newsrooms = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsroom_id FROM newsroom_stories WHERE story_id = '.(int)$options['id'];
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $newsrooms[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($newsrooms);
    }
}