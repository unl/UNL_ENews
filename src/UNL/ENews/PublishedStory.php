<?php
class UNL_ENews_PublishedStory extends UNL_ENews_Story
{
    function __construct($options = array())
    {
        parent::__construct($options);
        if (strtotime($this->request_publish_start) < time()) {
            throw new Exception('This story has not been published yet!');
        }
    }
}