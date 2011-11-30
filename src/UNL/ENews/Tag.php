<?php
class UNL_ENews_Tag extends UNL_ENews_Record
{
    public $id;
    
    public $name;
    
    function __construct($options = array())
    {
        
    }

	function getTable()
    {
        return 'tags';
    }

    function __toString()
    {
    	return $this->name;
    }

}