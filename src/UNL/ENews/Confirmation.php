<?php
class UNL_ENews_Confirmation extends UNL_ENews_LoginRequired
{
    public $type;
    
    function __postConstruct($options = array())
    {
        if (isset($options['_type'])) {
            $this->type = $options['_type'];
        }
    }
}
