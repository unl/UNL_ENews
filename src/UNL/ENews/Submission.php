<?php
class UNL_ENews_Submission extends UNL_ENews_LoginRequired
{
    protected $story;
    
    public $newsroom;
    
    function __postConstruct()
    {   
        if (!$this->newsroom = UNL_ENews_Newsroom::getByID($this->options['newsroom'])) {
            throw new Exception("Newsroom not found");
        }
        
        if (isset($this->options['id'])) {

            if (!$this->story = UNL_ENews_Story::getByID($this->options['id'])) {
                throw new Exception('Could not find the story you were trying to edit!');
            }

            //Can only edit the item specified if current user created it or has permission to a newsroom it's in
            if (!$this->story->userCanEdit(UNL_ENews_Controller::getUser(true))) {
                throw new Exception('No permission to edit this story');
            }
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