<?php
class UNL_ENews_Newsletter_Stories extends UNL_ENews_StoryList
{

    function __construct($options = array())
    {
//         @todo get all the stories associated with this newsletter, sorted by order
    }
    
    
    function current()
    {
        return new UNL_ENews_Newsletter_Story(array('newsletter_id' => $this->options['newsletter_id'],
                                                    'story_id'      => parent::current()));
    }
}