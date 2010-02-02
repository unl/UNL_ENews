<?php
class UNL_ENews_StoryList extends ArrayIterator
{
    
    /**
     * @return UNL_ENews_Story
     */
    function current()
    {
        return UNL_ENews_Story::getByID(parent::current());
    }
}