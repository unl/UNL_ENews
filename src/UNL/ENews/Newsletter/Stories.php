<?php
class UNL_ENews_Newsletter_Stories extends UNL_ENews_StoryList
{

    function __construct($options = array())
    {
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT story_id FROM newsletter_stories ';
        $sql .= 'WHERE newsletter_id = '.(int)$options['newsletter_id'] .
                ' ORDER BY order ASC;';
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
        return new UNL_ENews_Newsletter_Story(array('newsletter_id' => $this->options['newsletter_id'],
                                                    'story_id'      => parent::current()));
    }
}