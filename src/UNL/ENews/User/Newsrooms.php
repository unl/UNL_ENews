<?php
/**
 * Collection of newsrooms a user has permission to.
 * 
 * <code>
 * $newsrooms = new UNL_ENews_User_Newsrooms(array('uid'=>'bbieber2'));
 * </code>
 * 
 * @see UNL_ENews_User::getNewsrooms()
 */
class UNL_ENews_User_Newsrooms extends UNL_ENews_NewsroomList
{
    function __construct($options = array())
    {
        $newsroom_ids = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM user_has_permission WHERE user_uid = '".$mysqli->escape_string($options['uid'])."'";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $newsroom_ids[] = $row['newsroom_id'];
            }
        }
        parent::__construct($newsroom_ids);
    }
}