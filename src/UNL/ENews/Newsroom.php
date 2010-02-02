<?php
class UNL_ENews_Newsroom extends UNL_ENews_Record
{
    
    public $id;

    public $name;
    
    public $shortname;
    
    public $website;
    
    function getStories($type = 'pending')
    {
        
    }
    
    function getTable()
    {
        return 'newsrooms';
    }
    
    function getUsers()
    {
        return new UNL_ENews_Newsroom_Users(array('newsroom_id'=>$this->id));
    }
}