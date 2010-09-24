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

    function __construct($options = array())
    {
        //Check to make sure the story is valid for the given newsroom.
        if (isset($options['newsletter_id'])) {
            if (!($this->newsletter = UNL_ENews_Newsletter::getById($options['newsletter_id']))) {
                throw new Exception('This newsletter does not exist', 404);
            }
            if (!($this->story = UNL_ENews_Story::getById($options['id']))) {
                throw new Exception('This story does not exist.', 400);
            }
            if (!($this->newsletter->hasStory($this->story))) {
                throw new Exception('The story does not belong to the given newsletter.', 400);
            }
            if (!($this->newsroom = UNL_ENews_Newsroom::getByID($this->newsletter->newsroom_id))) {
                throw new Exception('The newsroom does not exist!', 404);
            }
            if (!($this->newsroom->shortname == $options['shortname'])) {
                throw new Exception('Not a valid news room name.', 400);
            }
        }
    }

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