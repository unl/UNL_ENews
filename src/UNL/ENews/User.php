<?php
class UNL_ENews_User extends UNL_ENews_Record
{
    public $uid;
    
    public $newsroom_id = 1;
    
    public $last_login;
    
    /**
     * the peoplefinder object for this person.
     * 
     * @var UNL_Peoplefinder_Record
     */
    public $peoplefinder_record;
    
    function __construct($options = array())
    {
        if (!isset($options['uid'])) {
            $this->uid = strtolower(UNL_UCARE_Controller::getUser()->uid);
        } else {
            $this->uid = $options['uid'];
        }
        $pf = new UNL_Peoplefinder();
        $this->peoplefinder_record = $pf->getUID($this->uid);
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
                return $this->peoplefinder_record->$var;
        }
    }
    
    function __toString()
    {
        return $this->uid;
    }
}