<?php
class UNL_ENews_FileList extends ArrayIterator
{
    
    /**
     * @return UNL_ENews_File
     */
    function current()
    {
        return UNL_ENews_File::getByID(parent::current());
    }
}