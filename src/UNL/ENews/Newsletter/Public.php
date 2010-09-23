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
            if (!$this->newsletter) {
                throw new Exception('Could not find that newsletter', 404);
            }
            if (isset($options['newsName'])) {
                if($this->newsletter->newsroom->shortname != $options['newsName']){
                    throw new Exception('Could not find a newsletter with that name.', 404);
                }
            }
            // If this is an unpublished newsletter, check permissions
            if ((empty($this->newsletter->release_date)
                || ($this->newsletter->release_date > date('Y-m-d H:i:s')))
                && !UNL_ENews_Controller::getUser(true)->hasPermission($this->newsletter->newsroom_id)) {
                throw new Exception('You do not have permission to view unpublished newsletters for this newsroom', 403);
            }
        } else {
            if (isset($options['shortname'])) {
                if($this->newsroom = UNL_ENews_Newsroom::getByShortname($options['shortname'])){
                    $this->newsletter = UNL_ENews_Newsletter::getLastReleased($this->newsroom->id);
                    if(!$this->newsletter){
                      throw new Exception('There are no published newsletters for this newsroom.', 404);
                    }
                }else{
                    throw new Exception('There are no newsrooms by that name.', 404);
                }
            }else{
                $this->newsletter = UNL_ENews_Newsletter::getLastReleased(NULL);
                if(!$this->newsletter){
                    throw new Exception('There are no published newsletters.', 404);
                }
            }
        }
    }
    
}