<?php
class UNL_ENews_User
{
    public $uid;
    
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
    
    function __get($var)
    {
        return $this->peoplefinder_record->$var;
    }
}