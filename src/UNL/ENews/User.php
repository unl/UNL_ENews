<?php
class UNL_ENews_User extends UNL_ENews_Record
{
    public $uid;
    
    public $newsroom_id = 1;
    
    public $last_login;
    
    /**
     * @var UNL_ENews_Newsroom $newsroom Newsroom
     */
    
    
    
    function __construct($options = array())
    {

    }
    
    public static function getByUID($uid)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM users WHERE uid = '".$mysqli->escape_string($uid)."';";
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
            return $object;
        }

        $object = new self();
        $object->uid = $uid;
        $object->insert();
        return $object;
    }
    
    function keys()
    {
        return array('uid');
    }
    
    function getTable()
    {
        return 'users';
    }
    
    function __get($var)
    {
        switch($var) {
            case 'newsroom':
                // @TODO check permissions before returning the newsroom!
                return UNL_ENews_Newsroom::getByID($this->newsroom_id);
            break;
            default:
                return $this->getPeoplefinderRecord()->$var;
        }
    }
    
    function getPeoplefinderRecord()
    {
        if (!isset($this->peoplefinder_record)) {
            $pf = new UNL_Peoplefinder();
            $this->peoplefinder_record = $pf->getUID($this->uid);
        }
        return $this->peoplefinder_record;
    }
    
    function __toString()
    {
        return $this->uid;
    }
    
    public function hasPermission($newsroom_id)
    {
        return UNL_ENews_User_Permission::userHasPermission($this->uid, $newsroom_id);
    }
}