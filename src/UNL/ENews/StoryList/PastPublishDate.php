<?php
class UNL_ENews_StoryList_PastPublishDate extends UNL_ENews_StoryList
{
    function __construct($options = array())
    {
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT story_id FROM stories WHERE request_publish_end < "'.date('Y-m-d H:i:s').'";';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories);
    }
}