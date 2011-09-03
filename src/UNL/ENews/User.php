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
                $this->synchronizeWithArray($result->fetch_assoc());
            }
        }
    }

    /**
     * Get a user object
     * 
     * If the user does not exist, it will be inserted into the db.
     *
     * @param string $uid User ID eg: bbieber2
     * 
     * @return UNL_ENews_User
     */
    public static function getByUID($uid)
    {
        if (empty($uid)) {
            throw new Exception('User id cannot be empty', 400);
        }

        /*
         * Usernames from CAS may come in with leading spaces, trim them off
         * and make sure it's lowercase.
         */
        $uid = trim(strtolower($uid));

        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM users WHERE uid = '".$mysqli->escape_string($uid)."';";
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            $object->synchronizeWithArray($result->fetch_assoc());
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

    /**
     * 
     * Get the newsrooms this user has permission to
     * 
     * @return UNL_ENews_User_Newsrooms
     */
    function getNewsrooms()
    {
        return new UNL_ENews_User_Newsrooms(array('uid'=>$this->uid));
    }

    /**
     * 
     * Get the newsrooms this user has permission to
     * 
     * @return UNL_ENews_User_NewsroomsWithStories
     */
    function getNewsroomsWithStories()
    {
        return new UNL_ENews_User_NewsroomsWithStories(array('uid'=>$this->uid));
    }

    function __get($var)
    {
        switch($var) {
            case 'newsroom':
                if (!$this->hasNewsroomPermission($this->newsroom_id)) {
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
    
    public function hasNewsroomPermission($newsroom_id = false)
    {
        return UNL_ENews_User_Permission::userHasNewsroomPermission($this->uid, $newsroom_id);
    }
}