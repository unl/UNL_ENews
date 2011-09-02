<?php
class UNL_ENews_Submission extends UNL_ENews_LoginRequired
{
    protected $story;

    public $newsroom;

    /**
     * Get the list of newsrooms that allow submissions.
     * 
     * @return UNL_ENews_NewsroomList_AllowSubmissions
     */
    function getOpenNewsrooms()
    {
        return new UNL_ENews_NewsroomList_AllowSubmissions();
    }

    function __postConstruct()
    {
        if (isset($this->options['shortname'])) {
            if (!$this->newsroom = UNL_ENews_Newsroom::getByShortname($this->options['shortname'])) {
                throw new Exception('Newsroom not found', 404);
            }
        } else if (isset($this->options['newsroom'])) {
            if (!$this->newsroom = UNL_ENews_Newsroom::getByID($this->options['newsroom'])) {
                throw new Exception('Newsroom not found', 404);
            }
        } else {
            if (!$this->newsroom = UNL_ENews_Newsroom::getByID(1)) {
                throw new Exception('Sorry, there is no newsroom with the id of 1', 404);
            }
        }

        if (isset($this->options['id'])) {
            if (!$this->story = UNL_ENews_Story::getByID($this->options['id'])) {
                throw new Exception('Could not find the story you were trying to edit!', 404);
            }

            //Can only edit the item specified if current user created it or has permission to a newsroom it's in
            if (!$this->story->userCanEdit(UNL_ENews_Controller::getUser(true))) {
                throw new Exception('No permission to edit this story', 403);
            }
        } else {
            $this->story = new UNL_ENews_Story();
        }
    }

    /**
     * Get the story
     *
     * @return UNL_ENews_Story
     */
    function getStory()
    {
        return $this->story;
    }

    function __get($var)
    {
        return $this->story->$var;
    }

    function __isset($var)
    {
        return isset($this->story->$var);
    }
}