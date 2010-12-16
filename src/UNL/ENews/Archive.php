<?php
class UNL_ENews_Archive extends UNL_ENews_Record
{
	public $shortname;
	public $page = 1;
	
function __construct($options = array())
    {
        if (isset($options['shortname'])) {
            $this->shortname = $options['shortname'];
        } else {
            throw new Exception('No shortname was provided.');
        }
        
        if (isset($options['page'])) {
            $this->page = $options['page'];
        }
    }
    
    function __get($var)
    {
        switch($var) {
            case 'newsroom':
                return UNL_ENews_Newsroom::getByShortname($this->shortname);
            case 'newsletters':
                return $this->getNewsLetters();
        }
        
        return false;
    }
    
    function getNewsLetters(){
        return $this->newsroom->getNewsletters();
    }
}