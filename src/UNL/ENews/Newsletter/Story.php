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
    public $sort_order;
    
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
    
    function keys()
    {
        return array('newsletter_id', 'story_id');
    }
    
    /**
     * get a story in this newsletter
     * 
     * @param int $newsletter_id
     * @param int $story_id
     * 
     * @return UNL_ENews_Newsletter_Story
     */
    static function getById($newsletter_id, $story_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsletter_stories WHERE newsletter_id = ".intval($newsletter_id)." AND story_id = ".intval($story_id);
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
            return $object;
        }
        return false;
    }
    
    function getStory()
    {
        static $story;
        if (!isset($story)) {
            $story = UNL_ENews_Story::getById($this->story_id);
        }
        return $story;
    }
    
    function getFiles()
    {
        return $this->getStory()->getFiles();
    }
    
    function __get($var)
    {
        return $this->getStory()->$var;
    }
}