<?php
class UNL_ENews_Manager extends UNL_ENews_LoginRequired
{
    protected $actionable;
    
    public $options = array('type'=>'pending');
    
    function __postConstruct()
    {
        switch($this->options['type']) {
            case 'pending':
                $this->actionable = new UNL_ENews_StoryList(array('status'=>'pending', 'newsroom_id'=>1));
                break;
        }
    }
}