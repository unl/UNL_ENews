<?php
class UNL_ENews_File extends UNL_ENews_Record
{
    public $id;
    
    public $name;
    
    public $type;
    
    public $size;
    
    public $data;
    
    function __construct($options = array())
    {
        if (isset($options['id'])) {
            if ($record = self::getRecordByID($this->getTable(), $options['id'])) {
                UNL_ENews_Controller::setObjectFromArray($this, $record);
            }
        }
    }
    
    function getTable()
    {
        return 'files';
    }
}