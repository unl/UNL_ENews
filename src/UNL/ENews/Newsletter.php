<?php
class UNL_ENews_Newsletter extends UNL_ENews_Record
{
    /**
     * Unique ID for this newsletter
     * 
     * @var int
     */
    public $id;
    
    /**
     * The newsroom this letter is associated with
     * 
     * @var int
     */
    public $newsroom_id;
    
    /**
     * Date this release will be published
     * 
     * @var string Y-m-d H:i:s
     */
    public $release_date;
    
    /**
     * Subject for the email for this newsletter
     * 
     * @var string
     */
    public $subject;
    
    /**
     * Optional introductory text to the email, prepended to the list of stories
     * 
     * @var string
     */
    public $intro;
    
    /**
     * All the stories associated with this newsletter
     * 
     * @var UNL_ENews_StoryList
     */
    public $stories;
    
    function __construct($options = array())
    {
        $this->stories = new UNL_ENews_StoryList_Latest();
    }
    
    function getTable()
    {
        return 'newsletters';
    }
}