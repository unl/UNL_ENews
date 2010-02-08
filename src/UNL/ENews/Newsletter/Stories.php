<?php
class UNL_ENews_Newsletter_Stories extends UNL_ENews_StoryList
{
    
    protected $newsletter_id;

    function __construct($options = array())
    {
        $this->newsletter_id = (int)$options['newsletter_id'];
        
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT story_id FROM newsletter_stories ';
        $sql .= 'WHERE newsletter_id = '.$this->newsletter_id .
                ' ORDER BY `sort_order` ASC;';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories);
    }
    
    
    function current()
    {
        return UNL_ENews_Newsletter_Story::getById($this->newsletter_id, parent::current()->id);
    }
}