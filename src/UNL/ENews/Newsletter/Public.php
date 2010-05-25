<?php
class UNL_ENews_Newsletter_Public
{
    /**
     * The newsletter
     * 
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;
    
    function __construct($options = array())
    {
        if (isset($options['id'])) {
            $this->newsletter = UNL_ENews_Newsletter::getById($options['id']);
        } else {
            $this->newsletter = UNL_ENews_Newsletter::getLastReleased();
        }
    }
    
}