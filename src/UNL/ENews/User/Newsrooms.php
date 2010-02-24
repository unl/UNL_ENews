<?php
class UNL_ENews_User_Newsrooms extends UNL_ENews_NewsroomList
{
    function __construct($options = array())
    {
        $newsroom_ids = array();
    	$mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM user_has_permission WHERE user_uid = '".$options['uid']."'";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
            	$newsroom_ids[] = $row['newsroom_id'];
            }
        }
        $mysqli->close();
        parent::__construct($newsroom_ids);
    }
}