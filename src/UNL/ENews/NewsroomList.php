<?php
class UNL_ENews_NewsroomList extends ArrayIterator
{
    
    /**
     * @return UNL_ENews_Newsroom
     */
    function current()
    {
        return UNL_ENews_Newsroom::getByID(parent::current());
    }
}