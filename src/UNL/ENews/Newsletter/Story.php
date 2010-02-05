<?php
class UNL_ENews_Newsletter_Story extends UNL_ENews_Record
{
    /**
     * The newsletter
     * 
     * @var int
     */
    public $newsletter_id;
    
    /**
     * Id of the story
     * 
     * @var id
     */
    public $story_id;
    
    /**
     * The order this story should be presented in
     * 
     * @var int
     */
    public $order;
    
    /**
     * Any introductory text to the story
     * 
     * @var string
     */
    public $intro;
    
    public function getTable()
    {
        return 'newsletter_stories';
    }
}