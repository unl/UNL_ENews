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
            throw new Exception('You are not an administrator of that newsroom and cannot send newsletters.', 403);
        }

        $to = null;
        if (!empty($_POST['to'])) {
            // Just sending a preview
            $to = $_POST['to'];
        }

        if (!$this->newsletter->distribute($to)) {
            throw new Exception('There was an error in distribution.', 500);
        }

        if (!isset($to)) {
            // This is the real deal, mark it as distributed.
            $this->newsletter->distributed = 1;
            $this->newsletter->save();
        }

        $format = '';
        if (isset($this->options['format'])
            && $this->options['format'] == 'partial') {
            $format = '&format=partial';
        }

        header('Location: ?view=thanks&_type=sendnews&id='.$this->newsletter->id.$format);
        exit();
    }
}