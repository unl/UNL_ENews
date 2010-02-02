<?php
class UNL_ENews_Files extends UNL_ENews_Record
{
    public $name;
    
    public $type;
    
    public $size;
    
    public $data;
    
    function getTable()
    {
        return 'files';
    }
}