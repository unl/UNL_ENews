<?php
class UNL_ENews_Story extends UNL_ENews_Record
{
    public $title;
    public $description;
    public $event_date;
    public $sponsor;
    public $website;
    public $uid_created;
    public $date_submitted;
    
    function getTable()
    {
        return 'stories';
    }
    
    function save()
    {
        $this->event_date       = $this->getDate($this->event_date);
        $this->uid_created      = strtolower(UNL_ENews_Controller::getUser()->uid);
        $this->date_submitted   = date('Y-m-d H:i:s');
        $result = parent::save();
        if (!$result) {
            throw new Exception('Error saving your application.');
        }
        return true;
    }
    
    /**
     * 
     * @param unknown_type $id
     * @return UNL_ENews_Story
     */
    public static function getByID($id)
    {
        if ($record = UNL_ENews_Record::getRecordByID('stories', $id)) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $record);
            return $object;
        }
        return false;
    }
}