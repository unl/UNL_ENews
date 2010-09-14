<?php
class UNL_ENews_File extends UNL_ENews_Record
{
    public $id;
    
    public $name;
    
    public $type;
    
    public $size;
    
    public $data;
    
    public $use_for;
    
    public $description;
    
    function __construct($options = array())
    {
        if (isset($options['id'])) {
            if ($record = self::getRecordByID($this->getTable(), $options['id'])) {
                UNL_ENews_Controller::setObjectFromArray($this, $record);
            }
        }
    }

    static public function getById($id)
    {
        if ($record = self::getRecordByID('files', $id)) {
            $class = __CLASS__;
            if (strstr($record['type'], 'image/')) {
                $class = 'UNL_ENews_File_Image';
            }
            $object = new $class;
            UNL_ENews_Controller::setObjectFromArray($object, $record);
            return $object;
        }
        return false;
    }
    
    function getTable()
    {
        return 'files';
    }
    
    /**
     * Check if type is valid/supported
     * 
     * @param string $type
     * 
     * @return bool
     */
    public static function validFileType($type)
    {
        return true;
    }
    
    /**
     * Checks if the filename is supported.
     * 
     * @param string $filename Filename to check
     * 
     * @return bool
     */
    public static function validFileName($filename)
    {
        $allowedExtensions = array("gif","jpeg","jpg","png");
        return in_array(end(explode(".", strtolower($filename))), $allowedExtensions);
    }
}