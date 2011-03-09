<?php
class UNL_ENews_StoryList_Latest extends UNL_ENews_StoryList
{
    /**
     * The newsroom which has the stories
     * 
     * @var UNL_ENews_Newsroom
     */
    public $newsroom;
    
    function __construct($options = array())
    {
        if (!$this->newsroom = UNL_ENews_Newsroom::getByID($options['newsroom'])) {
            throw new Exception('Newsroom not found', 404);
        }
        
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT story_id FROM newsroom_stories WHERE status != "pending" AND newsroom_id = '.(int)$options['newsroom'];
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        parent::__construct($stories);
    }
    
}