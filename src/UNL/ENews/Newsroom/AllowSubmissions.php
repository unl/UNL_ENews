<?php
class UNL_ENews_Newsroom_AllowSubmissions extends UNL_ENews_NewsroomList
{
    function __construct($options = array())
    {
        $newsroom_ids = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsrooms WHERE allow_submissions = '1' ORDER BY name";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $newsroom_ids[] = $row['id'];
            }
        }
        $mysqli->close();
        parent::__construct($newsroom_ids);
    }
}