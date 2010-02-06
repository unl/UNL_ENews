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
    
    function keys()
    {
        return array('newsletter_id', 'story_id');
    }
    
    static function getById($newsletter_id, $story_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsletter_stories WHERE newsletter_id = ".intval($newsroom_id)." AND story_id = ".intval($story_id);
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
            return $object;
        }
        return false;
    }
}