<?php
class UNL_ENews_Submission extends UNL_ENews_LoginRequired
{
    protected $story;
    
    function __postConstruct()
    {
        if (isset($this->options['id'])) {
            $this->story = UNL_ENews_Story::getByID($this->options['id']);
        } else {
            $this->story = new UNL_ENews_Story();
        }
    }
    
    function __get($var)
    {
        return $this->story->$var;
    }
    
    function __isset($var)
    {
        return isset($this->story->$var);
    }
}