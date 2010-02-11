<?php
class UNL_ENews_EmailDistributor extends UNL_ENews_LoginRequired
{
    /**
     * 
     * 
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;
    
    function __postConstruct()
    {
        if (isset($this->options['id'])) {
            $this->newsletter = UNL_ENews_Newsletter::getByID($this->options['id']);
        }
        if (!UNL_ENews_Controller::getUser(true)->hasPermission($this->newsletter->newsroom_id)) {
            throw new Exception('You are not an administrator of that newsroom and cannot send newsletters.');
        }
        $this->newsletter->distribute();
    }
}