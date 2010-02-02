<?php
class UNL_ENews_Newsroom_UserList extends UNL_ENews_UserList
{
    function __construct($options = array())
    {
        $users = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT user_uid FROM user_has_permission ';
        if (isset($options['newsroom_id'])) {
            $sql .= ' WHERE newsroom_id = '.(int)$options['newsroom_id'];
        }
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $users[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($users);
    }
}