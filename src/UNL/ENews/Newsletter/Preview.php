<?php
class UNL_ENews_Newsletter_Preview extends UNL_ENews_LoginRequired
{
    public $newsletter;
    
    public $available_stories;
    
    function __postConstruct()
    {
        $this->newsletter        = new UNL_ENews_Newsletter();
        $this->available_stories = new UNL_ENews_Newsroom_Stories(array('status'      => 'approved',
                                                                        'newsroom_id' => UNL_ENews_Controller::getUser(true)->newsroom->id));
    }
}