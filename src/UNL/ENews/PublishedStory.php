<?php
class UNL_ENews_PublishedStory extends UNL_ENews_Story
{
    function __construct($options = array())
    {
        parent::__construct($options);
        $this->checkPreconditions();
    }

    function checkPreconditions()
    {
        // Check if there is a user logged in that can edit this story
        if ($user = UNL_ENews_Controller::getUser()) {
            if ($this->userCanEdit($user)) {
                return true;
            }
        }

        // No user or user cannot edit. Verify the publish date.

        if (false === $this->pastPublishStart()) {
            throw new Exception('That story is not available to the public yet.', 400);
        }

        // We must be past the start date
        if ($this->getPresentationTypeString() == 'ad'
            && false === $this->hasNotExpired()) {
            throw new Exception('That advertisement is no longer available to the public.', 400);
        }

        // We're past the publish date, and it's not an ad so the content is still available
        return true;
    }
}