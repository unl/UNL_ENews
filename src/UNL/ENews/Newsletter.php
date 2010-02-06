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
    
    function __construct($options = array())
    {
        
    }
    
    /**
     * 
     * @param int $id
     * 
     * @return UNL_ENews_Newsletter
     */
    public static function getByID($id)
    {
        if ($record = UNL_ENews_Record::getRecordByID('newsletter', $id)) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $record);
            return $object;
        }
        return false;
    }
    
    function getTable()
    {
        return 'newsletters';
    }
    
    function addStory(UNL_ENews_Story $story)
    {
        if ($has_story = UNL_ENews_Newsletter_Story::getById($this->id, $story->id)) {
            // Already have this story thanks
            return true;
        }
        $has_story = new UNL_ENews_Newsletter_Story();
        $has_story->newsletter_id  = $this->id;
        $has_story->story_id     = $story->id;
        if ($result = $has_story->insert()) {
            return $result;
        }
        throw new Exception('Could not add the story');
    }
    
    function getStories()
    {
        return new UNL_ENews_Newsletter_Stories(array('newsletter_id'=>$this->id));
    }
}