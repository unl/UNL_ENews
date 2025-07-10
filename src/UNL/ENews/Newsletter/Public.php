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
    private $options;

    function __construct($options = array())
    {
        $this->options = $options;

        if ($this->newsletterIdCheck()) {
            $this->setNewsletter();
            $this->validateNewsletter();
        } else {
            if (isset($this->options['shortname'])) {
                $this->requestLogin();
                $this->setLastRealeasedNewsletter($this->newsroom->id);
            } else {
                $this->setLastRealeasedNewsletter(NULL);
            }
        }
    }


    private function setNewsroom()
    {   
        if (isset($this->options['shortname'])) {
            if (UNL_ENews_Newsroom::getByShortname($this->options['shortname'])) {
                $this->newsroom = UNL_ENews_Newsroom::getByShortname($this->options['shortname']);
            }
            if (!$this->newsroom) {
                throw new Exception('There are no newsrooms by that name.', 404);
            }
        }
    }

    private function setNewsletter()
    {
        if (isset($this->options['id'])) {
            $this->newsletter = UNL_ENews_Newsletter::getById($this->options['id']);
            if (!$this->newsletter) {
                throw new Exception('Could not find that newsletter', 404);
            }
        }
    }

    private function newsletterIdCheck()
    {
        if (isset($this->options['id'])) {
            return true;
        } else {
            return false;
        }
    }

    private function validateNewsletter()
    {
        // If a newsroom name was passed, verify that the newsroom is correct
        if (isset($this->options['shortname'])) {
            if ($this->newsletter->newsroom->shortname != $this->options['shortname']) {
                throw new Exception('This newsletter does not belong in this newsroom.', 404);
            }
        }

        // If this is an unpublished newsletter, check permissions
        if ((empty($this->newsletter->release_date)
                || ($this->newsletter->release_date > date('Y-m-d H:i:s')))
            && !UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($this->newsletter->newsroom_id)
        ) {
            throw new Exception('You do not have permission to view unpublished newsletters for this newsroom', 403);
        }

        $this->requestLogin();
    }

    private function setLastRealeasedNewsletter($id)
    {
        $this->newsletter = UNL_ENews_Newsletter::getLastReleased($id);
    }

    private function requestLogin()
    {
        $this->setNewsroom();
        if ($this->newsroom) {
            //Check if newsletter webview is only allowed for UNL-authenticated users
            if (isset($this->newsroom->private_web_view) && $this->newsroom->private_web_view === '1') {
                //Request login
                if (empty(UNL_ENews_Controller::authenticate())) {
                    throw new Exception('You do not have access to view this newsletter page', 404);
                }
            }
        }
    }
}
