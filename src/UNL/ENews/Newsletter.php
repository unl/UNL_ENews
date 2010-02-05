<?php
class UNL_ENews_Newsletter
{
    public $stories;
    
    function __construct($options = array())
    {
        $this->stories = new UNL_ENews_StoryList_Latest();
    }
}