<?php
class UNL_ENews_NewsletterList extends ArrayIterator
{
    
    /**
     * @return UNL_ENews_Newsletter
     */
    function current()
    {
        return UNL_ENews_Newsletter::getByID(parent::current());
    }
}