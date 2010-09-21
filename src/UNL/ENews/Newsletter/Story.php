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
        return UNL_ENews_Story::getById($this->story_id);
    }
    
    function getThumbnail()
    {
        return $this->getStory()->getThumbnail();
    }
    
    function getFiles()
    {
        return $this->getStory()->getFiles();
    }
    
    function __get($var)
    {
        switch($var){
            case 'story':
                return $this->getStory();
                break;
            case 'newsroom':
                return UNL_ENews_Newsroom::getByID($this->newsletter->newsroom_id);
                break;
            case 'newsletter':
                return UNL_Enews_newsletter::getByID($this->newsletter_id);
                break;
            default:
                return $this->getStory()->$var;
        }
    }
    
    function __isset($var)
    {
        return isset($this->getStory()->$var);
    }
    function getStoryLink(){
        return UNL_ENews_Controller::getURL().$this->newsroom->shortname.'/'.$this->newsletter->id.'/'.$this->story_id;
    }
}