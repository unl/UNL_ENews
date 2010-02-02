<?php
class UNL_ENews_Confirmation
{
    public $type;
    
    function __construct($options = array())
    {
        if (isset($options['_type'])) {
            $this->type = $options['_type'];
        }
    }
}
