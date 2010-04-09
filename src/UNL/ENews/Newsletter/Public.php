<?php
class UNL_ENews_Newsletter_Public
{
    /**
     * The newsletter
     * 
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;
    
    function __construct()
    {
        if (isset($this->options['id'])) {
            $this->newsletter = UNL_ENews_Newsletter::getById($this->options['id']);
        } else {
            $this->newsletter = UNL_ENews_Newsletter::getLastReleased();
        }
    }
    
}