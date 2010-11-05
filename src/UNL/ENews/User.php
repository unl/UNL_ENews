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
        if (isset($options['uid'])) {
            $mysqli = UNL_ENews_Controller::getDB();
            $sql = "SELECT * FROM users WHERE uid = '".$mysqli->escape_string($options['uid'])."';";
            if (($result = $mysqli->query($sql))
                && $result->num_rows > 0) {
                UNL_ENews_Controller::setObjectFromArray($this, $result->fetch_assoc());
            }
        }
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
    
    function getNewsrooms()
    {
        return new UNL_ENews_User_Newsrooms(array('uid'=>$this->uid));
    }
    
    
    function __get($var)
    {
        switch($var) {
            case 'newsroom':
                if (!$this->hasPermission($this->newsroom_id)) {
                    throw new Exception('Whoah nelly, your default newsroom is one you don\'t have permission to.', 403);
                }
                return UNL_ENews_Newsroom::getByID($this->newsroom_id);
            break;
            case 'newsrooms':
                return new UNL_ENews_User_Newsrooms(array('uid'=>$this->uid));
            default:
                if ($pf = $this->getPeoplefinderRecord()) {
                    return $pf->$var;
                }
                return false;
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
    
    public function hasPermission($newsroom_id = false)
    {
        return UNL_ENews_User_Permission::userHasPermission($this->uid, $newsroom_id);
    }
}