<?php
class UNL_ENews_Story_File extends UNL_ENews_Record
{
    public $story_id;
    
    public $file_id;
    
    function getTable()
    {
        return 'story_files';
    }
}