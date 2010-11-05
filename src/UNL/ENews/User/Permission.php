<?php
class UNL_ENews_User_Permission extends UNL_ENews_Record
{
    public $user_uid;

    public $newsroom_id;

    function getTable()
    {
        return 'user_has_permission';
    }

    function keys()
    {
        return array('user_uid', 'newsroom_id');
    }

    /**
     * get a story in this newsletter
     * 
     * @param string $user_uid
     * @param int    $newsroom_id
     * 
     * @return UNL_ENews_User_Permission
     */
    static function getById($user_uid, $newsroom_id = false)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM user_has_permission WHERE user_uid = '".$user_uid."'";
        if ($newsroom_id) {
            $sql .= " AND newsroom_id = ".intval($newsroom_id);
        }
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
            return $object;
        }
        return false;
    }

    public static function userHasPermission($user_uid, $newsroom_id = false)
    {
        if (self::getById($user_uid, $newsroom_id)) {
            return true;
        }

        return false;
    }
}