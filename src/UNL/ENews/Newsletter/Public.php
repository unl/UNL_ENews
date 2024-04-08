<?php
class UNL_ENews_Newsletter_Public 
{
    /**
     * The newsletter
     * 
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;
    public $newsroom;
 
    function __construct($options = array())
    {
        if (isset($options['id'])) {
                       // Open the newsletter requested
            $this->newsletter = UNL_ENews_Newsletter::getById($options['id']);
            if (!$this->newsletter) {
                throw new Exception('Could not find that newsletter', 404);
            }

            // If a newsroom name was passed, verify that the newsroom is correct
            if (isset($options['shortname'])) {
                if ($this->newsletter->newsroom->shortname != $options['shortname']) {
                    throw new Exception('This newsletter does not belong in this newsroom.', 404);
                }
            }

            // If this is an unpublished newsletter, check permissions
            if ((empty($this->newsletter->release_date)
                || ($this->newsletter->release_date > date('Y-m-d H:i:s')))
                && !UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($this->newsletter->newsroom_id)) {
                throw new Exception('You do not have permission to view unpublished newsletters for this newsroom', 403);
            }

            //Check if newsletter webview is only allowed for UNL-authenticated users
            if ($this->newsroom = UNL_ENews_Newsroom::getByShortname($options['shortname'])) {
                if(isset($this->newsroom->private_web_view) and $this->newsroom->private_web_view === '1' ) {
                    //Request login
                    if (empty(UNL_ENews_Controller::authenticate())) {
                        throw new Exception('You do not have access to view this newsletter page', 404);
                    }
                }
            }
        } else { 
            if (isset($options['shortname'])) {
                if ($this->newsroom = UNL_ENews_Newsroom::getByShortname($options['shortname'])) {
                    //Check if newsletter webview is only allowed for UNL-authenticated users
                    if(isset($this->newsroom->private_web_view) and $this->newsroom->private_web_view === '1' ) {
                        if (empty(UNL_ENews_Controller::authenticate())) {
                            throw new Exception('You do not have access to view this newsletter page', 404);
                        }

                        $this->newsletter = UNL_ENews_Newsletter::getLastReleased($this->newsroom->id);
                        if (!$this->newsletter) {
                          throw new Exception('There are no published newsletters for this newsroom.', 404);
                        }
                    } else {
                        $this->newsletter = UNL_ENews_Newsletter::getLastReleased($this->newsroom->id);
                        if (!$this->newsletter) {
                          throw new Exception('There are no published newsletters for this newsroom.', 404);
                        }
                    }
                } else {
                    throw new Exception('There are no newsrooms by that name.', 404);
                }  
            } else {
                $this->newsletter = UNL_ENews_Newsletter::getLastReleased(NULL);
                if (!$this->newsletter) {
                    throw new Exception('There are no published newsletters.', 404);
                }
            }
        }
    }
    
}
