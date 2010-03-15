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
        	//Can only edit the item specified if current user created it or has permission to a newsroom it's in
            $canedit = false;
            if (UNL_ENews_Controller::getUser()->uid == UNL_ENews_Story::getByID($this->options['id'])->uid_created) {
            	$canedit = true;
            } else {
            	$newsrooms = new UNL_ENews_User_Newsrooms(array('uid' => UNL_ENews_Controller::getUser(false)->uid));
            	foreach ($newsrooms as $newsroom) {
            		if (UNL_ENews_Newsroom_Stories::relationshipExists($newsroom->id,$this->options['id'])) {
            			$canedit = true;
            			break;
            		}
            	}	
            }
            if (!$canedit) {
            	throw new Exception('No permission to edit this story');
            }
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